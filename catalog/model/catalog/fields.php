<?php
class ModelCatalogFields extends Model
{
	public function getFields($data = array())
	{
		$sql   = "SELECT * FROM  " . DB_PREFIX . "review_fields rf
		WHERE
		review_id = '" . (int)$data['review_id'] . "'
		AND
		mark = '" . $data['mark'] . "'
		LIMIT 1";
		$query = $this->db->query($sql);
		return $query->rows;
	}


	public function getFieldsDB()
	{
        /*
		 $sql = "SELECT  f.*,fd.* FROM " . DB_PREFIX . "fields f
			LEFT JOIN " . DB_PREFIX . "fields_description fd ON (f.field_id = fd.field_id)
			WHERE fd.language_id = '" . (int) $this->config->get('config_language_id') . "'
			GROUP BY f.field_id
			ORDER BY f.field_order DESC ";
        */

		 $sql = "SELECT  f.* FROM " . DB_PREFIX . "fields f
			GROUP BY f.field_id
			ORDER BY f.field_order DESC ";


			$query = $this->db->query($sql);

			return $query->rows;

	}

	public function getDesc()
	{
		 $sql = "SELECT  * FROM " . DB_PREFIX . "fields_description";
		 $query = $this->db->query($sql);

		 return $query->rows;
	}


	public function getFieldsDBlang()
	{
         $fields = $this->getFieldsDB();
         $desc   = $this->getDesc();

         $fields_new = $fields;
		 foreach ($fields as $num => $field) {
            foreach ($desc as $num_desc => $field_desc) {
          	if ($field['field_id'] == $field_desc['field_id']) {
                 $fields_new[$num]['field_description'][$field_desc['language_id']] = $field_desc['field_description'];
                 $fields_new[$num]['field_error'][$field_desc['language_id']] 		= $field_desc['field_error'];
                 $fields_new[$num]['field_value'][$field_desc['language_id']] 		= $field_desc['field_value'];
                }
		 	}

		 }
		return $fields_new;
	}



 	public function getFieldsAll()
	{
		$sql   = "SELECT * FROM  " . DB_PREFIX . "review_fields rf	LIMIT 1";
		$query = $this->db->query($sql);
        $data['fields'] = array();
    	foreach ($query->rows as $val) {
	           	foreach ($val as $field => $key) {
	            		if ($field!="mark" && $field!="review_id") {
	            		$data['fields'][]['field_name'] = $field;
	            	}

	           	}
	    }

		return $data['fields'];
	}

	public function getField($field_id)
	{
		$query = $this->db->query("SELECT DISTINCT *
		FROM " . DB_PREFIX . "fields f
		LEFT JOIN " . DB_PREFIX . "fields_description fd ON (f.field_id = fd.field_id)
		WHERE f.field_id = '" . (int) $field_id . "' AND fd.language_id = '" . (int) $this->config->get('config_language_id') . "'");
		return $query->row;
	}

	public function getFieldOnly($field_id)
	{
		$query = $this->db->query("SELECT DISTINCT *
		FROM " . DB_PREFIX . "fields f
		WHERE f.field_id = '" . (int) $field_id . "'");
		return $query->row;
	}


	public function getFieldDescriptions($field_id)
	{
		$field_description_data = array();
		$query                   = $this->db->query("SELECT * FROM " . DB_PREFIX . "fields_description WHERE field_id = '" . (int) $field_id . "'");

		foreach ($query->rows as $result) {
			$field_description_data[$result['language_id']] = array(
				'field_error' => $result['field_error'],
				'field_value' => $result['field_value'],
				'field_description' => $result['field_description']
			);
		}
		return $field_description_data;
	}



	public function table_exists($tableName)
	{
		$found= false;
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


	public function editField($field_id, $data)
	{

    	if (!isset($data['field_must'])) {
	     $data['field_must'] = 0;
		}


		if ($this->db->escape($data['field_type']) == 'text') {
			$type_field = "VARCHAR";
			$type_filed_end = "(255) COLLATE utf8_general_ci NOT NULL";
		}

		if ($this->db->escape($data['field_type']) == 'textarea') {
          	$type_field = "TEXT";
          	$type_filed_end = " COLLATE utf8_general_ci NOT NULL";
		}


		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "review_fields `".$this->db->escape($data['field_name'])."`");
		if ($r->num_rows > 0 && strtoupper($r->row['Type']) != strtoupper ($type_field) ) {
			$msql  = "ALTER TABLE `" . DB_PREFIX . "review_fields`
			CHANGE `".$this->db->escape($data['field_name'])."` `".$this->db->escape($data['field_name'])."`
			" .$type_field.$type_filed_end;
			$query = $this->db->query($msql);
		}


       $data_info = $this->getFieldOnly($field_id);

		if ($data['field_name']!=$data_info['field_name']) {
         $msql  = "ALTER TABLE `" . DB_PREFIX . "review_fields`
			CHANGE `".$this->db->escape($data_info['field_name'])."` `".$this->db->escape($data['field_name'])."`
			" .$type_field.$type_filed_end;
			$query = $this->db->query($msql);

		}

		$sql = "UPDATE " . DB_PREFIX . "fields SET
		field_name = '" . $this->db->escape($data['field_name']) . "',
		field_image = '" . $this->db->escape($data['field_image']) . "',
		field_type = '" . $this->db->escape($data['field_type']) . "',
		field_must = '" . (int) $data['field_must'] . "',
		field_status = '" . (int) $data['field_status'] . "',
		field_order = '" . (int) $data['field_order'] . "'

		WHERE field_id = '" . (int) $field_id . "'";
		$this->db->query($sql);

		$this->db->query("DELETE FROM " . DB_PREFIX . "fields_description WHERE field_id = '" . (int) $field_id . "'");

		foreach ($data['fields_description'] as $language_id => $value) {
			$this->db->query("
			INSERT INTO " . DB_PREFIX . "fields_description SET
			field_id = '" . (int) $field_id . "',
			language_id = '" . (int) $language_id . "',
			field_description = '" . $this->db->escape($value['field_description']) . "',
            field_error = '" . $this->db->escape($value['field_error']) . "',
            field_value = '" . $this->db->escape($value['field_value']) . "'"

			);
		}

		$this->cache->delete('fields');
		$this->cache->delete('blog.module.view');
	}


	public function addField($data)
	{

		if ($this->db->escape($data['field_type']) == 'text') {
			$type_field = "VARCHAR";
			$type_filed_end = "(255) COLLATE utf8_general_ci NOT NULL";
		}

		if ($this->db->escape($data['field_type']) == 'textarea') {
          	$type_field = "TEXT";
          	$type_filed_end = " COLLATE utf8_general_ci NOT NULL";
		}


		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "review_fields `".$this->db->escape($data['field_name'])."`");
		if ($r->num_rows == 0) {
			$msql  = "ALTER TABLE `" . DB_PREFIX . "review_fields`
			ADD `".$this->db->escape($data['field_name'])."`
			" .$type_field.$type_filed_end;
			$query = $this->db->query($msql);
		}

		$sql = "INSERT INTO " . DB_PREFIX . "fields SET
		field_name = '" . $this->db->escape($data['field_name']) . "'";
		$this->db->query($sql);
 		$data['field_id'] = $this->db->getLastId();
        $this->request->get['field_id'] = $data['field_id'];
        $this->editField($data['field_id'], $data);


		$this->cache->delete('fields');
		$this->cache->delete('blog.module.view');
	}

 	public function deleteField($field_id) {

		$data = $this->getFieldOnly($field_id);


		if (isset($data['field_name'])) {
			$r = $this->db->query("DESCRIBE " . DB_PREFIX . "review_fields `".$this->db->escape($data['field_name'])."`");
			if ($r->num_rows > 0) {
				$msql  = "ALTER TABLE `" . DB_PREFIX . "review_fields` DROP `".$this->db->escape($data['field_name'])."`";
				$query = $this->db->query($msql);
			}
		}

		//$r = $this->db->query("DESCRIBE " . DB_PREFIX . "fields `".$this->db->escape($data['field_name'])."`");
		//if ($r->num_rows > 0)
		{
			$msql  = "DELETE FROM " . DB_PREFIX . "fields WHERE `field_id` = ".(int)$field_id."";
			$query = $this->db->query($msql);
			$msql  = "DELETE FROM " . DB_PREFIX . "fields_description WHERE `field_id` = ".(int)$field_id."";
			$query = $this->db->query($msql);
		}



 	}

 	public function copyField($field_id) {

		$data = $this->getFieldOnly($field_id);
        $data['fields_description'] = $this->getFieldDescriptions($field_id);

                $prefix = '';

                $prefix_str = str_replace("_","",md5($data['field_name']));
                $prefix_str = preg_replace ("/[^a-z\s]/","",$prefix_str);
                $prefix_array = preg_split('//', $prefix_str, -1, PREG_SPLIT_NO_EMPTY);
                shuffle($prefix_array);
                $prefix =  substr(implode($prefix_array), 0, 3);

		$data['field_name'] = $data['field_name']."_".$prefix;
		$this->addField($data);



 	}



}
?>