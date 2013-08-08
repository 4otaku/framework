<?php

namespace Otaku\Framework;

abstract class CronAbstract
{
	protected $db;

	public function __construct(DatabaseInstance $db) {
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
		} catch (Error $e) {
			$mail = new Mail();
			$mail->text(serialize($e))
				->send(Config::getInstance()->get('notify', 'mail'));
		}
	}
}
