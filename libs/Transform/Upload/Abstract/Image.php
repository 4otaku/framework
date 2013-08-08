<?php

namespace Otaku\Framework;

abstract class TransformUploadAbstractImage extends TransformUploadAbstractHaveImage
{
	protected function test_file() {
		parent::test_file();

		if (!is_array($this->info)) {
			throw new ErrorUpload(ErrorUpload::NOT_AN_IMAGE);
		}
	}
}
