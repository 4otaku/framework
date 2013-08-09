<?php

class Autoload
{
	protected static $spaces = array();
	protected $external = '';

	public function __construct($spaces, $external)
	{
		self::$spaces = (array) $spaces;
		$this->external = (string) $external;

		spl_autoload_register(array($this, 'find'), false);
		spl_autoload_register(array($this, 'external'), false);
	}

	public function find($class)
	{
		$class = explode('\\', $class);
		// remove main namespace from class
		$common = array_shift($class);
		if ($common != 'Otaku') {
			return;
		}
		// second namespace level points to a project
		$base = array_shift($class);
		if (!isset(self::$spaces[$base])) {
			return;
		}
		$className = array_pop($class);
		$className = preg_split('/(?<!^)(?=[A-Z])/', $className);
		$class = array_merge($class, $className);
		$class = implode(SL, $class);

		require_once self::$spaces[$base] . SL . $class . '.php';
	}

	public function external($class)
	{
		$class = str_replace('\\', SL, $class);
		require_once $this->external . SL . $class . '.php';
	}

	public static function getDefaultNamespace()
	{
		$default = key(self::$spaces);
		return 'Otaku\\' . $default;
	}
}
