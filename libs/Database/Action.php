<?php

namespace Otaku\Framework;

class DatabaseAction
{
	const
		ADD = 'Add',
		SUBTRACT = 'Subtract',
		INCREMENT = 'Increment',
		DECREMENT = 'Decrement';

	public static function get($type, $value = null)
	{
		$class = __NAMESPACE__ . '\DatabaseAction' . $type;
		return new $class($value);
	}
}