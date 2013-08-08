<?php

namespace Otaku\Framework;

class Database_Action_Add extends Database_Action_Abstract
{
	public function get_query_for($key)
	{
		return "`$key` = `$key`+" . (int) $this->value;
	}
}