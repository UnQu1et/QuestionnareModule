<?php
class ModelCatalogSearchMr extends Model {

  /*
   * Modification of standart getProducts() metod add support morphology and relevance
   */
  public function getProducts($data = array()) {

	$this->load->model('catalog/product');
	$this->load->model('tool/morphy');    
	
	$search_mr_options = $this->config->get('search_mr_options');
	if (!$search_mr_options) {
	  $search_mr_options = array();
	}
	
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	
		
		$cache = md5(http_build_query($data + $search_mr_options));
		
		$product_data = $this->cache->get('search.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$customer_group_id . '.' . $cache);
		
		if (!$product_data) {
	  $relevance_field = array();
	  
			$sql = "p.product_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)"; 
			
						
			if (!empty($data['filter_category_id'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";			
			}
			
			$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'"; 
			
			if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
				$sql .= " AND (";
											
				if (!empty($data['filter_name'])) {
					$implode = array();
					
					$words = explode(' ', trim(preg_replace('/\s\s+/', ' ', $data['filter_name'])));
		  if (isset($search_mr_options['use_morphology']) && $search_mr_options['use_morphology']) {
			$words = $this->model_tool_morphy->getRoots($words);
		  }
		  
		  $title_string_weight = isset($search_mr_options['title_string_weight']) ? $search_mr_options['title_string_weight'] : 60;
		  $title_word_weight = isset($search_mr_options['title_word_weight']) ? $search_mr_options['title_word_weight'] : 30;
		  $relevance_field[] = "IF (LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%', " . $title_string_weight . ", 0)";
		  $word_weight = round(($title_word_weight / count($words)), 2); 
		  
					foreach ($words as $word) {
						$implode[] = "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
			$relevance_field[] = "IF (LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%', " . $word_weight . ", 0)";
					}
						 
		  if (!empty($data['filter_description'])) {
			
			$description_string_weight = isset($search_mr_options['description_string_weight']) ? $search_mr_options['description_string_weight'] : 15;
			$description_word_weight = isset($search_mr_options['description_word_weight']) ? $search_mr_options['description_word_weight'] : 10;
			$relevance_field[] = "IF (LCASE(pd.description) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%', " . $description_string_weight . ", 0)";            
			$word_weight = round(($description_word_weight / count($words)), 2);             
			
			foreach ($words as $word) {
						$implode[] = "LCASE(pd.description) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
			  $relevance_field[] = "IF (LCASE(pd.description) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%', " . $word_weight . ", 0)";
			}
					}
		  
					if ($implode) {
						$sql .= " " . implode(" OR ", $implode) . "";
					}
				}
				
				if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
					$sql .= " OR ";
				}
				
				if (!empty($data['filter_tag'])) {
					$implode = array();
					
					$words = explode(' ', trim(preg_replace('/\s\s+/', ' ', $data['filter_tag'])));
		  if (isset($search_mr_options['use_morphology']) && $search_mr_options['use_morphology']) {
			$words = $this->model_tool_morphy->getRoots($words);
		  }
					
		  $tag_word_weight = isset($search_mr_options['tag_word_weight']) ? $search_mr_options['tag_word_weight'] : 20;
		  $word_weight = round(($tag_word_weight / count($words)), 2);             
		  
					foreach ($words as $word) {
						$implode[] = "LCASE(pd.tag) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
			$relevance_field[] = "IF (LCASE(pd.tag) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%', " . $word_weight . ", 0)";
					}
					
					if ($implode) {
						$sql .= " " . implode(" OR ", $implode);
					}
				}
			
				$sql .= ")";
			}
			
			if (!empty($data['filter_category_id'])) {
				if (!empty($data['filter_sub_category'])) {
					$implode_data = array();
					
					$implode_data[] = "p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
					
					$this->load->model('catalog/category');
					
					$categories = $this->model_catalog_category->getCategoriesByParentId($data['filter_category_id']);
										
					foreach ($categories as $category_id) {
						$implode_data[] = "p2c.category_id = '" . (int)$category_id . "'";
					}
								
					$sql .= " AND (" . implode(' OR ', $implode_data) . ")";			
				} else {
					$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
				}
			}		
					
			if (!empty($data['filter_manufacturer_id'])) {
				$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
			}
			
			$sql .= " GROUP BY p.product_id";
			
	  $sort_data = array(
		'pd.name',
		'p.model',
		'p.quantity',
		'p.price',
		'rating',
		'p.sort_order',
		'p.date_added',
		'relevance'  
	  );	

	  if (isset($search_mr_options['use_relevance']) 
			  && $search_mr_options['use_relevance'] 
			  && $relevance_field
			  && (!isset($data['sort'])
				  || (isset($data['sort']) 
				  // 'p.sort_order' - default sort
				  && $data['sort'] == 'p.sort_order'))) {
		$data['sort'] = 'relevance';
		$data['order'] = 'DESC';
	  }
	  
	  if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
		if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
		  $sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
		} else {
		  $sql .= " ORDER BY " . $data['sort'];
		}
	  } else {
		$sql .= " ORDER BY p.sort_order";	
	  }

	  if (isset($data['order']) && ($data['order'] == 'DESC')) {
		$sql .= " DESC, LCASE(pd.name) DESC";
	  } else {
		$sql .= " ASC, LCASE(pd.name) ASC";
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

	  if (isset($data['sort']) && $data['sort'] == 'relevance' && $relevance_field) {
		$relevance_field = " (" . implode(" + ", $relevance_field) . ") AS relevance, ";
	  }
	  else {
		$relevance_field = "";  
	  }
		
	  $sql =  "SELECT " . $relevance_field . $sql;

	  $product_data = array();
					
			$query = $this->db->query($sql);
		
			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->model_catalog_product->getProduct($result['product_id']);
			}
			
			$this->cache->set('search.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$customer_group_id . '.' . $cache, $product_data);
		}
		
		return $product_data;
	}
	
  /*
   * Modification of standart getTotalProducts() metod add support morphology
   */	
	public function getTotalProducts($data = array()) {
	
	$this->load->model('tool/morphy');
	
	$search_mr_options = $this->config->get('search_mr_options');
	if (!$search_mr_options) {
	  $search_mr_options = array();
	}
		
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)";

		if (!empty($data['filter_category_id'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";			
		}
		
							
		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
		
		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";
								
			if (!empty($data['filter_name'])) {
				$implode = array();
				
				$words = explode(' ', trim(preg_replace('/\s\s+/', ' ', $data['filter_name'])));
		if (isset($search_mr_options['use_morphology']) && $search_mr_options['use_morphology']) {
		  $words = $this->model_tool_morphy->getRoots($words);
		}
		
				foreach ($words as $word) {
					if (!empty($data['filter_description'])) {
						$implode[] = "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%' OR LCASE(pd.description) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
					} else {
						$implode[] = "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
					}				
				}
				
				if ($implode) {
					$sql .= " " . implode(" OR ", $implode) . "";
				}
			}
			
			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}
			
			if (!empty($data['filter_tag'])) {
				$implode = array();
				
				$words = explode(' ', trim(preg_replace('/\s\s+/', ' ', $data['filter_tag'])));
		if (isset($search_mr_options['use_morphology']) && $search_mr_options['use_morphology']) {
		  $words = $this->model_tool_morphy->getRoots($words);
		}
		
				foreach ($words as $word) {
					$implode[] = "LCASE(pd.tag) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
				}
				
				if ($implode) {
					$sql .= " " . implode(" OR ", $implode);
				}
			}
		
			$sql .= ")";
		}
		
		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$implode_data = array();
				
				$implode_data[] = "p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
				
				$this->load->model('catalog/category');
				
				$categories = $this->model_catalog_category->getCategoriesByParentId($data['filter_category_id']);
					
				foreach ($categories as $category_id) {
					$implode_data[] = "p2c.category_id = '" . (int)$category_id . "'";
				}
							
				$sql .= " AND (" . implode(' OR ', $implode_data) . ")";			
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}
		}		
		
		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}

}
