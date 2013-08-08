<?php

namespace Otaku\Framework;

class Singleton
{
	protected static $instances = array();

	private function __construct()
	{}
	private function __clone()
	{}
	private function __wakeup()
	{}

	/**
	 * @return static
	 */
	public static function getInstance()
	{
		$class = get_called_class();
		if (!isset(self::$instances[$class])) {
			self::$instances[$class] = new $class();
		}
		return self::$instances[$class];
	}
}