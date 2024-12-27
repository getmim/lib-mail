<?php
/**
 * Mail
 * @package lib-mail
 * @version 0.0.1
 */

namespace LibMail\Library;

use LibView\Library\View;

class Mail
{
	private static $handler;

	private static function applyParams(string $text, array $params): string
	{
		foreach ($params as $key => $val) {
			$text = str_replace('(:'.$key.')', $val ?? '', $text);
		}
		return $text;
	}

	private static function buildParams($params, string $parent=''): array
	{
		$result = [];
		foreach ($params as $par => $val) {
			if (is_array($val) || is_object($val)) {
				$res = self::buildParams($val, $parent . $par . '.');
				$result = array_merge($result, $res);
			} else {
				$result[$parent.$par] = $val;
			}
		}

		return $result;
	}

	private static function getHandler(): ?string
	{
		if (self::$handler) {
			return self::$handler;
		}

		$config = (object)\Mim::$app->config->libMail;
		if (!isset($config->handler) || !$config->handler) {
			throw new \Exception('No email handler registered');
			return null;
		}

		return $config->handler;
	}

    private static function validateOptions(array $options): bool
    {
        $req_fields = ['to','subject'];
        foreach ($req_fields as $fld) {
            if (!isset($options[$fld])) {
                throw new \Exception('Property `' . $fld . '` is required');
            }
        }

        return true;
    }

    static function send(array $options): bool
    {
    	$params = $options['view']['params'] ?? [];
    	$params = $params ? self::buildParams($params) : [];

        if (!self::validateOptions($options)) {
            return false;
        }

    	$recipients = $options['to'];

    	$handler = self::getHandler();

    	foreach ($recipients as $recipient) {
    		$params['to.name']  = $recipient['name'];
    		$params['to.email'] = $recipient['email'];

    		$fopts = [
    			'to' 		=> $recipient,
    			'subject' 	=> self::applyParams($options['subject'], $params),
    			'attachment'=> $options['attachment'] ?? [],
    			'text'      => $options['text'] ?? '',
    			'html' 		=> ''
    		];

    		if ($fopts['text']) {
    			$fopts['text'] = self::applyParams($fopts['text'], $params);
    		}

    		if (isset($options['view'])) {
    			$view              = $options['view'];
    			$view_params       = $view['params'] ?? [];
    			$view_params['to'] = $recipient;
    			$fopts['html']     = View::render($view['path'], $view_params, 'mail');
    		}

    		if (!$handler::send($fopts)) {
    			return false;
    		}
    	}

    	return true;
    }

    static function lastError(): ?string
    {
    	$handler = self::getHandler();
    	return $handler::lastError();
    }
}
