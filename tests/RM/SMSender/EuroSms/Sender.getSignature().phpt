<?php

/**
 * Test: EuroSms\Message
 */

use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

$message = new RM\SMSender\EuroSms\Message;
$message->setTo('421988171819');

$sender = new RM\SMSender\EuroSms\Sender;
$sender->config([
	'id' => '1-TB672G',
	'key' => '5^Af-8Ss',
]);

Assert::same('ffc3cd373ad', $sender->getSignature($message));
