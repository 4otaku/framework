<?php

class Config
{
	protected static $config = array();

	protected static $protected = array();

	public static function add($array, $protected = false)
	{
		foreach ($array as $top => $values) {
			foreach ($values as $key => $value) {
				if (!empty(self::$protected[$top][$key])) {
					continue;
				}

				self::$config[$top][$key] = $value;
				self::$protected[$top][$key] = $protected;
			}
		}
	}

	public static function parse($file, $protected = false)
	{
		if (!file_exists($file))
		{
			throw new Error(Error::NO_CONFIG_FILE,
				'Missing config file: ' . basename($file));
		}

		$data = (array) parse_ini_file($file);
		self::add($data, $protected);
	}
}
