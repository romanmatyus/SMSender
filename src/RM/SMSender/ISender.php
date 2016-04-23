<?php

namespace RM\SMSender;

interface ISender
{
	/**
	 * @param  mixed $config
	 * @return self
	 */
	public function config($config);

	/**
	 * @param  IMessage $message
	 * @return bool|string ID of Message
	 */
	public function send(IMessage $message);
}
