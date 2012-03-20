<?php

	define('ROOT_DIR', dirname(__DIR__));

	define('DEBUG', $_SERVER['REMOTE_ADDR'] == '80.252.16.11' ||
		$_SERVER['REMOTE_ADDR'] == '127.0.0.1');

	define('SL', DIRECTORY_SEPARATOR);

	define('FRAMEWORK', ROOT_DIR.SL.'framework');
	define('FRAMEWORK_LIBS', FRAMEWORK.SL.'libs');
	define('FRAMEWORK_EXTERNAL', FRAMEWORK.SL.'external');

	define('LIBS', ROOT_DIR.SL.'libs');
	define('EXTERNAL', ROOT_DIR.SL.'external');
	define('CONFIG', ROOT_DIR.SL.'config');

	define('FILES', ROOT_DIR.SL.'files');
	define('IMAGES', ROOT_DIR.SL.'images');

	define('HTML', ROOT_DIR.SL.'html');
	define('CSS', ROOT_DIR.SL.'css');
	define('JS', ROOT_DIR.SL.'js');
	define('CACHE', ROOT_DIR.SL.'cache');

	define('MINUTE', 60);
	define('HOUR', MINUTE * 60);
	define('DAY', HOUR * 24);
	define('WEEK', DAY * 7);
	define('MONTH', DAY * 30);

	define('KILOBYTE', 1024);
	define('MEGABYTE', KILOBYTE * 1024);
	define('GIGABYTE', MEGABYTE * 1024);
