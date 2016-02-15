<?php
class ModelCatalogPricelist extends Model {
  	public function getTotalProducts() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE status = '1' AND date_available <= NOW()");

		return $query->row['total'];
	}

  	public function getPricelistProducts($sort = 'pd.name', $order = 'ASC', $start = 0, $limit = 20) {
		$sql = "SELECT *, pd.name AS name, p.image, m.name AS manufacturer, ss.name AS stock, (SELECT AVG(r.rating) FROM " . DB_PREFIX . "review r WHERE p.product_id = r.product_id GROUP BY r.product_id) AS rating FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) LEFT JOIN " . DB_PREFIX . "stock_status ss ON (p.stock_status_id = ss.stock_status_id) WHERE p.status = '1' AND p.date_available <= NOW() AND pd.language_id = '" . (int)((method_exists($this->language, 'getId')) ? (int)$this->language->getId() : (int)$this->config->get('config_language_id')) . "' AND ss.language_id = '" . (int)((method_exists($this->language, 'getId')) ? (int)$this->language->getId() : (int)$this->config->get('config_language_id')) . "'";

		$sort_data = array(
			'pd.name',
			'p.price',
			'rating'
		);

		if (in_array($sort, $sort_data)) {
			$sql .= " ORDER BY " . $sort;
		} else {
			$sql .= " ORDER BY pd.name";
		}

		if ($order == 'DESC') {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		$sql .= " LIMIT " . (int)$start . "," . (int)$limit;

		$query = $this->db->query($sql);

		//Q: Restricted Categories support
		if (method_exists($this->model_catalog_product, 'removeRestricted')) {
			$query = $this->model_catalog_product->removeRestricted($query);
			if (empty($query->rows)) { $query->row = array(); }
		}
		//

		return $query->rows;
	}
	
	public function getCategories($parent_id) {
		$category_data = $this->cache->get('category.admin.' . $this->config->get('config_language_id') . '.' . $parent_id);

		if (!$category_data) {
			$category_data = array();

			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");

			foreach ($query->rows as $result) {
				$category_data[] = array(
				'category_id' => $result['category_id'],
				'name'        => $this->getPath($result['category_id'], $this->config->get('config_language_id')),
				'status'       => $result['status'],
				'sort_order'  => $result['sort_order']
				);

				$category_data = array_merge($category_data, $this->getCategories($result['category_id']));
			}

			$this->cache->set('category.admin.' . $this->config->get('config_language_id') . '.' . $parent_id, $category_data);
		}

		return $category_data;
	}

	public function getPath($category_id) {
		$query = $this->db->query("SELECT name, parent_id FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");

		$category_info = $query->row;

		if ($category_info['parent_id']) {
			return $this->getPath($category_info['parent_id'], $this->config->get('config_language_id')) . $this->language->get('text_separator') . $category_info['name'];
		} else {
			return $category_info['name'];
		}
	}
}
?>