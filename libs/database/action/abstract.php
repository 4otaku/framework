<?php

namespace otaku\framework;

abstract class Database_Action_Abstract
{
	protected $value = null;

	public function __construct($value = null)
	{
		if ($value !== null) {
			$this->value = $value;
		}
	}

	abstract public function get_query_for($key);
}