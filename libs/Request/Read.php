<?php

namespace Otaku\Framework;

class RequestRead extends Request implements RequestGroupable
{
	public function can_group_with(Request $request)
	{
		return ($request instanceof RequestRead);
	}

	public function get_grouped_request($requests)
	{
		$grouped = new RequestReadMulti();
		$grouped->add($this);
		foreach ($requests as $request) {
			$grouped->add($request);
		}
		return $grouped;
	}

	public function get_api()
	{
		return 'read_' . $this->api;
	}
}