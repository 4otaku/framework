<?
	include_once FRAMEWORK.'init.php';

	$dump = file_get_contents(ROOT.SL.'dump'.SL.Config::database('Type').'.sql');	
	$queries = array_filter(explode(';', $dump));

	foreach ($queries as $query) {
		if (trim($query)) {
			Objects::db()->sql($query);
		}
	}
