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
	
	mkdir(ENGINE);
	touch(ENGINE.SL.$dummy_file);	
	
	mkdir(TEMPLATES);
	touch(TEMPLATES.SL.$dummy_file);	
	mkdir(TEMPLATES.SL.'html');
	touch(TEMPLATES.SL.'html'.SL.$dummy_file);
	mkdir(TEMPLATES.SL.'javascript');
	touch(TEMPLATES.SL.'javascript'.SL.$dummy_file);
	mkdir(TEMPLATES.SL.'css');
	touch(TEMPLATES.SL.'css'.SL.$dummy_file);
	mkdir(TEMPLATES.SL.'images');
	touch(TEMPLATES.SL.'images'.SL.$dummy_file);

	$gitignore = '
config/*
.htaccess
cache/*
!cache/.gitignore
files/*
!files/.gitignore
images/*
!images/.gitignore
';

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
	
	file_put_contents(ROOT.SL.'.gitignore', $gitignore);
	file_put_contents(ROOT.SL.'.htaccess', $htaccess);
