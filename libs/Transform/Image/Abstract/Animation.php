<?php

namespace Otaku\Framework;

abstract class TransformImageAbstractAnimation implements TransformImageInterface
{
	public function can_scale_animated() {
		return true;
	}
}
