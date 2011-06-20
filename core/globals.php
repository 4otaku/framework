<?

final class Globals implements Plugins
{
	// Для загруженных данных
	public static $vars = array();
	
	// Для загруженных файлов
	public static $files = array();
	
	// Для адреса запроса
	public static $url = array();	
	
	// Для алиасов урла
	public static $url_aliases = array();
	
	// Для информации о пользователе
	public static $user_data = array();
	
	// Настройки пользователя
	public static $preferences = false;
	
	static private $safe_replacements = array(
		'&' => '&amp;',
		'"' => '&quot;',
		'<' => '&lt;',
		'>' => '&gt;',
		'?' => '&#63;',
		'\\' => '&#092;',
		"'" => '&apos;',
	);
	
	public static function get_vars($data) {
		$data = self::clean_globals($data);
		self::$vars = array_replace_recursive(self::$vars, $data);
	}
	
	public static function get_files() {
		self::$files = $_FILES;
		unset($_FILES);
	}
	
	public static function get_url($request) {

		$request = preg_replace('/^'.preg_quote(SITE_DIR,'/').'/', '', $request);
		$url = explode('/', preg_replace('/\?[^\/]+$/', '', $request)); 
		
		$url = array_values(array_filter($url));
		
		if (empty($url)) {
			$url = array('index');
		}
		
		$aliases = Config::alias();
		foreach ($aliases as $key => $alias) {
			if (empty($alias['from']) || empty($alias['to']) || !is_numeric($alias['position'])) {
				unset($aliases[$key]);
				continue;
			}
			
			$aliases[$key]['from'] = trim($alias['from'], '/');
			$aliases[$key]['to'] = explode('/', trim($alias['to'], '/'));
		}
		
		foreach ($url as $id => $section) {
			foreach ($aliases as $alias) {
				if ($alias['from'] == $section && $alias['position'] == $id + 1) {
					array_splice($url, $id, 1, $alias['to']);
				}
			}
		}
		
		self::$url = $url;
		self::$url_aliases = $aliases;
	}
	
	public static function get_user($user_data) {
		self::$user_data = $user_data;
	}
	
	public static function clean_globals(&$data, $iteration = 0) {
		if ($iteration > 10 || !is_array($data)) {
			return;
		}
		
		$return = array();

		foreach ($data as $key => $value) {
			$new_key = str_replace(
				array_keys(self::$safe_replacements),
				array_values(self::$safe_replacements),
				$key);

			if (is_array($value)) {
				$return[$new_key] = self::clean_globals($data[$key], ++$iteration);
			} else {
				$value = stripslashes($value);
				
				$value = str_replace(
					array_keys(self::$safe_replacements),
					array_values(self::$safe_replacements),
					$value);				
				
				$value = str_replace(chr('0'),'',$value);
				$value = str_replace("\0",'',$value);
				$value = str_replace("\x00",'',$value);
				$value = str_replace('%00','',$value);
				$value = str_replace("../","&#46;&#46;/",$value);
				
				$return[$new_key] = $value;
			}
		}
		
		return $return;
	}
	
	public static function user() {
		
		if (self::$preferences === false) {			
			$user_data = Cookie::get_preferences(self::$user_data['cookie']);
			
			$module = Query::get_module(self::$url, self::$vars);
			if (!empty($user_data[$module])) {
				$user_data['settings'] = $user_data[$module];
			}
					
			self::$preferences = $user_data;
		}
		
		$preferences = self::$preferences;
		$config = Config::data();
		$arguments = func_get_args();
		
		$preferences = self::search_array($preferences, $arguments);
		$config = self::search_array($config, $arguments);
		
		if (!is_array($preferences)) {
			return $preferences === null ? $config : $preferences;
		}
		
		return array_replace_recursive($config, $preferences);
	}
	
	public static function search_array($data, $arguments) {
		while (!empty($arguments)) {
			$argument = array_shift($arguments);
			
			if (!isset($data[$argument])) {
				return null;
			}
			
			$data = $data[$argument];
		}
		
		return $data;
	}	
	
	public static function user_info() {
		$arguments = func_get_args();
		
		array_unshift($arguments, 'info');
		
		return call_user_func_array(array('self', 'user'), $arguments);
	}
	
	public static function user_settings() {
		$arguments = func_get_args();
		
		array_unshift($arguments, 'settings');
		
		return call_user_func_array(array('self', 'user'), $arguments);
	}
}