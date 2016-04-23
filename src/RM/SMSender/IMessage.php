<?php

namespace RM\SMSender;

interface IMessage
{
	/**
	 * @param string $from
	 */
	public function setFrom($from);

	/**
	 * @param string $number
	 */
	public function setTo($number);

	/**
	 * @param string $text
	 */
	public function setText($text);

	/**
	 * @return string
	 */
	public function getFrom();

	/**
	 * @return string
	 */
	public function getTo();

	/**
	 * @return string
	 */
	public function getText();
}
