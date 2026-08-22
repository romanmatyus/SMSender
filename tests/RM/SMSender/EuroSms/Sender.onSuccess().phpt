<?php

/**
 * Test: EuroSms\Message
 */

use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

$message = new RM\SMSender\EuroSms\Message;
$message->setFrom('Tester')
	->setTo('+421900123456')
	->setText('Text');

$config = getSecretConfig();

$sender = new RM\SMSender\EuroSms\Sender;
$sender->setDebugMode(TRUE)
	->config($config['eurosms']);

$status = NULL;

$sender->onSuccess[] = function ($message, $response) use (&$status) {
	$status = TRUE;
};

$sender->send($message);

Assert::true($status);
