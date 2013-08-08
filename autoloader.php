<?php

class Autoload
{
	protected $spaces = array();
	protected $external = '';

	public function __construct($spaces, $external)
	{
		$this->spaces = (array) $spaces;
		$this->external = (string) $external;

		spl_autoload_register(array($this, 'find'), false);
		spl_autoload_register(array($this, 'external'), false);
	}

	public function find($class)
	{
		var_dump($class);
		$class = explode('\\', $class);
		// remove main namespace from class
		$common = array_shift($class);
		if ($common != 'Otaku') {
			return;
		}
		// second namespace level points to a project
		$base = array_shift($class);
		if (!isset($this->spaces[$base])) {
			return;
		}
		$className = array_pop($class);
		$className = preg_split('/(?<!^)(?=[A-Z])/', $className);
		$class = array_merge($class, $className);
		$class = implode(SL, $class);

		require_once $this->spaces[$base] . SL . $class . '.php';
	}

	public function external($class)
	{
		require_once $this->external . SL . $class . '.php';
	}
}
