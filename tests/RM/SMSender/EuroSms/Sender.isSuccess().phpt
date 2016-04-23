<?php

/**
 * Test: EuroSms\Message
 */

use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

$sender = new RM\SMSender\EuroSms\Sender;
$sender->setDebugMode(TRUE);
Assert::true($sender->isSuccess('SMSValid'));
Assert::false($sender->isSuccess('ok'));
Assert::false($sender->isSuccess('BadSignature'));

$sender->setDebugMode(FALSE);
Assert::false($sender->isSuccess('SMSValid'));
Assert::true($sender->isSuccess('ok'));
Assert::false($sender->isSuccess('BadSignature'));
