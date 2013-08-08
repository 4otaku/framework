<?php

namespace Otaku\Framework;

abstract class Transform_Upload_Abstract
{
	protected $name;
	protected $file;
	protected $size;
	protected $unlink;
	protected $result = array('success' => true);
	protected $base_path = null;

	public function __construct($file, $name, $base_path = false) {
		if (!@is_file($file)) {
			$file = $this->create_temp_file($file);
		}

		$this->size = filesize($file);
		$this->name = $name;
		$this->file = $file;

		if (!empty($base_path)) {
			$this->base_path = $base_path;
		}
	}

	protected function get_base_path() {
		return $this->base_path;
	}

	abstract protected function process();
	abstract protected function get_max_size();

	public function process_file() {
		$this->test_file();

		$this->process();

		return $this->get_result();
	}

	protected function test_file() {
		$maxsize = $this->get_max_size();

		if ($this->size > $maxsize) {
			throw new Error_Upload(Error_Upload::FILE_TOO_LARGE);
		}
	}

	protected function get_result() {
		return $this->result;
	}

	protected function set_result($field, $value) {
		$this->result[$field] = $value;
	}

	protected function set($array) {
		foreach ($array as $field => $value) {
			$this->set_result($field, $value);
		}
	}

	protected function create_temp_file($data) {
		$temp = CACHE.SL.'tmp'.SL.microtime(true).'_'.md5(rand());

		$handle = fopen($temp, "w");
		fwrite($handle, $data);
		fclose($handle);

		$this->unlink = $temp;

		return $temp;
	}

   public function __destruct() {
		if (!empty($this->unlink)) {
			unlink($this->unlink);
		}
   }
}
