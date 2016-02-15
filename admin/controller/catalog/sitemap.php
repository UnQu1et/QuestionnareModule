<?php  

include $_SERVER['DOCUMENT_ROOT'].'/system/library/sitemap.php';

class ControllerCatalogSitemap extends Controller {
	public function index() {
		$this->load->language('catalog/sitemap');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/sitemap', 'token=' . $this->session->data['token'] , 'SSL'),
			'separator' => ' :: '
		);
	
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['entry_description'] =  $this->language->get('entry_description');
		$this->data['entry_priority'] =  $this->language->get('entry_priority');
		$this->data['entry_frequency'] =  $this->language->get('entry_frequency');
		$this->data['button_save'] =  $this->language->get('button_save');
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=catalog/sitemap/creat&token=' . $this->session->data['token'];
		
		
		$this->template = 'catalog/sitemap.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	
	public function creat() {
		$urls=array();
		
		$urls['index'][] =array(
		'url'      =>	$this->url->link('common/home'),                          
		'name'      => ''
		);
			
		$this->load->model('tool/seo_url');
		$this->load->model('catalog/sitemap');
	
		foreach ($this->model_catalog_sitemap->getInformations() as $result) {
		
		$urls['other'][] =array(
			'url'      => $this->url->link('information/information', 'information_id=' .  $result['information_id']), 
			'name'      => $result['title']
			);
		};
	
		$results = $this->model_catalog_sitemap->getCategorieForSitemap(0);

		$current_path = '';
		foreach ($results as $result) {	
			if (!$current_path) {
				$new_path = $result['category_id'];
			} else {
				$new_path = $current_path . '_' . $result['category_id'];
			}
			$urls['category'][] =array(
			'url'      =>$this->url->link('product/category', 'path=' . $new_path),                          
			'name'      => $result['name']
			);
		}
	
		$results1 = $this->model_catalog_sitemap->getAllProducts();
	
		foreach ($results1 as $result) {
			$urls['tovar'][] =array(
			'url'      =>$this->url->link('product/product', 'product_id=' . $result['product_id']),                          
			'name'      => $result['model']
			);
		}
		
		$priority_arr['category_priority'] =  $this->request->post['category_priority'];
		$priority_arr['tovar_priority'] =  $this->request->post['tovar_priority'];
		$priority_arr['other_priority'] =  $this->request->post['other_priority'];
		
		// делаем чпу
		foreach($urls as $k => $v){
			foreach($urls[$k] as $kk => $vv){
				$urls[$k][$kk]['url'] = str_replace('/admin/','/',$urls[$k][$kk]['url']);
				$urls[$k][$kk]['url'] = 'http://'.$_SERVER['HTTP_HOST'].'/'.$this->rewrite($urls[$k][$kk]['url']);	
				$urls[$k][$kk]['url'] = str_replace('index.php?route=common/home','',$urls[$k][$kk]['url']);			
			}
		
		}
		
		
		$sitemap = new Sitemaps();
		$sitemap->createSitemap ($urls, $priority_arr, $this->request->post['freq'] );
		$this->load->language('catalog/sitemap');
		$this->session->data['success'] = $this->language->get('text_success');
		$this->index();
	}
	
	public function url($route) {
		return HTTP_CATALOG . 'index.php?route=' . str_replace('&amp;', '&', $route);
	} 
	
	
	public function rewrite($link) { 
		if (!$this->config->get('config_seo_url')) return $link;
				
		$seo_url = '';

		$component = parse_url(str_replace('&amp;', '&', $link));

		$data = array();
		parse_str($component['query'], $data);

		$route = $data['route'];
		unset($data['route']);

		switch ($route) {
			case 'product/product':
				if (isset($data['product_id'])) {
					$tmp = $data;
					$data = array();
					if ($this->config->get('config_seo_url_include_path')) {
						$data['path'] = $this->getPathByProduct($tmp['product_id']);
						if (!$data['path']) return $link;
					}
					$data['product_id'] = $tmp['product_id'];
					if (isset($tmp['tracking'])) {
						$data['tracking'] = $tmp['tracking'];
					}
				}
				break;
				
			case 'product/category':
				if (isset($data['path'])) {
					$category = explode('_', $data['path']);
					$category = end($category);
					$data['path'] = $this->getPathByCategory($category);
					if (!$data['path']) return $link;
				}
				break;

			case 'information/information/info':
				return $link;
				break;
			
			case 'news/news/article':
				return $link;
				break;

			default:
				break;
		}

		if ($component['scheme'] == 'https') {
			$link = $this->config->get('config_ssl');
		} else {
			$link = $this->config->get('config_url');
		}

		$link .= 'index.php?route=' . $route;

		if (count($data)) {
			$link .= '&amp;' . urldecode(http_build_query($data, '', '&amp;'));
		}

		$queries = array();
		foreach ($data as $key => $value) {
			
			switch ($key) {
				case 'product_id':
				case 'manufacturer_id':
				case 'category_id':
				case 'information_id':
				case 'news_id':
					$queries[] = $key . '=' . $value;
					unset($data[$key]);
					$postfix = 1;
					break;

				case 'path':
					$categories = explode('_', $value);
					foreach ($categories as $category) {
						$queries[] = 'category_id=' . $category;
					}
					unset($data[$key]);
					break;

				default:
					break;
					
				case 'ncat':
					$ncategories = explode('_', $value);
						foreach ($ncategories as $ncategory) {
							$queries[] = 'ncategory_id=' . $ncategory;
					}
					unset($data[$key]);
					break;

				default:
					break;				
						
			}
		}

		if (!empty($queries)) {
			$query_in = array_map(array($this->db, 'escape'), $queries);
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` IN ('" . implode("', '", $query_in) . "')");

			if ($query->num_rows == count($queries)) {
				$aliases = array();
				foreach ($query->rows as $row) {
					$aliases[$row['query']] = $row['keyword'];
				}
				foreach ($queries as $query) {
					$seo_url .= '/' . rawurlencode($aliases[$query]);
				}
			}
		}

		if ($seo_url == '') return $link;

		$seo_url = trim($seo_url, '/');

		if ($component['scheme'] == 'https') {
			$seo_url = $this->config->get('config_ssl') . $seo_url;
		} else {
			$seo_url = $this->config->get('config_url') . $seo_url;
		}

		if (isset($postfix)) {
			$seo_url .= trim($this->config->get('config_seo_url_postfix'));
		} else {
			$seo_url .= '/';
		}

		if (count($data)) {
			$seo_url .= '?' . urldecode(http_build_query($data, '', '&amp;'));
		}

		return $seo_url;
	}

	private function getPathByProduct($product_id) {
		$product_id = (int)$product_id;
		if ($product_id < 1) return false;

		static $path = null;
		if (!is_array($path)) {
			$path = $this->cache->get('product.seopath');
			if (!is_array($path)) $path = array();
		}

		if (!isset($path[$product_id])) {
			$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . $product_id . "' ORDER BY main_category DESC LIMIT 1");

			$path[$product_id] = $this->getPathByCategory($query->num_rows ? (int)$query->row['category_id'] : 0);

			$this->cache->set('product.seopath', $path);
		}

		return $path[$product_id];
	}

	private function getPathByCategory($category_id) {
		$category_id = (int)$category_id;
		if ($category_id < 1) return false;

		static $path = null;
		if (!is_array($path)) {
			$path = $this->cache->get('category.seopath');
			if (!is_array($path)) $path = array();
		}

		if (!isset($path[$category_id])) {
			$max_level = 10;

			$sql = "SELECT CONCAT_WS('_'";
			for ($i = $max_level-1; $i >= 0; --$i) {
				$sql .= ",t$i.category_id";
			}
			$sql .= ") AS path FROM " . DB_PREFIX . "category t0";
			for ($i = 1; $i < $max_level; ++$i) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "category t$i ON (t$i.category_id = t" . ($i-1) . ".parent_id)";
			}
			$sql .= " WHERE t0.category_id = '" . $category_id . "'";

			$query = $this->db->query($sql);

			$path[$category_id] = $query->num_rows ? $query->row['path'] : false;

			$this->cache->set('category.seopath', $path);
		}

		return $path[$category_id];
	}

	private function validate() {
		if (empty($this->request->get['route']) || $this->request->get['route'] == 'error/not_found') {
			return;
		}

		if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			return;
		}

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$url = str_replace('&amp;', '&', $this->config->get('config_ssl') . ltrim($this->request->server['REQUEST_URI'], '/'));
			$seo = str_replace('&amp;', '&', $this->url->link($this->request->get['route'], $this->getQueryString(array('route')), 'SSL'));
		} else {
			$url = str_replace('&amp;', '&', $this->config->get('config_url') . ltrim($this->request->server['REQUEST_URI'], '/'));
			$seo = str_replace('&amp;', '&', $this->url->link($this->request->get['route'], $this->getQueryString(array('route')), 'NONSSL'));
		}

		if (rawurldecode($url) != rawurldecode($seo)) {
			header($this->request->server['SERVER_PROTOCOL'] . ' 301 Moved Permanently');

			$this->response->redirect($seo);
		}
	}

	private function getQueryString($exclude = array()) {
		if (!is_array($exclude)) {
			$exclude = array();
		}

		return urldecode(http_build_query(array_diff_key($this->request->get, array_flip($exclude))));
	}
	
}
?>