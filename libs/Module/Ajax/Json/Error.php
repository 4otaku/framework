<?php

namespace Otaku\Framework\Module;

use Otaku\Framework\Query;
use Otaku\Framework\Error;

class AjaxJsonError extends AjaxJson
{
	public function __construct(Query $query, $disabled = false) {
		parent::__construct($query, $disabled);
		$this->set_error(Error::INCORRECT_URL);
	}
}