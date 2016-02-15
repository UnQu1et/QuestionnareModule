<?php  
class ControllerModuleandtemp extends Controller {
	protected function index($setting) {

		//$this->data['andtemp'] = $setting;
		
			
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/setttings.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/setttings.tpl';
		} else {
			$this->template = 'default/template/module/setttings.tpl';
		}
		
		$GLOBALS["store_andtemp"] = $setting;
		// Sliding products in Modules
		$GLOBALS["sliding_products"] = isset($setting['sliding_products']) ? $setting['sliding_products'] : 0;
		
		/*
		echo "<pre>";
		var_dump($setting);
		echo "</pre>";
		*/
		
		
	//	$this->render();

	}
}
?>