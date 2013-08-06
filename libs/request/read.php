<?php

class Request_Read extends Request implements Request_Groupable
{
	public function can_group_with(Request $request)
	{
		return ($request instanceof Request_Read);
	}

	public function get_grouped_request($requests)
	{
		$grouped = new Request_Read_Multi();
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