<?php

namespace Otaku\Framework;

class DatabaseActionAdd extends DatabaseActionAbstract
{
	public function get_query_for($key)
	{
		return "`$key` = `$key`+" . (int) $this->value;
	}
}