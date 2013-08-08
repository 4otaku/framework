<?php

namespace Otaku\Framework;

abstract class ModuleAjaxJson extends ModuleAbstract
{
	use TraitOutputJson;

	protected $header = array('Content-type' => 'application/json');
}