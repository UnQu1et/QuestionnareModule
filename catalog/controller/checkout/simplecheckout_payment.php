<?php  
/*
@author	Dmitriy Kubarev
@link	http://www.simpleopencart.com
@link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/  

class ControllerCheckoutSimpleCheckoutPayment extends Controller {
    public function index() {
      
        $this->language->load('checkout/simplecheckout');

        $this->data['address_empty'] = false;
        
        $address = $this->simple->payment_address;
        $address_fields = $this->simple->payment_address_fields;

        $reload = explode(',', $this->config->get('simple_set_for_reload'));
        
        if ($address['country_id'] == '' && in_array('main_country_id', $reload) && !empty($address_fields['main_country_id']) && $address_fields['main_country_id']['type'] != 'hidden') {
            $this->data['address_empty'] = true;
        }
        
        if ($address['zone_id'] === '' && in_array('main_zone_id', $reload) && !empty($address_fields['main_zone_id']) && $address_fields['main_zone_id']['type'] != 'hidden') {
            $this->data['address_empty'] = true;
        }
        
        if ($address['city'] == '' && in_array('main_city', $reload) && !empty($address_fields['main_city']) && $address_fields['main_city']['type'] != 'hidden') {
            $this->data['address_empty'] = true;
        }
        
        if ($address['postcode'] == '' && in_array('main_postcode', $reload) && !empty($address_fields['main_postcode']) && $address_fields['main_postcode']['type'] != 'hidden') {
            $this->data['address_empty'] = true;
        }
        
        $this->data['simple_debug'] = $this->config->get('simple_debug');
        $this->data['address'] = $address;
        
        $this->data['simple_payment_view_address_empty'] = $this->config->get('simple_payment_view_address_empty');
        
        $simple_payment_view_address_full = $this->config->get('simple_payment_view_address_full');
        $simple_payment_view_autoselect_first = $this->config->get('simple_payment_methods_hide') ? true : $this->config->get('simple_payment_view_autoselect_first');
        $simple_payment_methods_hide = $this->config->get('simple_payment_methods_hide');        
           
        $this->data['payment_methods'] = array();
      
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

        $method_data = array();
        
        $this->load->model('setting/extension');
        
        $results = $this->model_setting_extension->getExtensions('payment');
        
        $simple_links = $this->cart->hasShipping() ? $this->config->get('simple_links') : array();
        
        $shipping_method = $this->simple->shipping_method;
        
        $shipping_method_code = !empty($shipping_method['code']) ? $shipping_method['code'] : false;
        $shipping_method_code = $shipping_method_code ? explode('.',$shipping_method_code) : false;
        $shipping_method_code = count($shipping_method_code) == 2 ? $shipping_method_code : false;
        
        if ($this->customer->isLogged()) {
            $customer_group_id = $this->customer->getCustomerGroupId();
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }
  
        if ($this->config->get('simple_customer_view_customer_type')) {
            $customer_groups = $this->simple->get_customer_groups();
        
            if (isset($this->request->post['customer_group_id']) && array_key_exists($this->request->post['customer_group_id'], $customer_groups)) {
                $customer_group_id = $this->request->post['customer_group_id'];
            }
        } 
    
        $filter_methods = array();
        $simple_group_payment = $this->config->get('simple_group_payment');
        if (!empty($simple_group_payment[$customer_group_id])) {
            $filter_methods = explode(',', $simple_group_payment[$customer_group_id]);
        }

        $simple_payment_titles = $this->config->get('simple_payment_titles');
        
        foreach ($results as $result) {
            $show_module = true;

            if ($this->data['address_empty'] && !empty($simple_payment_view_address_full[$result['code']])) {
                $show_module = false;
            } 

            if (is_array($filter_methods) && !empty($filter_methods)) {
                if (in_array($result['code'], $filter_methods)) {
                    $show_module = true;
                } else {
                    $show_module = false;
                }
            }
            
            if ($this->config->get($result['code'] . '_status') && $show_module) {
                
                $for_shipping_methods = array();
                if (!empty($simple_links[$result['code']])) {
                    $for_shipping_methods = explode(",",$simple_links[$result['code']]);
                }
                
                if (empty($for_shipping_methods) || ($shipping_method_code && (in_array($shipping_method_code[0], $for_shipping_methods) || in_array($shipping_method_code[0].'.'.$shipping_method_code[1], $for_shipping_methods)))) {
                    $this->load->model('payment/' . $result['code']);
                    
                    $method = $this->{'model_payment_' . $result['code']}->getMethod($address, $total); 
                    
                    if ($method) {
                        $method['description'] = !empty($simple_payment_titles[$result['code']]['use_description']) && !empty($simple_payment_titles[$result['code']]['description'][$this->simple->get_language_code()]) ? html_entity_decode($simple_payment_titles[$result['code']]['description'][$this->simple->get_language_code()]) : (!empty($method['description']) ? $method['description'] : '');
                        $method_data[$result['code']] = $method;
                    }
                }
            }
        }
                     
        $sort_order = array(); 
      
        foreach ($method_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $method_data);            
        
        $this->data['payment_methods'] = $method_data;    
        $this->data['payment_method'] = null;
        $this->data['error_warning'] = '';

        $this->data['checked_code'] = '';

        $this->data['disabled_methods'] = array();
        
        if (!empty($simple_payment_titles)) {
            foreach ($simple_payment_titles as $key => $value) {
                if (!array_key_exists($key, $this->data['payment_methods']) && !empty($value['show']) && ($value['show'] == 1 || ($value['show'] == 2 && $this->data['address_empty']))) {
                    $this->data['disabled_methods'][$key]['title'] = !empty($value['title'][$this->simple->get_language_code()]) ? html_entity_decode($value['title'][$this->simple->get_language_code()]) : $key;
                    $this->data['disabled_methods'][$key]['description'] = !empty($value['description'][$this->simple->get_language_code()]) ? html_entity_decode($value['description'][$this->simple->get_language_code()]) : '';
                }
            }
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && !empty($this->request->post['payment_method_checked']) && !empty($this->data['payment_methods'][$this->request->post['payment_method_checked']])) {
            $this->data['checked_code'] = $this->request->post['payment_method_checked'];
        }
        
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['payment_method']) && !empty($this->data['payment_methods'][$this->request->post['payment_method']])) {
            $this->data['payment_method'] = $this->data['payment_methods'][$this->request->post['payment_method']];
            
            if (isset($this->request->post['payment_method_current']) && $this->request->post['payment_method_current'] != $this->request->post['payment_method']) {
                $this->data['checked_code'] = $this->request->post['payment_method'];
            }
        }
        
        if ($this->request->server['REQUEST_METHOD'] == 'GET' && isset($this->request->cookie['payment_method']) && !empty($this->data['payment_methods'][$this->request->cookie['payment_method']])) {
            $this->data['payment_method'] = $this->data['payment_methods'][$this->request->cookie['payment_method']];
        }
        
        if (!empty($this->data['payment_methods'])) {
            $first_method = reset($this->data['payment_methods']);
        }
        
        if (!empty($first_method) && ($simple_payment_methods_hide || ($simple_payment_view_autoselect_first && $this->data['checked_code'] == ''))) {
            $this->data['payment_method'] = $first_method;
        }
        
        if (isset($this->data['payment_method'])) {
            setcookie('payment_method', $this->data['payment_method']['code'], time() + 60 * 60 * 24 * 30);
        }
        
        $this->data['code'] = !empty($this->data['payment_method']) ? $this->data['payment_method']['code'] : '';                
        
        $this->simple->payment_methods = $this->data['payment_methods'];
        $this->simple->payment_method = $this->data['payment_method'];
        
        $this->data['simple_show_errors'] = !empty($this->request->post['simple_create_order']) || (!empty($this->request->post['simple_step_next']) && !empty($this->request->post['simple_step']) && $this->request->post['simple_step'] == 'simplecheckout_payment');
        
        $this->validate();
        
        $this->save_to_session();
        
        $this->data['text_payment_address'] = $this->language->get('text_payment_address');
        $this->data['error_no_payment'] = sprintf($this->language->get('error_no_payment'), $this->url->link('information/contact'));
        $this->data['text_checkout_payment_method'] = $this->language->get('text_checkout_payment_method');        
        
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/simplecheckout_payment.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/checkout/simplecheckout_payment.tpl';
        } else {
            $this->template = 'default/template/checkout/simplecheckout_payment.tpl';
        }
                
        $this->response->setOutput($this->render());
    }
    
    private function save_to_session() {
        $this->session->data['payment_methods'] = $this->simple->payment_methods;
        $this->session->data['payment_method'] = $this->simple->payment_method;
        
        if (empty($this->session->data['payment_methods'])) {
            unset($this->session->data['payment_method']);
        }
    }
    
    private function validate() {
        $error = false;
        
        if (empty($this->data['payment_method'])) {
  			$this->data['error_warning'] = $this->language->get('error_payment');
            $error = true;
        }
        
        if ($error) {
            $this->simple->add_error('payment');
        }
        
    	return !$error;
    }
}
?>