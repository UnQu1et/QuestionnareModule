<?php 
/*
@author	Dmitriy Kubarev
@link	http://www.simpleopencart.com
@link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/  

class ControllerCheckoutSimpleCheckoutShipping extends Controller {
    public function index() {

        if (!$this->cart->hasShipping()) {
            return;
        }
        
        $this->language->load('checkout/simplecheckout');

        $this->data['address_empty'] = false;
        
        $address = $this->simple->shipping_address;
        $address_fields = $this->simple->shipping_address_fields;

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
        
        $this->data['simple_shipping_view_title'] = $this->config->get('simple_shipping_view_title');
        $this->data['simple_shipping_view_address_empty'] = $this->config->get('simple_shipping_view_address_empty');
        $simple_shipping_view_address_full = $this->config->get('simple_shipping_view_address_full');
        $simple_shipping_view_autoselect_first = $this->config->get('simple_shipping_methods_hide') ? true : $this->config->get('simple_shipping_view_autoselect_first');
        $simple_shipping_methods_hide = $this->config->get('simple_shipping_methods_hide');
        
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
        $simple_group_shipping = $this->config->get('simple_group_shipping');
        if (!empty($simple_group_shipping[$customer_group_id])) {
            $filter_methods = explode(',', $simple_group_shipping[$customer_group_id]);
        }

        $this->data['shipping_methods'] = array();
    
        $quote_data = array();
        
        $this->load->model('setting/extension');
        
        $results = $this->model_setting_extension->getExtensions('shipping');
        
        $simple_shipping_titles = $this->config->get('simple_shipping_titles');
        
        foreach ($results as $result) {
            $show_module = true;

            if ($this->data['address_empty'] && !empty($simple_shipping_view_address_full[$result['code']])) {
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
                $this->load->model('shipping/' . $result['code']);
                
                $quote = $this->{'model_shipping_' . $result['code']}->getQuote($address); 
    
                if ($quote) {
                    $quote_data[$result['code']] = array( 
                        'title'      => $quote['title'],
                        'quote'      => $quote['quote'], 
                        'sort_order' => $quote['sort_order'],
                        'error'      => $quote['error'],
                        'warning'    => (isset($quote['warning']) ? $quote['warning'] : ''),
                        'description' => !empty($simple_shipping_titles[$result['code']]['use_description']) && !empty($simple_shipping_titles[$result['code']]['description'][$this->simple->get_language_code()]) ? html_entity_decode($simple_shipping_titles[$result['code']]['description'][$this->simple->get_language_code()]) : ''
                    );
                }
            }
        }

        $sort_order = array();
      
        foreach ($quote_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $quote_data);
        
        $this->data['shipping_methods'] = $quote_data;
        $this->data['shipping_method'] = null;
        $this->data['error_warning'] = '';
        
        $this->data['checked_code'] = '';

        $this->data['disabled_methods'] = array();
        
        if (!empty($simple_shipping_titles)) {
            foreach ($simple_shipping_titles as $key => $value) {
                if (!array_key_exists($key, $this->data['shipping_methods']) && !empty($value['show']) && ($value['show'] == 1 || ($value['show'] == 2 && $this->data['address_empty']))) {
                    $this->data['disabled_methods'][$key]['title'] = !empty($value['title'][$this->simple->get_language_code()]) ? html_entity_decode($value['title'][$this->simple->get_language_code()]) : $key;
                    $this->data['disabled_methods'][$key]['description'] = !empty($value['description'][$this->simple->get_language_code()]) ? html_entity_decode($value['description'][$this->simple->get_language_code()]) : '';
                }
            }
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && !empty($this->request->post['shipping_method_checked'])) {
            $shipping = explode('.', $this->request->post['shipping_method_checked']);
            
            if (isset($this->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
                $this->data['checked_code'] = $this->request->post['shipping_method_checked'];
            }
        }
        
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['shipping_method'])) {
            $shipping = explode('.', $this->request->post['shipping_method']);
            
            if (isset($this->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
                $this->data['shipping_method'] = $this->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
               
                if (isset($this->request->post['shipping_method_current']) && $this->request->post['shipping_method_current'] != $this->request->post['shipping_method']) {
                    $this->data['checked_code'] = $this->request->post['shipping_method'];
                }
            }
        }

        if ($this->request->server['REQUEST_METHOD'] == 'GET' && (isset($this->session->data['shipping_method']) || isset($this->request->cookie['shipping_method']))) {
            $user_checked = false;
            if (isset($this->session->data['shipping_method'])) {
                $shipping = explode('.', $this->session->data['shipping_method']['code']);
                $user_checked = true;
            } elseif (isset($this->request->cookie['shipping_method'])) {
                $shipping = explode('.', $this->request->cookie['shipping_method']);
            }
            
            if (isset($this->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
                $this->data['shipping_method'] = $this->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
                if ($user_checked) {
                    $this->data['checked_code'] = $this->session->data['shipping_method']['code'];
                }
            }
        }
        
        if (!empty($this->data['shipping_methods'])) {
            $first = reset($this->data['shipping_methods']);
            if (!empty($first['quote'])) {
                $first_method = reset($first['quote']);
            }
        }
        
        if (!empty($first_method) && ($simple_shipping_methods_hide || ($simple_shipping_view_autoselect_first && $this->data['checked_code'] == ''))) {
            $this->data['shipping_method'] = $first_method;
        }
        
        if (isset($this->data['shipping_method'])) {
            setcookie('shipping_method', $this->data['shipping_method']['code'], time() + 60 * 60 * 24 * 30);
        }
        
        $this->data['code'] = !empty($this->data['shipping_method']) ? $this->data['shipping_method']['code'] : '';                
        
        $this->simple->shipping_methods = $this->data['shipping_methods'];
        $this->simple->shipping_method = $this->data['shipping_method'];
        
        $this->data['simple_show_errors'] = !empty($this->request->post['simple_create_order']) || (!empty($this->request->post['simple_step_next']) && !empty($this->request->post['simple_step']) && $this->request->post['simple_step'] == 'simplecheckout_shipping');
        
        $this->validate();
        
        $this->save_to_session();
        
        $this->data['text_checkout_shipping_method'] = $this->language->get('text_checkout_shipping_method');
        $this->data['text_shipping_address'] = $this->language->get('text_shipping_address');
        $this->data['error_no_shipping'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
                        
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/simplecheckout_shipping.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/checkout/simplecheckout_shipping.tpl';
        } else {
            $this->template = 'default/template/checkout/simplecheckout_shipping.tpl';
        }

        $this->response->setOutput($this->render());        
    }
    
    private function save_to_session() {
        $this->session->data['shipping_methods'] = $this->simple->shipping_methods;
        $this->session->data['shipping_method'] = $this->simple->shipping_method;
        
        if (empty($this->session->data['shipping_methods'])) {
            unset($this->session->data['shipping_method']);
        }
    }
    
    private function validate() {
        $error = false;
        
        if (empty($this->data['shipping_method'])) {
            $this->data['error_warning'] = $this->language->get('error_shipping');
            $error = true;
        } 
        
        if ($error) {
            $this->simple->add_error('shipping');
        }
        
    	return !$error;
    }
}
?>