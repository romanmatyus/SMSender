<?php

namespace RM\SMSender\EuroSms;

use Nette\Utils\Strings;
use RM;
use RM\SMSender\BaseSender;
use RM\SMSender\ISender;
use RM\SMSender\IMessage;
use RM\SMSender\Exception;
use RM\SMSender\ConfigurationException;
use RM\SMSender\GatewayException;
use RM\SMSender\MissingParameterException;

/**
 * Sender for service EuroSms.sk
 * @method onBeforeSend(IMessage $message)
 * @method onSuccess(IMessage $message, $response)
 * @method onError(IMessage $message, $response, Exception $e)
 */
class Sender extends BaseSender implements ISender
{
	CONST URL = 'https://as.eurosms.com/api/v2/Sender';

	/** @var string */
	private $id;

	/** @var string */
	private $key;


	public function __construct(array $config = NULL)
	{
		if (is_array($config) && !empty($config))
			$this->config($config);
	}

	/**
	 * @param  array $config
	 * @return self
	 */
	public function config($config)
	{
		$this->checkConfig($config['id'], $config['key']);

		$this->id = $config['id'];
		$this->key = $config['key'];
		return $this;
	}

	/**
	 * @throws RM\SMSender\Exception
	 * @return bool|string ID of Message
	 */
	public function send(IMessage $message)
	{
		$this->check($message);
		$this->onBeforeSend($message);
		$res = $this->getHttpClient()->request('GET', self::URL . '?' . str_replace(urlencode($message->getTo()), $message->getTo(), http_build_query([
				'action' => ($this->debug ? 'validate' : 'send') . '1SMSHTTP',
				'iid' => $this->id,
				'signature' => $this->getSignature($message),
				'from' => $message->getFrom(),
				'to' => $message->getTo(),
				'message' => $message->getText(),
				'flags' => 0x002,
			])));
		$response = $res->getBody();
		if ($this->isSuccess($response)) {
			$this->onSuccess($message, $response);
		} else {
			$e = new GatewayException($response);
			$this->onError($message, $response, $e);
			throw $e;
		}
		return ($this->debug)
			? TRUE
			: substr(Strings::trim((string)$response), 3);
	}

	public function getSignature(IMessage $message) : string
	{
		return hash_hmac('sha1', $message->getFrom() . $message->getTo() . $message->getText(), $this->key);
	}

	public function isSuccess(string $response)
	{
		return ($this->debug && Strings::startsWith($response, 'VALID_REQUEST')) || Strings::startsWith($response, 'ENQUEUED');
	}

	/**
	 * @param  string $id
	 * @param  string $key
	 * @throws ConfigurationException
	 * @return bool
	 */
	private function checkConfig($id, $key)
	{
		if (!Strings::match($id, '~^\d-[0-9a-zA-Z]{6}$~'))
			throw new ConfigurationException('Parameter "id" must be in format "\d-[0-9a-zA-Z]{6}".');
		if (strlen($key) !== 8 && strlen($key) !== 9)
			throw new ConfigurationException('Parameter "key" must have 8 or 9 characters. It has ' . strlen($key) . ' characters.');
		return TRUE;
	}

	/**
	 * @throws MissingParameterException
	 */
	private function check(IMessage $message) : bool
	{
		if (empty($message->getFrom()))
			throw new MissingParameterException('Message has empty sender. Use method setFrom().');
		if (empty($message->getTo()))
			throw new MissingParameterException('Message has empty recipent number. Use method setTo().');
		if (empty($message->getText()))
			throw new MissingParameterException('Message has empty text. Use method setText().');
		return TRUE;
	}
}
