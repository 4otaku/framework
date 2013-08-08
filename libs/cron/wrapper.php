<?php

namespace otaku\framework;

class Cron
{
	protected static $db;
	protected static $workers = array();

	public static function process($class, $function, $id = 0)
	{
		$class = 'Cron_' . ucfirst($class);

		if (empty(self::$workers[$class])) {
			self::$workers[$class] = new $class(self::$db);
		}

		self::$workers[$class]->execute($id, $function);
	}

	public static function set_db($db)
	{
		self::$db = Database::db($db);
	}

	public static function process_db($db)
	{
		self::set_db($db);

		$tasks = self::$db->order('id', 'asc')->get_table('cron',
			array('id', 'class', 'function', 'period'),
			'last_time < ?', self::$db->unix_to_date());

		foreach ($tasks as $task) {

			$period = new Text($task['period']);
			$nexttime = self::$db->unix_to_date($period->to_time() - 15);
			self::$db->update('cron', array('last_time' => $nexttime),
				'id = ?', $task['id']);

			self::process($task['class'], $task['function'], $task['id']);
		}
	}
}
