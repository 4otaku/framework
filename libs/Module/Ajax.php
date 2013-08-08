<?php

namespace Otaku\Framework;

class Module_Ajax extends Module_Abstract
{
	use Trait_Output_Plain;

	protected function get_modules(Query $query) {
		$url = $query->url();
		array_shift($url);
		$url = array_filter($url);

		if (empty($url)) {
			return array();
		}

		$class = implode('_', array_map('ucfirst', $url));
		$class = 'Module_Ajax_' . $class;

		if (!class_exists($class)) {
			if ($query->get('format') == 'json') {
				return new Module_Ajax_Json_Error($query);
			} else {
				return new Module_Ajax_Error($query);
			}
		}

		return new $class($query);
	}
}