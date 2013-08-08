<?php

namespace Otaku\Framework;

class RequestDelete extends Request
{
	protected $priority = 3;

	public function get_api()
	{
		return 'delete_' . $this->api;
	}
}
