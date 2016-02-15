<?php
class ControllerModuleSearchMr extends Controller {
	private $error = array(); 
		
	public function index() {   
		$this->load->language('module/search_mr');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
		
			$this->model_setting_setting->editSetting('search_mr', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
    
    $this->data['heading_title']            = $this->language->get('heading_title');
    
		$this->data['l'] = $this->language;
		    		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  	$this->data['breadcrumbs'] = array();

   	$this->data['breadcrumbs'][] = array(
    	'text'  => $this->language->get('text_home'),
			'href'  => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      'separator' => false
   	);

   	$this->data['breadcrumbs'][] = array(
    	'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      'separator' => ' :: '
   	);
		
   	$this->data['breadcrumbs'][] = array(
    	'text'  => $this->language->get('heading_title'),
			'href'  => $this->url->link('module/search_mr', 'token=' . $this->session->data['token'], 'SSL'),
      'separator' => ' :: '
   	);
		
		$this->data['action'] = $this->url->link('module/search_mr', 'token=' . $this->session->data['token'], 'SSL');		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
    
    if (isset($this->request->post['search_mr_options'])) {
			$this->data['options'] = $this->request->post['search_mr_options'];
		} elseif ($this->config->get('search_mr_options')) { 
			$this->data['options'] = $this->config->get('search_mr_options');
		}

		$this->template = 'module/search_mr.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
  
  public function install () {
    $this->load->model('setting/setting');
    $this->load->model('catalog/search_mr');
		$this->model_setting_setting->deleteSetting('search_mr');
		$setting['search_mr_options'] = $this->model_catalog_search_mr->getDefaultOptions();
		$this->model_setting_setting->editSetting('search_mr', $setting);
  }
  
  public function uninstall () {
    $this->load->model('setting/setting');
		$this->model_setting_setting->deleteSetting('search_mr');
  }
  
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/search_mr')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
    return !$this->error ? TRUE : FALSE;
	}
}