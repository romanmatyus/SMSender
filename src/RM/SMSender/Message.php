<?php

namespace RM\SMSender;

use Nette\Object;
use RM\SMSender\IMessage;

/**
 * Basic implementation of Message.
 */
class Message extends Object implements IMessage
{
	/** @var string */
	protected $from;

	/** @var string */
	protected $to;

	/** @var string */
	protected $text;

	/**
	 * @param  string $from
	 * @return self
	 */
	public function setFrom($from)
	{
		$this->from = $from;
		return $this;
	}

	/**
	 * @param  string $number
	 * @return self
	 */
	public function setTo($number)
	{
		$this->to = $number;
		return $this;
	}

	/**
	 * @param  string $text
	 * @return self
	 */
	public function setText($text)
	{
		$this->text = $text;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFrom()
	{
		return $this->from;
	}

	/**
	 * @return string
	 */
	public function getTo()
	{
		return $this->to;
	}

	/**
	 * @return string
	 */
	public function getText()
	{
		return $this->text;
	}
}
