<?php

namespace RM\SMSender\EuroSms;

use Nette\Utils\Strings;
use RM\SMSender;
use RM\SMSender\InvalidArgumentException;

/**
 * Message for service EuroSms.sk
 */
class Message extends SMSender\Message implements SMSender\IMessage
{

	public function setFrom(string $from = '') : SMSender\IMessage
	{
		if (!Strings::match($from, '~^[0-9a-zA-Z\. -]{1,14}$~'))
			throw new InvalidArgumentException('Parameter "from" can contain only alphanumerical character, space, "-" and "." and must have from 1-14 characters.');
		return parent::setFrom($from);
	}

	public function setTo(string $number = '') : SMSender\IMessage
	{
		if (!Strings::match($number, '~^09\d{8}|\+?\d{11,12}$~'))
			throw new InvalidArgumentException('Parameter "number" can use number in formats "09xxYYYYYY" or "+xxxYYYzzzzzz".');
		return parent::setTo(ltrim($number, '+'));
	}

	public function setText(string $text = '') : SMSender\IMessage
	{
		if (strlen($text) < 1 OR strlen($text) > 459)
			throw new InvalidArgumentException('Parameter "text" must be length 1-459 characters. Has ' . strlen($text) . ' characters.');
		return parent::setText(Strings::toAscii($text));
	}
}
