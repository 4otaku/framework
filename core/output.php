<?

abstract class Output extends Query implements Plugins
{
	// Флаги вывода, вроде размера тамбнейлов
	public $flags = array();
	
	// Для сущностей, которые надо вывести
	public $items = array();
	
	// Для дополнительных модулей, вроде шапки или последних комментариев
	public $submodules = array();
	
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

		$this->$function($query);

		if (!empty($this->items) && is_array($this->items)) {
			foreach ($this->items as & $item) {
				if (is_object($item) && is_callable(array($item, 'postprocess'))) {
					$item->postprocess();
				}
			}
		}
	
		return $this;
	}
	
	public function add_sub_data ($data, $name) {

		if (!is_object($data)) {
			$this->submodules[$name] = $data;
			
		} else {		
			$this->submodules[$name] = array(
				'items' => $data->items, 
				'flag' => $data->flags
			);
		}
	}
}
