<?php

/**
 * Test: EuroSms\Message
 */

use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

$message = new RM\SMSender\EuroSms\Message;
$message->setFrom('RZi');
$message->setTo('421903622237');
$message->setText('Testovacia sprava');

$sender = new RM\SMSender\EuroSms\Sender;
$sender->config([
	'id' => '2-A2gHjk',
	'key' => 'Gh-s7-J6',
]);

Assert::same('6f56060b6b7db97ca25782b771cca0a65077bd5b', $sender->getSignature($message));
