<?php

namespace Otaku\Framework\Module;

use Otaku\Framework\TraitOutputContainer;
use Otaku\Framework\QueryDummy;

class Container extends Base
{
	use TraitOutputContainer;

	protected $class_name;

	public function __construct($type = '', $disabled = false) {
		$this->class_name = $type;

		parent::__construct(new QueryDummy(), $disabled = false);
	}

	public function recieve_data($data) {
		$class_name = $this->class_name;
		$dummy = new QueryDummy();
		foreach ($data as $value) {
			$module = new $class_name($dummy);
			$module->recieve_data($value);
			$this->modules[] = $module;
		}
	}
}