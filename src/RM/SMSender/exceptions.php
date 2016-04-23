<?php

namespace RM\SMSender;

class Exception extends \Exception {}

class InvalidArgumentException extends Exception {}

class MissingParameterException extends Exception {}

class ConfigurationException extends Exception {}

class GatewayException extends Exception {}
