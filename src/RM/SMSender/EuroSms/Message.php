<?php

namespace RM\SMSender\EuroSms;

use Nette\Object;
use Nette\Utils\Strings;
use RM\SMSender;
use RM\SMSender\InvalidArgumentException;

/**
 * Message for service EuroSms.sk
 */
class Message extends SMSender\Message implements SMSender\IMessage
{
	/**
	 * @param  string $from
	 * @return self
	 */
	public function setFrom($from)
	{
		if (!is_string($from))
			throw new InvalidArgumentException('Parameter "from" must be type of string, it\'s ' . gettype($from) . '.');
		if (!Strings::match($from, '~^[0-9a-zA-Z\. -]{1,14}$~'))
			throw new InvalidArgumentException('Parameter "from" can contain only alphanumerical character, space, "-" and "." and must have from 1-14 characters.');
		return parent::setFrom($from);
	}

	/**
	 * @param  string $number
	 * @return self
	 */
	public function setTo($number)
	{
		if (!is_string($number))
			throw new InvalidArgumentException('Parameter "number" must be type of string, it\'s ' . gettype($number) . '.');
		if (!Strings::match($number, '~^09\d{8}|\+?\d{12}$~'))
			throw new InvalidArgumentException('Parameter "number" can use number in formats "09xxYYYYYY" or "+xxxYYYzzzzzz".');
		return parent::setTo($number);
	}

	/**
	 * @param  string $text
	 * @return self
	 */
	public function setText($text)
	{
		if (!is_string($text))
			throw new InvalidArgumentException('Parameter "text" must be type of string, it\'s ' . gettype($text) . '.');
		if (strlen($text) < 1 OR strlen($text) > 160)
			throw new InvalidArgumentException('Parameter "text" must be length 1-160 characters. Has ' . strlen($text) . ' characters.');
		return parent::setText(Strings::toAscii($text));
	}
}
