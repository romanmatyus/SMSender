<?php

namespace RM\SMSender\DI;

use Nette\Configurator;
use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nette\DI\Helpers;

/**
 * Nette DI extension for SMSender.
 */
class SMSenderExtension extends CompilerExtension
{
	/** @var [] */
	public $defaults = [
		'config' => [],
		'senderClass' => 'RM\SMSender\EuroSms\Sender',
		'setDebugMode' => FALSE,
		'messageClass' => 'RM\SMSender\EuroSms\Message',
		'messageFactoryClass' => 'RM\SMSender\MessageFactory',
		'message' => [
			'setFrom' => NULL,
			'signature' => NULL,
		],
	];

	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();

		$config = (array) $this->validateConfig($this->defaults);

		$sender = $builder->addDefinition($this->prefix('sender'))
			->setClass($config['senderClass']);

		foreach ($config as $method => $value) {
			$tmp = new $config['senderClass'];
			if ($tmp->getReflection()->hasMethod($method)) {
				$sender->addSetup($method, [$value]);
			}
		}

		if ($config['message']['signature']) {
			$sender->addSetup('$service->onBeforeSend[] = function ($message) { $message->setText($message->getText() . ?); };', [$config['message']['signature']]);
		}

		$builder->addDefinition($this->prefix('messageFactory'))
			->setClass($config['messageFactoryClass'])
			->addSetup('$class', [$config['messageClass']])
			->addSetup('$params', [$config['message']]);
	}

	/**
	 * Register extension to DI Container.
	 * @param  Configurator $config
	 */
	public static function register(Configurator $config)
	{
		$config->onCompile[] = function (Configurator $config, Compiler $compiler) {
			$compiler->addExtension('smsender', new SMSenderExtension());
		};
	}
}
