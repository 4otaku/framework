<?php

namespace Otaku\Framework;

trait TraitOutputPlain
{
	protected function format_data() {
		return isset($this->params['module_0']) ?
			$this->params['module_0'] : '';
	}
}
