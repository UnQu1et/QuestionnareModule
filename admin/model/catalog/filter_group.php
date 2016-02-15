<?php 
class ModelCatalogFilterGroup extends Model {
	public function addFilterGroup($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "filter_group` SET sort_order = '" . (int)$data['sort_order'] . "'");
		
		$filter_group_id = $this->db->getLastId();
		
		foreach ($data['filter_group_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "filter_group_description` SET filter_group_id = '" . (int)$filter_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
		
		if (isset($data['category_id'])) {
			foreach ($data['category_id'] as $category_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "filter_group_to_category` SET filter_group_id = '" . (int)$filter_group_id . "', category_id = '" . (int)$category_id . "'");
			}
		}
	}

	public function editFilterGroup($filter_group_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "filter_group` SET sort_order = '" . (int)$data['sort_order'] . "' WHERE filter_group_id = '" . (int)$filter_group_id . "'");
		
		$this->db->query("DELETE FROM `" . DB_PREFIX . "filter_group_description` WHERE filter_group_id = '" . (int)$filter_group_id . "'");

		foreach ($data['filter_group_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "filter_group_description` SET filter_group_id = '" . (int)$filter_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "filter_group_to_category WHERE filter_group_id = '" . (int)$filter_group_id . "'");
		
		if (isset($data['category_id'])) {
			foreach ($data['category_id'] as $category_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "filter_group_to_category` SET filter_group_id = '" . (int)$filter_group_id . "', category_id = '" . (int)$category_id . "'");
			}
		}
	}
	
	public function deleteFilterGroup($filter_group_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "filter_group` WHERE filter_group_id = '" . (int)$filter_group_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "filter_group_description` WHERE filter_group_id = '" . (int)$filter_group_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "filter_group_to_category` WHERE filter_group_id = '" . (int)$filter_group_id . "'");
	}
		
	public function getFilterGroup($filter_group_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "filter_group` WHERE filter_group_id = '" . (int)$filter_group_id . "'");
		
		return $query->row;
	}
		
	public function getFilterGroups($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "filter_group` ag LEFT JOIN `" . DB_PREFIX . "filter_group_description` agd ON (ag.filter_group_id = agd.filter_group_id) WHERE agd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			
		$sort_data = array(
			'agd.name',
			'ag.sort_order'
		);	
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY agd.name";	
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
		
		$group_data = array();
		
		foreach ($query->rows as $group) {
			
			$category_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category` c LEFT JOIN `" . DB_PREFIX . "filter_group_to_category` cotc ON (c.category_id = cotc.category_id) LEFT JOIN `" . DB_PREFIX . "category_description` cd ON (cd.category_id = cotc.category_id) WHERE cotc.filter_group_id = '" . (int)$group['filter_group_id'] . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			$group_data[] = array(
				'filter_group_id' => $group['filter_group_id'],
				'name'            => $group['name'],
				'categories'      => $category_query->rows,
				'sort_order'      => $group['sort_order']
			);
			
			
		}
		
		return $group_data;
	}
	
	
	public function getFilterGroupDescriptions($filter_group_id) {
		$filter_group_data = array();
		
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "filter_group_description` WHERE filter_group_id = '" . (int)$filter_group_id . "'");
		
		foreach ($query->rows as $result) {
			$filter_group_data[$result['language_id']] = array('name' => $result['name']);
		}
		
		return $filter_group_data;
	}
	
	public function getGroupCategories($filter_group_id) {
		$categories_id = array();

		$query = $this->db->query("SELECT c.category_id AS category_id FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "filter_group_to_category cotc ON (c.category_id = cotc.category_id) WHERE cotc.filter_group_id = '" . (int)$filter_group_id . "'");

		foreach ($query->rows as $result) {
			$categories_id[] = $result['category_id'];
		}

		return $categories_id;
	}
	
	public function getTotalFilterGroups() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "filter_group`");
		
		return $query->row['total'];
	}	
}
?>