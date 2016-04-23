<?php

/**
 * Test: EuroSms\Message
 */

use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

$message = new RM\SMSender\EuroSms\Message;

### Original test ###

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

### Set From ###

Assert::exception(function() use ($message) {
	$message->setFrom([]);
}, 'RM\SMSender\InvalidArgumentException', 'Parameter "from" must be type of string, it\'s array.');

Assert::exception(function() use ($message) {
	$message->setFrom('#sender');
}, 'RM\SMSender\InvalidArgumentException', 'Parameter "from" can contain only alphanumerical character, space, "-" and "." and must have from 1-14 characters.');

Assert::true($message->setFrom('s4 F4.s-887d f') instanceof $message);

### Set To ###

Assert::exception(function() use ($message) {
	$message->setTo([]);
}, 'RM\SMSender\InvalidArgumentException', 'Parameter "number" must be type of string, it\'s array.');

Assert::exception(function() use ($message) {
	$message->setTo('any number');
}, 'RM\SMSender\InvalidArgumentException', 'Parameter "number" can use number in formats "09xxYYYYYY" or "+xxxYYYzzzzzz".');

Assert::true($message->setTo('0900123456') instanceof $message);
Assert::true($message->setTo('+421900123456') instanceof $message);

### Set Text ###

Assert::exception(function() use ($message) {
	$message->setText([]);
}, 'RM\SMSender\InvalidArgumentException', 'Parameter "text" must be type of string, it\'s array.');

Assert::exception(function() use ($message) {
	$message->setText(Nette\Utils\Random::generate(161));
}, 'RM\SMSender\InvalidArgumentException', 'Parameter "text" must be length 1-160 characters. Has 161 characters.');

Assert::exception(function() use ($message) {
	$message->setText('');
}, 'RM\SMSender\InvalidArgumentException', 'Parameter "text" must be length 1-160 characters. Has 0 characters.');

Assert::true($message->setText('Hello world!') instanceof $message);
