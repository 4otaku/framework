<?php

class Request_Read_Multi extends Request
{

	public function get_api()
	{
		return 'read_multi';
	}

	public function prepare()
	{
		$return = array();
		foreach ($this->requests as $request) {
			$data = $request->get_data();
			$data['api'] = $request->api;
			$return[$request->get_hash()] = $data;
		}

		return $return;
	}

	protected function process_response($response)
	{
		foreach ($response as $hash => $data) {
			if (isset($this->requests[$hash])) {
				$this->requests[$hash]->pass_data($data);
				unset($this->requests[$hash]);
			}
		}
	}
}