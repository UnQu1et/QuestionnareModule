<?php    
class ControllerCatalogTovartype extends Controller { 
	private $error = array();
  
	public function index() {
		$this->load->language('catalog/tovartype');
		
		$this->document->setTitle($this->language->get('heading_title'));
		 
		$this->load->model('catalog/tovartype');
		
		$this->model_catalog_tovartype->checkTable();
		
		$this->getList();
	}
  
	public function insert() {
		$this->load->language('catalog/tovartype');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/tovartype');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_tovartype->addTovartype($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('catalog/tovartype', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
	
		$this->getForm();
	} 
   
	public function update() {
		$this->load->language('catalog/tovartype');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/tovartype');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_tovartype->editTovartype($this->request->get['tovartype_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('catalog/tovartype', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
	
		$this->getForm();
	}   

	public function delete() {
		$this->load->language('catalog/tovartype');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/tovartype');
			
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $tovartype_id) {
				$this->model_catalog_tovartype->deleteTovartype($tovartype_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('catalog/tovartype', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
	
		$this->getList();
	}  
	
	private function getList() {
		$this->load->model('tool/image');
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
				
		$url = '';
			
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/tovartype', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);
							
		$this->data['insert'] = $this->url->link('catalog/tovartype/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/tovartype/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	

		$this->data['tovartypes'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$tovartype_total = $this->model_catalog_tovartype->getTotalTovartypes();
	
		$results = $this->model_catalog_tovartype->getTovartypes($data);
 
		foreach ($results as $result) {
			$action = array();
			
			if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', 40, 40);
			}
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/tovartype/update', 'token=' . $this->session->data['token'] . '&tovartype_id=' . $result['tovartype_id'] . $url, 'SSL')
			);
						
			$this->data['tovartypes'][] = array(
				'tovartype_id' => $result['tovartype_id'],
				'image'			  => $image,
				'name'            => $result['name'],
				'sort_order'      => $result['sort_order'],
				'selected'        => isset($this->request->post['selected']) && in_array($result['tovartype_id'], $this->request->post['selected']),
				'action'          => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_image'] = $this->language->get('column_image');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');		
		
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
 
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_name'] = $this->url->link('catalog/tovartype', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
		$this->data['sort_sort_order'] = $this->url->link('catalog/tovartype', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $tovartype_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/tovartype', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'catalog/tovartype_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
  
	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');			
		$this->data['text_percent'] = $this->language->get('text_percent');
		$this->data['text_amount'] = $this->language->get('text_amount');
				
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$this->data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_seo_title'] = $this->language->get('entry_seo_title');
		$this->data['entry_seo_h1'] = $this->language->get('entry_seo_h1');
		
		$this->data['info_keyword'] = $this->language->get('info_keyword');
		$this->data['info_image'] = $this->language->get('info_image');
		  
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_data'] = $this->language->get('tab_data');
			  
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}
			
		$url = '';
			
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/tovartype', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);
							
		if (!isset($this->request->get['tovartype_id'])) {
			$this->data['action'] = $this->url->link('catalog/tovartype/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/tovartype/update', 'token=' . $this->session->data['token'] . '&tovartype_id=' . $this->request->get['tovartype_id'] . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('catalog/tovartype', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		if (isset($this->request->get['tovartype_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$tovartype_info = $this->model_catalog_tovartype->getTovartype($this->request->get['tovartype_id']);
		}

		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['tovartype_description'])) {
			$this->data['tovartype_description'] = $this->request->post['tovartype_description'];
		} elseif (!empty($tovartype_info)) {
			$this->data['tovartype_description'] = $this->model_catalog_tovartype->getTovartypeDescriptions($this->request->get['tovartype_id']);
		} else {
			$this->data['tovartype_description'] = array();
		}

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (!empty($tovartype_info)) {
			$this->data['name'] = $tovartype_info['name'];
		} else {	
			$this->data['name'] = '';
		}
		
		$this->load->model('setting/store');
		
		$this->data['stores'] = $this->model_setting_store->getStores();
		
		if (isset($this->request->post['tovartype_store'])) {
			$this->data['tovartype_store'] = $this->request->post['tovartype_store'];
		} elseif (isset($this->request->get['tovartype_id'])) {
			$this->data['tovartype_store'] = $this->model_catalog_tovartype->getTovartypeStores($this->request->get['tovartype_id']);
		} else {
			$this->data['tovartype_store'] = array(0);
		}	
		
		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($tovartype_info)) {
			$this->data['keyword'] = $tovartype_info['keyword'];
		} else {
			$this->data['keyword'] = '';
		}

		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (!empty($tovartype_info)) {
			$this->data['image'] = $tovartype_info['image'];
		} else {
			$this->data['image'] = '';
		}
		
		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && file_exists(DIR_IMAGE . $this->request->post['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($tovartype_info) && $tovartype_info['image'] && file_exists(DIR_IMAGE . $tovartype_info['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($tovartype_info['image'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		
		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($tovartype_info)) {
			$this->data['sort_order'] = $tovartype_info['sort_order'];
		} else {
			$this->data['sort_order'] = '';
		}
		
		$this->template = 'catalog/tovartype_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}  
	 
	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/tovartype')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}    

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/tovartype')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}	
		
		$this->load->model('catalog/product');

		foreach ($this->request->post['selected'] as $tovartype_id) {
			$product_total = $this->model_catalog_product->getTotalProductsByTovartypeId($tovartype_id);
	
			if ($product_total) {
				$this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);	
			}	
		} 
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}  
	}
}
?>