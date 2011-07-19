<?

// Для вывода простых модулей и суб-модулей, не содержащих блоков

class Output_Simple extends Output implements Plugins
{
	public function process ($query) {
		$query = (array) $query;	
			
		if (empty($query['function'])) {
			$function = 'main';
		} else { 
			$function = $query['function'];
		}
		
		if (!empty($query['ajax'])) {
			$this->flags['ajax'] = true;
		}

		$this->items = $this->$function($query);
		return $this->items;
	}
	
	public function make_subquery ($query, $module) {
		unset($query['function']);
		
		$subquery = array_merge($query, array('module' => $module));
		
		return $subquery;
	}
}
