<?php

namespace Otaku\Framework;

abstract class DatabaseActionAbstract
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