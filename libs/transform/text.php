<?php

class Transform_Text
{
	protected static $alfavit_lower = array('ё','й','ц','у','к','е','н','г', 'ш','щ','з','х','ъ','ф','ы','в', 'а','п','р','о','л','д','ж','э', 'я','ч','с','м','и','т','ь','б','ю');
	protected static $alfavit_upper = array('Ё','Й','Ц','У','К','Е','Н','Г', 'Ш','Щ','З','Х','Ъ','Ф','Ы','В', 'А','П','Р','О','Л','Д','Ж','Э', 'Я','Ч','С','М','И','Т','Ь','Б','Ю');

	public static function strtolower_ru($text) {
		return str_replace(self::$alfavit_upper,
			self::$alfavit_lower, strtolower($text));
	}

	public static function format_search($text) {
		$text = self::strtolower_ru($text);

		$replacements = array('/([^\p{L}\d\-]|　)/u',
			'/\-(?![\p{L}\d])/','/ +/');

		$text = preg_replace($replacements, ' ', $text);

		return trim($text);
	}
}
