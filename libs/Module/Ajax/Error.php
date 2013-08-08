<?php

namespace Otaku\Framework;

class ModuleAjaxError extends ModuleAbstract
{
	use TraitOutputPlain;
	protected $header = ['status' => 404];
}