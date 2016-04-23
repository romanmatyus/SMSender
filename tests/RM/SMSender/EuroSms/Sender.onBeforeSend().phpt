<?php

/**
 * Test: EuroSms\Message
 */

use Nette\Neon\Neon;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

$message = new RM\SMSender\EuroSms\Message;
$message->setFrom('Tester')
	->setTo('+421900123456')
	->setText('Text');

$config = Neon::decode(file_get_contents(__DIR__ . '/../../../secret.neon'));

$sender = new RM\SMSender\EuroSms\Sender;
$sender->setDebugMode(TRUE)
	->config($config['eurosms']);

$sender->onBeforeSend[] = function ($message) {
	$message->setText($message->getText() . ' -- Example.com');
};

$sender->onSuccess[] = function ($message) {
	Assert::same('Text -- Example.com', $message->getText());
};

$sender->send($message);
