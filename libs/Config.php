<?php

namespace Otaku\Framework;

class Config extends Singleton
{
	protected $config = array();

	protected $protected = array();

	public function add($array, $protected = false)
	{
		foreach ($array as $top => $values) {
			foreach ($values as $key => $value) {
				if (!empty($this->protected[$top][$key])) {
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

				$this->config[$top][$key] = $value;
				$this->protected[$top][$key] = $protected;
			}
		}
	}

	public function parse($file, $protected = false)
	{
		if (!file_exists($file))
		{
			throw new Error(Error::NO_CONFIG_FILE,
				'Missing config file: ' . basename($file));
		}

		$data = (array) parse_ini_file($file, true);
		$this->add($data, $protected);
	}

	public function get($section = false, $key = false, $default = null)
	{
		if (empty($section))
		{
			return $this->config;
		}

		if (empty($key) && isset($this->config[$section]))
		{
			return $this->config[$section];
		}

		if (isset($this->config[$section]) && isset($this->config[$section][$key]))
		{
			return $this->config[$section][$key];
		}

		return $default;
	}
}
