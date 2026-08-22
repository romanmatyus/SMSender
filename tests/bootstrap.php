<?php

if (@!include __DIR__ . '/../vendor/autoload.php') {
	echo 'Install Nette Tester using `composer update --dev`';
	exit(1);
}

Tester\Environment::setup();
date_default_timezone_set('Europe/Bratislava');


/**
 * Credentials for tests calling the real EuroSMS gateway.
 * Skips the test when tests/secret.neon is not available (e.g. pull requests from forks).
 * @return array
 */
function getSecretConfig()
{
	$file = __DIR__ . '/secret.neon';
	if (!is_file($file)) {
		Tester\Environment::skip('Missing tests/secret.neon with EuroSMS credentials.');
	}
	return (array) Nette\Neon\Neon::decode(file_get_contents($file));
}
