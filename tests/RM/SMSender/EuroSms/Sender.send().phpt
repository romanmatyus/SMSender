<?php

/**
 * Test: EuroSms\Message
 */

use Nette\Neon\Neon;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

$message = new RM\SMSender\EuroSms\Message;

$config = Neon::decode(file_get_contents(__DIR__ . '/../../../secret.neon'));

$sender = new RM\SMSender\EuroSms\Sender;
$sender->setDebugMode(true);
$sender->config([
	'id' => '1-JA67XG',
	'key' => '12345678',
]);

Assert::exception(function() use ($sender, $message) {
	$sender->send($message);
}, 'RM\SMSender\MissingParameterException', 'Message has empty sender. Use method setFrom().');

$message->setFrom('Tester');

Assert::exception(function() use ($sender, $message) {
	$sender->send($message);
}, 'RM\SMSender\MissingParameterException', 'Message has empty recipent number. Use method setTo().');

$message->setTo('+421900123456');

Assert::exception(function() use ($sender, $message) {
	$sender->send($message);
}, 'RM\SMSender\MissingParameterException', 'Message has empty text. Use method setText().');

$message->setText('Text');

$sender->setDebugMode(false);
Assert::exception(function() use ($sender, $message) {
	$sender->send($message);
}, 'RM\SMSender\GatewayException');

$sender->config($config['eurosms']);

$sender->setDebugMode(true);
Assert::true($sender->send($message));
