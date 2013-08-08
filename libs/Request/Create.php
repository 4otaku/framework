<?php

namespace Otaku\Framework;

class RequestCreate extends Request
{
	protected $priority = 2;

	public function get_api()
	{
		return 'create_' . $this->api;
	}
}
