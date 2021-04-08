<?php

namespace RM\SMSender;

use ReflectionClass;

class MessageFactory implements IMessageFactory
{
	/**
	 * @var string
	 */
	public $class;

	/**
	 * List of methods with values for Message
	 * @var array
	 */
	public $params = [];

	/**
	 * @return IMessage
	 */
	public function create()
	{
		$message = new $this->class;
		foreach ($this->params as $method => $value) {
			if ((new ReflectionClass($message))->hasMethod($method)) {
				$message->$method($value);
			}
		}
		return $message;
	}
}
