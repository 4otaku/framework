<?php

namespace Otaku\Framework;

class Request
{
	protected $api = false;
	protected $data = array();
	protected $hash = false;
	protected $binded = array();
	/**
	 * @var Request[]
	 */
	protected $requests = array();
	protected $priority = 0;

	public function __construct($api = false, $object = false, $data = array(),
	                            $method = 'recieve_data')
	{
		if ($api) {
			$this->api = (string) $api;
		}

		$this->data = (array) $data;
		$this->data['cookie'] = Session::get_instance()->get_hash();
		$this->data['ip'] = Session::get_instance()->get_ip();

		if ($object && is_callable(array($object, $method))) {
			$this->hash = md5($this->api . serialize($this->data));

			$this->bind(array($object, $method));
		}
	}

	public function add(Request $request)
	{
		$hash = $request->get_hash();
		$new_requests = $request->extract_children();

		if ($this->get_hash() == $hash) {
			foreach ($request->get_binded() as $object) {
				$this->bind($object);
			}
			unset($request);
		} else {
			if (isset($this->requests[$hash])) {
				foreach ($request->get_binded() as $callback) {
					$this->requests[$hash]->bind($callback);
				}
				unset($request);
			} else {
				$this->requests[$hash] = $request;
			}
		}

		foreach ($new_requests as $new_request) {
			$this->add($new_request);
		}
	}

	public function perform()
	{
		$requests = array_values($this->requests);
		$requests[] = $this;

		// Собираем groupable реквесты
		$count = count($requests);
		for ($i = 0; $i < $count; $i++) {
			if (!isset($requests[$i])) {
				continue;
			}

			$request = $requests[$i];
			if (!($request instanceof Request_Groupable)) {
				continue;
			}
			$group = array();
			for ($j = $i + 1; $j < $count; $j++) {
				if ($request->can_group_with($requests[$j])) {
					$group[] = $requests[$j];
					unset($requests[$j]);
				}
			}
			if (!empty($group)) {
				$requests[$i] = $request->get_grouped_request($group);
			}
		}

		usort($requests, function($a, $b){
			return $a->get_priority() < $b->get_priority();
		});

		foreach ($requests as $request) {
			$data = $request->prepare();
			$request->do_request($data);
		}

		$this->requests = array();
	}

	public function do_request($data)
	{
		$url = Config::get('api', 'url');
		$api = $this->get_api();

		if (!$api) {
			return;
		}

		if (!Config::get('api', 'inner')) {
			$url .= '/' . str_replace('_', '/', $api);

			if (function_exists('igbinary_serialize')) {
				$data['format'] = 'igbinary';
				$data = igbinary_serialize($data);
				$url .= '?f=igbinary';
			} else {
				$data['format'] = 'json';
				$data = json_encode($data, JSON_NUMERIC_CHECK);
				$url .= '?f=json';
			}

			$response = Http::post($url, $data);

			if (empty($response)) {
				throw new Error('No response: ' . $url);
			}

			if (function_exists('igbinary_unserialize')) {
				$response = igbinary_unserialize($response);
			} else {
				$response = json_decode($response, true);
			}
		} else {
			$class = 'Api_' . implode('_', array_map('ucfirst',
					explode('_', $api)));
			$api_request = new Api_Request_Inner($data);
			$worker = new $class($api_request);
			$response = $worker->process_request()->get_response();
		}

		$this->process_response($response);
	}

	protected function process_response($response)
	{
		$this->pass_data($response);
	}

	public function prepare()
	{
		return $this->get_data();
	}

	public function bind($callback)
	{
		foreach ($this->binded as $binded) {
			if ($binded === $callback) {
				return;
			}
		}

		$this->binded[] = $callback;
	}

	public function get_hash()
	{
		return $this->hash;
	}

	public function get_api()
	{
		return $this->api;
	}

	public function get_data()
	{
		return $this->data;
	}

	public function get_binded()
	{
		return $this->binded;
	}

	public function get_priority()
	{
		return $this->priority;
	}

	public function extract_children()
	{
		$children = array_values($this->requests);
		$this->requests = array();
		return $children;
	}

	public function pass_data($data)
	{
		foreach ($this->binded as $callback) {
			$object = $callback[0];
			$method = $callback[1];
			$object->$method($data);
		}
	}
}