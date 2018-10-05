<?php

namespace RM\SMSender;

use Nette\Reflection\ClassType;

class MessageFactory implements IMessageFactory
{
	/**
	 * @var string
	 */
	public $class;

	/**
	 * List of methods with values for Message
	 * @var [type]
	 */
	public $params = [];

	/**
	 * @return IMessage
	 */
	public function create()
	{
		$message = new $this->class;
		foreach ($this->params as $method => $value) {
			if ((new ClassType($message))->hasMethod($method)) {
				$message->$method($value);
			}
		}
		return $message;
	}
}
