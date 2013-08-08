<?php

namespace Otaku\Framework\Module;

use Otaku\Framework\TraitOutputPlain;

class AjaxError extends Base
{
	use TraitOutputPlain;
	protected $header = ['status' => 404];
}