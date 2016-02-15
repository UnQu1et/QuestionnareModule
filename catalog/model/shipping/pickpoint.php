<?php 
class ModelShippingPickPoint extends Model {    
  	public function getQuote($address) {
		$this->load->language('shipping/pickpoint');

		$quote_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "geo_zone ORDER BY name");
	
		foreach ($query->rows as $result) {
			if ($this->config->get('pickpoint_' . $result['geo_zone_id'] . '_status')) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$result['geo_zone_id'] . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
			
				if ($query->num_rows) {
					$status = true;
				} else {
					$status = false;
				}
			} else {
				$status = false;
			}
		
			if ($status) {
				$cost = '';
				//$pickpoint = $this->cart->getweight();
				
				$rates = explode(',', $this->config->get('pickpoint_' . $result['geo_zone_id'] . '_rate'));
				
				foreach ($rates as $rate) {

					$cost = $rate;

				}
				
				if ($this->config->get('pickpoint_free_shipping') > 0)
				if ($this->cart->getSubTotal() >  $this->config->get('pickpoint_free_shipping')) {
					$cost = 0;
				}

				if ((string)$cost != '') { 
					$quote_data['pickpoint_' . $result['geo_zone_id']] = array(
						'code'         => 'pickpoint.pickpoint_' . $result['geo_zone_id'],
						'title'        => $this->language->get('pickpoint_text_pickpoint'), //$result['name'] . '  (' . $this->language->get('pickpoint_text_pickpoint') . ')',
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('pickpoint_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('pickpoint_tax_class_id'), $this->config->get('config_tax')))//,
					);	
				}
			}
		}
		
		$method_data = array();

				if (isset($this->request->post['pickpoint_terminal_id'])) 
					$this->session->data['pickpoint_terminal_id'] = $this->request->post['pickpoint_terminal_id'];
				else
					unset($this->session->data['pickpoint_terminal_id']);

	
		if ($quote_data) {
      		$method_data = array(
        		'code'       => 'pickpoint',
        		'title'      => $this->language->get('pickpoint_text_title'),
        		'quote'      => $quote_data,
			'sort_order' => $this->config->get('pickpoint_sort_order'),
			'error'      => false
      		);
		}
	
		return $method_data;
  	}
}
?>