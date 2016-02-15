<?php
class ModelCatalogTovartype extends Model {
	public function addTovartype($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "tovartype SET name = '" . $this->db->escape($data['name']) . "', sort_order = '" . (int)$data['sort_order'] . "'");
		
		$tovartype_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "tovartype SET image = '" . $this->db->escape($data['image']) . "' WHERE tovartype_id = '" . (int)$tovartype_id . "'");
		}
		
		foreach ($data['tovartype_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "tovartype_description SET tovartype_id = '" . (int)$tovartype_id . "', language_id = '" . (int)$language_id . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "', seo_title = '" . $this->db->escape($value['seo_title']) . "', seo_h1 = '" . $this->db->escape($value['seo_h1']) . "'");
		}
		
		if (isset($data['tovartype_store'])) {
			foreach ($data['tovartype_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "tovartype_to_store SET tovartype_id = '" . (int)$tovartype_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
				
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'tovartype_id=" . (int)$tovartype_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		$this->cache->delete('tovartype');
	}
	
	public function editTovartype($tovartype_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "tovartype SET name = '" . $this->db->escape($data['name']) . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE tovartype_id = '" . (int)$tovartype_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "tovartype SET image = '" . $this->db->escape($data['image']) . "' WHERE tovartype_id = '" . (int)$tovartype_id . "'");
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "tovartype_description WHERE tovartype_id = '" . (int)$tovartype_id . "'");

		foreach ($data['tovartype_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "tovartype_description SET tovartype_id = '" . (int)$tovartype_id . "', language_id = '" . (int)$language_id . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "', seo_title = '" . $this->db->escape($value['seo_title']) . "', seo_h1 = '" . $this->db->escape($value['seo_h1']) . "'");
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "tovartype_to_store WHERE tovartype_id = '" . (int)$tovartype_id . "'");

		if (isset($data['tovartype_store'])) {
			foreach ($data['tovartype_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "tovartype_to_store SET tovartype_id = '" . (int)$tovartype_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
			
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'tovartype_id=" . (int)$tovartype_id. "'");
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'tovartype_id=" . (int)$tovartype_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		$this->cache->delete('tovartype');
	}
	
	public function deleteTovartype($tovartype_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "tovartype WHERE tovartype_id = '" . (int)$tovartype_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "tovartype_description WHERE tovartype_id = '" . (int)$tovartype_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "tovartype_to_store WHERE tovartype_id = '" . (int)$tovartype_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'tovartype_id=" . (int)$tovartype_id . "'");
			
		$this->cache->delete('tovartype');
	}	
	
	public function getTovartype($tovartype_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'tovartype_id=" . (int)$tovartype_id . "') AS keyword FROM " . DB_PREFIX . "tovartype WHERE tovartype_id = '" . (int)$tovartype_id . "'");
		
		return $query->row;
	}
	
	public function getTovartypes($data = array()) {
			$sql = "SELECT * FROM " . DB_PREFIX . "tovartype";
			
			$sort_data = array(
				'name',
				'sort_order'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY name";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}					

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}				
			
			$query = $this->db->query($sql);
		
			return $query->rows;
		}
	
	public function getTovartypeStores($tovartype_id) {
		$tovartype_store_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tovartype_to_store WHERE tovartype_id = '" . (int)$tovartype_id . "'");

		foreach ($query->rows as $result) {
			$tovartype_store_data[] = $result['store_id'];
		}
		
		return $tovartype_store_data;
	}
	
	public function getTotalTovartypesByImageId($image_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "tovartype WHERE image_id = '" . (int)$image_id . "'");

		return $query->row['total'];
	}

	public function getTotalTovartypes() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "tovartype");
		
		return $query->row['total'];
	}	

	public function getTovartypeDescriptions($tovartype_id) {
		$tovartype_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tovartype_description WHERE tovartype_id = '" . (int)$tovartype_id . "'");
		
		foreach ($query->rows as $result) {
			$tovartype_description_data[$result['language_id']] = array(
				'seo_title'        => $result['seo_title'],
				'seo_h1'           => $result['seo_h1'],
				'meta_keyword'     => $result['meta_keyword'],
				'meta_description' => $result['meta_description'],
				'description'      => $result['description']
			);
		}
		
		return $tovartype_description_data;
	}
	
	public function checkTable(){
		$sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "tovartype` (
				`tovartype_id`  int(11) NOT NULL AUTO_INCREMENT ,
				`name`  varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
				`image`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
				`sort_order`  int(3) NOT NULL ,
				PRIMARY KEY (`tovartype_id`)
				)
				ENGINE=MyISAM
				DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
				AUTO_INCREMENT=1082
				CHECKSUM=0
				ROW_FORMAT=DYNAMIC
				DELAY_KEY_WRITE=0
				;";
				
		$this->db->query($sql);
		
		$sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "tovartype_description` (
				`tovartype_id`  int(11) NOT NULL DEFAULT 0 ,
				`language_id`  int(11) NOT NULL DEFAULT 0 ,
				`description`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
				`meta_description`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
				`meta_keyword`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
				`seo_title`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
				`seo_h1`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
				PRIMARY KEY (`tovartype_id`, `language_id`)
				)
				ENGINE=MyISAM
				DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
				CHECKSUM=0
				ROW_FORMAT=DYNAMIC
				DELAY_KEY_WRITE=0
				;";
		
		$this->db->query($sql);		
				
		$sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "tovartype_to_store` (
				`tovartype_id`  int(11) NOT NULL ,
				`store_id`  int(11) NOT NULL ,
				PRIMARY KEY (`tovartype_id`, `store_id`)
				)
				ENGINE=MyISAM
				DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
				CHECKSUM=0
				ROW_FORMAT=FIXED
				DELAY_KEY_WRITE=0
				;";
				
		$this->db->query($sql);
		
		$sql = "show columns FROM `" . DB_PREFIX . "product` WHERE `Field` = 'tovartype_id'";
		
		$query = $this->db->query($sql);
			
		if(count($query->row) == 0){
			$sql = "ALTER TABLE `" . DB_PREFIX . "product` ADD `tovartype_id` int(11) NOT NULL";			
			$this->db->query($sql);
		}
		
	}	
}
?>