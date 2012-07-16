<?php

class Cron
{
	protected static $db;
	protected static $workers = array();

	public static function process($id, $class, $function) {

		$class = 'Cron_' . $class;

		if (empty(self::$workers[$class])) {
			self::$workers[$class] = new $class(self::$db);
		}

		self::$workers[$class]->execute($id, $function);
	}

	public static function process_db($db) {
		self::$db = Database::db($db);

		$tasks = self::$db->order('id', 'asc')->get_table('cron',
			array('id', 'class', 'function', 'period'),
			'last_time < ?', $db->unix_to_date());

		foreach ($tasks as $task) {

			self::process($task['id'], $task['class'], $task['function']);

			$period = new Text($task['period']);
			$nexttime = $db->unix_to_date($period->to_time() - 15);
			self::$db->update('cron', array('last_time' => $nexttime),
				'function = ?', $task);
		}
	}
}
