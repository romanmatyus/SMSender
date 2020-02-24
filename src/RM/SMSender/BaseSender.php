<?php

namespace RM\SMSender;

use GuzzleHttp;
use Nette\SmartObject;
use Nette\Utils\Strings;

/**
 * Base Sender
 */
abstract class BaseSender implements ISender
{
	use SmartObject;

	/** @var array of function (IMessage $message); Occurs before send SMS. */
	public $onBeforeSend;

	/** @var array of function (IMessage $message, $response); Occurs after success send SMS. */
	public $onSuccess;

	/** @var array of function (IMessage $message, $response); Occurs after failed send SMS. */
	public $onError;

	/** @var bool */
	protected $debug = FALSE;

	/** @var GuzzleHttp\Client */
	protected $httpClient;

	public function setDebugMode(bool $value) : ISender
	{
		$this->debug = $value;
		return $this;
	}

	public function getHttpClient() : GuzzleHttp\Client
	{
		if (!($this->httpClient instanceof GuzzleHttp\Client))
			$this->httpClient = new GuzzleHttp\Client;
		return $this->httpClient;
	}
}
