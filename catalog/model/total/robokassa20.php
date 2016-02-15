<?php
class ModelTotalRobokassa20 extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) 
	{
		if( isset($this->session->data['payment_method']) && 
			preg_match("/^robokassa[\d]*/", $this->session->data['payment_method']['code']) &&
			$this->config->get('robokassa_dopcost')
		) 
		{
		
			$title = $this->session->data['payment_method']['title'];
			
			$STORE_ID = $this->config->get('config_store_id');
		
			$CONFIG = array();
		
			if( $STORE_ID )
			{
				$CONFIG['robokassa_dopcostname'] = $this->config->get('robokassa_dopcostname_store');
				$CONFIG['robokassa_dopcost'] = $this->config->get('robokassa_dopcost_store');
				$CONFIG['robokassa_dopcosttype'] = $this->config->get('robokassa_dopcosttype_store');
			
				
				foreach($CONFIG as $key=>$value)
				{
					if( $this->is_serialized($value) )
					$value = unserialize($value);
				
					$CONFIG[$key] = $value;
				}
				
				$CONFIG['robokassa_dopcostname'] = $CONFIG['robokassa_dopcostname'][ $this->config->get('config_language') ];

			}
			else
			{
				$CONFIG['robokassa_dopcostname'] = $this->config->get('robokassa_dopcostname');
				$CONFIG['robokassa_dopcost'] = $this->config->get('robokassa_dopcost');
				$CONFIG['robokassa_dopcosttype'] = $this->config->get('robokassa_dopcosttype');
			
				
				foreach($CONFIG as $key=>$value)
				{
					if( $this->is_serialized($value) )
					$value = unserialize($value);
				
					$CONFIG[$key] = $value;
				}
				
				$CONFIG['robokassa_dopcostname'] = $CONFIG['robokassa_dopcostname'][ $this->config->get('config_language') ];
			}
			
			
			$cost = 0;
			
			if( $CONFIG['robokassa_dopcost'] )
			{
			
				if( $CONFIG['robokassa_dopcosttype'] == 'int' )
				{
					$cost = $CONFIG['robokassa_dopcost'];
				}
				else
				{
					$cost = round( ($total * $CONFIG['robokassa_dopcost'] / 100), 2 );
				}
			}
			
			
			if( !empty($CONFIG['robokassa_dopcostname']) )
			$title = $CONFIG['robokassa_dopcostname'];
		
			$total_data[] = array( 
				'code'       => 'robokassa20',
        		'title'      => $title,
        		'text'       => $this->currency->format($cost),
        		'value'      => $cost,
				'sort_order' => $this->config->get('robokassa_sort_order')
			);
			
			$total += $cost;
		}			
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