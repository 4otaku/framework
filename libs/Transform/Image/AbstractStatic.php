<?php

namespace Otaku\Framework;

abstract class TransformImageAbstractStatic implements TransformImageInterface
{
	public function can_scale_animated() {
		return false;
	}

	public function has_next_image() {
		return false;
	}

	public function next_image() {
		throw new ErrorImage(ErrorImage::CANT_SCALE_ANIMATED);
	}

	public function write_images($path, $adjoin) {
		throw new ErrorImage(ErrorImage::CANT_SCALE_ANIMATED);
	}

	public function deconstruct_images() {
		throw new ErrorImage(ErrorImage::CANT_SCALE_ANIMATED);
	}
}
