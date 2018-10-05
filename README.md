RM\SMSender
==========

[![Build Status](https://travis-ci.org/romanmatyus/SMSender.svg?branch=master)](https://travis-ci.org/romanmatyus/SMSender)
[![Code Quality](https://scrutinizer-ci.com/g/romanmatyus/SMSender/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/romanmatyus/SMSender/)
[![Code Coverage](https://scrutinizer-ci.com/g/romanmatyus/SMSender/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/romanmatyus/SMSender/)
[![Latest Stable Version](https://img.shields.io/github/release/romanmatyus/SMSender.svg)](https://packagist.org/packages/rm/smsender)
[![Latest Unstable Version](https://poser.pugx.org/rm/smsender/v/unstable)](https://packagist.org/packages/rm/smsender)
[![License](https://poser.pugx.org/rm/smsender/license)](https://packagist.org/packages/rm/smsender)

Component for sending SMS through service EuroSMS.sk for Nette.

> Library is possible use too without Nette.


Installation
-----------

```
$ composer require rm/smsender
```

Minimal example
---------------

### Pure PHP

```php
$message = new RM\SMSender\Message;
$message->setFrom('Example.com')
	->setTo('+421900123456')
	->setText('SMS text');
try {
	$smsender = new RM\SMSender\EuroSms\Sender([
		'id' => 'API-id',
		'key' => 'API-key',
	]);
	$smsender->send($message);
} catch (RM\SMSender\Exception $e) {
	echo 'ERROR: ' . $e->getMessage();
}
```

### Nette

`config.neon`

```neon
extensions:
	smsender: RM\SMSender\DI\SMSenderExtension

smsender:
	config:	[
		id: API-id
		key: API-key
	]
```

```php
namespace App;

use Nette\Application\UI\Presenter;
use RM;

class SmsPresenter extends Presenter
{
	/** @var RM\SMSender\IMessageFactory @inject */
	public $messageFactory;

	/** @var RM\SMSender\ISender @inject */
	public $SMSender;

	protected function startup()
	{
		parent::startup();
		$this->SMSender->onBeforeSend[] = function ($message) {
			$message->setText($message->getText() . ' -- Example.com');
		};
		$this->SMSender->onSuccess[] = function () {
			$this->flashMessage('SMS has been sent.', 'success');
		};
		$this->SMSender->onError[] = function () {
			$this->flashMessage('Sending SMS failed.', 'warning');
		};
	}

	function actionSendSms($to, $text)
	{
		$message = $this->messageFactory->create();
		$message->setFrom('Example.com')
			->setTo($to)
			->setMessage($text);
		try {
			$this->SMSender->send($message);
		} catch (RM\SMSender\Exception $e) {}
	}
}
```

### Full feature configuration

`config.neon`

```neon
extensions:
	smsender: RM\SMSender\DI\SMSenderExtension

smsender:
	config:	[
		id: API-id
		key: API-key
	]
	setDebugMode: TRUE
	senderClass: RM\SMSender\EuroSms\Sender
	messageClass: RM\SMSender\EuroSms\Message
	messageFactoryClass: RM\SMSender\MessageFactory
	message:
		setFrom: Example.com
		signature: ' -- Example.com'
```
