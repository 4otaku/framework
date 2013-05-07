<?php

class Database_Action
{
	const
		ADD = 'Add',
		SUBTRACT = 'Subtract',
		INCREMENT = 'Increment',
		DECREMENT = 'Decrement';

	public static function get($type, $value = null)
	{
		$class = 'Database_Action_' . $type;
		return new $class($value);
	}
}