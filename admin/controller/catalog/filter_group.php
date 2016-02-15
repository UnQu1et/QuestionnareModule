<?php 
class ControllerCatalogFilterGroup extends Controller { 
	private $error = array();
   
  	public function index() {
		$this->load->language('catalog/filter_group');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/filter_group');
		
    	$this->getList();
  	}
              
  	public function insert() {
		$this->load->language('catalog/filter_group');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/filter_group');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      		$this->model_catalog_filter_group->addFilterGroup($this->request->post);
		  	
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
						
      		$this->redirect($this->url->link('catalog/filter_group', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
	
    	$this->getForm();
  	}

  	public function update() {
		$this->load->language('catalog/filter_group');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/filter_group');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
	  		$this->model_catalog_filter_group->editFilterGroup($this->request->get['filter_group_id'], $this->request->post);
			
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
			
			$this->redirect($this->url->link('catalog/filter_group', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}
	
    	$this->getForm();
  	}

  	public function delete() {
		$this->load->language('catalog/filter_group');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/filter_group');
		
    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $filter_group_id) {
				$this->model_catalog_filter_group->deleteFilterGroup($filter_group_id);
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
			
			$this->redirect($this->url->link('catalog/filter_group', 'token=' . $this->session->data['token'] . $url, 'SSL'));
   		}
	
    	$this->getList();
  	}
    
  	private function getList() {
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
			'href'      => $this->url->link('catalog/filter_group', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = $this->url->link('catalog/filter_group/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/filter_group/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	

		$this->data['filter_groups'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$filter_group_total = $this->model_catalog_filter_group->getTotalFilterGroups();
	
		$results = $this->model_catalog_filter_group->getFilterGroups($data);
 
    	foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/filter_group/update', 'token=' . $this->session->data['token'] . '&filter_group_id=' . $result['filter_group_id'] . $url, 'SSL')
			);
			
			$group_categories = $this->model_catalog_filter_group->getGroupCategories($result['filter_group_id']);
		
			$this->data['filter_groups'][] = array(
				'filter_group_id'    => $result['filter_group_id'],
				'name'               => $result['name'],
				'categories'		 => $result['categories'],
				'sort_order'         => $result['sort_order'],
				'selected'           => isset($this->request->post['selected']) && in_array($result['filter_group_id'], $this->request->post['selected']),
				'action'             => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_categories'] = $this->language->get('column_categories');
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
		
		$this->data['sort_name'] = $this->url->link('catalog/filter_group', 'token=' . $this->session->data['token'] . '&sort=agd.name' . $url, 'SSL');
		$this->data['sort_sort_order'] = $this->url->link('catalog/filter_group', 'token=' . $this->session->data['token'] . '&sort=ag.sort_order' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $filter_group_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/filter_group', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'catalog/filter_group_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
  	}
  
  	private function getForm() {
     	$this->data['heading_title'] = $this->language->get('heading_title');
	
    	$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_categories'] = $this->language->get('entry_categories');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
    
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = array();
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
			'href'      => $this->url->link('catalog/filter_group', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		if (!isset($this->request->get['filter_group_id'])) {
			$this->data['action'] = $this->url->link('catalog/filter_group/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/filter_group/update', 'token=' . $this->session->data['token'] . '&filter_group_id=' . $this->request->get['filter_group_id'] . $url, 'SSL');
		}
			
		$this->data['cancel'] = $this->url->link('catalog/filter_group', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['filter_group_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$filter_group_info = $this->model_catalog_filter_group->getFilterGroup($this->request->get['filter_group_id']);
		}
				
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->post['filter_group_description'])) {
			$this->data['filter_group_description'] = $this->request->post['filter_group_description'];
		} elseif (isset($this->request->get['filter_group_id'])) {
			$this->data['filter_group_description'] = $this->model_catalog_filter_group->getFilterGroupDescriptions($this->request->get['filter_group_id']);
		} else {
			$this->data['filter_group_description'] = array();
		}

		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($filter_group_info)) {
			$this->data['sort_order'] = $filter_group_info['sort_order'];
		} else {
			$this->data['sort_order'] = '';
		}
		
		$this->load->model('catalog/category');
		$this->data['categories'] = $this->model_catalog_category->getCategories(0);

		if (isset($this->request->post['option_categories'])) {
		  $this->data['option_categories'] = $this->request->post['option_categories'];
		} elseif (isset($filter_group_info)) {
		  $this->data['option_categories'] = $this->model_catalog_filter_group->getGroupCategories($this->request->get['filter_group_id']);
		} else {
		  $this->data['option_categories'] = array();
		}
		
		$this->template = 'catalog/filter_group_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());	
  	}
  	
	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'catalog/filter_group')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
	
    	foreach ($this->request->post['filter_group_description'] as $language_id => $value) {
      		if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 64)) {
        		$this->error['name'][$language_id] = $this->language->get('error_name');
      		}
    	}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

  	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/filter_group')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		
		$this->load->model('catalog/filter');
		
		foreach ($this->request->post['selected'] as $filter_group_id) {
			$filter_total = $this->model_catalog_filter->getTotalFiltersByFilterGroupId($filter_group_id);

			if ($filter_total) {
				$this->error['warning'] = sprintf($this->language->get('error_filter'), $filter_total);
			}
			
			$filter_modules = $this->config->get('filter_module');
			
			$filter_modules_total = 0;
			
			foreach ($filter_modules as $filter_module)
			{
			
				if ($filter_module['filter_group_id'] == $filter_group_id)
				{
					$filter_modules_total++;
				}
				
			}
			
			if ($filter_modules_total > 0)
			{
				$this->error['warning'] = sprintf($this->language->get('error_filter_module'), $filter_modules_total);
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