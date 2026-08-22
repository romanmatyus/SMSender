RM\SMSender
==========

[![Tests](https://github.com/romanmatyus/SMSender/actions/workflows/tests.yml/badge.svg?branch=master)](https://github.com/romanmatyus/SMSender/actions/workflows/tests.yml)
[![Latest Stable Version](https://img.shields.io/github/release/romanmatyus/SMSender.svg)](https://packagist.org/packages/rm/smsender)
[![Latest Unstable Version](https://poser.pugx.org/rm/smsender/v/unstable)](https://packagist.org/packages/rm/smsender)
[![License](https://poser.pugx.org/rm/smsender/license)](https://packagist.org/packages/rm/smsender)

Component for sending SMS through service EuroSMS.sk for Nette.

Supported PHP: 7.1 - 8.5.

> Library is possible use too without Nette.


Installation
-----------

```
$ composer require rm/smsender
```

Minimal example
---------------

### Pure PHP


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
			->setText($text);
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


Tests
-----

```
$ composer update
$ vendor/bin/tester tests -s -C
```

Some tests call the real EuroSMS gateway (in `validate` mode, no SMS is sent) and need
credentials in `tests/secret.neon` — a git-ignored file that must never be committed:

```neon
eurosms:
	id: API-id
	key: API-key
```

Without that file those tests skip themselves, the rest of the suite still runs.

In CI the file is generated from the repository secrets `EUROSMS_ID` and `EUROSMS_KEY`
(*Settings → Secrets and variables → Actions*). GitHub does not expose secrets to pull
requests opened from forks, so the gateway tests are skipped there.

Code coverage is collected on the newest supported PHP only, attached to the workflow run as
the `coverage` artifact, and the build fails if it drops below the threshold in the workflow:

```
$ vendor/bin/tester tests -s -C --coverage coverage.xml --coverage-src src
```
