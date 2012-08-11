<?php

class Text
{
	const URL_REGEX = '/(?<!href=\")(?<!src=\")(https?|ftp):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&amp;:\/~\+#!]*[\w\-\@?^=%&amp;\/~\+#!])?(?!<\/a>)/uis';
	protected $alfavitlover = array('ё','й','ц','у','к','е','н','г', 'ш','щ','з','х','ъ','ф','ы','в', 'а','п','р','о','л','д','ж','э', 'я','ч','с','м','и','т','ь','б','ю');
	protected $alfavitupper = array('Ё','Й','Ц','У','К','Е','Н','Г', 'Ш','Щ','З','Х','Ъ','Ф','Ы','В', 'А','П','Р','О','Л','Д','Ж','Э', 'Я','Ч','С','М','И','Т','Ь','Б','Ю');

	protected $text;

	public function __construct($text) {
	    $this->text = $text;
	}

	public function lower() {
		$this->text = str_replace($this->alfavitupper,
			$this->alfavitlover, strtolower($this->text));
		return $this;
	}

	public function upper() {
		$this->text = str_replace($this->alfavitlover,
			$this->alfavitupper, strtoupper($this->text));
		return $this;
	}

	public function html_escape() {
		$this->text = htmlspecialchars($this->text,
			ENT_QUOTES, 'UTF-8', false);
		return $this;
	}

	public function trim($mask = false) {
		$this->text = $mask === false ? trim($this->text) :
			trim($this->text, $mask);
		return $this;
	}

	public function strip($mask = false) {
		$this->text = $mask === false ? strip_tags($this->text) :
			strip_tags($this->text, $mask);
		return $this;
	}

	public function cut_on($mask) {
		$this->text = substr($this->text, 0, strcspn($this->text, $mask));
		return $this;
	}

	public function to_time($add_current = true) {
		$parts = preg_split('/([^\d]+)/', $this->text, null,
			PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

		$time = 0;

		for ($i = 1; $i < count($parts); $i += 2) {
			$parts[$i] = trim($parts[$i]);
			switch ($parts[$i]) {
				case 'm': $multiplier = MINUTE; break;
				case 'h': $multiplier = HOUR; break;
				case 'd': $multiplier = DAY; break;
				case 'w': $multiplier = WEEK; break;
				case 'M': $multiplier = MONTH; break;
				default: $multiplier = 0; break;
			}

			$time = $time + $parts[$i - 1] * $multiplier;
		}

		return $time + (int) $add_current * time();
	}

	public function punto() {
		$lat = array('`','q','w','e','r','t','y','u','i', 'o','p','[',']','a','s','d','f', 'g','h','j','k','l',';','\'', 'z','x','c','v','b','n','m',',','.',
			'~','Q','W','E','R','T','Y','U','I', 'O','P','{','}','A','S','D','F', 'G','H','J','K','L',':','"', 'Z','X','C','V','B','N','M','<','>');
		$ru = array('ё','й','ц','у','к','е','н','г', 'ш','щ','з','х','ъ','ф','ы','в', 'а','п','р','о','л','д','ж','э', 'я','ч','с','м','и','т','ь','б','ю',
			'Ё','Й','Ц','У','К','Е','Н','Г', 'Ш','Щ','З','Х','Ъ','Ф','Ы','В', 'А','П','Р','О','Л','Д','Ж','Э', 'Я','Ч','С','М','И','Т','Ь','Б','Ю');
		$chars = preg_split('//u', $this->text, -1, PREG_SPLIT_NO_EMPTY);

		foreach ($chars as &$char) {
			if (in_array($char, $lat)) {
				$char = str_replace($lat, $ru, $char);
			} else {
				$char = str_replace($ru, $lat, $char);
			}
		}
		$this->text = implode('', $chars);
		return $this;
	}

	public function format() {
		$this->text = str_replace("\r", '', $this->text);
		$this->bb2html();
		$this->links2html();
		$this->text = nl2br($this->text);
		return $this;
	}

	public function links2html() {
		$this->text = preg_replace(self::URL_REGEX,
			'<a href="$0">$0</a>', $this->text);
		return $this;
	}

	public function cut_words($length = 25, $break = '<wbr />') {
		$parts = preg_split('/(<[^>]*>|\s)/', $this->text, null, PREG_SPLIT_DELIM_CAPTURE);

		foreach ($parts as $key => $part) {
			if (
				!in_array($part{0}, array(' ', "\t", "\r", "\n", '<')) &&
				strlen($part) > $length &&
				preg_match_all('/(&[a-z]{1,8};|.){' . ($length + 1) . '}/iu',
					$part, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER)
			) {
				$parts[$key] = '';
				$last_position = 0;
				foreach ($matches as $match) {
					$parts[$key] .= substr($part, $last_position, $match[1][1] - $last_position);
					$parts[$key] .= $break;
					$last_position = $match[1][1];
				}
				$parts[$key] .= substr($part, $last_position, strlen($part) - $last_position);
			}
		}

		$this->text = implode($parts);
		return $this;
	}

	public function cut_long($length, $prepend = ' ...', $cut_words = false) {
		if (
			strlen($this->text) < $length ||
			!preg_match('/^(&\p{L}{1,8};|(<.+?>)*.){0,'.$length.'}(.*)/ius', $this->text, $match)
		) {
			if (!empty($cut_words)) {
				$this->cut_words((int) $cut_words);
			}
			return $this;
		}

		preg_match_all('/<([^\s>\/]+)(?![^>]*\/>)[^>]*>/is', $match[0], $opening_tags);
		preg_match_all('/<\/([^\s>]+)[^>]*>/is', $match[0], $ending_tags);

		$tags = array();
		foreach ($opening_tags[1] as $tag_name) {
		    if (!isset($tags[$tag_name])){
				$tags[$tag_name] = 1;
		    } else {
				$tags[$tag_name]++;
		    }
		}

		foreach ($ending_tags[1] as $tag_name) {
			$tags[$tag_name]--;
		}

		$this->text = $match[0];
		if (!empty($cut_words)) {
			$this->cut_words((int) $cut_words);
		}

		foreach ($tags as $tag_name => $count) {
			if ($count > 0) {
				$this->text .= str_repeat('</'.$tag_name.'>', $count);
			} elseif ($count < 0) {
				$this->text = str_repeat('<'.$tag_name.'>', abs($count))
					. $this->text;
			}
		}

		if (strlen($match[1])) {
			$this->text .= $prepend;
		}

		return $this;
	}

	public function bb2html() {
		while (preg_match_all('/\[([a-zA-Z]*)=?([^\n]*?)\](.*?)\[\/\1\]/is', $this->text, $matches)) {
			foreach ($matches[0] as $key => $match) {
				list($tag, $param, $innertext) =
					array($matches[1][$key], $matches[2][$key], $matches[3][$key]);
				switch ($tag) {
					case 'b':
						$replacement = '<b>' . $innertext . '</b>';
						break;
					case 'i':
						$replacement = '<em>' . $innertext . '</em>';
						break;
					case 's':
						$replacement = '<s>' . $innertext . '</s>';
						break;
					case 'size':
						if ($param{0} != '+' && $param{0} != '-') {
							$param = '+' . $param;
						}
						$replacement = '<font size="' . $param . ';">' .
							$innertext . '</font>';
						break;
					case 'color':
						$replacement = '<span style="color: ' . $param . ';">' .
							$innertext . '</span>';
						break;
					case 'url':
						$replacement = '<a href="' .
							($param? $param : $innertext) . '">'.
							$innertext . '</a>';
						break;
					case 'img':
						$param = explode('x', strtolower($param));
						$replacement = '<img src="' .
							$innertext . '" ' .
							(is_numeric($param[0]) ? 'width="' . $param[0] . '" ' : '') .
							(is_numeric($param[1]) ? 'height="' . $param[1] . '" ' : '') .
							'/><br />';
						break;
			        case 'spoiler':
						$replacement = '<div class="mini-shell"><div class="handler" width="100%">' .
							'<span class="sign">↓</span> <a href="#" class="disabled">' .
							str_replace(array('[', ']'), array('', ''), $param) . '</a></div>' .
							'<div class="text hidden">' . ltrim($innertext) . '</div></div>';
						break;
				}
				$this->text = str_replace($match, $replacement, $this->text);
			}
		}
		return $this;
	}

	public function get_text() {
		return $this->__toString();
	}

	public function __toString() {
		return $this->text;
	}
}
