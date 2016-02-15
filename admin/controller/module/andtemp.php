<?php
class ControllerModuleandtemp extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('module/andtemp');
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			for ($i=1;$i<=25;$i++){
				$andtemp['andtemp_module'][] = array_merge(array (
					'layout_id' => $i,
					'position' => 'content_top',
					'sort_order' => ''
				),$this->request->post['andtemp_module']);
			}

			$this->model_setting_setting->editSetting('andtemp', $andtemp);	
						
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['and_colorstyle'] = $this->language->get('and_colorstyle');
		$this->data['and_infofooter'] = $this->language->get('and_infofooter');	
		$this->data['and_enabled'] = $this->language->get('and_enabled');	
		$this->data['and_disabled'] = $this->language->get('and_disabled');	
		$this->data['and_html'] = $this->language->get('and_html');	
		$this->data['and_html2'] = $this->language->get('and_html2');	
		$this->data['and_about'] = $this->language->get('and_about');	
		$this->data['and_about_name'] = $this->language->get('and_about_name');
		$this->data['and_about_text'] = $this->language->get('and_about_text');
		$this->data['and_anyhtml'] = $this->language->get('and_anyhtml');	
		$this->data['and_anyhtml_name'] = $this->language->get('and_anyhtml_name');
		$this->data['and_anyhtml_text'] = $this->language->get('and_anyhtml_text');
		$this->data['and_contacts'] = $this->language->get('and_contacts');
		$this->data['and_contacts_name'] = $this->language->get('and_contacts_name');
		$this->data['and_contacts_phone1'] = $this->language->get('and_contacts_phone1');
		$this->data['and_contacts_phone2'] = $this->language->get('and_contacts_phone2');
		$this->data['and_contacts_email'] = $this->language->get('and_contacts_email');
		$this->data['and_contacts_skype'] = $this->language->get('and_contacts_skype');
		$this->data['and_contacts_adress'] = $this->language->get('and_contacts_adress');
		$this->data['and_feedback'] = $this->language->get('and_feedback');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => 'Home',
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => 'Modules',
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => 'ANDesign',
			'href'      => $this->url->link('module/andtemp', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/andtemp', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['modules'] = array();
		
		if (isset($this->request->post['andtemp_module'])) {
			$this->data['modules'] = $this->request->post['andtemp_module'];
		} elseif ($this->config->get('andtemp_module')) { 
			$layout_modules = $this->config->get('andtemp_module');
			$this->data['modules'] = $layout_modules['0'];
		}	

		$this->data['img_files'] = array();
		$curr_theme = $this->config->get("config_template");

		// Usage 
		$dir = 'view/image/bgrs/';
		$this->data['img_files'] = ReadFolderDirectory($dir);
				
		$this->template = 'module/andtemp.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
				
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/andtemp')) {
			$this->error['warning'] = "Warning: You do not have permission to modify this module!";
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}


function ReadFolderDirectory($dir) 
    { 
        $listDir = array(); 
        if($handler = opendir($dir)) { 
            while (($sub = readdir($handler)) !== FALSE) { 
                if ($sub != "." && $sub != ".." && $sub != "Thumb.db") { 
                    if(is_file($dir."/".$sub)) { 
                        $listDir[] = $sub; 
                    }elseif(is_dir($dir."/".$sub)){ 
                        $listDir[$sub] = $this->ReadFolderDirectory($dir."/".$sub); 
                    } 
                } 
            }    
	        sort($listDir);
	        return $listDir; 
        } 
        return $listDir;    
    } 
?>