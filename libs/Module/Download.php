<?php

namespace Otaku\Framework\Module;

use Otaku\Framework\Query;
use Otaku\Framework\TraitOutputPlain;

class Download extends Base
{
	use TraitOutputPlain;

	protected function get_modules(Query $query) {
		$type = (string) $query->get('type');
		$type = explode('_', $type);
		$type = implode('_', array_map('ucfirst', $type));
		$class = 'ModuleDownload_' . $type;

		if (!class_exists($class)) {
			return [];
		}

		return new $class($query);
	}
}
