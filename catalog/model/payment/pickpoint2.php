<?php 
class ModelPaymentPickPoint2 extends Model {

	public function setPickPointOrder($order_id, $terminal_id, $terminal_address) {
		if (($terminal_id!="")&&($terminal_address!=""))
			$this->db->query("INSERT INTO " . DB_PREFIX . "pickpoint SET pickpoint_order='',order_id = '" . (int)$order_id . "', terminal_id = '".$terminal_id."', terminal_address='" . $this->db->escape($terminal_address) . "'" );
	}

	public function getPickPointOrder($order_id) {
		$query = $this->db->query("SELECT pickpoint_order FROM " . DB_PREFIX . "pickpoint WHERE order_id = '" . (int)$order_id . "'");
		
		if (isset($query->row['pickpoint_order']))
		return $query->row['pickpoint_order'];
		else
		return NULL;
	}

  	public function getMethod($address, $total) {
		$this->load->language('payment/pickpoint2');
		
	
		if (isset($this->session->data['shipping_method']['code']))
		$shipping_method = $this->session->data['shipping_method']['code'];
		else
		$shipping_method = "";

		if (strstr($shipping_method, 'pickpoint')==false) {
		        $status = false;
		} else {
		        $status = true;
		}

		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'code'       => 'pickpoint2',
        		'title'      =>  $this->language->get('text_title'),
			'sort_order' => $this->config->get('pickpoint2_sort_order')
      		);
    	}
   
    	return $method_data;
  	}
}
?>