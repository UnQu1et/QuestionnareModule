<?php
class ControllerShippingPickPoint extends Controller { 
	private $error = array();
	
	public function index() {  
		$this->load->language('shipping/pickpoint');

		$this->data['pickpoint_version'] = "1.5.4";

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				 
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('pickpoint', $this->request->post);	

			$this->session->data['success'] = $this->language->get('text_success');
									
			$this->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['entry_rate'] = $this->language->get('entry_rate');
		$this->data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_shop_id'] = $this->language->get('entry_shop_id');
		$this->data['entry_pickpoint_free_shipping'] = $this->language->get('entry_pickpoint_free_shipping');
		$this->data['entry_name'] = $this->language->get('entry_geo_zone');

	
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['button_create'] = $this->language->get('button_create');
		$this->data['create_geo_zones'] = $this->url->link('shipping/pickpoint/create_geo_zones',  'token=' . $this->session->data['token'], 'SSL'); 

		$this->load->model('sale/pickpoint');
		$this->data['is_geo_zones_exist'] = $this->model_sale_pickpoint->isGeoZonesExist();

		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_geo_zones'] = $this->language->get('tab_geo_zones');


 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->session->data['error'])) {
			$this->data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} else {
			$this->data['error_warning'] = '';
		}


  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_shipping'),
			'href'      => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('shipping/pickpoint', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('shipping/pickpoint', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'); 

		$this->load->model('localisation/geo_zone');
		
		$geo_zones = $this->model_localisation_geo_zone->getGeoZones();
		
		foreach ($geo_zones as $geo_zone) {
			if (isset($this->request->post['pickpoint_' . $geo_zone['geo_zone_id'] . '_rate'])) {
				$this->data['pickpoint_' . $geo_zone['geo_zone_id'] . '_rate'] = $this->request->post['pickpoint_' . $geo_zone['geo_zone_id'] . '_rate'];
			} else {
				$this->data['pickpoint_' . $geo_zone['geo_zone_id'] . '_rate'] = $this->config->get('pickpoint_' . $geo_zone['geo_zone_id'] . '_rate');
			}		
			
			if (isset($this->request->post['pickpoint_' . $geo_zone['geo_zone_id'] . '_status'])) {
				$this->data['pickpoint_' . $geo_zone['geo_zone_id'] . '_status'] = $this->request->post['pickpoint_' . $geo_zone['geo_zone_id'] . '_status'];
			} else {
				$this->data['pickpoint_' . $geo_zone['geo_zone_id'] . '_status'] = $this->config->get('pickpoint_' . $geo_zone['geo_zone_id'] . '_status');
			}		
		}
		
		$this->data['geo_zones'] = $geo_zones;

		if (isset($this->request->post['pickpoint_tax_class_id'])) {
			$this->data['pickpoint_tax_class_id'] = $this->request->post['pickpoint_tax_class_id'];
		} else {
			$this->data['pickpoint_tax_class_id'] = $this->config->get('pickpoint_tax_class_id');
		}
		
		if (isset($this->request->post['pickpoint_status'])) {
			$this->data['pickpoint_status'] = $this->request->post['pickpoint_status'];
		} else {
			$this->data['pickpoint_status'] = $this->config->get('pickpoint_status');
		}
		
		if (isset($this->request->post['pickpoint_sort_order'])) {
			$this->data['pickpoint_sort_order'] = $this->request->post['pickpoint_sort_order'];
		} else {
			$this->data['pickpoint_sort_order'] = $this->config->get('pickpoint_sort_order');
		}

		if (isset($this->request->post['pickpoint_shop_id'])) {
			$this->data['pickpoint_shop_id'] = $this->request->post['pickpoint_shop_id'];
		} else {
			$this->data['pickpoint_shop_id'] = $this->config->get('pickpoint_shop_id');
		}	

		if (isset($this->request->post['pickpoint_free_shipping'])) {
			$this->data['pickpoint_free_shipping'] = $this->request->post['pickpoint_free_shipping'];
		} else {
			$this->data['pickpoint_free_shipping'] = $this->config->get('pickpoint_free_shipping');
		}	
		


		$this->load->model('localisation/tax_class');
				
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		$this->template = 'shipping/pickpoint.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
		
	private function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/pickpoint')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}

	public function install() {
		$this->load->model('sale/pickpoint');
		$this->model_sale_pickpoint->createPickPointDatabaseTables();
	}

	public function create_geo_zones() {
		$this->load->language('shipping/pickpoint');

		$this->load->model('sale/pickpoint');
		if ($this->model_sale_pickpoint->createGeoZones() == false)
			$this->session->data['error'] = $this->language->get('error_create_geo_zones');
		else
			unset($this->session->data['error']);
								
		$this->redirect($this->url->link('shipping/pickpoint', 'token=' . $this->session->data['token'], 'SSL'));

	}

}
?>