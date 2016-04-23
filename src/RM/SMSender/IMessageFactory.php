<?php

namespace RM\SMSender;

interface IMessageFactory
{
	/** @return IMessage */
	public function create();
}
