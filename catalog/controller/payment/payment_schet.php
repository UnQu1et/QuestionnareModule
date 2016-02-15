<?php
class ControllerPaymentPaymentSchet extends Controller {
	protected function index() {
		$this->language->load('payment/payment_schet');

		$this->data['text_instruction'] = $this->language->get('text_instruction');
		$this->data['text_description'] = $this->language->get('text_description');
		$this->data['text_instruction_2'] = str_replace('{server}', HTTPS_SERVER, $this->language->get('text_instruction_2'));
		$this->data['text_instruction_3'] = $this->language->get('text_instruction_3');
		$this->data['text_payment'] = $this->language->get('text_payment');
         $this->data['text_printpay'] = $this->language->get('text_printpay');

		$this->data['button_confirm'] = $this->language->get('button_confirm');

		$this->data['bank'] = $this->config->get('payment_schet_bank');

		$this->data['continue'] = $this->url->link('checkout/success');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/payment_schet.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/payment_schet.tpl';
		} else {
			$this->template = 'default/template/payment/payment_schet.tpl';
		}

		$this->render();
		
		
	}


    public function printpay() {
		
		if (!empty($this->request->get['order_id'])) {
			$this->load->model('account/order');

			$order_info = $this->model_account_order->getOrder($this->request->get['order_id']);
		} else {
			$this->load->model('checkout/order');

			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		}

		if (!$order_info) {
			return $this->forward('account/order');
		}	
	
		$this->language->load('payment/payment_schet');

		$this->data['text_instruction'] = $this->language->get('text_instruction');
		$this->data['text_payment'] = $this->language->get('text_payment');

		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');

		$this->data['bank'] = $this->config->get('payment_schet_bank');
		$this->data['faktadres'] = $this->config->get('payment_schet_faktadres');
		$this->data['uradres'] = $this->config->get('payment_schet_uradres');
		$this->data['kpp'] = $this->config->get('payment_schet_kpp');
		$this->data['inn'] = $this->config->get('payment_schet_inn');
		$this->data['rs'] = $this->config->get('payment_schet_rs');
		$this->data['bankuser'] = $this->config->get('payment_schet_bankuser');
		$this->data['bik'] = $this->config->get('payment_schet_bik');
		$this->data['ks'] = $this->config->get('payment_schet_ks');
		$this->data['tel'] = $this->config->get('payment_schet_tel');
		$this->data['mobtel'] = $this->config->get('payment_schet_mobtel');
		$this->data['punkt'] = nl2br($this->config->get('payment_schet_punkt'));
		$this->data['images'] = $this->config->get('payment_schet_image');
		$this->data['podpis'] = $this->config->get('payment_schet_podpis');
		
		$this->load->model('tool/suminwords');

		$rur_code = 'RUB';
		$rur_order_total = $this->currency->convert($order_info['total'], $order_info['currency_code'], $rur_code);
		$this->data['amount'] = $this->currency->format($rur_order_total, $rur_code, $order_info['currency_value'], FALSE);
		$this->data['price'] = $this->model_tool_suminwords->num2str($this->currency->format($rur_order_total, $rur_code, $order_info['currency_value'], FALSE));
		$this->data['order_id'] = $order_info['order_id'];
		$this->data['name'] = $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'];
		$this->data['dates'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));
				
		
		if ($this->config->get('config_punkton')) {
			$this->data['punkt'] = $this->data['punkt'];
		} else {
			$this->data['punkt'] = false;
		}		
		if ($this->config->get('config_punkton')) {
			$this->data['schet'] = 'Счет-Договор';
		} else {
			$this->data['schet'] = 'Счет';
		}	
		
		if (!$order_info['payment_address_2']) {
			$this->data['address'] = $order_info['payment_zone'] . ', ' . $order_info['payment_city'] . ', ' .$order_info['payment_address_1'] ;
		} else {
			$this->data['address'] = $order_info['payment_zone'] . ', ' . $order_info['payment_address_2'] . ', ' . $order_info['payment_city'] . ', ' .$order_info['payment_address_1'] ;
		}

		$this->data['postcode'] = $order_info['payment_postcode'];
		
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_price'] = $this->language->get('column_price');
		$this->data['column_total'] = $this->language->get('column_total');
	
		$redirect = '';
		
		if ($this->cart->hasShipping()) {
			$this->load->model('account/address');
	
			if ($this->customer->isLogged() && isset($this->session->data['shipping_address_id'])) {					
				$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);		
			} elseif (isset($this->session->data['guest'])) {
				$shipping_address = $this->session->data['guest']['shipping'];
			}
			
			if (empty($shipping_address)) {								
				$redirect = $this->url->link('checkout/checkout', '', 'SSL');
			}
			
			if (!isset($this->session->data['shipping_method'])) {
				$redirect = $this->url->link('checkout/checkout', '', 'SSL');
			}
		} else {
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
		}

		$this->load->model('account/address');
		
		if ($this->customer->isLogged() && isset($this->session->data['payment_address_id'])) {
			$payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);		
		} elseif (isset($this->session->data['guest'])) {
			$payment_address = $this->session->data['guest']['payment'];
		}	
				
		if (empty($payment_address)) {
			$redirect = $this->url->link('checkout/checkout', '', 'SSL');
		}			

		if (!isset($this->session->data['payment_method'])) {
			$redirect = $this->url->link('checkout/checkout', '', 'SSL');
		}
	
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$redirect = $this->url->link('checkout/cart');				
		}	
		
		$products = $this->cart->getProducts();
				
		foreach ($products as $product) {
			$product_total = 0;
				
			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}		
			
			if ($product['minimum'] > $product_total) {
				$redirect = $this->url->link('checkout/cart');
				
				break;
			}				
		}
						
		if (!$redirect) {
			$total_data = array();
			$total = 0;
			$taxes = $this->cart->getTaxes();
			 
			$this->load->model('setting/extension');
			
			$sort_order = array(); 
			
			$results = $this->model_setting_extension->getExtensions('total');
			
			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}
			
			array_multisort($sort_order, SORT_ASC, $results);
			
			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('total/' . $result['code']);
		
					$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
				}
			}
			
			$sort_order = array(); 
		  
			foreach ($total_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}
	
			array_multisort($sort_order, SORT_ASC, $total_data);
	
			$this->language->load('checkout/checkout');
			
			$data = array();
			
			$data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
			$data['store_id'] = $this->config->get('config_store_id');
			$data['store_name'] = $this->config->get('config_name');
			
			if ($data['store_id']) {
				$data['store_url'] = $this->config->get('config_url');		
			} else {
				$data['store_url'] = HTTP_SERVER;	
			}
			
			if ($this->customer->isLogged()) {
				$data['customer_id'] = $this->customer->getId();
				$data['customer_group_id'] = $this->customer->getCustomerGroupId();
				$data['firstname'] = $this->customer->getFirstName();
				$data['lastname'] = $this->customer->getLastName();
				$data['email'] = $this->customer->getEmail();
				$data['telephone'] = $this->customer->getTelephone();
				$data['fax'] = $this->customer->getFax();
			
				$this->load->model('account/address');
				
				$payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
			} elseif (isset($this->session->data['guest'])) {
				$data['customer_id'] = 0;
				$data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
				$data['firstname'] = $this->session->data['guest']['firstname'];
				$data['lastname'] = $this->session->data['guest']['lastname'];
				$data['email'] = $this->session->data['guest']['email'];
				$data['telephone'] = $this->session->data['guest']['telephone'];
				$data['fax'] = $this->session->data['guest']['fax'];
				
				$payment_address = $this->session->data['guest']['payment'];
			}
			
			$data['payment_firstname'] = $payment_address['firstname'];
			$data['payment_lastname'] = $payment_address['lastname'];	
			$data['payment_company'] = $payment_address['company'];	
			$data['payment_company_id'] = $payment_address['company_id'];	
			$data['payment_tax_id'] = $payment_address['tax_id'];	
			$data['payment_address_1'] = $payment_address['address_1'];
			$data['payment_address_2'] = $payment_address['address_2'];
			$data['payment_city'] = $payment_address['city'];
			$data['payment_postcode'] = $payment_address['postcode'];
			$data['payment_zone'] = $payment_address['zone'];
			$data['payment_zone_id'] = $payment_address['zone_id'];
			$data['payment_country'] = $payment_address['country'];
			$data['payment_country_id'] = $payment_address['country_id'];
			$data['payment_address_format'] = $payment_address['address_format'];
		
			if (isset($this->session->data['payment_method']['title'])) {
				$data['payment_method'] = $this->session->data['payment_method']['title'];
			} else {
				$data['payment_method'] = '';
			}
			
			if (isset($this->session->data['payment_method']['code'])) {
				$data['payment_code'] = $this->session->data['payment_method']['code'];
			} else {
				$data['payment_code'] = '';
			}
						
			if ($this->cart->hasShipping()) {
				if ($this->customer->isLogged()) {
					$this->load->model('account/address');
					
					$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);	
				} elseif (isset($this->session->data['guest'])) {
					$shipping_address = $this->session->data['guest']['shipping'];
				}			
				
				$data['shipping_firstname'] = $shipping_address['firstname'];
				$data['shipping_lastname'] = $shipping_address['lastname'];	
				$data['shipping_company'] = $shipping_address['company'];	
				$data['shipping_address_1'] = $shipping_address['address_1'];
				$data['shipping_address_2'] = $shipping_address['address_2'];
				$data['shipping_city'] = $shipping_address['city'];
				$data['shipping_postcode'] = $shipping_address['postcode'];
				$data['shipping_zone'] = $shipping_address['zone'];
				$data['shipping_zone_id'] = $shipping_address['zone_id'];
				$data['shipping_country'] = $shipping_address['country'];
				$data['shipping_country_id'] = $shipping_address['country_id'];
				$data['shipping_address_format'] = $shipping_address['address_format'];
			
				if (isset($this->session->data['shipping_method']['title'])) {
					$data['shipping_method'] = $this->session->data['shipping_method']['title'];
				} else {
					$data['shipping_method'] = '';
				}
				
				if (isset($this->session->data['shipping_method']['code'])) {
					$data['shipping_code'] = $this->session->data['shipping_method']['code'];
				} else {
					$data['shipping_code'] = '';
				}				
			} else {
				$data['shipping_firstname'] = '';
				$data['shipping_lastname'] = '';	
				$data['shipping_company'] = '';	
				$data['shipping_address_1'] = '';
				$data['shipping_address_2'] = '';
				$data['shipping_city'] = '';
				$data['shipping_postcode'] = '';
				$data['shipping_zone'] = '';
				$data['shipping_zone_id'] = '';
				$data['shipping_country'] = '';
				$data['shipping_country_id'] = '';
				$data['shipping_address_format'] = '';
				$data['shipping_method'] = '';
				$data['shipping_code'] = '';
			}
			
			$product_data = array();
		
			foreach ($this->cart->getProducts() as $product) {
				$option_data = array();
	
				foreach ($product['option'] as $option) {
					if ($option['type'] != 'file') {
						$value = $option['option_value'];	
					} else {
						$value = $this->encryption->decrypt($option['option_value']);
					}	
					
					$option_data[] = array(
						'product_option_id'       => $option['product_option_id'],
						'product_option_value_id' => $option['product_option_value_id'],
						'option_id'               => $option['option_id'],
						'option_value_id'         => $option['option_value_id'],								   
						'name'                    => $option['name'],
						'value'                   => $value,
						'type'                    => $option['type']
					);					
				}
	 
				$product_data[] = array(
					'product_id' => $product['product_id'],
					'name'       => $product['name'],
					'model'      => $product['model'],
					'option'     => $option_data,
					'download'   => $product['download'],
					'quantity'   => $product['quantity'],
					'subtract'   => $product['subtract'],
					'price'      => $product['price'],
					'total'      => $product['total'],
					'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
					'reward'     => $product['reward']
				); 
			}

			$voucher_data = array();
			
			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $voucher) {
					$voucher_data[] = array(
						'description'      => $voucher['description'],
						'code'             => substr(md5(mt_rand()), 0, 10),
						'to_name'          => $voucher['to_name'],
						'to_email'         => $voucher['to_email'],
						'from_name'        => $voucher['from_name'],
						'from_email'       => $voucher['from_email'],
						'voucher_theme_id' => $voucher['voucher_theme_id'],
						'message'          => $voucher['message'],						
						'amount'           => $voucher['amount']
					);
				}
			}  
						
			$data['products'] = $product_data;
			$data['vouchers'] = $voucher_data;
			$data['totals'] = $total_data;
			$data['comment'] = $this->session->data['comment'];
			$data['total'] = $total;
			
			if (isset($this->request->cookie['tracking'])) {
				$this->load->model('affiliate/affiliate');
				
				$affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);
				
				if ($affiliate_info) {
					$data['affiliate_id'] = $affiliate_info['affiliate_id']; 
					$data['commission'] = ($total / 100) * $affiliate_info['commission']; 
				} else {
					$data['affiliate_id'] = 0;
					$data['commission'] = 0;
				}
			} else {
				$data['affiliate_id'] = 0;
				$data['commission'] = 0;
			}
			
			$data['language_id'] = $this->config->get('config_language_id');
			$data['currency_id'] = $this->currency->getId();
			$data['currency_code'] = $this->currency->getCode();
			$data['currency_value'] = $this->currency->getValue($this->currency->getCode());
			$data['ip'] = $this->request->server['REMOTE_ADDR'];
			
			if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
				$data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];	
			} elseif(!empty($this->request->server['HTTP_CLIENT_IP'])) {
				$data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];	
			} else {
				$data['forwarded_ip'] = '';
			}
			
			if (isset($this->request->server['HTTP_USER_AGENT'])) {
				$data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];	
			} else {
				$data['user_agent'] = '';
			}
			
			if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
				$data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];	
			} else {
				$data['accept_language'] = '';
			}
						
			$this->load->model('checkout/order');
			
			$this->session->data['order_id'] = $this->model_checkout_order->addOrder($data);
			
			$this->data['column_name'] = $this->language->get('column_name');
			$this->data['column_model'] = $this->language->get('column_model');
			$this->data['column_quantity'] = $this->language->get('column_quantity');
			$this->data['column_price'] = $this->language->get('column_price');
			$this->data['column_total'] = $this->language->get('column_total');
	
			$this->data['products'] = array();
	
			foreach ($this->cart->getProducts() as $product) {
				$option_data = array();
	
				foreach ($product['option'] as $option) {
					if ($option['type'] != 'file') {
						$value = $option['option_value'];	
					} else {
						$filename = $this->encryption->decrypt($option['option_value']);
						
						$value = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
					}
										
					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
				}  
	 
				$this->data['products'][] = array(
					'product_id' => $product['product_id'],
					'name'       => $product['name'],
					'model'      => $product['model'],
					'option'     => $option_data,
					'quantity'   => $product['quantity'],
					'subtract'   => $product['subtract'],
					'price'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
					'total'      => $this->currency->format($this->tax->calculate($product['total'], $product['tax_class_id'], $this->config->get('config_tax'))),
					'href'       => $this->url->link('product/product', 'product_id=' . $product['product_id'])
				); 
			} 

			$this->data['vouchers'] = array();
			
			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $voucher) {
					$this->data['vouchers'][] = array(
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount'])
					);
				}
			}  
						
			$this->data['totals'] = $total_data;
	
			$this->data['payment'] = $this->getChild('payment/' . $this->session->data['payment_method']['code']);
		} else {
			$this->data['redirect'] = $redirect;
		}
		
		$this->data['numbers'] = sprintf($this->language->get('text_counts'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total, $rur_code, $order_info['currency_value'], FALSE));
				
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/payment_schet.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/payment_schet_printpay.tpl';
		} else {
			$this->template = 'default/template/payment/payment_schet_printpay.tpl';
		}

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}


	public function confirm() {
		$this->language->load('payment/payment_schet');

        $this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$comment  = $this->language->get('text_instruction') . "\n\n";

		/*$comment .= $this->config->get('fl_sberbank_bank_' . $this->config->get('config_language_id')) . "\n\n";
		$comment .= $this->config->get('fl_sberbank_inn_' . $this->config->get('config_language_id')) . "\n\n";
		$comment .= $this->config->get('fl_sberbank_rs_' . $this->config->get('config_language_id')) . "\n\n";
		$comment .= $this->config->get('fl_sberbank_bankuser_' . $this->config->get('config_language_id')) . "\n\n";
		$comment .= $this->config->get('fl_sberbank_bik_' . $this->config->get('config_language_id')) . "\n\n";
		$comment .= $this->config->get('fl_sberbank_ks_' . $this->config->get('config_language_id')) . "\n\n";*/
		$comment .= str_replace('{server}', HTTPS_SERVER, $this->language->get('text_instruction_2')) . $this->data['order_id'] = $order_info['order_id'] .  $this->language->get('text_instruction_3') . "\n\n";
		$comment .= $this->language->get('text_payment');





		$comment  = $this->language->get('text_instruction') . "\n\n";

		$comment .= str_replace('{server}', HTTPS_SERVER, $this->language->get('text_instruction_2')) . $this->data['order_id'] = $order_info['order_id'] .  $this->language->get('text_instruction_3') . "\n\n";
		$comment .= $this->language->get('text_payment_coment');

		$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('payment_schet_order_status_id'), $comment);
	}
	
	
}


?>