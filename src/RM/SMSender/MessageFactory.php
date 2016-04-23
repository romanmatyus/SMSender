<?php

namespace RM\SMSender;

use Nette\Object;

class MessageFactory extends Object implements IMessageFactory
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
			if ($message->getReflection()->hasMethod($method)) {
				$message->$method($value);
			}
		}
		return $message;
	}
}
