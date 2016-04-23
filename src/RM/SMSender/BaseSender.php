<?php

namespace RM\SMSender;

use GuzzleHttp;
use Nette\Object;
use Nette\Utils\Strings;
use RM\SMSender\ISender;

/**
 * Base Sender
 */
abstract class BaseSender extends Object implements ISender
{
	/** @var callable[] function (IMessage $message); Occurs before send SMS. */
	public $onBeforeSend;

	/** @var callable[] function (IMessage $message, $response); Occurs after success send SMS. */
	public $onSuccess;

	/** @var callable[] function (IMessage $message, $response); Occurs after failed send SMS. */
	public $onError;

	/** @var bool */
	protected $debug = FALSE;

	/** @var GuzzleHttp\Client */
	protected $httpClient;

	/**
	 * @param  bool $value
	 * @return self
	 */
	public function setDebugMode($value)
	{
		$this->debug = (bool) $value;
		return $this;
	}

	/**
	 * @return Guzzle\Client
	 */
	public function getHttpClient()
	{
		if ($this->httpClient)
			return $this->httpClient;
		$this->httpClient = new GuzzleHttp\Client;
		return $this->httpClient;
	}
}
