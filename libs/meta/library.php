<?

class Meta_Library implements Plugins
{	
	protected $items_info = array();
	
	public function __construct ($items_type = false, $items_area = false) {
		$this->items_info = array(
			'type' => $items_type, 
			'area' => $items_area ? $items_area : 'main',
		);
	}
	
	public static function parse_mixed_url($url) {
		
		return array();
	}
		
	public function get_meta_numbers($aliases, $type, $module, $area) {
		
		$condition = '`type` = ? and `module` = ? and `area` = ? and ';
		$condition .= Database::array_in('alias', $aliases);
		$params = array($type, $module, $area);
		$params = array_merge($params, $aliases);
		$found = Database::get_vector('meta_count', array('alias', 'count'), $condition, $params);

		$aliases = array_diff($aliases, array_keys($found));
		$return = array();
		
		if (!empty($aliases)) {
			foreach ($aliases as $alias) {
				$return[$alias] = self::count_meta($alias, $type, $module, $area);
			}
		}
		
		return array_merge($return, $found);
	}
	
	public static function count_meta($alias, $type, $module, $area) {
		$condition = '`area`= ? and ';
		$search = array('+', $alias, $type);
		$condition .= Database::make_search_condition('meta', array($search));
		$count = Database::get_count($module, $condition, $area);
		
		$insert = array(
			'type' => $type,
			'alias' => $alias,
			'module' => $module,
			'area' => $area,
			'count' => $count,
			'expires' => Database::unix_to_date(time() + WEEK),
		);
		
		$dont_update = array('type', 'alias', 'module', 'area');
		
		Database::replace('meta_count', $insert, $dont_update);
		
		return $count;
	}
	
	public static function add($type, $name) {

		$insert = array(
			'type' => $type,
			'alias' => self::make_alias($name),
			'name' => $name,
		);
		
		Database::insert('meta', $insert);
	}
	
	public static function make_alias($name) {

		$replace = array(
			'&amp;' => '&', 
			'&quot;' => '"', 
			'&lt;' => '<',  
			'&gt;' => '>',  
			'&092;' => '\\', 
			'&apos;' => "'", 
		);

		$alias = strtr($name, $replace);
		$alias = self::ru2lat($alias);
		$alias = self::jap2lat($alias);
		$alias = strtolower($alias);
		$alias = preg_replace('/[^a-z_\d]/','_',$alias);	
		
		return $alias;
	}
	
	/* Не трогаем - тут какая-то аццкая хрень с пробелами, работает только так */
	
	public static function jap2lat($string) {
		$replace = array('/きゃ/' => 'kya', '/きゅ/' => 'kyu', '/きょ/' => 'kyo', '/
しゃ/' => 'sha', '/しゅ/' => 'shu', '/しょ/' => 'sho', '/ちゃ/' =>
'cha', '/ちゅ/' => 'chu', '/ちょ/' => 'cho', '/にゃ/' => 'nya', '/にゅ/'
=> 'nyu', '/にょ/' => 'nyo', '/ひゃ/' => 'hya', '/ひゅ/' => 'hyu', '/
ひょ/' => 'hyo', '/みゃ/' => 'mya', '/みゅ/' => 'myu', '/みょ/' =>
'myo', '/りゃ/' => 'rya', '/りゅ/' => 'ryu', '/りょ/' => 'ryo', '/ぎゃ/'
=> 'gya', '/ぎゅ/' => 'gyu', '/ぎょ/' => 'gyo', '/じゃ/' => 'ja', '/じゅ
/' => 'ju', '/じょ/' => 'jo', '/ぢゃ/' => 'ja', '/ぢゅ/' => 'ju', '/ぢょ
/' => 'jo', '/びゃ/' => 'bya', '/びゅ/' => 'byu', '/びょ/' => 'byo', '/
ぴゃ/' => 'pya', '/ぴゅ/' => 'pyu', '/ぴょ/' => 'pyo', '/あ/' => 'a', '/
い/' => 'i', '/う/' => 'u', '/え/' => 'e', '/お/' => 'o', '/か/' =>
'ka', '/き/' => 'ki', '/く/' => 'ku', '/け/' => 'ke', '/こ/' => 'ko', '/
さ/' => 'sa', '/し/' => 'shi', '/す/' => 'su', '/せ/' => 'se', '/そ/' =>
'so', '/た/' => 'ta', '/ち/' => 'chi', '/つ/' => 'tsu', '/て/' => 'te',
'/と/' => 'to', '/な/' => 'na', '/に/' => 'ni', '/ぬ/' => 'nu', '/ね/'
=> 'ne', '/の/' => 'no', '/は/' => 'ha', '/ひ/' => 'hi', '/ふ/' => 'fu',
'/へ/' => 'he', '/ほ/' => 'ho', '/ま/' => 'ma', '/み/' => 'mi', '/む/'
=> 'mu', '/め/' => 'me', '/も/' => 'mo', '/や/' => 'ya', '/ゆ/' => 'yu',
'/よ/' => 'yo', '/ら/' => 'ra', '/り/' => 'ri', '/る/' => 'ru', '/れ/'
=> 're', '/ろ/' => 'ro', '/わ/' => 'wa', '/ゐ/' => 'wi', '/ゑ/' => 'we',
'/を/' => 'wo', '/ん/' => 'n', '/が/' => 'ga', '/ぎ/' => 'gi', '/ぐ/' =>
'gu', '/げ/' => 'ge', '/ご/' => 'go', '/ざ/' => 'za', '/じ/' => 'ji', '/
ず/' => 'zu', '/ぜ/' => 'ze', '/ぞ/' => 'zo', '/だ/' => 'da', '/ぢ/' =>
'ji', '/づ/' => 'zu', '/で/' => 'de', '/ど/' => 'do', '/ば/' => 'ba', '/
び/' => 'bi', '/ぶ/' => 'bu', '/べ/' => 'be', '/ぼ/' => 'bo', '/ぱ/' =>
'pa', '/ぴ/' => 'pi', '/ぷ/' => 'pu', '/ぺ/' => 'pe', '/ぽ/' => 'po',
'/　/'=>' ', '/っ(.)/' => '$1$1');

		return preg_replace(array_keys($replace), array_values($replace), $string);
	}
	
	public static function ru2lat($string) {
		$replace = array(
			'а'=>'a', 'б'=>'b', 'в'=>'v', 'г'=>'g', 'д'=>'d', 'е'=>'e', 'ж'=>'g', 
			'з'=>'z', 'и'=>'i', 'й'=>'y', 'к'=>'k', 'л'=>'l', 'м'=>'m', 'н'=>'n', 
			'о'=>'o', 'п'=>'p', 'р'=>'r', 'с'=>'s', 'т'=>'t', 'у'=>'u', 'ф'=>'f', 
			'ы'=>'i', 'э'=>'e', 'А'=>'A', 'Б'=>'B', 'В'=>'V', 'Г'=>'G', 'Д'=>'D', 
			'Е'=>'E', 'Ж'=>'G', 'З'=>'Z', 'И'=>'I', 'Й'=>'Y', 'К'=>'K', 'Л'=>'L', 
			'М'=>'M', 'Н'=>'N', 'О'=>'O', 'П'=>'P', 'Р'=>'R', 'С'=>'S', 'Т'=>'T', 
			'У'=>'U', 'Ф'=>'F', 'Ы'=>'I', 'Э'=>'E', 'ё'=>'yo', 'х'=>'h', 'ц'=>'ts', 
			'ч'=>'ch', 'ш'=>'sh', 'щ'=>'shch', 'ъ'=>'', 'ь'=>'', 'ю'=>'yu', 'я'=>'ya', 
			'Ё'=>'YO', 'Х'=>'H', 'Ц'=>'TS', 'Ч'=>'CH', 'Ш'=>'SH', 'Щ'=>'SHCH', 'Ъ'=>'', 
			'Ь'=>'', 'Ю'=>'YU', 'Я'=>'YA' 
		); 
		return strtr($string, $replace);
	}	
	
	// Алиасы для более удобного вызова самой частоиспользуемой вариации
		
	public function get_tag_numbers($aliases, $module, $area) {		
		return $this->get_meta_numbers($aliases, 'tag', $module, $area);		
	}
	
	public static function count_tag($alias, $module, $area) {
		return self::count_meta($alias, 'tag', $module, $area);		
	}
}
