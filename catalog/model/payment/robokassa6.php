<?php  /* robokassa metka */
class ModelPaymentRobokassa6 extends Model {

	private $INDEX=6;

  	public function getMethod($address, $total) {
	
		if( $this->config->get('robokassa_status') && $this->config->get('robokassa__status') ){
		
		
		$STORE_ID = $this->config->get('config_store_id');
		
		$CONFIG = array();
		
		if( $STORE_ID )
		{
			$CONFIG['robokassa_geo_zone_id'] = $this->config->get('robokassa_geo_zone_id_store');
			$CONFIG['robokassa_methods'] = $this->config->get('robokassa_methods_store');
			$CONFIG['robokassa_currencies'] = $this->config->get('robokassa_currencies_store');
			$CONFIG['robokassa_images'] = $this->config->get('robokassa_images_store');
			$CONFIG['robokassa_icons'] = $this->config->get('robokassa_icons_store');
			$CONFIG['robokassa_sort_order'] = $this->config->get('robokassa_sort_order_store');
			$CONFIG['robokassa_sort_order_num'] = $this->config->get('robokassa'.$this->INDEX.'_sort_order_store');
			
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
			$CONFIG['robokassa_geo_zone_id'] = $this->config->get('robokassa_geo_zone_id');
			$CONFIG['robokassa_methods'] = $this->config->get('robokassa_methods');
			$CONFIG['robokassa_currencies'] = $this->config->get('robokassa_currencies');
			$CONFIG['robokassa_images'] = $this->config->get('robokassa_images');
			$CONFIG['robokassa_icons'] = $this->config->get('robokassa_icons');
			$CONFIG['robokassa_sort_order'] = $this->config->get('robokassa_sort_order');
			$CONFIG['robokassa_sort_order_num'] = $this->config->get('robokassa'.$this->INDEX.'_sort_order');
			
			
			foreach($CONFIG as $key=>$value)
			{
				if( $this->is_serialized($value) )
				$value = unserialize($value);
				
				$CONFIG[$key] = $value;
			}
		}
		
		
		
		$this->load->model('localisation/currency');
		$currencies = $this->model_localisation_currency->getCurrencies();
		
		$RUB = '';
		
		if( !isset($currencies['RUB']) && !isset($currencies['RUR']) ) return;
		elseif( isset($currencies['RUB']) ) $RUB = 'RUB';
		elseif( isset($currencies['RUR']) ) $RUB = 'RUR';
		
		if( !empty($address['country_id']) && !empty($address['zone_id']) )
		{
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$CONFIG['robokassa_geo_zone_id'] . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
			if (!$CONFIG['robokassa_geo_zone_id']) {
				$status = true;
			} elseif ($query->num_rows) {
				$status = true;
			} else {
				$status = false;
			}	
		}
		else
		{
			$status = true;
		}
		
		
		$vr_robokassa_methods = $CONFIG['robokassa_methods'];
		if( !is_array( $vr_robokassa_methods[$this->INDEX] ) )
		{
			$robokassa_methods[$this->INDEX][ $CONFIG['config_language'] ] = $vr_robokassa_methods[$this->INDEX];			
		}
		else
		{
			$robokassa_methods = $vr_robokassa_methods;
		}
		
		$method_data = array();
	
		$robokassa_currencies = $CONFIG['robokassa_currencies'];
		$robokassa_images = $CONFIG['robokassa_images'];
		
		if( empty($robokassa_images[$this->INDEX]) )
		$robokassa_images[$this->INDEX] = 'data/robokassa_icons/'.$robokassa_currencies[$this->INDEX].'.png';
		
		if ($status) 
		{
			if($this->INDEX!=0)
			$name = 'robokassa'.$this->INDEX;
			else
			$name = 'robokassa';			
			
			$image = '';
			
			if(  $CONFIG['robokassa_icons'] && file_exists(DIR_IMAGE.$robokassa_images[$this->INDEX]) )
			{
				$img_url = preg_replace("/\/$/", "", HTTP_SERVER);
				$img_url .= '/image/'.$robokassa_images[$this->INDEX];
				$image = '<table><tr><td style="vertical-align: middle;  text-align: left; width: 30px"><img src="'.$img_url.'"></td><td style="vertical-align: middle; text-align: left; " valign=middle>'.$robokassa_methods[$this->INDEX][$this->config->get('config_language')].'</td></tr></table>';
			}
			else
			{
				$title = $robokassa_methods[$this->INDEX][$this->config->get('config_language')];
			}
			
			$title = $robokassa_methods[$this->INDEX][$this->config->get('config_language')];
			
      		$method_data = array( 
        		'code'       => $name,
				'title'		 => $title,
				'image'		 => $image,
				'sort_order' => $CONFIG['robokassa_sort_order'] + $CONFIG['robokassa_sort_order_num']
      		);
    	}
   
   
    	return $method_data;
  	}}
	
	
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