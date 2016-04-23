<?php

/**
 * Test: EuroSms\Message
 */

use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

$sender = new RM\SMSender\EuroSms\Sender;

Assert::true($sender->config([
	'id' => '1-TB672G',
	'key' => '5^Af-8Ss',
]) instanceof $sender);

Assert::exception(function() use ($sender) {
	$sender->config([
		'id' => 'anyid',
		'key' => Nette\Utils\Random::generate(7),
	]);
}, 'RM\SMSender\ConfigurationException', 'Parameter "id" must be in format "1-[0-9a-zA-Z]{6}".');

Assert::exception(function() use ($sender) {
	$sender->config([
		'id' => '1-TB672G',
		'key' => Nette\Utils\Random::generate(7),
	]);
}, 'RM\SMSender\ConfigurationException', 'Parameter "key" must have 8 charactest. It has 7 characters.');
