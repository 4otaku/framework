<?php

namespace otaku\framework;

class Request_Create extends Request
{
	protected $priority = 2;

	public function get_api()
	{
		return 'create_' . $this->api;
	}
}
