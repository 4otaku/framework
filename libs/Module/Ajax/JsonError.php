<?php

namespace Otaku\Framework;

class ModuleAjaxJsonError extends ModuleAjaxJson
{
	public function __construct(Query $query, $disabled = false) {
		parent::__construct($query, $disabled);
		$this->set_error(Error::INCORRECT_URL);
	}
}