<?php  
class ControllerModuleandsocial extends Controller {
	protected function index() {
		$this->language->load('module/andsocial');

      	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['code'] = html_entity_decode($this->config->get('andsocial_code'));
		$this->data['code2'] = html_entity_decode($this->config->get('andsocial_code2'));
		$this->data['code3'] = html_entity_decode($this->config->get('andsocial_code3'));

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/andsocial.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/andsocial.tpl';
		} else {
			$this->template = 'default/template/module/andsocial.tpl';
		}
		
		$this->render();
	}
}
?>