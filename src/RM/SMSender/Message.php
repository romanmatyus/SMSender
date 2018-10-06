<?php

namespace RM\SMSender;

use RM\SMSender\IMessage;

/**
 * Basic implementation of Message.
 */
class Message implements IMessage
{
	/** @var string */
	protected $from = '';

	/** @var string */
	protected $to = '';

	/** @var string */
	protected $text = '';

	public function setFrom(string $from = NULL) : IMessage
	{
		$this->from = (string) $from;
		return $this;
	}

	public function setTo(string $number = NULL) : IMessage
	{
		$this->to = (string) $number;
		return $this;
	}

	public function setText(string $text = NULL) : IMessage
	{
		$this->text = (string) $text;
		return $this;
	}

	public function getFrom() : string
	{
		return $this->from;
	}

	public function getTo() : string
	{
		return $this->to;
	}

	public function getText() : string
	{
		return $this->text;
	}
}
