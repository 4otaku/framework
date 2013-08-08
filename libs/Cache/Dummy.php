<?php

namespace Otaku\Framework;

class CacheDummy implements CacheInterfaceSingle, CacheInterfaceArray
{
	public $able_to_work = true;

	public function set ($key, $value, $expire) {}

	public function set_array ($keys, $values, $expire) {}

	public function get ($key) {
		return null;
	}

	public function get_array ($keys) {
		return array();
	}

	public function delete ($key) {}

	public function delete_array ($keys) {}

	public function increment ($key, $value) {}

	public function increment_array ($keys, $value) {}

	public function decrement ($key, $value) {}

	public function decrement_array ($keys, $value) {}
}
