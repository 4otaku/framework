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
	}

	public function find($class)
	{
		$class = explode('\\', $class);
		// remove main namespace from classname
		array_shift($class);
		// second namespace level points to a project
		$base = array_shift($class);
		if (!isset($this->spaces[$base])) {
			return;
		}
		$classname = array_pop($class);
		$classname = preg_split('/(?<!^)(?=[A-Z])/', $classname);
		$class = array_merge($class, $classname);
		$class = implode(SL, $class);

		require_once $this->spaces[$base] . SL . $class . '.php';
	}
}
