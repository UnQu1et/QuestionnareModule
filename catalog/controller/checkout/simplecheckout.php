<?php
/*
@author	Dmitriy Kubarev
@link	http://www.simpleopencart.com
@link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/  

require_once(DIR_SYSTEM . 'library/simple/simple.php');

class ControllerCheckoutSimpleCheckout extends Controller { 
    public function index() {

        if (!$this->customer->isLogged() && $this->config->get('simple_disable_guest_checkout')) {
            $this->session->data['redirect'] = $this->url->link('checkout/simplecheckout', '', 'SSL');
            $this->redirect($this->url->link('account/login','','SSL'));
        }
        
        $this->language->load('checkout/simplecheckout');
        
        $this->document->setTitle($this->language->get('heading_title')); 

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home'),
            'separator' => false
        ); 
        
        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('checkout/simplecheckout', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );
        
        $this->data['action'] = $this->url->link('checkout/simplecheckout', '', 'SSL');

        $this->data['heading_title'] = $this->language->get('heading_title');
        
        $this->simple = new Simple($this->registry);

        $this->clear_prevent_delete();

        $this->data['error_warning'] = '';
            
        if ($this->cart->hasProducts() || !empty($this->session->data['vouchers'])) {
            $this->data['text_proceed_payment']    = $this->language->get('text_proceed_payment');
            $this->data['text_payment_form_title'] = $this->language->get('text_payment_form_title');
            $this->data['text_need_save_changes']  = $this->language->get('text_need_save_changes');
            $this->data['text_saving_changes']     = $this->language->get('text_saving_changes');
            $this->data['button_save_changes']     = $this->language->get('button_save_changes');
            $this->data['button_order']            = $this->language->get('button_order');
            $this->data['button_back']             = $this->language->get('button_back');
            $this->data['button_prev']             = $this->language->get('button_prev');
            $this->data['button_next']             = $this->language->get('button_next');
            $this->data['text_cart']               = $this->language->get('text_cart');
            $this->data['text_please_confirm']     = $this->language->get('text_please_confirm');

            $this->data['simple_common_view_agreement_checkbox']      = false;
            $this->data['simple_common_view_agreement_text']          = false;
            $this->data['simple_common_view_agreement_checkbox_init'] = 0;
            $this->data['simple_common_view_help_text']               = false;
            
            $this->data['agree_warning'] = '';
            
            if ($this->config->get('simple_common_view_agreement_id')) {
                $this->load->model('catalog/information');
                
                $this->data['information_info'] = $this->model_catalog_information->getInformation($this->config->get('simple_common_view_agreement_id'));
                
                if ($this->data['information_info']) {
                    $this->data['simple_common_view_agreement_checkbox'] = $this->config->get('simple_common_view_agreement_checkbox');
                    $this->data['simple_common_view_agreement_text'] = $this->config->get('simple_common_view_agreement_text');
                    $this->data['simple_common_view_agreement_checkbox_init'] = $this->config->get('simple_common_view_agreement_checkbox_init');
            
                    $this->data['information_title'] = $this->data['information_info']['title'];
                    $this->data['information_text'] = html_entity_decode($this->data['information_info']['description'], ENT_QUOTES, 'UTF-8');
                    
                    $current_theme = $this->config->get('config_template');
                    
                    $id = ($current_theme == 'shoppica' || $current_theme == 'shoppica2') ? 'text_agree_shoppica' : 'text_agree';
                    $this->data['text_agree'] = sprintf($this->language->get($id), $this->url->link('information/information/info', 'information_id=' . $this->config->get('simple_common_view_agreement_id'), 'SSL'), $this->data['information_info']['title'], $this->data['information_info']['title']);
                    $this->data['agree_warning'] = sprintf($this->language->get('error_agree'), $this->data['information_info']['title']);
                }
            }
            
            if ($this->config->get('simple_common_view_help_id')) {
                $this->load->model('catalog/information');
                
                $this->data['information_info'] = $this->model_catalog_information->getInformation($this->config->get('simple_common_view_help_id'));
                
                if ($this->data['information_info']) {
                    $this->data['simple_common_view_help_text'] = $this->config->get('simple_common_view_help_text');
                    
                    $this->data['help_title'] = $this->data['information_info']['title'];
                    $this->data['help_text'] = html_entity_decode($this->data['information_info']['description'], ENT_QUOTES, 'UTF-8');
                }
            }
            
            if (isset($this->request->post['agree'])) {
                $this->data['agree'] = $this->request->post['agree'];
            } elseif ($this->request->server['REQUEST_METHOD'] == 'POST') {
                $this->data['agree'] = 0;
            } else {
                $this->data['agree'] = $this->config->get('simple_common_view_agreement_checkbox_init');
            }
            
            $this->data['simple_show_errors'] = !empty($this->request->post['simple_create_order']) || (!empty($this->request->post['simple_step_next']) && !empty($this->request->post['simple_step']) && $this->request->post['simple_step'] == 'simplecheckout_agreement');
            
            if ($this->simple->get_simple_steps()) {
                //$this->config->set('simple_shipping_view_autoselect_first', true);
                //$this->config->set('simple_payment_view_autoselect_first', true);
                $this->config->set('simple_checkout_asap_for_logged', false);
                $this->config->set('simple_checkout_asap_for_not_logged', false);
            }

            $simple_common_template = $this->config->get('simple_common_template');
            $simple_common_template = $simple_common_template != '' ? $simple_common_template : '{help}{left_column}{cart}{customer}{/left_column}{right_column}{shipping}{payment}{agreement}{/right_column}{payment_form}';

            if (strpos($simple_common_template, '{payment_form}') === false) {
                $simple_common_template .= '{payment_form}';
            }
                    
            $this->getChild('checkout/simplecheckout_cart/update');
            $this->getChild('checkout/simplecheckout_customer/update');
            
            $redirect = $this->simple->redirect;
            
            if ($redirect) {
                if (!isset($this->request->server['HTTP_X_REQUESTED_WITH']) || $this->request->server['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
                    $this->redirect($redirect);                
                } else {
                    $this->response->setOutput('<script type="text/javascript">location="'.$redirect.'";</script>');
                    return;    
                }
            }
            
            $this->data['simplecheckout_shipping'] = $this->getChild('checkout/simplecheckout_shipping');
            $this->data['simplecheckout_payment']  = $this->getChild('checkout/simplecheckout_payment');
            $this->data['simplecheckout_cart']     = $this->getChild('checkout/simplecheckout_cart');
            $this->data['simplecheckout_customer'] = $this->getChild('checkout/simplecheckout_customer');

            $this->data['modules'] = array();
            if (strpos($simple_common_template, '[[') !== false) {
                $matches = array();
                preg_match_all('/\[\[[-_a-zA-Z0-9.]+\]\]/si', $simple_common_template, $matches);
                if (!empty($matches[0])) {
                    foreach ($matches[0] as $m) {
                        $name_of_m = trim($m,' ][');
                        if ($name_of_m != 'payment_simple') {
                            $this->data['modules'][$m] = $this->getChild('module/'.$name_of_m, array('limit' => 5, 'width' => 100, 'height' => 100, 'banner_id' => 6));
                        } else {
                            $payment_method = $this->simple->payment_method;
                            if (!empty($payment_method['code']) && file_exists(DIR_APPLICATION . 'controller/module/' . $payment_method['code'] . '.php')) {
                                $this->data['modules'][$m] = $this->getChild('module/'.$payment_method['code']);
                            } elseif (!empty($payment_method['code']) && file_exists(DIR_APPLICATION . 'controller/module/' . $payment_method['code'] . '_simple.php')) {
                                $this->data['modules'][$m] = $this->getChild('module/'.$payment_method['code'].'_simple');
                            } else {
                                $this->data['modules'][$m] = '';
                            }
                        }
                    }
                }
            }
            
            $this->data['block_order'] = $this->simple->block_order;
            
            $this->data['error_warning_agree'] = '';
            $this->data['payment_form'] = '';
            
            $asap = $this->simple->get_checkout_asap();
            
            $this->data['has_shipping'] = $this->cart->hasShipping();
            $shipping_address_same = $this->simple->shipping_address_same;

            // fix for steps

            if ($this->validate() && ($asap || (!$asap && !empty($this->request->post['simple_create_order'])))) {                
                $order_id = $this->order();
            
                $this->simple->save_custom_data(Simple::SET_CHECKOUT_CUSTOMER, Simple::OBJECT_TYPE_ORDER, $order_id, 0, $shipping_address_same);
                if (!$shipping_address_same) {
                    $this->simple->save_custom_data(Simple::SET_CHECKOUT_ADDRESS, Simple::OBJECT_TYPE_ORDER, $order_id);
                }

                $payment_method = $this->simple->payment_method;
                
                $this->request->server['REQUEST_METHOD'] = 'GET';
                
                $this->data['payment_form'] = $this->getChild('payment/' . $payment_method['code']);
            }
            
            $this->data['simple_step'] = '';
            $this->data['simple_steps'] = $this->simple->get_simple_steps();

            if ($this->data['simple_steps']) {
                $find = array(
                    '{left_column}',
                    '{/left_column}',
                    '{right_column}',
                    '{/right_column}',
                    '{payment_form}'
                );  

                $replace = array(
                    '{left_column}'   => '',
                    '{/left_column}'  => '',
                    '{right_column}'  => '',
                    '{/right_column}' => '',
                    '{payment_form}'  => ''
                );

                $simple_common_template = trim(str_replace($find, $replace, $simple_common_template)).'{payment_form}';

                if (isset($this->request->post['simple_step'])) {
                    $this->data['simple_step'] = $this->request->post['simple_step'];
                } else {
                    $this->data['simple_step'] = '';
                }
            }

            $this->data['simple_common_template'] = $simple_common_template;
            $this->data['language_code'] = $this->config->get('config_language');
            
            $errors = $this->simple->get_errors();
            
            $this->data['simple_shipping_methods_hide']   = empty($errors['shipping']) ? $this->config->get('simple_shipping_methods_hide') : false;
            $this->data['simple_payment_methods_hide']    = empty($errors['payment']) ? $this->config->get('simple_payment_methods_hide') : false;
            $this->data['simple_customer_hide_if_logged'] = $this->customer->isLogged() && empty($errors['customer']) ? $this->config->get('simple_customer_hide_if_logged') : false;
            
            $this->data['simple_errors'] = implode(',', $errors);

            $this->data['simple_show_weight'] = $this->config->get('simple_show_weight');
            $this->data['weight'] = $this->weight->format($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
			
            $this->data['simple_show_back'] = $this->config->get('simple_show_back');
            
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/simplecheckout.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/checkout/simplecheckout.tpl';
                $this->data['template'] = $this->config->get('config_template');
            } else {
                $this->template = 'default/template/checkout/simplecheckout.tpl';
                $this->data['template'] = 'default';
            }

            $this->simple->add_static($this->data['template'], 'simplecheckout');
            
            $this->data['ajax'] = true;

            $this->data['simple'] = $this->simple;
            
            if (!isset($this->request->server['HTTP_X_REQUESTED_WITH']) || $this->request->server['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
                $this->data['ajax'] = false;
                
                $this->children = array(
                    'common/column_left',
                    'common/column_right',
                    'common/content_top',
                    'common/content_bottom',
                    'common/footer',
                    'common/header',
                );
            }
                  
            $this->response->setOutput($this->render());
        } else {
            $this->data['heading_title'] = $this->language->get('heading_title');
            
            $this->data['text_error'] = $this->language->get('text_empty');
            
            $this->data['button_continue'] = $this->language->get('button_continue');
            
            $this->data['continue'] = $this->url->link('common/home');
            
            $this->data['simple'] = $this->simple;
            
            if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && $this->request->server['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
                $this->response->setOutput('<script type="text/javascript">location="'.$this->url->link('checkout/simplecheckout', '', 'SSL').'";</script>');
                return;    
            }

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/simplecheckout_cart_empty.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/checkout/simplecheckout_cart_empty.tpl';
                $this->data['template'] = $this->config->get('config_template');
            } else {
                $this->template = 'default/template/checkout/simplecheckout_cart_empty.tpl';
                $this->data['template'] = 'default';
            }
            
            $this->children = array(
                'common/column_left',
                'common/column_right',
                'common/content_top',
                'common/content_bottom',
                'common/footer',
                'common/header'    
            );
                    
            $this->response->setOutput($this->render());            
        }
    }
    
    private function validate() {
        $error = false;
        
        if (!empty($this->data['simple_common_view_agreement_checkbox']) && empty($this->data['agree'])) {
            $this->data['error_warning_agree'] = sprintf($this->language->get('error_agree'), $this->data['information_title']);
            $this->simple->add_error('agreement');
        }
        
        $errors = $this->simple->get_errors();
        
        if (!empty($errors)) {
            $error = true;
        }
        
        return !$error;
    }
    
    private function clear_prevent_delete() {
        if ($this->request->server['REQUEST_METHOD'] == 'GET' && !isset($this->session->data['order_id']) && isset($this->session->data['prevent_delete'])) {
            unset($this->session->data['prevent_delete']);
        }
    }

    public function prevent_delete() {
        $order_id = isset($this->session->data['order_id']) ? $this->session->data['order_id'] : 0;
        $this->session->data['prevent_delete'][$order_id] = true;
    }
    
    private function order() {
        if (isset($this->session->data['order_id']) && !isset($this->session->data['prevent_delete'][$this->session->data['order_id']]) && !isset($this->session->data['prevent_delete'][0])) {
            $this->simple->delete_order($this->session->data['order_id']);
            unset($this->session->data['order_id']);
        }
        
        $customer = $this->simple->customer_info;
        
        if (empty($customer['email'])) {
            $email = $this->config->get('simple_empty_email');
            $email = !empty($email) ? $email : 'empty@localhost';
            $customer['email'] = $email;
        }
        
        $this->simple->customer_info = $customer;
        
        $version = $this->simple->opencart_version;
        
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
    
        $data = array();
        
        $data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
        $data['store_id'] = $this->config->get('config_store_id');
        $data['store_name'] = $this->config->get('config_name');
        
        if ($data['store_id']) {
            $data['store_url'] = $this->config->get('config_url');        
        } else {
            $data['store_url'] = HTTP_SERVER;    
        }
        
        $data['customer_id'] = $this->simple->customer_info['customer_id'];
        $data['customer_group_id'] = $this->simple->customer_info['customer_group_id'];
        $data['firstname'] = $this->simple->customer_info['firstname'];
        $data['lastname'] = $this->simple->customer_info['lastname'];
        $data['email'] = $this->simple->customer_info['email'];
        $data['telephone'] = $this->simple->customer_info['telephone'];
        $data['fax'] = $this->simple->customer_info['fax'];
    
        $data['payment_firstname'] = $this->simple->payment_address['firstname'];
        $data['payment_lastname'] = $this->simple->payment_address['lastname'];    
        $data['payment_company'] = $this->simple->payment_address['company'];    
        $data['payment_address_1'] = $this->simple->payment_address['address_1'];
        $data['payment_address_2'] = $this->simple->payment_address['address_2'];
        $data['payment_city'] = $this->simple->payment_address['city'];
        $data['payment_postcode'] = $this->simple->payment_address['postcode'];
        $data['payment_zone'] = $this->simple->payment_address['zone'];
        $data['payment_zone_id'] = $this->simple->payment_address['zone_id'];
        $data['payment_country'] = $this->simple->payment_address['country'];
        $data['payment_country_id'] = $this->simple->payment_address['country_id'];
        $data['payment_address_format'] = $this->simple->payment_address['address_format'];
        $data['payment_company_id'] = $this->simple->payment_address['company_id'];    
        $data['payment_tax_id'] = $this->simple->payment_address['tax_id'];    
            
        $payment_method = $this->simple->payment_method;
        
        if (isset($payment_method['title'])) {
            $data['payment_method'] = $payment_method['title'];
        } else {
            $data['payment_method'] = '';
        }
        
        if (isset($payment_method['code'])) {
            $data['payment_code'] = $payment_method['code'];
        } else {
            $data['payment_code'] = '';
        }
        
        if ($this->cart->hasShipping()) {
            $data['shipping_firstname'] = $this->simple->shipping_address['firstname'];
            $data['shipping_lastname'] = $this->simple->shipping_address['lastname'];    
            $data['shipping_company'] = $this->simple->shipping_address['company'];    
            $data['shipping_address_1'] = $this->simple->shipping_address['address_1'];
            $data['shipping_address_2'] = $this->simple->shipping_address['address_2'];
            $data['shipping_city'] = $this->simple->shipping_address['city'];
            $data['shipping_postcode'] = $this->simple->shipping_address['postcode'];
            $data['shipping_zone'] = $this->simple->shipping_address['zone'];
            $data['shipping_zone_id'] = $this->simple->shipping_address['zone_id'];
            $data['shipping_country'] = $this->simple->shipping_address['country'];
            $data['shipping_country_id'] = $this->simple->shipping_address['country_id'];
            $data['shipping_address_format'] = $this->simple->shipping_address['address_format'];
            
            $shipping_method = $this->simple->shipping_method;
            
            if (isset($shipping_method['title'])) {
                $data['shipping_method'] = $shipping_method['title'];
            } else {
                $data['shipping_method'] = '';
            }
            
            if (isset($shipping_method['code'])) {
                $data['shipping_code'] = $shipping_method['code'];
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
        
        if ($version < 152) {
        
            if (method_exists($this->tax,'setZone')) {
                if ($this->cart->hasShipping()) {
    				$this->tax->setZone($data['shipping_country_id'], $data['shipping_zone_id']);
    			} else {
    				$this->tax->setZone($data['payment_country_id'], $data['payment_zone_id']);
    			}
            }
            
            $this->load->library('encryption');
        
            foreach ($this->cart->getProducts() as $product) {
                $option_data = array();
        
                foreach ($product['option'] as $option) {
                    if ($option['type'] != 'file') {    
                        $option_data[] = array(
                            'product_option_id'       => $option['product_option_id'],
                            'product_option_value_id' => $option['product_option_value_id'],
                            'product_option_id'       => $option['product_option_id'],
                            'product_option_value_id' => $option['product_option_value_id'],
                            'option_id'               => $option['option_id'],
                            'option_value_id'         => $option['option_value_id'],                                   
                            'name'                    => $option['name'],
                            'value'                   => $option['option_value'],
                            'type'                    => $option['type']
                        );                    
                    } else {
                        $encryption = new Encryption($this->config->get('config_encryption'));
                        
                        $option_data[] = array(
                            'product_option_id'       => $option['product_option_id'],
                            'product_option_value_id' => $option['product_option_value_id'],
                            'product_option_id'       => $option['product_option_id'],
                            'product_option_value_id' => $option['product_option_value_id'],
                            'option_id'               => $option['option_id'],
                            'option_value_id'         => $option['option_value_id'],                                   
                            'name'                    => $option['name'],
                            'value'                   => $encryption->decrypt($option['option_value']),
                            'type'                    => $option['type']
                        );                                
                    }
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
                    'tax'        => method_exists($this->tax,'getRate') ? $this->tax->getRate($product['tax_class_id']) : $this->tax->getTax($product['price'], $product['tax_class_id'])
                ); 
            }
            
            // Gift Voucher
            if (isset($this->session->data['vouchers']) && $this->session->data['vouchers']) {
                foreach ($this->session->data['vouchers'] as $voucher) {
                    $product_data[] = array(
                        'product_id' => 0,
                        'name'       => $voucher['description'],
                        'model'      => '',
                        'option'     => array(),
                        'download'   => array(),
                        'quantity'   => 1,
                        'subtract'   => false,
                        'price'      => $voucher['amount'],
                        'total'      => $voucher['amount'],
                        'tax'        => 0
                    );
                }
            } 
                        
            $data['products'] = $product_data;
            $data['totals'] = $total_data;
            $data['comment'] = $this->simple->comment;
            $data['total'] = $total;
            $data['reward'] = $this->cart->getTotalRewardPoints();
        } elseif ($version >= 152) {
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
            
            // Gift Voucher
            $voucher_data = array();
            
            if (!empty($this->session->data['vouchers'])) {
                foreach ($this->session->data['vouchers'] as $voucher) {
                    $voucher_data[] = array(
                        'description'      => $voucher['description'],
                        'code'             => substr(md5(rand()), 0, 10),
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
            $data['comment'] = $this->simple->comment;
            $data['total'] = $total; 
        }
        
        if (isset($this->request->cookie['tracking'])) {
            $this->load->model('affiliate/affiliate');
            
            $affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);
            $subtotal = $this->cart->getSubTotal();

            if ($affiliate_info) {
                $data['affiliate_id'] = $affiliate_info['affiliate_id']; 
                $data['commission'] = ($subtotal / 100) * $affiliate_info['commission']; 
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

        $shipping_address_same = $this->simple->shipping_address_same;
        
        $custom_data_order_1 = $this->simple->get_custom_data_for_object(Simple::SET_CHECKOUT_CUSTOMER, Simple::OBJECT_TYPE_ORDER);
        $custom_data_order_2 = array();
        $custom_data_customer = $this->simple->get_custom_data_for_object(Simple::SET_CHECKOUT_CUSTOMER, Simple::OBJECT_TYPE_CUSTOMER);
        $custom_data_payment_address = $this->simple->get_custom_data_for_object(Simple::SET_CHECKOUT_CUSTOMER, Simple::OBJECT_TYPE_ADDRESS);
        
        $tmp = array();
        foreach ($custom_data_payment_address as $key => $value) {
            $tmp['payment_'.$key] = $value;
        }
        $custom_data_payment_address = $tmp;
        $tmp = null;

        $custom_data_shipping_address = array();
        
        if (!$shipping_address_same) {
            $custom_data_order_2 = $this->simple->get_custom_data_for_object(Simple::SET_CHECKOUT_ADDRESS, Simple::OBJECT_TYPE_ORDER);
            $custom_data_shipping_address = $this->simple->get_custom_data_for_object(Simple::SET_CHECKOUT_ADDRESS, Simple::OBJECT_TYPE_ADDRESS);

            $tmp = array();
            foreach ($custom_data_shipping_address as $key => $value) {
                $tmp['shipping_'.$key] = $value;
            }
            $custom_data_shipping_address = $tmp;
            $tmp = null;
        }
        
        $data = array_merge($data, $custom_data_order_1, $custom_data_order_2, $custom_data_customer, $custom_data_payment_address, $custom_data_shipping_address);
        
        $data['simple'] = array();
        $data['simple']['order'] = $this->simple->get_custom_data(Simple::SET_CHECKOUT_CUSTOMER, Simple::OBJECT_TYPE_ORDER);
        $data['simple']['customer'] = $this->simple->get_custom_data(Simple::SET_CHECKOUT_CUSTOMER, Simple::OBJECT_TYPE_CUSTOMER);
        $data['simple']['payment_address']  = $this->simple->get_custom_data(Simple::SET_CHECKOUT_CUSTOMER, Simple::OBJECT_TYPE_ADDRESS);
                
        if (!$shipping_address_same) {
            $data['simple']['order']  = $this->simple->get_custom_data(Simple::SET_CHECKOUT_ADDRESS, Simple::OBJECT_TYPE_ADDRESS);
            $data['simple']['shipping_address']  = $this->simple->get_custom_data(Simple::SET_CHECKOUT_ADDRESS, Simple::OBJECT_TYPE_ADDRESS);
        }
        
        $this->load->model('checkout/order');
        
        $order_id = 0;
        
        if ($version < 152) {
            $order_id = $this->model_checkout_order->create($data);
            
            // Gift Voucher
            if (isset($this->session->data['vouchers']) && is_array($this->session->data['vouchers'])) {
                $this->load->model('checkout/voucher');
        
                foreach ($this->session->data['vouchers'] as $voucher) {
                    $this->model_checkout_voucher->addVoucher($order_id, $voucher);
                }
            }
        } elseif ($version >= 152) {
            $order_id = $this->model_checkout_order->addOrder($data);
        }
        
        $this->session->data['order_id'] = $order_id;
        
        return $order_id;
    }
}


?>