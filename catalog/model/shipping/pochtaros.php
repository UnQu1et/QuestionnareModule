<?php
class ModelShippingPochtaros extends Model {
	function getQuote($address) {
		$this->load->language('shipping/pochtaros');

		$query = $this->db->query("SELECT name FROM " . DB_PREFIX . "zone WHERE zone_id = " . (int)$address['zone_id'] . " AND country_id = " . (int)$address['country_id']);


		if ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {

			$region = array();
			$region['from'] = $this->config->get('pochtaros_zone');
			$region['to'] = $query->rows[0]['name'];
			
			

			$this->load->model('localisation/zone_dv');
			$region = $this->model_localisation_zone_dv->getZone($region);

			  if ($region){
				$from=urlencode($region['from']);
				$to=urlencode($region['to']);
				$weight=$this->cart->getWeight();
				$ocen=$this->cart->getSubTotal();

				$Request='http://api.postcalc.ru/?f='.$from.'&t='.$to.'&w='.$weight.'&v='.$ocen.'&o=php&e=0';
				$Response=file_get_contents($Request);
				$arrResponse=unserialize($Response);
				$price=floor($arrResponse['ЦеннаяБандероль1Класс']['Доставка']);
				
			  }

			if(isset($price)){
			
				$cost = $this->config->get('pochtaros_cost')+$price;
				
				$text = $this->currency->format($this->tax->calculate($cost,'', $this->config->get('config_tax')),'RUB',1);
			}else{
				$cost = '';
				$text = '';
			}

			$quote_data = array();

			$quote_data['pochtaros'] = array(
				'code'         => 'pochtaros.pochtaros',
				'title'        => $this->language->get('text_description'),
				'cost'         => $cost,
				'tax_class_id' => '',
				'text'         => $text
			);

			$method_data = array(
				'code'       => 'pochtaros',
				'title'      => $this->config->get('pochtaros_name'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('pochtaros_sort_order'),
				'error'      => false
			);
		}

		return $method_data;
	}
}
?>