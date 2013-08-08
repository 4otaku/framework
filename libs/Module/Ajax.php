<?php

namespace Otaku\Framework\Module;

use Otaku\Framework\Query;
use Otaku\Framework\TraitOutputPlain;

class Ajax extends Base
{
	use TraitOutputPlain;

	protected function get_modules(Query $query) {
		$url = $query->url();
		array_shift($url);
		$url = array_filter($url);

		if (empty($url)) {
			return array();
		}

		$class = implode('_', array_map('ucfirst', $url));
		$class = 'ModuleAjax_' . $class;

		if (!class_exists($class)) {
			if ($query->get('format') == 'json') {
				return new AjaxJsonError($query);
			} else {
				return new AjaxError($query);
			}
		}

		return new $class($query);
	}
}