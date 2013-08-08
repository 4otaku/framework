<?php

namespace Otaku\Framework;

class ModuleContainer extends ModuleAbstract
{
	use TraitOutputContainer;

	protected $class_name;

	public function __construct($type = '', $disabled = false) {
		$type = explode('_', $type);
		$type = array_map('ucfirst', $type);
		$this->class_name = 'Module_' . implode('_', $type);

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