<?

	define('SL', DIRECTORY_SEPARATOR);
	define('FRAMEWORK', __DIR__);
	define('ROOT', dirname(__DIR__));
	define('ENGINE', ROOT.SL.'engine');
	define('CACHE', ROOT.SL.'cache');
	define('FILES', ROOT.SL.'files');
	define('IMAGES', ROOT.SL.'images');
	define('TEMPLATES', ROOT.SL.'templates');
	
	define('MINUTE', 60);
	define('HOUR', MINUTE * 60);
	define('DAY', HOUR * 24);
	define('WEEK', DAY * 7);
	define('MONTH', DAY * 30);
	
	define('KILOBYTE', 1024);
	define('MEGABYTE', KILOBYTE * 1024);
	define('GIGABYTE', MEGABYTE * 1024);
