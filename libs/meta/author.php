<?

class Meta_Author extends Meta_Library implements Plugins
{
	public function get_data_by_alias ($aliases) {
		$condition = "type='author' and ".Database::array_in('alias', $aliases);

		$select = array('alias','name');

		return Database::get_vector('meta', $select, $condition, $aliases, false);
	}	

	public function get_alias_by_name ($name) {
		$condition = "type='author' and name=?";

		return Database::get_field('meta', 'alias', $condition, $name);
	}
}
