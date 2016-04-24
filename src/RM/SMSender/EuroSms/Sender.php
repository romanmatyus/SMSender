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
	CONST URL = 'https://as.eurosms.com/sms/Sender';

	/** @var string */
	private $id;

	/** @var string */
	private $key;

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
	 * @param  IMessage $message
	 * @throws RM\SMSender\Exception
	 * @return bool|string ID of Message
	 */
	public function send(IMessage $message)
	{
		$this->check($message);
		$this->onBeforeSend($message);
		$res = $this->getHttpClient()->request('GET', self::URL . '?' . str_replace(urlencode($message->getTo()), $message->getTo(), http_build_query([
				'action' => ($this->debug ? 'validate' : 'send') . '1SMSHTTP',
				'i' => $this->id,
				's' => $this->getSignature($message),
				'd' => 1,
				'sender' => $message->getFrom(),
				'number' => $message->getTo(),
				'msg' => urlencode($message->getText()),
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

	/**
	 * @param  Message $message
	 * @return string
	 */
	public function getSignature(IMessage $message)
	{
		return substr(md5($this->key . str_replace('+', '', $message->getTo())), 10, 11);
	}

	/**
	 * @param  string  $response
	 * @return boolean
	 */
	public function isSuccess($response)
	{
		$response = Strings::trim((string)$response);
		return ($this->debug && $response === 'SMSValid') || (!$this->debug && substr($response, 0, 2) === 'ok');
	}

	/**
	 * @param  string $id
	 * @param  string $key
	 * @throws ConfigurationException
	 * @return bool
	 */
	private function checkConfig($id, $key)
	{
		if (!Strings::match($id, '~^1-[0-9a-zA-Z]{6}$~'))
			throw new ConfigurationException('Parameter "id" must be in format "1-[0-9a-zA-Z]{6}".');
		if (strlen($key) !== 8)
			throw new ConfigurationException('Parameter "key" must have 8 charactest. It has ' . strlen($key) . ' characters.');
		return TRUE;
	}

	/**
	 * @param  Message $message
	 * @throws MissingParameterException
	 * @return bool
	 */
	private function check(IMessage $message)
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
