<?php

namespace Otaku\Framework\Module;

use Otaku\Framework\TraitOutputJson;

abstract class AjaxJson extends Base
{
	use TraitOutputJson;

	protected $header = array('Content-type' => 'application/json');
}