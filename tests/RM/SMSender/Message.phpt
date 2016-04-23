<?php

/**
 * Test: Message
 */

use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

$message = new RM\SMSender\Message;

$from = 'From';
$to = '+421940123456';
$text = 'Some text.';

$val = $message->setFrom($from);
Assert::true($val instanceof $message);

$val = $message->setTo($to);
Assert::true($val instanceof $message);

$val = $message->setText($text);
Assert::true($val instanceof $message);

Assert::same($from, $message->getFrom());
Assert::same($to, $message->getTo());
Assert::same($text, $message->getText());
