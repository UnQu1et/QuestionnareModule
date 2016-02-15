<?php
class ModelCatalogBlogreview extends Model
{
	public function editReview($review_id, $data)
	{
		if ($review_id != "" && $data['date_added'] != "") {
			$this->db->query("UPDATE " . DB_PREFIX . "review SET   date_added = '" . $data['date_added'] . "' WHERE review_id = '" . (int) $review_id . "'");
			$this->cache->delete('product');
			$this->cache->delete('blog.module.view');
		} //$review_id != "" && $data['date_added'] != ""


		if (isset($data['af'])) {
		$sql_add='';
			$af_bool = $this->table_exists(DB_PREFIX . "review_fields");
			if ($af_bool) {
			   $i=1;
				foreach ($data['af'] as $num => $value) {

						$sql_add .= " " . $this->db->escape(strip_tags($num)) . " = '" . $this->db->escape(strip_tags($value))."' ";

                     	if ($i!=count($data['af'])) $sql_add .=",";


                    $i++;
				}
				if (substr($sql_add, -1)==',') {
				 $sql_add = substr($sql_add, 0, strlen($sql_add)-1);
				}

				if ($sql_add != "") {

					 $sql   = "SELECT * FROM  " . DB_PREFIX . "review_fields rf
						WHERE
						review_id = '" . (int) $review_id . "'
						AND
						mark = '" . $data['mark'] . "'
						LIMIT 1";
						$query = $this->db->query($sql);

                      if (count($query->rows) > 0) {
						$sql = "UPDATE " . DB_PREFIX . "review_fields SET " . $sql_add . " WHERE review_id='" . (int) $review_id . "' AND mark='".$data['mark']."' ";

                      } else {
			  		$sql = "INSERT INTO " . DB_PREFIX . "review_fields SET " . $sql_add . ", review_id='" . (int) $review_id . "', mark='".$data['mark']."' ";

					}
					$this->db->query($sql);
				} //$sql_add != ""
			} //$af_bool
		} //isset($data['af'])

	}
	public function table_exists($tableName)
	{
		$like   = addcslashes($tableName, '%_\\');
		$result = $this->db->query("SHOW TABLES LIKE '" . $this->db->escape($like) . "';");
		$found  = $result->num_rows > 0;
		return $found;
	}
	public function field_exists($tableName, $field)
	{
		$r = $this->db->query("SELECT `" . $field . "` FROM `" . DB_PREFIX . $tableName . "` WHERE 0");
		return $r;
	}
}
?>