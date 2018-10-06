<?php

namespace RM\SMSender;

interface IMessage
{
	public function setFrom(string $from = '') : IMessage;

	public function setTo(string $number = '') : IMessage;

	public function setText(string $text = '') : IMessage;

	public function getFrom() : string;

	public function getTo() : string;

	public function getText() : string;
}
