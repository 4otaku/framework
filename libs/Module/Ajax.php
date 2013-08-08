<?php

namespace Otaku\Framework;

class ModuleAjax extends ModuleAbstract
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
				return new ModuleAjaxJsonError($query);
			} else {
				return new ModuleAjaxError($query);
			}
		}

		return new $class($query);
	}
}