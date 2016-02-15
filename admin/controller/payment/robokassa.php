<?php /* robokassa metka */
class ControllerPaymentRobokassa extends Controller {
	private $error = array(); 

	public function index() {
		$this->language->load('payment/robokassa');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		$this->load->model('setting/store');		
		$stores = $this->model_setting_store->getStores();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) 
		{
			if( !$this->request->post['robokassa_password1'] 
			&& $this->config->get('robokassa_password1') )
			$this->request->post['robokassa_password1'] = $this->config->get('robokassa_password1');
			
			if( !$this->request->post['robokassa_password2'] 
			&& $this->config->get('robokassa_password2') )
			$this->request->post['robokassa_password2'] = $this->config->get('robokassa_password2');
			
			if( !empty( $stores ) )
			{
				foreach($stores as $store)
				{
					$dat = $this->config->get('robokassa_password1_store');
					if( empty($this->request->post['robokassa_password1_store'][ $store['store_id'] ])
					&& !empty($dat[ $store['store_id'] ]) )
					$this->request->post['robokassa_password1_store'][ $store['store_id'] ] = $dat[ $store['store_id'] ];
			
					$dat = $this->config->get('robokassa_password2_store');
					if( empty($this->request->post['robokassa_password2_store'][ $store['store_id'] ] )
					&& !empty($dat[ $store['store_id'] ]) )
					$this->request->post['robokassa_password2_store'][ $store['store_id'] ] = $dat[ $store['store_id'] ];
				}
			}
			
			$ext_arr = array();
			$updExt = array();
			
			if( !empty($this->request->post['robokassa_currencies'][0]) )
			{
				$this->request->post['robokassa__status'] = 1;
			}
			else
			{
				$this->request->post['robokassa__status'] = 0;
			}
			
			if( !empty($this->request->post['robokassa_methods']) )
			{
				$i=0;
				foreach( $this->request->post['robokassa_methods'] as $val )
				{
					if( 
					/* start update: a1 
						Earlier:
						$val &&
					*/ !empty($this->request->post['robokassa_currencies'][$i]) )
					{
						if($i!=0)
						{
							$this->request->post['robokassa'.$i.'_status'] = 1;
							$updExt[] = $i;
						}
					}
					else
					{
						if($i!=0)
						{
							$this->request->post['robokassa'.$i.'_status'] = 0;
						}
					}
					
					$i++;
				}
			}
			
			if( !empty($this->request->post['robokassa_methods']) )
			$this->request->post['robokassa_methods'] = serialize($this->request->post['robokassa_methods']);
			
			if( !empty($this->request->post['robokassa_currencies']) )
			$this->request->post['robokassa_currencies'] = serialize($this->request->post['robokassa_currencies']);
			
			if( !empty($this->request->post['robokassa_confirm_comment']) )
			$this->request->post['robokassa_confirm_comment'] = serialize($this->request->post['robokassa_confirm_comment']);
			
			if( !empty($this->request->post['robokassa_doopcostname']) )
			$this->request->post['robokassa_doopcostname'] = serialize($this->request->post['robokassa_doopcostname']);
			
			if( !empty($this->request->post['robokassa_order_comment']) )
			$this->request->post['robokassa_order_comment'] = serialize($this->request->post['robokassa_order_comment']);
			
			if( !empty($this->request->post['robokassa_images']) )
			$this->request->post['robokassa_images'] = serialize($this->request->post['robokassa_images']);
			
			if( !empty( $stores ) )
			{
				foreach($this->request->post as $key=>$val)
				{
					if( preg_match("/_store$/", $key ) && is_array($val) )
					{
						$this->request->post[$key] = serialize($this->request->post[$key]);
					}
				}
			}
			
			$this->model_setting_setting->editSetting('robokassa', $this->request->post);
			
			$this->load->model('localisation/robokassa');
			$this->model_localisation_robokassa->updateExtentions($updExt);
			
			$this->session->data['current_store_id'] = $this->request->post['current_store_id'];
			
			
			if($this->request->post['robokassa_stay'])
			$this->redirect($this->url->link('payment/robokassa', 'success=1&token=' . $this->session->data['token'], 'SSL'));
			else
			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		if( !empty( $stores ) )
		{
			$this->data['tab_general'] = $this->config->get('config_name');
			
			foreach($stores as $store)
			{
				$this->data['stores'][] = $store;
			}
		}
		else
		{
			$this->data['stores'] = array();
			$this->data['tab_general'] = $this->language->get('tab_general');
		
		}
		
		
		
		if( !empty($this->request->get['success'] ) )
		$this->data['success'] = $this->language->get('text_success');
		else
		$this->data['success'] = '';
		
		
		$this->load->model('localisation/currency');
		$results = $this->model_localisation_currency->getCurrencies();
		
		if( !isset($results['RUB']) && !isset($results['RUR']) )
		{
			$this->error[] = $this->language->get('error_rub');
		}			

		$this->data['notice'] = $this->language->get('notice');

		$this->data['heading_title'] = $this->language->get('heading_title');
	
		$this->data['text_saved'] = $this->language->get('text_saved');
		$this->data['entry_icons'] = $this->language->get('entry_icons');
		$this->data['text_mode_notice'] = $this->language->get('text_mode_notice');
		
		$this->data['entry_dopcost'] = $this->language->get('entry_dopcost');
		$this->data['entry_dopcostname'] = $this->language->get('entry_dopcostname');
		$this->data['text_dopcost_int'] = $this->language->get('text_dopcost_int');
		$this->data['text_dopcost_percent'] = $this->language->get('text_dopcost_percent');
		
		
		$this->data['text_robokassa_method'] = $this->language->get('text_robokassa_method');
		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
				
		$this->data['entry_shop_login'] = $this->language->get('entry_shop_login');		
		
		$this->data['entry_order_comment'] = $this->language->get('entry_order_comment');		
		$this->data['entry_order_comment_notice'] = $this->language->get('entry_order_comment_notice');
		
		$this->data['entry_test_mode'] = $this->language->get('entry_test_mode');
		
		$this->data['entry_result_url'] = $this->language->get('entry_result_url');
		$this->data['entry_result_method'] = $this->language->get('entry_result_method');
		$this->data['entry_success_url'] = $this->language->get('entry_success_url');
		$this->data['entry_success_method'] = $this->language->get('entry_success_method');
		$this->data['entry_fail_url'] = $this->language->get('entry_fail_url');
		$this->data['entry_fail_method'] = $this->language->get('entry_fail_method');
		
		$this->data['entry_paynotify'] = $this->language->get('entry_paynotify');
		$this->data['entry_paynotify_email'] = $this->language->get('entry_paynotify_email');
		
		
		$this->data['entry_commission'] = $this->language->get('entry_commission');
		$this->data['text_commission_shop'] = $this->language->get('text_commission_shop');
		$this->data['text_commission_customer'] = $this->language->get('text_commission_customer');
		$this->data['text_commission_j'] = $this->language->get('text_commission_j');
		
		$this->data['entry_interface_language'] = $this->language->get('entry_interface_language');
		$this->data['entry_interface_language_ru'] = $this->language->get('entry_interface_language_ru');
		$this->data['entry_interface_language_en'] = $this->language->get('entry_interface_language_en');
		$this->data['entry_interface_language_detect'] = $this->language->get('entry_interface_language_detect');
		$this->data['entry_interface_language_notice'] = $this->language->get('entry_interface_language_notice');
		$this->data['entry_default_language'] = $this->language->get('entry_default_language');
		$this->data['entry_default_language_ru'] = $this->language->get('entry_default_language_ru');
		$this->data['entry_default_language_en'] = $this->language->get('entry_default_language_en');
		$this->data['entry_default_language_notice'] = $this->language->get('entry_default_language_notice');
		
		$this->data['entry_log'] = $this->language->get('entry_log');	
		
		$this->data['entry_log'] = str_replace("#url#", HTTP_CATALOG.'system/logs/robokassa_log.txt', $this->data['entry_log']);
		
		$this->data['entry_no_methods'] = $this->language->get('entry_no_methods');
		$this->data['entry_methods'] = $this->language->get('entry_methods');
		
		$this->data['entry_password1'] = $this->language->get('entry_password1');		
		$this->data['entry_password2'] = $this->language->get('entry_password2');		
		
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');	
		$this->data['entry_preorder_status'] = $this->language->get('entry_preorder_status');	
		
		$this->data['entry_order_status2'] = $this->language->get('entry_order_status2');		
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['entry_confirm_status'] = $this->language->get('entry_confirm_status');
		$this->data['entry_confirm_status_notice'] = $this->language->get('entry_confirm_status_notice');
		$this->data['entry_confirm_status_before'] = $this->language->get('entry_confirm_status_before');
		$this->data['entry_confirm_status_after'] = $this->language->get('entry_confirm_status_after');
		$this->data['entry_confirm_notify'] = $this->language->get('entry_confirm_notify');
		$this->data['entry_confirm_comment'] = $this->language->get('entry_confirm_comment');
		$this->data['text_confirm_comment_default'] = $this->language->get('text_confirm_comment_default');
		
		/* kin insert metka: d1 */
		$this->data['entry_robokassa_desc'] = $this->language->get('entry_robokassa_desc');
		/* end kin metka: d1 */
		
		$this->data['entry_no_robokass_methods'] = $this->language->get('entry_no_robokass_methods');
		
		$this->data['select_currency'] = $this->language->get('select_currency');
	
		$this->data['methods_col1'] = $this->language->get('methods_col1');
	
		$this->data['methods_col2'] = $this->language->get('methods_col2');
		$this->data['methods_col3'] = $this->language->get('methods_col3');
		
		
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_save_and_go'] = $this->language->get('button_save_and_go');
		$this->data['button_save_and_stay'] = $this->language->get('button_save_and_stay');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		$this->data['tab_support'] = $this->language->get('tab_support');
		$this->data['text_contact'] = $this->language->get('text_contact');
		$this->data['tab_instruction'] = $this->language->get('tab_instruction');
		
		$this->data['text_frame'] = $this->language->get('text_frame');
		
		$this->data['text_sms_instruction']  = $this->language->get('text_sms_instruction');
		$this->data['entry_sms_status'] 	 = $this->language->get('entry_sms_status');
		$this->data['entry_sms_instruction'] = $this->language->get('entry_sms_instruction');
		$this->data['entry_sms_phone'] 		 = $this->language->get('entry_sms_phone');
		$this->data['entry_sms_message'] 		 = $this->language->get('entry_sms_message');
		
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');
		
		$currencies = array();
		$this->data['currencies'] = array();
		
		if (isset($this->request->post['current_store_id'])) {
			$this->data['current_store_id'] = $this->request->post['current_store_id'];
		} else {
			$this->data['current_store_id'] = $this->config->get('current_store_id'); 
		}
		
		//---------
		
		if (isset($this->request->post['robokassa_test_mode'])) {
			$this->data['robokassa_test_mode'] = $this->request->post['robokassa_test_mode'];
		} elseif( $this->config->has('robokassa_test_mode') ) {
			$this->data['robokassa_test_mode'] = $this->config->get('robokassa_test_mode'); 
		} else {
			$this->data['robokassa_test_mode'] = 1; 
		}
		
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{
				if (isset($this->request->post['robokassa_test_mode_store'][$store['store_id']])) {
					$this->data['robokassa_test_mode_store'][$store['store_id']] = $this->request->post['robokassa_test_mode_store'][$store['store_id']];
				} else {
					$dat = $this->config->get('robokassa_test_mode_store');
					if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
					$this->data['robokassa_test_mode_store'][$store['store_id']] = $dat[$store['store_id']]; 
				}
			}
		}
		
		//---------
		
		if (isset($this->request->post['robokassa_order_comment'])) {
			$this->data['robokassa_order_comment'] = $this->request->post['robokassa_order_comment'];
		} elseif( $this->config->get('robokassa_order_comment') ) 
		{
			if( is_array($this->config->get('robokassa_order_comment')) )
			{
				$this->data['robokassa_order_comment'] = $this->config->get('robokassa_order_comment'); 
			}
			elseif( $this->config->get('robokassa_order_comment') )
			{
				$this->data['robokassa_order_comment'] = unserialize($this->config->get('robokassa_order_comment')); 
			}
			else
			{
				$this->data['robokassa_order_comment'] = array();
			}
			
		} elseif( !$this->config->has('robokassa_order_comment') ) {
			
			foreach($this->data['languages'] as $language)
			{
				$Lang = new Language( $language['directory'] );
				$Lang->load('payment/robokassa');
				
				$this->data['robokassa_order_comment'][$language['code']] = $Lang->get('text_order_comment_default');
			}
		} else {
			$this->data['robokassa_order_comment'] = array();
		}
		
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{
				if (isset($this->request->post['robokassa_order_comment_store'][$store['store_id']])) {
					$this->data['robokassa_order_comment_store'][$store['store_id']] = $this->request->post['robokassa_order_comment_store'][$store['store_id']];
				} 
				elseif( $this->config->get('robokassa_order_comment_store') ) 
				{
					$dat = $this->config->get('robokassa_order_comment_store');
					if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
					
					if( is_array($dat[$store['store_id']]) )
					{
						$this->data['robokassa_order_comment_store'][$store['store_id']] = $dat[$store['store_id']];
					}
					else
					{
						$this->data['robokassa_order_comment_store'][$store['store_id']] = $dat[$store['store_id']];
					}
				} 
				elseif( !$this->config->has('robokassa_order_comment_store') ) 
				{
					foreach($this->data['languages'] as $language)
					{
						$Lang = new Language( $language['directory'] );
						$Lang->load('payment/robokassa');
				
						$this->data['robokassa_order_comment_store'][$store['store_id']][$language['code']] = $Lang->get('text_order_comment_default');
					}
				} 
				else 
				{
					$this->data['robokassa_order_comment_store'][$store['store_id']] = array();
				}
			}
		}
		
		//---------
		
		
		if (isset($this->request->post['robokassa_paynotify'])) {
			$this->data['robokassa_paynotify'] = $this->request->post['robokassa_paynotify'];
		} elseif( $this->config->has('robokassa_paynotify') ) {
			$this->data['robokassa_paynotify'] = $this->config->get('robokassa_paynotify'); 
		} else {
			$this->data['robokassa_paynotify'] = 1; 
		}
		
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{
				if (isset($this->request->post['robokassa_paynotify_store'][$store['store_id']])) {
					$this->data['robokassa_paynotify_store'][$store['store_id']] = $this->request->post['robokassa_paynotify_store'][$store['store_id']];
				} else {
					$dat = $this->config->get('robokassa_paynotify_store');
					if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
					$this->data['robokassa_paynotify_store'][$store['store_id']] = $dat[$store['store_id']]; 
				}
			}
		}
		
		if (isset($this->request->post['robokassa_paynotify_email'])) {
			$this->data['robokassa_paynotify_email'] = $this->request->post['robokassa_paynotify_email'];
		} elseif( $this->config->has('robokassa_paynotify_email') ) {
			$this->data['robokassa_paynotify_email'] = $this->config->get('robokassa_paynotify_email'); 
		} else {
			$this->data['robokassa_paynotify_email'] = $this->config->get('config_email'); 
		}
		
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{
				if (isset($this->request->post['robokassa_paynotify_email_store'][$store['store_id']])) {
					$this->data['robokassa_paynotify_email_store'][$store['store_id']] = $this->request->post['robokassa_paynotify_email_store'][$store['store_id']];
				} else {
					if( $this->config->has('robokassa_paynotify_email_store') )
					{
						$dat = $this->config->get('robokassa_paynotify_email_store');
						if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
						$this->data['robokassa_paynotify_email_store'][$store['store_id']] = $dat[$store['store_id']]; 
					}
					else
					{
						$this->data['robokassa_paynotify_email_store'][$store['store_id']] = $this->config->get('config_email'); 
					}
				}
			}
		}
		
		
		//---------
		
		
		if (isset($this->request->post['robokassa_sms_status'])) {
			$this->data['robokassa_sms_status'] = $this->request->post['robokassa_sms_status'];
		} elseif( $this->config->has('robokassa_sms_status') ) {
			$this->data['robokassa_sms_status'] = $this->config->get('robokassa_sms_status'); 
		} else {
			$this->data['robokassa_sms_status'] = 0; 
		}
		
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{
				if (isset($this->request->post['robokassa_sms_status_store'][$store['store_id']])) {
					$this->data['robokassa_sms_status_store'][$store['store_id']] = $this->request->post['robokassa_sms_status_store'][$store['store_id']];
				} else {
					$dat = $this->config->get('robokassa_sms_status_store');
					if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
					$this->data['robokassa_sms_status_store'][$store['store_id']] = $dat[$store['store_id']]; 
				}
			}
		}
		
		if (isset($this->request->post['robokassa_sms_phone'])) {
			$this->data['robokassa_sms_phone'] = $this->request->post['robokassa_sms_phone'];
		} elseif( $this->config->has('robokassa_sms_phone') ) {
			$this->data['robokassa_sms_phone'] = $this->config->get('robokassa_sms_phone'); 
		} else {
			$this->data['robokassa_sms_phone'] = ''; 
		}
		
		//-------------
		
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{
				if (isset($this->request->post['robokassa_dopcost_store'][$store['store_id']])) {
					$this->data['robokassa_dopcost_store'][$store['store_id']] = $this->request->post['robokassa_dopcost_store'][$store['store_id']];
				} else {
					$dat = $this->config->get('robokassa_dopcost_store');
					if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
					$this->data['robokassa_dopcost_store'][$store['store_id']] = $dat[$store['store_id']]; 
				}
			}
		}
		
		if (isset($this->request->post['robokassa_dopcost'])) {
			$this->data['robokassa_dopcost'] = $this->request->post['robokassa_dopcost'];
		} elseif( $this->config->has('robokassa_dopcost') ) {
			$this->data['robokassa_dopcost'] = $this->config->get('robokassa_dopcost'); 
		} else {
			$this->data['robokassa_dopcost'] = ''; 
		}
		
		//-----------
		
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{
				if (isset($this->request->post['robokassa_dopcosttype_store'][$store['store_id']])) {
					$this->data['robokassa_dopcosttype_store'][$store['store_id']] = $this->request->post['robokassa_dopcosttype_store'][$store['store_id']];
				} else {
					$dat = $this->config->get('robokassa_dopcosttype_store');
					if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
					$this->data['robokassa_dopcosttype_store'][$store['store_id']] = $dat[$store['store_id']]; 
				}
			}
		}
		
		if (isset($this->request->post['robokassa_dopcosttype'])) {
			$this->data['robokassa_dopcosttype'] = $this->request->post['robokassa_dopcosttype'];
		} elseif( $this->config->has('robokassa_dopcosttype') ) {
			$this->data['robokassa_dopcosttype'] = $this->config->get('robokassa_dopcosttype'); 
		} else {
			$this->data['robokassa_dopcosttype'] = ''; 
		}
		
		//-------------
		
		
		if (isset($this->request->post['robokassa_dopcostname'])) {
			$this->data['robokassa_dopcostname'] = $this->request->post['robokassa_dopcostname'];
		} elseif( $this->config->get('robokassa_dopcostname') ) 
		{
			if( is_array($this->config->get('robokassa_dopcostname')) )
			{
				$this->data['robokassa_dopcostname'] = $this->config->get('robokassa_dopcostname'); 
			}
			else
			{
				$this->data['robokassa_dopcostname'] = unserialize($this->config->get('robokassa_dopcostname')); 
			}
		} elseif( $this->config->get('robokassa_shop_login')=='' ) {
			
			foreach($this->data['languages'] as $language)
			{
				$this->data['robokassa_dopcostname'][$language['code']] = '';
			}
		} else {
			$this->data['robokassa_dopcostname'] = array();
		}
		
		
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{
				if (isset($this->request->post['robokassa_dopcostname_store'][$store['store_id']])) {
					$this->data['robokassa_dopcostname_store'][$store['store_id']] = $this->request->post['robokassa_dopcostname_store'][$store['store_id']];
				} 
				else
				{
					$dat = $this->config->get('robokassa_dopcostname_store');
					if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
					if( empty($dat[$store['store_id']]) ) $dat[$store['store_id']] = false;
					
					if( !empty( $dat[$store['store_id']] ) ) 
					{
						if( is_array( $dat[$store['store_id']] ) )
						{
							$this->data['robokassa_dopcostname_store'][$store['store_id']] = $dat[$store['store_id']]; 
						}
						else
						{
							$this->data['robokassa_dopcostname_store'][$store['store_id']] = $dat[$store['store_id']]; 
						}
					} 
					else
					{
						$dat = $this->config->get('robokassa_shop_login_store');
						if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
						if( empty($dat[$store['store_id']]) ) $dat[$store['store_id']] = false;
						
						if( $dat[$store['store_id']] ) 
						{
							foreach($this->data['languages'] as $language)
							{
								$this->data['robokassa_dopcostname_store'][$store['store_id']][$language['code']] = '';
							}
						} else {
							$this->data['robokassa_dopcostname_store'][$store['store_id']] = array();
						}
					}
				}
			}
		}
		
		//-------------
		
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{
				if (isset($this->request->post['robokassa_sms_phone_store'][$store['store_id']])) {
					$this->data['robokassa_sms_phone_store'][$store['store_id']] = $this->request->post['robokassa_sms_phone_store'][$store['store_id']];
				} else {
					if( $this->config->has('robokassa_sms_phone_store') )
					{
						$dat = $this->config->get('robokassa_sms_phone_store');
						if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
						$this->data['robokassa_sms_phone_store'][$store['store_id']] = $dat[$store['store_id']]; 
					}
					else
					{
						$this->data['robokassa_sms_phone_store'][$store['store_id']] = ''; 
					}
				}
			}
		}
		
		
		if (isset($this->request->post['robokassa_sms_message'])) {
			$this->data['robokassa_sms_message'] = $this->request->post['robokassa_sms_message'];
		} elseif( $this->config->has('robokassa_sms_message') ) {
			$this->data['robokassa_sms_message'] = $this->config->get('robokassa_sms_message'); 
		} else {
			$this->data['robokassa_sms_message'] = $this->language->get('entry_sms_message_default'); 
		}
		
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{
				if (isset($this->request->post['robokassa_sms_message_store'][$store['store_id']])) {
					$this->data['robokassa_sms_message_store'][$store['store_id']] = $this->request->post['robokassa_sms_message_store'][$store['store_id']];
				} else {
					if( $this->config->has('robokassa_sms_message_store') )
					{
						$dat = $this->config->get('robokassa_sms_message_store');
						if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
						$this->data['robokassa_sms_message_store'][$store['store_id']] = $dat[$store['store_id']]; 
					}
					else
					{
						$this->data['robokassa_sms_message_store'][$store['store_id']] = $this->language->get('entry_sms_message_default'); 
					}
				}
			}
		}
		
		//---------
		if (isset($this->request->post['robokassa_interface_language'])) 
		{
			$this->data['robokassa_interface_language'] = $this->request->post['robokassa_interface_language'];
		} 
		else 
		{
			$this->data['robokassa_interface_language'] = $this->config->get('robokassa_interface_language'); 
		}
		
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{
				if (isset($this->request->post['robokassa_interface_language_store'][$store['store_id']])) 
				{
					$this->data['robokassa_interface_language_store'][$store['store_id']] = $this->request->post['robokassa_interface_language_store'][$store['store_id']];
				} 
				else 
				{
					$dat = $this->config->get('robokassa_interface_language_store');
					if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
					
					$this->data['robokassa_interface_language_store'][$store['store_id']] = $dat[$store['store_id']]; 
				}
			}
		}
		
		//---------
		
		
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{
				if (isset($this->request->post['robokassa_default_language_store'][$store['store_id']])) {
					$this->data['robokassa_default_language_store'][$store['store_id']] = $this->request->post['robokassa_default_language_store'][$store['store_id']];
				} else {
					$dat = $this->config->get('robokassa_default_language_store');
					if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
					$this->data['robokassa_default_language_store'][$store['store_id']] = $dat[$store['store_id']]; 
				}
			}
		}
		
		
		//---------
		
		if (isset($this->request->post['robokassa_commission'])) {
			$this->data['robokassa_commission'] = $this->request->post['robokassa_commission'];
		} else {
			$this->data['robokassa_commission'] = $this->config->get('robokassa_commission');
		}
				
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{
				if (isset($this->request->post['robokassa_commission_store'][$store['store_id']])) {
					$this->data['robokassa_commission_store'][$store['store_id']] = $this->request->post['robokassa_commission_store'][$store['store_id']];
				} else {
					$dat = $this->config->get('robokassa_commission_store');
					if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
					$this->data['robokassa_commission_store'][$store['store_id']] = $dat[$store['store_id']]; 
				}
			}
		}
		//---------
		
		$this->load->model('tool/image');
		
		$all_images = array();
			
		if( $this->config->get('robokassa_shop_login') )
		{
			$interface_lang = $this->config->get('config_admin_language');
			
			if( $interface_lang != 'en' ) 
			$interface_lang = 'ru';
		
		    if( $this->data['robokassa_test_mode'] )
			$url = "http://test.robokassa.ru/Webservice/Service.asmx/GetCurrencies?MerchantLogin=".$this->config->get('robokassa_shop_login')."&Language=".$interface_lang;
			else
			$url = "http://merchant.roboxchange.com/Webservice/Service.asmx/GetCurrencies?MerchantLogin=".$this->config->get('robokassa_shop_login')."&Language=".$interface_lang;
			
			//$url = "http://auth.robokassa.ru/Webservice/Service.asmx/GetCurrencies?MerchantLogin=".$this->config->get('robokassa_shop_login')."&Language=ru";
			// http://merchant.roboxchange.com/WebService/Service.asmx/CalcOutSumm?MerchantLogin=obrands&IncCurrLabel=WMR&IncSum=210
			// http://merchant.roboxchange.com/Webservice/Service.asmx/GetCurrencies?MerchantLogin=obrands&Language=ru
			if( extension_loaded('curl') )
			{
				$c = curl_init($url);
				curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
				$page = curl_exec($c);
				curl_close($c);
			}
			else
			{
				$page = file_get_contents($url);
			}
			
			
			if( !preg_match("/<Code>0<\/Code>/i", $page) )
			{
				$this->data['robokassa_methods'] = '';
			}
			elseif($page)
			{
				$arr_value = array();
				$group_value = array();
				
				//<Group Code="EMoney" Description="Электронные валюты"></Group>
				//preg_match_all("/(<offer([^>]*)>(.*)<\/offer>)/Ui", $yml_data, $pred_offer);
				//preg_match_all("/(<Group Code=\"([^\"]+)\" Description=\"([^\"]+)\">(.*)<\/Group>)/Ui", $page, $group_value);
				
				$ar = array();
				
				//<Currency Label="Qiwi29OceanR" Name="QIWI Кошелек" />
				
				$groups = explode("<Group ", $page);
				
				for($i=1; $i<count($groups); $i++)
				{
					$ar = array();
					preg_match("/^Code=\"([^\"]+)\" Description=\"([^\"]+)\"/",
					$groups[$i], $ar);
					
					if( empty($ar) ) continue;
					
					$group_description = $ar[2];
					
					$ar = array();
					preg_match_all("/(<Currency Label=\"([^\"]+)\" Name=\"([^\"]+)\" \/>)/", 
									$groups[$i], $ar);
					
					if( empty($ar) ) continue;
					
					
					for($e=0; $e<count($ar[2]); $e++)
					{
						$Label = $ar[2][$e];
						$Name  = $ar[3][$e];
						$currencies[ trim($Label) ] = $Name." (".$group_description.")"; 
								
						if( file_exists( DIR_IMAGE.'data/robokassa_icons/'.trim($Label).'.png' )  )
						{
							$all_images[ trim($Label) ]  =array( 
								"thumb" => HTTP_CATALOG.'image/data/robokassa_icons/'.trim($Label).'.png',
								"value" => 'data/robokassa_icons/'.trim($Label).'.png'
							);
						}
						else
						{
							$all_images[ trim($Label) ] = array(
										"thumb" => $this->model_tool_image->resize('no_image.jpg', 40, 40),
										"value" => 'no_image.jpg'
								);
						}
					}
					
					$all_images["robokassa"] = array(
										"thumb" => HTTP_CATALOG.'image/data/robokassa_icons/robokassa.png',
										"value" => 'data/robokassa_icons/robokassa.png'
								);
					
				}
				
				$this->data['currencies'] = $currencies;
			}
		}
		else
		{
			$this->data['robokassa_methods'] = '';
		}
		
		$all_images_store = array();
		
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{
				$dat = $this->config->get('robokassa_shop_login_store');
				if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
				
				if( !empty( $dat[$store['store_id']] ) )
				{
					$dat = $this->config->get('config_admin_language_store');
					if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
					$interface_lang = $dat[$store['store_id']];
			
					if( $interface_lang != 'en' ) 
					$interface_lang = 'ru';
		
					if( !empty( $this->data['robokassa_test_mode_store'][$store['store_id']] ) )
					$url = "http://test.robokassa.ru/Webservice/Service.asmx/GetCurrencies?MerchantLogin=".$this->config->get('robokassa_shop_login')."&Language=".$interface_lang;
					else
					$url = "http://merchant.roboxchange.com/Webservice/Service.asmx/GetCurrencies?MerchantLogin=".$this->config->get('robokassa_shop_login')."&Language=".$interface_lang;
			
					//$url = "http://auth.robokassa.ru/Webservice/Service.asmx/GetCurrencies?MerchantLogin=".$this->config->get('robokassa_shop_login')."&Language=ru";
					// http://merchant.roboxchange.com/WebService/Service.asmx/CalcOutSumm?MerchantLogin=obrands&IncCurrLabel=WMR&IncSum=210
					// http://merchant.roboxchange.com/Webservice/Service.asmx/GetCurrencies?MerchantLogin=obrands&Language=ru
					if( extension_loaded('curl') )
					{
						$c = curl_init($url);
						curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
						$page = curl_exec($c);
						curl_close($c);
					}
					else
					{
						$page = file_get_contents($url);
					}
			
			
					if( !preg_match("/<Code>0<\/Code>/i", $page) )
					{
						$this->data['robokassa_methods_store'][$store['store_id']] = '';
					}
					elseif($page)
					{
						$arr_value = array();
						$group_value = array();
						
						$ar = array();
				
						$groups = explode("<Group ", $page);
				
						for($i=1; $i<count($groups); $i++)
						{
							$ar = array();
							preg_match("/^Code=\"([^\"]+)\" Description=\"([^\"]+)\"/",
							$groups[$i], $ar);
					
							if( empty($ar) ) continue;
					
							$group_description = $ar[2];
					
							$ar = array();
							preg_match_all("/(<Currency Label=\"([^\"]+)\" Name=\"([^\"]+)\" \/>)/", 
									$groups[$i], $ar);
					
							if( empty($ar) ) continue;
					
					
							for($e=0; $e<count($ar[2]); $e++)
							{
								$Label = $ar[2][$e];
								$Name  = $ar[3][$e];
								$currencies[ trim($Label) ] = $Name." (".$group_description.")"; 
								
								#echo $store['store_id']." - ".trim($Label)."<br>";
								
								if( file_exists( DIR_IMAGE.'data/robokassa_icons/'.trim($Label).'.png' )  )
								{
									$all_images_store[$store['store_id']][ trim($Label) ]  =array( 
										"thumb" => HTTP_CATALOG.'image/data/robokassa_icons/'.trim($Label).'.png',
										"value" => 'data/robokassa_icons/'.trim($Label).'.png'
									);
								}
								else
								{
									$all_images_store[$store['store_id']][ trim($Label) ] = array(
												"thumb" => $this->model_tool_image->resize('no_image.jpg', 40, 40),
												"value" => 'no_image.jpg'
										);
								}
							}
					
							$all_images_store[$store['store_id']]["robokassa"] = array(
												"thumb" => HTTP_CATALOG.'image/data/robokassa_icons/robokassa.png',
												"value" => 'data/robokassa_icons/robokassa.png'
										);
							
						}
				
						$this->data['currencies_store'][$store['store_id']] = $currencies;
					}
				}
				else
				{
					$this->data['robokassa_methods_store'][$store['store_id']] = '';
				}
			}
		}	
		
		//---------
		
  		if (isset($this->error)) {
			$this->data['error_warning'] = $this->error;
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
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/robokassa', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->link('payment/robokassa', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['callback'] = HTTP_CATALOG . 'index.php?route=payment/robokassa/callback';

		/* kin insert metka: a4 */
		
		$this->load->model('localisation/currency');
		$results = $this->model_localisation_currency->getCurrencies();
		
		if( !isset($results['RUB']) && !isset($results['RUR']) )
		{
			$this->error[] = $this->language->get('error_rub');
		}			
		
		$this->data['opencart_currencies'] = $results;
		
		$this->data['entry_currency'] = $this->language->get('entry_currency');
		$this->data['text_currency_notice'] = $this->language->get('text_currency_notice');
		
		$this->data['text_img_notice'] = $this->language->get('text_img_notice');
		
		//---------
		
		if (isset($this->request->post['robokassa_currency'])) {
			$this->data['robokassa_currency'] = $this->request->post['robokassa_currency'];
		} else {
			$this->data['robokassa_currency'] = $this->config->get('robokassa_currency');
		} 	
				
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{
				if (isset($this->request->post['robokassa_currency_store'][$store['store_id']])) {
					$this->data['robokassa_currency_store'][$store['store_id']] = $this->request->post['robokassa_currency_store'][$store['store_id']];
				} else {
					$dat = $this->config->get('robokassa_currency_store');
					if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
					if( empty($dat[$store['store_id']]) ) $dat[$store['store_id']] = false;
					$this->data['robokassa_currency_store'][$store['store_id']] = $dat[$store['store_id']]; 
				} 	
			}
		}
		//---------
		
		if (isset($this->request->post['robokassa_default_language'])) {
			$this->data['robokassa_default_language'] = $this->request->post['robokassa_default_language'];
		} else {
			$this->data['robokassa_default_language'] = $this->config->get('robokassa_default_language');
		} 
				
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{
				if (isset($this->request->post['robokassa_default_language_store'][$store['store_id']])) {
					$this->data['robokassa_default_language_store'][$store['store_id']] = $this->request->post['robokassa_default_language_store'][$store['store_id']];
				} else {
					$dat = $this->config->get('robokassa_default_language_store');
					if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
					if( empty($dat[$store['store_id']]) ) $dat[$store['store_id']] = false;
					$this->data['robokassa_default_language_store'][$store['store_id']] = $dat[$store['store_id']]; 
				} 
			}
		}
		
		//---------
		
		if (isset($this->request->post['robokassa_order_status_id'])) {
			$this->data['robokassa_order_status_id'] = $this->request->post['robokassa_order_status_id'];
		} else {
			$this->data['robokassa_order_status_id'] = $this->config->get('robokassa_order_status_id');
		} 
				
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{
				if (isset($this->request->post['robokassa_order_status_id_store'][$store['store_id']])) {
					$this->data['robokassa_order_status_id_store'][$store['store_id']] = $this->request->post['robokassa_order_status_id_store'][$store['store_id']];
				} else {
					$dat = $this->config->get('robokassa_order_status_id_store');
					if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
					if( empty($dat[$store['store_id']]) ) $dat[$store['store_id']] = false;
					$this->data['robokassa_order_status_id_store'][$store['store_id']] = $dat[$store['store_id']]; 
				} 
			}
		}
		//---------
		if (isset($this->request->post['robokassa_order_status_id2'])) {
			$this->data['robokassa_order_status_id2'] = $this->request->post['robokassa_order_status_id2'];
		} else {
			$this->data['robokassa_order_status_id2'] = $this->config->get('robokassa_order_status_id2');
		} 
				
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{
				if (isset($this->request->post['robokassa_order_status_id2_store'][$store['store_id']])) {
					$this->data['robokassa_order_status_id2_store'][$store['store_id']] = $this->request->post['robokassa_order_status_id2_store'][$store['store_id']];
				} else {
					$dat = $this->config->get('robokassa_order_status_id2_store'); 
					if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
					if( empty($dat[$store['store_id']]) ) $dat[$store['store_id']] = false;
					$this->data['robokassa_order_status_id2_store'][$store['store_id']] = $dat[$store['store_id']];
				} 
			}
		}
		
		//---------
		
		if (isset($this->request->post['robokassa_shop_login'])) {
			$this->data['robokassa_shop_login'] = $this->request->post['robokassa_shop_login'];
		} else {
			$this->data['robokassa_shop_login'] = $this->config->get('robokassa_shop_login');
		}
				
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{
				if (isset($this->request->post['robokassa_shop_login_store'][$store['store_id']])) {
					$this->data['robokassa_shop_login_store'][$store['store_id']] = $this->request->post['robokassa_shop_login_store'][$store['store_id']];
				} else {
					$dat = $this->config->get('robokassa_shop_login_store');
					if( $this->is_serialized( $dat ) ) $dat = unserialize($dat); 
					if( empty($dat[$store['store_id']]) ) $dat[$store['store_id']] = false;
					$this->data['robokassa_shop_login_store'][$store['store_id']] = $dat[$store['store_id']];
				}
			}
		}
		
		//---------
				
		if (isset($this->request->post['robokassa_confirm_notify'])) {
			$this->data['robokassa_confirm_notify'] = $this->request->post['robokassa_confirm_notify'];
		} else {
			$this->data['robokassa_confirm_notify'] = $this->config->get('robokassa_confirm_notify'); 
		}
		
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{
				if (isset($this->request->post['robokassa_confirm_notify_store'][$store['store_id']])) {
					$this->data['robokassa_confirm_notify_store'][$store['store_id']] = $this->request->post['robokassa_confirm_notify_store'][$store['store_id']];
				} else {
					$dat = $this->config->get('robokassa_confirm_notify_store'); 
					if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
					if( empty($dat[$store['store_id']]) ) $dat[$store['store_id']] = false;
					$this->data['robokassa_confirm_notify_store'][$store['store_id']] = $dat[$store['store_id']];
				}
			}
		}
		//---------
				
		
		if (isset($this->request->post['robokassa_confirm_status'])) {
			$this->data['robokassa_confirm_status'] = $this->request->post['robokassa_confirm_status'];
		} else {
			$this->data['robokassa_confirm_status'] = $this->config->get('robokassa_confirm_status'); 
		}
		
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{
				if (isset($this->request->post['robokassa_confirm_status_store'][$store['store_id']])) {
					$this->data['robokassa_confirm_status_store'][$store['store_id']] = $this->request->post['robokassa_confirm_status_store'][$store['store_id']];
				} else {
					$dat = $this->config->get('robokassa_confirm_status_store'); 
					if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
					if( empty($dat[$store['store_id']]) ) $dat[$store['store_id']] = false;
					$this->data['robokassa_confirm_status_store'][$store['store_id']] = $dat[$store['store_id']]; 
				}
			}
		}
		
		//---------
				
		if (isset($this->request->post['robokassa_confirm_comment'])) {
			$this->data['robokassa_confirm_comment'] = $this->request->post['robokassa_confirm_comment'];
		} elseif( $this->config->get('robokassa_confirm_comment') ) 
		{
			if( is_array($this->config->get('robokassa_confirm_comment')) )
			{
				$this->data['robokassa_confirm_comment'] = $this->config->get('robokassa_confirm_comment'); 
			}
			else
			{
				$this->data['robokassa_confirm_comment'] = unserialize($this->config->get('robokassa_confirm_comment')); 
			}
		} elseif( $this->config->get('robokassa_shop_login')=='' ) {
			
			foreach($this->data['languages'] as $language)
			{
				$Lang = new Language( $language['directory'] );
				$Lang->load('payment/robokassa');
				
				$this->data['robokassa_confirm_comment'][$language['code']] = $Lang->get('text_confirm_comment_default');
			}
		} else {
			$this->data['robokassa_confirm_comment'] = array();
		}
		
		
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{
				if (isset($this->request->post['robokassa_confirm_comment_store'][$store['store_id']])) {
					$this->data['robokassa_confirm_comment_store'][$store['store_id']] = $this->request->post['robokassa_confirm_comment_store'][$store['store_id']];
				} 
				else
				{
					$dat = $this->config->get('robokassa_confirm_comment_store');
					if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
					if( empty($dat[$store['store_id']]) ) $dat[$store['store_id']] = false;
					
					if( !empty( $dat[$store['store_id']] ) ) 
					{
						if( is_array( $dat[$store['store_id']] ) )
						{
							$this->data['robokassa_confirm_comment_store'][$store['store_id']] = $dat[$store['store_id']]; 
						}
						else
						{
							$this->data['robokassa_confirm_comment_store'][$store['store_id']] = $dat[$store['store_id']]; 
						}
					} 
					else
					{
						$dat = $this->config->get('robokassa_shop_login_store');
						if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
						if( empty($dat[$store['store_id']]) ) $dat[$store['store_id']] = false;
						
						if( $dat[$store['store_id']] ) 
						{
							foreach($this->data['languages'] as $language)
							{
								$Lang = new Language( $language['directory'] );
								$Lang->load('payment/robokassa');
					
								$this->data['robokassa_confirm_comment_store'][$store['store_id']][$language['code']] = $Lang->get('text_confirm_comment_default');
							}
						} else {
							$this->data['robokassa_confirm_comment_store'][$store['store_id']] = array();
						}
					}
				}
			}
		}
		//---------
				
		
		if (isset($this->request->post['robokassa_log'])) {
			$this->data['robokassa_log'] = $this->request->post['robokassa_log'];
		} else {
			$this->data['robokassa_log'] = $this->config->get('robokassa_log'); 
		} 
		
		
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{
				if (isset($this->request->post['robokassa_log_store'][$store['store_id']])) {
					$this->data['robokassa_log_store'][$store['store_id']] = $this->request->post['robokassa_log_store'][$store['store_id']];
				} else {
					$dat = $this->config->get('robokassa_log_store');
					if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
					if( empty($dat[$store['store_id']]) ) $dat[$store['store_id']] = false;
					$this->data['robokassa_log_store'][$store['store_id']] = $dat[$store['store_id']]; 
				} 
			}
		}
		//---------
				
		
		if (isset($this->request->post['robokassa_preorder_status_id'])) {
			$this->data['robokassa_preorder_status_id'] = $this->request->post['robokassa_preorder_status_id'];
		} else {
			$this->data['robokassa_preorder_status_id'] = $this->config->get('robokassa_preorder_status_id'); 
		} 
		
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{
				if (isset($this->request->post['robokassa_preorder_status_id_store'][$store['store_id']])) {
					$this->data['robokassa_preorder_status_id_store'][$store['store_id']] = $this->request->post['robokassa_preorder_status_id_store'][$store['store_id']];
				} else {
					$dat = $this->config->get('robokassa_preorder_status_id_store');
					if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
					if( empty($dat[$store['store_id']]) ) $dat[$store['store_id']] = false;
					$this->data['robokassa_preorder_status_id_store'][$store['store_id']] = $dat[$store['store_id']]; 
				} 
			}
		}
		//---------
				
		$robokassa_methods = array();
		
		if (isset($this->request->post['robokassa_methods'])) {
			$robokassa_methods = $this->request->post['robokassa_methods'];
		} elseif( $this->config->has('robokassa_methods') ) {
			$robokassa_methods = unserialize( $this->config->get('robokassa_methods') ); 
		}
		
		$robokassa_methods_store = array();
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{
				if (isset($this->request->post['robokassa_methods_store'][$store['store_id']])) {
					$robokassa_methods_store[$store['store_id']] = $this->request->post['robokassa_methods_store'][$store['store_id']];
				} elseif( $this->config->has('robokassa_methods_store') ) {
					$dat = $this->config->get('robokassa_methods_store');
					if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
					if( empty($dat[$store['store_id']]) ) $dat[$store['store_id']] = array();
					
					$robokassa_methods_store[$store['store_id']] = $dat[$store['store_id']]; 
				}
			}
		}
		//---------
				
		$is_ru = 0;
		foreach($this->data['languages'] as $lang)
		{
			if( $lang['code']=='ru' )
			{
				$is_ru = 1;
			}
		}
		
		if($robokassa_methods)
		{
			foreach( $robokassa_methods as $value )
			{
				if( !is_array($value) )
				{
					$i = 0;
					foreach($this->data['languages'] as $lang_id=>$val)
					{
						$i++;
					
						if( ($is_ru && $val['code']=='ru' ) || 
						(!$is_ru && $i==1 ) )
						{
							$method[$val['code']] = $value;		
						}
						else
						{
							$method[$val['code']] = '';
						}
					}
				}
				else
				{
					$method = $value;
				}
			
				$this->data['robokassa_methods'][] = $method;
			}
		}
		
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{
				if( !empty( $robokassa_methods_store[ $store['store_id'] ] ) )
				{
					foreach( $robokassa_methods_store[ $store['store_id'] ] as $value )
					{
						if( !is_array($value) )
						{
							$i = 0;
							foreach($this->data['languages'] as $lang_id=>$val)
							{
								$i++;
								
								if( ($is_ru && $val['code']=='ru' ) || 
								(!$is_ru && $i==1 ) )
								{
									$method[$val['code']] = $value;		
								}
								else
								{
									$method[$val['code']] = '';
								}
							}
						}
						else
						{
							$method = $value;
						}
						
						$this->data['robokassa_methods_store'][ $store['store_id'] ][] = $method;
					}
				}
			}
		}
		
		//---------
				
		
		if (isset($this->request->post['robokassa_currencies'])) {
			$this->data['robokassa_currencies'] = $this->request->post['robokassa_currencies'];
		} else {
			$this->data['robokassa_currencies'] = unserialize( $this->config->get('robokassa_currencies') ); 
		}
		
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{
				if (isset($this->request->post['robokassa_currencies_store'][ $store['store_id'] ])) {
					$this->data['robokassa_currencies_store'][ $store['store_id'] ] = $this->request->post['robokassa_currencies_store'][ $store['store_id'] ];
				} else {
					$dat = $this->config->get('robokassa_currencies_store');
					if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
					if( empty($dat[$store['store_id']]) ) $dat[$store['store_id']] = array();
					$this->data['robokassa_currencies_store'][ $store['store_id'] ] = $dat[$store['store_id']]; 
				}
			}
		}
		
		//---------
		
		
		if (isset($this->request->post['robokassa_images'])) {
			$robokassa_images = $this->request->post['robokassa_images'];
		} elseif( $this->config->get('robokassa_images') ) {
			$robokassa_images = unserialize( $this->config->get('robokassa_images') ); 
		} else {
			$robokassa_images = array();
			$this->data['robokassa_images'] = array();
		}
		
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{
				if (isset($this->request->post['robokassa_images_store'][ $store['store_id'] ])) {
					$robokassa_images_store[ $store['store_id'] ] = $this->request->post['robokassa_images_store'][ $store['store_id'] ];
				} elseif( $this->config->get('robokassa_images_store') ) 
				{
					$dat = $this->config->get('robokassa_images_store');
					if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
					if( empty($dat[$store['store_id']]) ) $dat[$store['store_id']] = array();
					$robokassa_images_store[ $store['store_id'] ] = $dat[ $store['store_id'] ]; 
				} else {
					$robokassa_images_store[ $store['store_id'] ] = array();
					$this->data['robokassa_images_store'][ $store['store_id'] ] = array();
				}
			}
		}
		
		//---------
		
		
		$this->data['all_images'] = $all_images;
		
		for($i=0; $i<20; $i++  )
		{
		
			if( empty($robokassa_images[$i]) )
			{
				if( !empty($this->data['robokassa_currencies'][$i]) )
				{
					$thumb = $all_images[$this->data['robokassa_currencies'][$i]]['thumb'];
					$value = $all_images[$this->data['robokassa_currencies'][$i]]['value']; 
				}
				else
				{
					$thumb = $this->model_tool_image->resize('no_image.jpg', 40, 40);
					$value = 'no_image.jpg';
				}
			}
			else
			{
				$thumb = HTTP_CATALOG.'image/'.$robokassa_images[$i];
				$value = $robokassa_images[$i];
			}
			
			if( empty($this->data['robokassa_currencies'][$i]) )
			$this->data['robokassa_currencies'][$i] = '';
			
			
			$this->data['robokassa_images'][$i] = array(
					"thumb" => $thumb,
					"value" => $value
				);
		}
		
		$this->data['all_images_store'] = $all_images_store;
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{
				for($i=0; $i<20; $i++  )
				{
					//echo $robokassa_images_store[ $store['store_id'] ]."---<br>";
					
					
					if( empty($robokassa_images_store[ $store['store_id'] ][$i]) )
					{
						if( !empty($this->data['robokassa_currencies_store'][ $store['store_id'] ][$i]) )
						{
							$thumb = $all_images_store[ $store['store_id'] ][$this->data['robokassa_currencies_store'][ $store['store_id'] ][$i]  ]['thumb'];
							$value = $all_images_store[ $store['store_id'] ][$this->data['robokassa_currencies_store'][ $store['store_id'] ][$i]  ]['value']; 
						}
						else
						{
							$thumb = $this->model_tool_image->resize('no_image.jpg', 40, 40);
							$value = 'no_image.jpg';
						}
					}
					else
					{
						$thumb = HTTP_CATALOG.'image/'.$robokassa_images_store[ $store['store_id'] ][$i];
						$value = $robokassa_images_store[ $store['store_id'] ][$i];
					}
			
					if( empty($this->data['robokassa_currencies_store'][ $store['store_id'] ][$i]) )
					$this->data['robokassa_currencies_store'][ $store['store_id'] ][$i] = '';
			
			
					$this->data['robokassa_images_store'][ $store['store_id'] ][$i] = array(
							"thumb" => $thumb,
							"value" => $value
						);
				}
			}
		}
		//---------
		
		
		
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 40, 40);
		
		/* start update: a1 */		
		if (  $this->config->get('robokassa_password1')  ) {
			$this->data['robokassa_password1'] = 1; 
		}
		else
		{
			$this->data['robokassa_password1'] = 0; 
		}
		
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{		
				$dat = $this->config->get('robokassa_password1_store');
				if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
				if( empty($dat[$store['store_id']]) ) $dat[$store['store_id']] = false;
				
				if( !empty( $dat[ $store['store_id'] ] ) ) 
				{
					$this->data['robokassa_password1_store'][ $store['store_id'] ] = 1; 
				}
				else
				{
					$this->data['robokassa_password1_store'][ $store['store_id'] ] = 0; 
				}
			}
		}
		//---------
		
		$this->data['token'] = $this->session->data['token'];
		
		if (  $this->config->get('robokassa_password2')  ) {
			$this->data['robokassa_password2'] = 1; 
		} 	
		else
		{
			$this->data['robokassa_password2'] = 0; 
		}	
		
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{	
				$dat = $this->config->get('robokassa_password2_store');
				if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
				if( empty($dat[$store['store_id']]) ) $dat[$store['store_id']] = false;
				
				if ( !empty( $dat[ $store['store_id'] ] ) ) {
					$this->data['robokassa_password2_store'][ $store['store_id'] ] = 1; 
				} 	
				else
				{
					$this->data['robokassa_password2_store'][ $store['store_id'] ] = 0; 
				}	
			}
		}
		
		//---------
		
		
		if (isset($this->request->post['robokassa_icons'])) {
			$this->data['robokassa_icons'] = $this->request->post['robokassa_icons'];
		} else {
			$this->data['robokassa_icons'] = $this->config->get('robokassa_icons'); 
		} 		
		
		/* kin insert metka: d1 */
		if (isset($this->request->post['robokassa_desc'])) {
			$this->data['robokassa_desc'] = $this->request->post['robokassa_desc'];
		} elseif( $this->config->get('robokassa_desc') ) 
		{
			if( is_array($this->config->get('robokassa_desc')) )
			{
				$this->data['robokassa_desc'] = $this->config->get('robokassa_desc'); 
			}
			else
			{
				$this->data['robokassa_desc'] = unserialize($this->config->get('robokassa_desc')); 
			}
		} elseif( !$this->config->has('robokassa_desc') ) {
			
			foreach($this->data['languages'] as $language)
			{
				$Lang = new Language( $language['directory'] );
				$Lang->load('payment/robokassa');
				
				$this->data['robokassa_desc'][$language['code']] = $Lang->get('entry_robokassa_desc_default');
			}
		} else {
			$this->data['robokassa_desc'] = array();
		}
		
		
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{
				if (isset($this->request->post['robokassa_desc_store'][$store['store_id']])) {
					$this->data['robokassa_desc_store'][$store['store_id']] = $this->request->post['robokassa_desc_store'][$store['store_id']];
				} 
				elseif( $this->config->get('robokassa_desc_store') ) 
				{
					$dat = $this->config->get('robokassa_desc_store');
					if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
					
					if( is_array($dat[$store['store_id']]) )
					{
						$this->data['robokassa_desc_store'][$store['store_id']] = $dat[$store['store_id']];
					}
					else
					{
						$this->data['robokassa_desc_store'][$store['store_id']] = $dat[$store['store_id']];
					}
				} 
				elseif( !$this->config->has('robokassa_desc_store') ) 
				{
					foreach($this->data['languages'] as $language)
					{
						$Lang = new Language( $language['directory'] );
						$Lang->load('payment/robokassa');
				
						$this->data['robokassa_desc_store'][$store['store_id']][$language['code']] = $Lang->get('text_order_comment_default');
					}
				} 
				else 
				{
					$this->data['robokassa_desc_store'][$store['store_id']] = array();
				}
			}
		}
		
		/* end kin metka: d1 */
		
		
		
		
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{	
				if (isset($this->request->post['robokassa_icons_store'][ $store['store_id'] ])) {
					$this->data['robokassa_icons_store'][ $store['store_id'] ] = $this->request->post['robokassa_icons_store'][ $store['store_id'] ];
				} else {
					$dat = $this->config->get('robokassa_icons_store');
					if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
					if( empty($dat[$store['store_id']]) ) $dat[$store['store_id']] = false;
					$this->data['robokassa_icons_store'][ $store['store_id'] ] = $dat[ $store['store_id'] ]; 
				} 		
			}
		}
		
		//---------
		
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['robokassa_geo_zone_id'])) {
			$this->data['robokassa_geo_zone_id'] = $this->request->post['robokassa_geo_zone_id'];
		} else {
			$this->data['robokassa_geo_zone_id'] = $this->config->get('robokassa_geo_zone_id'); 
		} 
		
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{	
				if (isset($this->request->post['robokassa_geo_zone_id_store'][ $store['store_id'] ])) {
					$this->data['robokassa_geo_zone_id_store'][ $store['store_id'] ] = $this->request->post['robokassa_geo_zone_id_store'][ $store['store_id'] ];
				} else {
					$dat = $this->config->get('robokassa_geo_zone_id_store');
					if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
					if( empty($dat[$store['store_id']]) ) $dat[$store['store_id']] = false;
					$this->data['robokassa_geo_zone_id_store'][ $store['store_id'] ] = $dat[ $store['store_id'] ]; 
				} 
			}
		}
		
		//---------
		
		$this->load->model('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['robokassa_status'])) {
			$this->data['robokassa_status'] = $this->request->post['robokassa_status'];
		} else {
			$this->data['robokassa_status'] = $this->config->get('robokassa_status');
		}
		
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{	
				if (isset($this->request->post['robokassa_status_store'][ $store['store_id'] ])) {
					$this->data['robokassa_status_store'][ $store['store_id'] ] = $this->request->post['robokassa_status_store'][ $store['store_id'] ];
				} else {
					$dat = $this->config->get('robokassa_status_store');
					if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
					if( empty($dat[$store['store_id']]) ) $dat[$store['store_id']] = false;
					$this->data['robokassa_status_store'][ $store['store_id'] ] = $dat[ $store['store_id'] ]; 
				}
			}
		}
		
		//---------
		
		if (isset($this->request->post['robokassa_sort_order'])) {
			$this->data['robokassa_sort_order'] = $this->request->post['robokassa_sort_order'];
		} else {
			$this->data['robokassa_sort_order'] = $this->config->get('robokassa_sort_order');
		}
		
		if( !empty($stores) )
		{
			foreach($stores as $store)
			{	
				if (isset($this->request->post['robokassa_sort_order_store'][ $store['store_id'] ])) {
					$this->data['robokassa_sort_order_store'][ $store['store_id'] ] = $this->request->post['robokassa_sort_order_store'][ $store['store_id'] ];
				} else {
					$dat = $this->config->get('robokassa_sort_order_store');
					if( $this->is_serialized( $dat ) ) $dat = unserialize($dat);
					if( empty($dat[$store['store_id']]) ) $dat[$store['store_id']] = false;
					$this->data['robokassa_sort_order_store'][ $store['store_id'] ] = $dat[ $store['store_id'] ]; 
				}
			}
		}
		//-----------------------------------
		
		
		$this->template = 'payment/robokassa.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
				
		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/robokassa')) {
			$this->error[] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['robokassa_password1'] && !$this->config->get('robokassa_password1') ) 
		{
			$this->error[] = $this->language->get('error_robokassa_password1');
		}
		
		if (!$this->request->post['robokassa_password2'] && !$this->config->get('robokassa_password2') ) 
		{
			$this->error[] = $this->language->get('error_robokassa_password2');
		}
		
		/*
		if( !empty($this->request->post['robokassa_password1']) && 
			!preg_match("/^[a-zA-Z0-9]+$/", $this->request->post['robokassa_password1']) )
		{
			$this->error[] = $this->language->get('error_robokassa_password_symbols');
		}
		*/
		
		if (!$this->request->post['robokassa_shop_login']) {
			$this->error[] = $this->language->get('error_robokassa_shop_login');
		}
		
		if( !empty($this->request->post['robokassa_shop_login']) )
		$this->request->post['robokassa_shop_login'] = trim($this->request->post['robokassa_shop_login']);
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
	
	
	public function image() {
		$this->load->model('tool/image');
		
		if (isset($this->request->get['image'])) {
			$this->response->setOutput(HTTP_IMAGE.$this->request->get['image']);
		}
	}
	
	function is_serialized( $data ) {
    // if it isn't a string, it isn't serialized
		if ( !is_string( $data ) )
        return false;
		$data = trim( $data );
		if ( 'N;' == $data )
        return true;
		if ( !preg_match( '/^([adObis]):/', $data, $badions ) )
        return false;
		switch ( $badions[1] ) {
        case 'a' :
        case 'O' :
        case 's' :
            if ( preg_match( "/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data ) )
                return true;
            break;
        case 'b' :
        case 'i' :
        case 'd' :
            if ( preg_match( "/^{$badions[1]}:[0-9.E-]+;\$/", $data ) )
                return true;
            break;
		}
		return false;
	}
	
}
?>