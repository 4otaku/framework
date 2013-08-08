<?php

namespace Otaku\Framework;

class TransformImage
{
	protected static $worker_name;

	public static function get_worker($path) {
		try {
			$name = self::get_worker_name();

			return new $name($path);
		} catch (Exception $e) {
			throw new ErrorImage(ErrorImage::BROKEN_IMAGE);
		}
	}

	protected static function get_worker_name() {
		if (empty(self::$worker_name)) {
			if (!class_exists('Imagick', false)) {
				self::$worker_name = 'TransformImageGd';
			} else {
				self::$worker_name = 'TransformImageImagick';
			}
		}

		return self::$worker_name;
	}
}
