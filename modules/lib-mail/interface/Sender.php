<?php
/**
 * Sender
 * @package lib-mail
 * @version 0.0.1
 */

namespace LibMail\Iface;


interface Sender
{
	static function send(array $options): bool;

	static function lastError(): ?string;
}