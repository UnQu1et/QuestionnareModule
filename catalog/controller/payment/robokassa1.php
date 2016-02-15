<?php /* robokassa metka */
class ControllerPaymentRobokassa1 extends Controller {

	private $INDEX = 1;

	protected function index() 
	{
		
		$STORE_ID = $this->config->get('config_store_id');
		
		$this->load->model('localisation/currency');
		$currencies = $this->model_localisation_currency->getCurrencies();
		
		$CONFIG = array();
		
		if( $STORE_ID )
		{
			$CONFIG['robokassa_test_mode'] = $this->config->get('robokassa_test_mode_store');
			$CONFIG['robokassa_password1'] = $this->config->get('robokassa_password1_store');
			$CONFIG['robokassa_shop_login'] = $this->config->get('robokassa_shop_login_store');
			$CONFIG['robokassa_currency'] = $this->config->get('robokassa_currency_store');
			$CONFIG['robokassa_currencies'] = $this->config->get('robokassa_currencies_store');
			$CONFIG['robokassa_commission'] = $this->config->get('robokassa_commission_store');
			$CONFIG['robokassa_confirm_status'] = $this->config->get('robokassa_confirm_status_store');
			$CONFIG['robokassa_interface_language'] = $this->config->get('robokassa_interface_language_store');
			$CONFIG['robokassa_default_language'] = $this->config->get('robokassa_default_language_store');
			$CONFIG['robokassa_desc'] = $this->config->get('robokassa_desc_store');
			$CONFIG['robokassa_desc'] = $CONFIG['robokassa_desc'][ $this->config->get('config_language') ];
			
			foreach($CONFIG as $key=>$value)
			{
				if( $this->is_serialized($value) )
				$value = unserialize($value);
				
				if( isset( $value[$STORE_ID] ) )
				$CONFIG[$key] = $value[$STORE_ID];
				else
				$CONFIG[$key] = '';
			}
		}
		else
		{
			$CONFIG['robokassa_test_mode'] = $this->config->get('robokassa_test_mode');
			$CONFIG['robokassa_password1'] = $this->config->get('robokassa_password1');
			$CONFIG['robokassa_shop_login'] = $this->config->get('robokassa_shop_login');
			$CONFIG['robokassa_currency'] = $this->config->get('robokassa_currency');
			$CONFIG['robokassa_currencies'] = $this->config->get('robokassa_currencies');
			$CONFIG['robokassa_commission'] = $this->config->get('robokassa_commission');
			$CONFIG['robokassa_confirm_status'] = $this->config->get('robokassa_confirm_status');
			$CONFIG['robokassa_interface_language'] = $this->config->get('robokassa_interface_language');
			$CONFIG['robokassa_default_language'] = $this->config->get('robokassa_default_language');
			$CONFIG['robokassa_desc'] = $this->config->get('robokassa_desc');
			$CONFIG['robokassa_desc'] = $CONFIG['robokassa_desc'][ $this->config->get('config_language') ];
			
			
			foreach($CONFIG as $key=>$value)
			{
				if( $this->is_serialized($value) )
				$value = unserialize($value);
				
				$CONFIG[$key] = $value;
			}
		}
		
		
		$RUB = '';
		
		if( !isset($currencies['RUB']) && !isset($currencies['RUR']) ){}
		elseif( isset($currencies['RUB']) ) $RUB = 'RUB';
		elseif( isset($currencies['RUR']) ) $RUB = 'RUR';
		
	
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		
		$this->load->model('checkout/order');
		
		$order_info = array();
		
		
		
		if( !empty($this->session->data['order_id']) )
		{
			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
			
			if( empty( $order_info['total'] ) || $order_info['total'] == 0 )
			{
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` 
										   WHERE order_id=".(int)$this->session->data['order_id']);
				$hash = array();
			
				if( !empty($query->rows) )
				{
					foreach($query->rows as $row)
					{
						$hash[ $row['code'] ] = $row['value'];
					}
				}
				
				if( empty( $hash['sub_total'] ) )
				{
					$hash['sub_total'] = $this->cart->getTotal();
				}
			
				$order_info['total'] = 0;
			
				foreach($hash as $key=>$value) 
				{
					$order_info['total'] += $value;
				}
			}
		}
		else
		{
			$order_info['total'] = $this->cart->getTotal();
			$order_info['store_name'] = $this->config->get('config_name');
			$order_info['store_url'] = $_SERVER['HTTP_HOST'];
		}
		
		
		if( $CONFIG['robokassa_test_mode'] )
		{
			$this->data['action'] = "http://test.robokassa.ru/Index.aspx";
		}
		else
		{
			//$this->data['action'] = "https://merchant.roboxchange.com/Index.aspx";
			$this->data['action'] = "https://auth.robokassa.ru/Merchant/Index.aspx";
			
		}
		
		$mrh_pass1 = $CONFIG['robokassa_password1'];
		$this->data['mrh_login'] = $CONFIG['robokassa_shop_login'];
			
		$mrh_login = $this->data['mrh_login'];
		
		$out_summ = $order_info['total'];
				
		if( $this->config->get('config_currency')!=$CONFIG['robokassa_currency'] ) 
		{
			$out_summ = $this->currency->convert($out_summ, $this->config->get('config_currency'), $CONFIG['robokassa_currency']);
		}
		
		$robokassa_currencies = $CONFIG['robokassa_currencies'];
		if( $robokassa_currencies[$this->INDEX] == 'robokassa' )
		$robokassa_currencies[$this->INDEX] = '';
		$this->data['in_curr'] = $robokassa_currencies[$this->INDEX];
		
		if( $CONFIG['robokassa_commission'] == 'shop' && !$CONFIG['robokassa_test_mode'] )
		{
			$url = 'http://merchant.roboxchange.com/WebService/Service.asmx/CalcOutSumm?MerchantLogin='.$mrh_login.
					'&IncCurrLabel='.$this->data['in_curr'].'&IncSum='.$out_summ;
			
			#echo $url;
			
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
			
			$ar = array();
			//<OutSum>93.200000</OutSum>
			
			if( $page && preg_match("/<OutSum>([\d\.]+)<\/OutSum>/", $page, $ar) )
			{
				if( !empty($ar[1]) )
				{
					$out_summ = $ar[1];
				}
			}
		}
		
		$shp_item = "2";
		
		
		$this->data['robokassa_confirm_status'] = $CONFIG['robokassa_confirm_status'];
		
		$in_curr = $robokassa_currencies[$this->INDEX];
		
		if( !empty($this->session->data['order_id']) )
		$inv_id =  $this->session->data['order_id'];
		else
		$inv_id = 0;
		
		$this->data['out_summ'] = $out_summ;
		
		if( !empty($this->session->data['order_id']) )
		$this->data['inv_id'] =  $this->session->data['order_id'];
		else
		$this->data['inv_id'] = 0;
		

		$this->data['inv_desc'] = $CONFIG['robokassa_desc'];
		$this->data['inv_desc'] = str_replace("{number}", $this->data['inv_id'], $this->data['inv_desc']);
		$this->data['inv_desc'] = str_replace("{siteurl}", $order_info['store_url'], $this->data['inv_desc']);
		
		$this->data['crc'] = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item");
		$this->data['shp_item'] = $shp_item;
		
		$culture = $this->session->data['language'];
		
		if( $CONFIG['robokassa_interface_language'] && $CONFIG['robokassa_interface_language']!='detect' )
		{
			$culture = $CONFIG['robokassa_interface_language'];
		}
		elseif( $CONFIG['robokassa_interface_language']=='detect' )
		{
			if( $this->session->data['language'] == 'ru' || $this->session->data['language']=='en' )
			{
				$culture = $this->session->data['language'];
			}
			elseif( $CONFIG['robokassa_default_language'] )
			{
				$culture = $CONFIG['robokassa_default_language'];
			}
			else
			{
				$culture = 'ru';
			}
		}
		else
		{
			if( $culture!='en' )
			{
				$culture!='ru';
			}
		}
		
		$this->data['culture'] = $culture;
		
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/robokassa.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/robokassa.tpl';
		} else {
			$this->template = 'default/template/payment/robokassa.tpl';
		}		
		
		$this->render();
	}
	
	protected function is_serialized( $data ) {
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