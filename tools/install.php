<?

	include_once(dirname(__DIR__).DIRECTORY_SEPARATOR.'constants.php');
	
	$dummy_file = '.gitignore';
	
	mkdir(CACHE);
	touch(CACHE.SL.$dummy_file);
	chmod(CACHE, 0777);
	
	mkdir(FILES);
	touch(FILES.SL.$dummy_file);
	chmod(FILES, 0777);
	
	mkdir(IMAGES);
	touch(IMAGES.SL.$dummy_file);	
	chmod(IMAGES, 0777);

	$gitignore = '
config/*
.htaccess
cache/*
files/*
images/*
';
	
	if (!file_exists(ROOT.SL.'.gitignore')) {
		file_put_contents(ROOT.SL.'.gitignore', $gitignore);
	}

	if (file_exists(ROOT.SL.'sample.htaccess')) {
		$htaccess = file_get_contents(ROOT.SL.'sample.htaccess');
	} else {
		$htaccess = '
AddDefaultCharset UTF-8
php_value memory_limit 512M
php_flag short_open_tag on

<IfModule mod_rewrite.c>
	RewriteEngine On
	RewriteBase /
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteRule ^ /framework/run.php [NE,L]
</IfModule>	
	';
	} 
	
	file_put_contents(ROOT.SL.'.htaccess', $htaccess);

	if (is_dir(ROOT.SL.'sample.config') && !file_exists(ROOT.SL.'config')) {
		$files = scandir(ROOT.SL.'sample.config');
		
		mkdir(ROOT.SL.'config');
		chmod(ROOT.SL.'config', 0777);
		foreach ($files as $file) {
			
			if (pathinfo($file, PATHINFO_EXTENSION) == 'ini') {
				$text = file_get_contents(ROOT.SL.'sample.config'.SL.$file);
			
				preg_match_all('/;[ \t]*install_prompt[ \t]*=(?P<message>.*)\n(?P<anchor>.+?)=[ \t]*(?P<default>.*)/', $text, $matches, PREG_SET_ORDER);	
				
				foreach ($matches as $match) {
					
					$default = empty($match['default']) ? 
						'' : "(значение по умолчанию: {$match['default']})";
					
					print "{$match['message']} $default\n";
					flush();
					$confirmation = trim(fgets(STDIN));

					if (!empty($confirmation)) {
						$text = preg_replace('/\n'.preg_quote($match['anchor'], '/').'=.*/', "\n".$match['anchor'].'= '.$confirmation, $text);
					}
				}

				file_put_contents(ROOT.SL.'config'.SL.$file, $text);
			}
		}
	}
	
	include 'create_db.php';
