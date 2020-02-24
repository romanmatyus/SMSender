<?php

/**
 * Test: DI\SMSenderExtension
 */

use Nette\Neon\Neon;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

$tempDir = __DIR__ . '/../../../temp';
$tempConfig = $tempDir . '/config.neon';

@mkdir($tempDir);

$config = Neon::decode(file_get_contents(__DIR__ . '/../../../secret.neon'));
file_put_contents($tempConfig, Neon::encode([
	'smsender' => [
		'config' => [
			'id' => $config['eurosms']['id'],
			'key' => $config['eurosms']['key'],
		],
		'setDebugMode' => TRUE,
		'message' => [
			'setFrom' => 'Example.com',
			'signature' => ' -- Example.com',
		],
	],
	'services' => [
		'Nette\Caching\Storages\DevNullStorage',
	],
]));

$config = new Nette\Configurator;
$config->setTempDirectory($tempDir);
RM\SMSender\DI\SMSenderExtension::register($config);
$config->addConfig($tempConfig);
$container = $config->createContainer();

$message = $container->getByType('RM\SMSender\IMessageFactory')->create();
$message->setTo('+421900123456')
	->setText('Text');

$sender = $container->getByType('RM\SMSender\ISender');
$sender->onSuccess[] = function ($message) {
	Assert::same('Example.com', $message->getFrom());
	Assert::same('421900123456', $message->getTo());
	Assert::same('Text -- Example.com', $message->getText());
};

Assert::true($sender->send($message));
