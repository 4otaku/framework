<?php

namespace Otaku\Framework;

class Request_Update extends Request
{
	protected $priority = 1;

	public function get_api()
	{
		return 'update_' . $this->api;
	}
}
