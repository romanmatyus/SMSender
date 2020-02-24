<?php

/**
 * Test: EuroSms\Message
 */

use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

$sender = new RM\SMSender\EuroSms\Sender;
$sender->setDebugMode(TRUE);
Assert::false($sender->isSuccess('UNKNOWN_OPERATION|Unknown operation or missing action param.'));
Assert::true($sender->isSuccess('ENQUEUED|Message accepted and enqueued to send|1|588f02fedec4-4ada-b948-aab2e320dcbc'));
Assert::true($sender->isSuccess('ENQUEUED|Message accepted and enqueued to send|2|889a4e59-ec80-429d-82ce-f7dd0459bbd2|78efac19-b293-4b56-be30-e169f3f62f4d'));
Assert::true($sender->isSuccess('VALID_REQUEST|The request is valid. Message wasn\'t send.|2|FAKE-90aa64c5-700d-41b9-ad2f-73cd08a1b15e|FAKE-5e01b4ef-0365-4b08-9ed1-17350ff87e63'));

$sender->setDebugMode(FALSE);
Assert::false($sender->isSuccess('UNKNOWN_OPERATION|Unknown operation or missing action param.'));
Assert::true($sender->isSuccess('ENQUEUED|Message accepted and enqueued to send|1|588f02fedec4-4ada-b948-aab2e320dcbc'));
Assert::true($sender->isSuccess('ENQUEUED|Message accepted and enqueued to send|2|889a4e59-ec80-429d-82ce-f7dd0459bbd2|78efac19-b293-4b56-be30-e169f3f62f4d'));
Assert::false($sender->isSuccess('VALID_REQUEST|The request is valid. Message wasn\'t send.|2|FAKE-90aa64c5-700d-41b9-ad2f-73cd08a1b15e|FAKE-5e01b4ef-0365-4b08-9ed1-17350ff87e63'));
