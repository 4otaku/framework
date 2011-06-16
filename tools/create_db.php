<?
	include_once(dirname(__DIR__).DIRECTORY_SEPARATOR.'init.php');

	$dump = file_get_contents(ROOT.SL.'dump'.SL.Config::database('main', 'Type').'.sql');	
	$queries = array_filter(explode(';', $dump));

	foreach ($queries as $query) {
		if (trim($query)) {
			Database::sql($query);
		}
	}
