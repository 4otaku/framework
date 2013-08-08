<?php

namespace Otaku\Framework;

abstract class Cron_Abstract
{
	protected $db;

	public function __construct(Database_Instance $db) {
		$this->db = $db;
	}

	public function execute($id, $function) {
		try {
			$time = microtime(true);
			$memory = $this->$function();
			$this->db->insert('cron_log', array(
				'id_task' => $id,
				'exec_time' => microtime(true) - $time,
				'exec_memory' => $memory,
			));
		} catch (Error_Cron $e) {
			$mail = new Mail();
			$mail->text(serialize($e))
				->send(Config::get('notify', 'mail'));
		}
	}
}
