<?php

namespace otaku\framework;

class Error extends Exception
{
	const
		NO_CONFIG_FILE = 2,
		INVALID_CONFIG = 3,
		EMPTY_FILE = 5,
		FILE_TOO_LARGE = 10,
		NOT_AN_IMAGE = 20,
		INCORRECT_URL = 410;

	public function __construct($message = '', $code = 0, Exception $previous = null) {

		if (is_int($message) && (empty($code) || !is_int($code))) {
			if ($code == 0) {
				$code = null;
			}
			parent::__construct('', $message, $code);
		} else {
			parent::__construct($message, $code, $previous);
		}
	}
}
