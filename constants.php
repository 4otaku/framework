<?php

	define('ROOT_DIR', dirname(__DIR__));

	if (isset($_SERVER['REMOTE_ADDR'])) {
		define('DEBUG', $_SERVER['REMOTE_ADDR'] == '80.252.16.11'
			|| $_SERVER['REMOTE_ADDR'] == '127.0.0.1'
			|| $_SERVER['APP_ENV'] == 'dev');
	} else {
		define('DEBUG', 0);
	}

	define('SL', DIRECTORY_SEPARATOR);

	define('FRAMEWORK', ROOT_DIR.SL.'framework');
	define('FRAMEWORK_LIBS', FRAMEWORK.SL.'libs');
	define('FRAMEWORK_EXTERNAL', FRAMEWORK.SL.'external');

	define('LIBS', ROOT_DIR.SL.'libs');
	define('EXTERNAL', ROOT_DIR.SL.'external');
	define('CONFIG', ROOT_DIR.SL.'config');

	define('FILES', ROOT_DIR.SL.'files');
	define('IMAGES', ROOT_DIR.SL.'images');

	define('TPL', ROOT_DIR.SL.'tpl');
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

// it could be useful if you using nginx instead of apache
if (!function_exists('getallheaders'))
{
    function getallheaders()
    {
        $headers = '';
        foreach ($_SERVER as $name => $value)
        {
            if (substr($name, 0, 5) == 'HTTP_')
            {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

if( !function_exists('apache_request_headers') ) {
    function apache_request_headers() {
        $arh = array();
        $rx_http = '/\AHTTP_/';
        foreach($_SERVER as $key => $val) {
            if( preg_match($rx_http, $key) ) {
                $arh_key = preg_replace($rx_http, '', $key);
                // do some nasty string manipulations to restore the original letter case
                // this should work in most cases
                $rx_matches = explode('_', $arh_key);
                if( count($rx_matches) > 0 and strlen($arh_key) > 2 ) {
                    foreach($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst($ak_val);
                    $arh_key = implode('-', $rx_matches);
                }
                $arh[$arh_key] = $val;
            }
        }
        return( $arh );
    }
}