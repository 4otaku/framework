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

				if (preg_match('/^\s*(\d+(?:\.\d+)?)\s+([kmg]?b)\s*$/ui', $value, $parts)) {
					$multiplier = false;
					switch ($parts[2]) {
						case 'b': $multiplier = 1; break;
						case 'kb': $multiplier = KILOBYTE; break;
						case 'mb': $multiplier = MEGABYTE; break;
						case 'gb': $multiplier = GIGABYTE; break;
						default: break;
					}

					if ($multiplier) {
						$value = $parts[1] * $multiplier;
					}
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

		$data = (array) parse_ini_file($file, true);
		self::add($data, $protected);
	}

	public static function get($section = false, $key = false, $default = null)
	{
		if (empty($section))
		{
			return self::$config;
		}

		if (empty($key) && isset(self::$config[$section]))
		{
			return self::$config[$section];
		}

		if (isset(self::$config[$section]) && isset(self::$config[$section][$key]))
		{
			return self::$config[$section][$key];
		}

		return $default;
	}
}
