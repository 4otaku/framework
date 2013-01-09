<?php

class Database_Action
{
	protected $type = null;

	const
		INCREMENT = '++',
		DECREMENT = '--';

	public function __construct($type)
	{
		$this->type = $type;
	}

	public function is($type)
	{
		return ($type === $this->type);
	}
}