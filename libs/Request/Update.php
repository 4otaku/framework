<?php

namespace Otaku\Framework;

class RequestUpdate extends Request
{
	protected $priority = 1;

	public function get_api()
	{
		return 'update_' . $this->api;
	}
}
