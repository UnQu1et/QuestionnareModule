<?php
class ControllerModulePricelist extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('module/pricelist');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		$this->load->model('sale/customer_group');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('pricelist', $this->request->post);		
			
			$this->cache->delete('product');
			
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token']);
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');

		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_module'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('module/pricelist', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/pricelist', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
        $customer_groups = $this->model_sale_customer_group->getCustomerGroups();
		$this->data['customer_groups'][] = array(
            'customer_group_id' => 0,
            'name' => 'No restriction',
        );
        $this->data['customer_groups'] = array_merge($this->data['customer_groups'], $customer_groups);
		
		if (isset($this->request->post['pricelist_position'])) {
			$this->data['pricelist_customer_group'] = $this->request->post['pricelist_customer_group'];
		} else {
			$this->data['pricelist_customer_group'] = $this->config->get('pricelist_customer_group');
		}
		
		if (isset($this->request->post['pricelist_status'])) {
			$this->data['pricelist_status'] = $this->request->post['pricelist_status'];
		} else {
			$this->data['pricelist_status'] = $this->config->get('pricelist_status');
		}
		
		if (isset($this->request->post['pricelist_sort_order'])) {
			$this->data['pricelist_sort_order'] = $this->request->post['pricelist_sort_order'];
		} else {
			$this->data['pricelist_sort_order'] = $this->config->get('pricelist_sort_order');
		}				
		
		$this->template = 'module/pricelist.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/pricelist')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>