<?
	
	// Начало работы скрипта, константы, подгрузка конфига, autoload
	
	mb_internal_encoding("UTF-8");
	
	include_once "constants.php";

	include_once "autoloader.php";

	// Подгружаем конфиг, если не нашли - бросаем ошибку,
	// т.к. сайт без конфига нежизнеспособен.
	
	$config_files = glob(ROOT.SL."config".SL."*");
	
	if (!empty($config_files)) {
		foreach ($config_files as $config_file) {
			Config::add_file($config_file);
		}
	} else {
		Error::fatal("Конфиг не найден.");
	}
	
	define("SITE_DIR", Config::main("website", "Directory"));
