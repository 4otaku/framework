<?php

namespace Otaku\Framework;

class Module_Ajax_Json_Error extends Module_Ajax_Json
{
	public function __construct(Query $query, $disabled = false) {
		parent::__construct($query, $disabled);
		$this->set_error(Error::INCORRECT_URL);
	}
}