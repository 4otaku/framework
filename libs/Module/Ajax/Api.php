<?php

namespace Otaku\Framework\Module;

abstract class AjaxApi extends AjaxJson
{
	public function recieve_data($data) {
		$this->set_success($data['success']);
		if (!empty($data['errors'])) {
			foreach ($data['errors'] as $error) {
				$this->set_error($error['code'], $error['message']);
			}
		}
	}
}

