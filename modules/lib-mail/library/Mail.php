<?php
/**
 * Mail
 * @package lib-mail
 * @version 0.0.1
 */

namespace LibMail\Library;


class Mail
{
	private static $handler;

	private static function getHandler(): ?string{
		if(self::$handler)
			return self::$handler;

		$config = \Mim::$app->config->libMail;
		if(!$config->handler){
			throw new Exception('No email handler registered');
			return null;
		}

		return $config->handler;
	}

    static function send(array $options): bool {
        $handler = self::getHandler();
        return $handler::send($options);
    }

    static function lastError(): ?string {
    	$handler = self::getHandler();
    	return $handler::lastError();
    }
}