<?php 
/*
@author	Dmitriy Kubarev
@link	http://www.simpleopencart.com
@link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/  

class ControllerCheckoutSimpleCheckoutCustomer extends Controller {
    static $static_data = array();
    private $error = array();
    private $language_code = '';
    private $cookies = array();
    
    public function index() {
    
        $this->load->model('account/address');
        $this->load->model('account/customer');

        $this->language->load('checkout/simplecheckout');
        
        $this->data['text_checkout_customer']                = $this->language->get('text_checkout_customer');
        $this->data['text_checkout_shipping_address']        = $this->language->get('text_checkout_shipping_address');
        $this->data['text_checkout_customer_login']          = $this->language->get('text_checkout_customer_login');
        $this->data['text_checkout_customer_cancel']         = $this->language->get('text_checkout_customer_cancel');
        $this->data['text_private']                          = $this->language->get('text_private');
        $this->data['text_company']                          = $this->language->get('text_company');
        $this->data['text_select']                           = $this->language->get('text_select');
        $this->data['text_add_new']                          = $this->language->get('text_add_new');
        $this->data['text_your_company']                     = $this->language->get('text_your_company');
        $this->data['text_select_address']                   = $this->language->get('text_select_address');
        $this->data['entry_register']                        = $this->language->get('entry_register');
        $this->data['entry_newsletter']                      = $this->language->get('entry_newsletter');
        $this->data['entry_password']                        = $this->language->get('entry_password');
        $this->data['entry_password_confirm']                = $this->language->get('entry_password_confirm');
        $this->data['entry_address_same']                    = $this->language->get('entry_address_same');
        $this->data['entry_customer_type']                   = $this->language->get('entry_customer_type');
        $this->data['text_yes']                              = $this->language->get('text_yes');
        $this->data['text_no']                               = $this->language->get('text_no');
        $this->data['button_update']                         = $this->language->get('button_update');
        $this->data['entry_email_confirm']                   = $this->language->get('entry_email_confirm');
        $this->data['error_email_confirm']                   = $this->language->get('error_email_confirm');
        
        $this->data['simple_customer_generate_password']     = $this->config->get('simple_customer_generate_password');
        $this->data['simple_customer_view_customer_type']    = $this->config->get('simple_customer_view_customer_type');
        $this->data['simple_customer_view_email']            = $this->config->get('simple_customer_view_email');
        $this->data['simple_customer_action_register']       = $this->config->get('simple_customer_action_register');
        $this->data['simple_customer_view_password_confirm'] = $this->config->get('simple_customer_view_password_confirm');
        $this->data['simple_customer_view_address_select']   = $this->config->get('simple_customer_view_address_select');
        $this->data['simple_shipping_view_address_select']   = $this->config->get('simple_shipping_view_address_select');
        $this->data['simple_customer_view_email_confirm']    = $this->config->get('simple_customer_view_email_confirm');

        $this->data['simple_type_of_selection_of_group'] = $this->config->get('simple_type_of_selection_of_group');
        $this->data['simple_type_of_selection_of_group'] = !empty($this->data['simple_type_of_selection_of_group']) ? $this->data['simple_type_of_selection_of_group'] : 'select';

        $this->data['login_link'] = $this->url->link('checkout/simplecheckout_customer/login', '', 'SSL');
        $this->data['default_login_link'] = $this->url->link('account/login', '', 'SSL');
        
        $this->data['simple_show_errors'] = !empty($this->request->post['simple_create_order']) || (!empty($this->request->post['simple_step_next']) && !empty($this->request->post['simple_step']) && $this->request->post['simple_step'] == 'simplecheckout_customer');

        $this->load_cookies();
        $this->load_adresses();
		$this->load_main_address();
        $this->load_fields();
        $this->save_cookies();
        $this->get_comment_value();

        if (!$this->validate()) {
            $this->simple->add_error('customer');
        }

        $asap = $this->simple->get_checkout_asap();

        if (!$this->error && ($asap || (!$asap && !empty($this->request->post['simple_create_order'])))) {
            $this->save_customer_data();
            $this->load_adresses();
            $this->load_main_address();
        }

        $this->data['customer_logged'] = $this->customer->isLogged();
        
        $this->data['text_you_will_be_registered'] = '';
        if (!$this->customer->isLogged() && $this->config->get('simple_show_will_be_registered') && $this->data['simple_customer_action_register'] == Simple::REGISTER_YES) {
            $this->data['text_you_will_be_registered'] = $this->language->get('text_you_will_be_registered');
        }

        $this->data['simple_customer_view_login'] = !$this->customer->isLogged() ? $this->config->get('simple_customer_view_login') : false;
        
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('checkout/simplecheckout', '', 'SSL');
        }
        
        $payment_address = $this->simple->payment_address;
        if (!empty($payment_address)) {
            $this->data['customer_address_id'] = $payment_address['address_id'];
        } else {
            $this->data['customer_address_id'] = 0;
        }
        
        $shipping_address = $this->simple->shipping_address;
        if (!empty($shipping_address)) {
            $this->data['shipping_address_id'] = $shipping_address['address_id'];
        } else {
            $this->data['shipping_address_id'] = 0;
        }
        
        $this->data['simple_show_shipping_address'] = $this->simple->show_shipping_address;
        $this->data['shipping_address_same'] = $this->simple->shipping_address_same;
        
        $this->data['simple_show_shipping_address_same_show'] = $this->simple->simple_show_shipping_address_same_show;

        $this->data['register']         = $this->simple->register;
        $this->data['subscribe']        = $this->simple->subscribe;
        $this->data['customer_type']    = $this->simple->customer_type;
        $this->data['password']         = $this->simple->password;
        $this->data['password_confirm'] = $this->simple->password_confirm;

        $this->data['simple_customer_action_subscribe'] = $this->simple->register ? $this->config->get('simple_customer_action_subscribe') : 0;
        
        $this->data['simple_customer_two_column'] = $this->simple->get_simple_steps() ? true : $this->config->get('simple_customer_two_column'); 
        
        $this->data['simple_customer_registered'] = '';
        if (isset($this->session->data['simple_customer_registered'])) {
            $this->data['simple_customer_registered'] = $this->session->data['simple_customer_registered'];
            unset($this->session->data['simple_customer_registered']);
        }
        
        $this->data['simple_debug'] = $this->config->get('simple_debug');
        $this->data['customer'] = $this->simple->customer_info;
        $this->data['comment'] = $this->simple->comment;

        $this->data['customer_groups'] = array();
        if ($this->config->get('simple_customer_view_customer_type')) {
            $this->data['customer_groups'] = $this->simple->get_customer_groups();
        }

        $this->data['email_confirm'] = isset($this->request->post['email_confirm']) ? trim($this->request->post['email_confirm']) : '';
        
        $this->data['customer_group_id'] = $this->simple->customer_group_id;

        $this->data['simple'] = $this->simple;
        $this->data['is_mobile'] = $this->simple->is_mobile();

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/simplecheckout_customer.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/checkout/simplecheckout_customer.tpl';
        } else {
            $this->template = 'default/template/checkout/simplecheckout_customer.tpl';
        }
          
        $this->response->setOutput($this->render());         
    }
    
    private function load_main_address() {
        if ($this->customer->isLogged()) {
            if (!isset(self::$static_data['address'])) {
                self::$static_data['address'] = array();
                $main_address_id = $this->customer->getAddressId();
                if (!empty(self::$static_data['addresses'][$main_address_id])) {
                    self::$static_data['address'] = self::$static_data['addresses'][$main_address_id];
                } else {
                    self::$static_data['address'] = $this->model_account_address->getAddress($main_address_id);
                }
            }
            $this->data['address'] = self::$static_data['address'];
        }
    }
    
    private function load_adresses() {
        if ($this->customer->isLogged()) {
            if (!isset(self::$static_data['addresses'])) {
                self::$static_data['addresses'] = array();
                $addresses = $this->model_account_address->getAddresses();
                foreach ($addresses as $address) {
                    self::$static_data['addresses'][$address['address_id']] = $address;
                }
            }
            $this->data['addresses'] = self::$static_data['addresses'];
        }
    }
    
    private function set_password_value() {
        $eng = "qwertyuiopasdfghjklzxcvbnm1234567890";
        $password = '';
        $password_confirm = '';
        
        $min_length = $this->config->get('simple_customer_view_password_length_min');
        $generated_password = '';
        while ($min_length) {
            $generated_password .= $eng[rand(0,35)]; 
            $min_length--;
        }
        
        if (!$this->customer->isLogged()) {
            $password = $this->config->get('simple_customer_generate_password') ? $generated_password : (isset($this->request->post['password']) ? trim($this->request->post['password']) : '');
            $password_confirm = $this->config->get('simple_customer_generate_password') ? $password : (isset($this->request->post['password_confirm']) ? trim($this->request->post['password_confirm']) : '');
            
            if (!$this->config->get('simple_customer_view_password_confirm')) {
                $password_confirm = $password;
            }
        }
        
        $this->simple->password = $password;
        $this->simple->password_confirm = $password_confirm;
    }

    private function get_comment_value() {
        $comment = $this->simple->get_total_value(Simple::SET_CHECKOUT_CUSTOMER,'comment');
        
        $simple_show_shipping_address = $this->cart->hasShipping() ? $this->config->get('simple_show_shipping_address') : 0;

        if ($simple_show_shipping_address && $this->request->server['REQUEST_METHOD'] == 'POST' && empty($this->request->post['shipping_address_same'])) {
            $comment .= ' '.$this->simple->get_total_value(Simple::SET_CHECKOUT_ADDRESS,'comment');
        }

        $this->simple->comment = $comment;
        $this->session->data['comment'] = $comment;
    }
    
    private function set_customer_group_id_value() {
        if ($this->customer->isLogged()) {
            $customer_group_id = $this->customer->getCustomerGroupId();
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }
        
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->config->get('simple_customer_view_customer_type')) {
            $customer_groups = $this->simple->get_customer_groups();
            
            if (isset($this->request->post['customer_group_id']) && array_key_exists($this->request->post['customer_group_id'], $customer_groups)) {
                $customer_group_id = $this->request->post['customer_group_id'];
            }
        }
        
        $this->simple->customer_group_id = $customer_group_id;
    }
    
    private function set_register_value() {
        $register_customer = false;
        
        if (!$this->customer->isLogged()) {
            if ($this->request->server['REQUEST_METHOD'] == 'POST') {
                if ($this->config->get('simple_customer_action_register') == Simple::REGISTER_USER_CHOICE) {
                    if (isset($this->request->post['register'])) {
                        $register_customer = $this->request->post['register'];
                        $this->session->data['simple']['register'] = $register_customer;
                    } else {
                        $register_customer = false;
                    }
                } else {
                     $register_customer = $this->config->get('simple_customer_action_register') == Simple::REGISTER_YES;
                }
            } elseif ($this->request->server['REQUEST_METHOD'] == 'GET') {
                if ($this->config->get('simple_customer_action_register') == Simple::REGISTER_USER_CHOICE) {
                    if (isset($this->session->data['simple']['register'])) {
                        $register_customer = $this->session->data['simple']['register'];
                    } else {
                        $register_customer = $this->config->get('simple_customer_view_customer_register_init');
                    }
                } else {
                    $register_customer = $this->config->get('simple_customer_action_register') == Simple::REGISTER_YES;
                }
            } 
        }
        
        $this->simple->register = $register_customer;
    }
    
    private function set_subscribe_value() {
        $subscribe_customer = false;
        
        if (!$this->customer->isLogged()) {
            if ($this->config->get('simple_customer_action_subscribe') == Simple::SUBSCRIBE_USER_CHOICE) {
                $subscribe_customer = $this->config->get('simple_customer_view_customer_subscribe_init');

                if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['subscribe'])) {
                    $subscribe_customer = $this->request->post['subscribe'];
                    $this->session->data['simple']['subscribe'] = $subscribe_customer;
                } elseif ($this->request->server['REQUEST_METHOD'] == 'GET' && isset($this->session->data['simple']['subscribe'])) {
                    $subscribe_customer = $this->session->data['simple']['subscribe'];
                }
            } else {
                 $subscribe_customer = $this->config->get('simple_customer_action_subscribe') == Simple::SUBSCRIBE_YES;
            }
        }
        
        $this->simple->subscribe = $subscribe_customer;
    }

    private function load_cookies() {
        if ($this->config->get('simple_use_cookies') && isset($this->request->cookie['simple'])) {
            $this->cookies = unserialize(base64_decode($this->request->cookie['simple']));
        }
    }
    
    private function save_cookies() {
        if ($this->config->get('simple_use_cookies') && $this->request->server['REQUEST_METHOD'] == 'POST') {
            if (isset($this->request->post[Simple::SET_CHECKOUT_CUSTOMER])) {
                $this->cookies[Simple::SET_CHECKOUT_CUSTOMER] = $this->request->post[Simple::SET_CHECKOUT_CUSTOMER];
            }
            if (isset($this->request->post[Simple::SET_CHECKOUT_ADDRESS])) {
                $this->cookies[Simple::SET_CHECKOUT_ADDRESS] = $this->request->post[Simple::SET_CHECKOUT_ADDRESS];
            }
            setcookie('simple', base64_encode(serialize($this->cookies)), time() + 60 * 60 * 24 * 30);
        }
    }

    private function load_fields() {
        $this->set_register_value();
        $this->set_subscribe_value();
        $this->set_password_value();
        $this->set_customer_group_id_value();

        $shipping_method = $this->simple->shipping_method;
        $payment_method = $this->simple->payment_method;
        
        $shipping_method_code = false;
        if (!empty($shipping_method['code'])) {
            $shipping_method_code = $shipping_method['code'];
        }

        $payment_method_code = false;        
        if (!empty($payment_method['code'])) {
            $payment_method_code = $payment_method['code'];
        }

        $simple_show_shipping_address = $this->cart->hasShipping() ? $this->config->get('simple_show_shipping_address') : 0;

        $ignore_post_customer = false;
        $ignore_post_address = false;

        $main_checkout_customer = array();
        $main_checkout_address = array();

        $custom_checkout_customer = array();
        $custom_checkout_address = array();

        if ($this->customer->isLogged()) {
            $custom_checkout_customer = $this->simple->load_custom_data(Simple::OBJECT_TYPE_CUSTOMER, $this->customer->getId());
            
            $customer_address_id = $this->get_customer_address_id();
             
            if ($customer_address_id) {
                $custom_checkout_customer = array_merge($custom_checkout_customer, $this->simple->load_custom_data(Simple::OBJECT_TYPE_ADDRESS, $customer_address_id));
            }

            $shipping_address_id = $this->get_shipping_address_id();
            
            if ($shipping_address_id) {
                $custom_checkout_address = array_merge($custom_checkout_customer, $this->simple->load_custom_data(Simple::OBJECT_TYPE_ADDRESS, $shipping_address_id));
            }

            $this->load->model('account/customer');

            $main_checkout_customer = $this->model_account_customer->getCustomer($this->customer->getId());
            
            if ($customer_address_id && isset($this->data['addresses'][$customer_address_id]) && is_array($this->data['addresses'][$customer_address_id])) {
                $main_checkout_customer = array_merge($main_checkout_customer, $this->data['addresses'][$customer_address_id]);
            }

            if ($shipping_address_id && isset($this->data['addresses'][$shipping_address_id]) && is_array($this->data['addresses'][$shipping_address_id])) {
                $main_checkout_address = array_merge($main_checkout_customer, $this->data['addresses'][$shipping_address_id]);
            }

            if ($this->request->server['REQUEST_METHOD'] == 'POST') {
                if ($this->get_customer_address_id_changed()) {
                    $ignore_post_customer = true;
                }
                
                if ($this->get_shipping_address_id_changed()) {
                    $ignore_post_address = true;
                }
            }
        } else {
            if ($this->request->server['REQUEST_METHOD'] == 'GET') {
                if (isset($this->cookies[Simple::SET_CHECKOUT_CUSTOMER]) && is_array($this->cookies[Simple::SET_CHECKOUT_CUSTOMER])) {
                    $main_checkout_customer = $this->cookies[Simple::SET_CHECKOUT_CUSTOMER];
                }
                if (isset($this->cookies[Simple::SET_CHECKOUT_ADDRESS]) && is_array($this->cookies[Simple::SET_CHECKOUT_ADDRESS])) {
                    $main_checkout_address = $this->cookies[Simple::SET_CHECKOUT_ADDRESS];
                }
                if (isset($this->session->data['simple'][Simple::SET_CHECKOUT_CUSTOMER]) && is_array($this->session->data['simple'][Simple::SET_CHECKOUT_CUSTOMER])) {
                    $main_checkout_customer = array_merge($main_checkout_customer, $this->session->data['simple'][Simple::SET_CHECKOUT_CUSTOMER]);
                }
                if (isset($this->session->data['simple'][Simple::SET_CHECKOUT_ADDRESS]) && is_array($this->session->data['simple'][Simple::SET_CHECKOUT_ADDRESS])) {
                    $main_checkout_address = array_merge($main_checkout_address, $this->session->data['simple'][Simple::SET_CHECKOUT_ADDRESS]);
                }
                if (isset($this->session->data['guest']['payment']) && is_array($this->session->data['guest']['payment'])) {
                    $main_checkout_customer = array_merge($main_checkout_customer, $this->session->data['guest']['payment']);
                }
                if (isset($this->session->data['guest']['shipping']) && is_array($this->session->data['guest']['shipping'])) {
                    $main_checkout_address = array_merge($main_checkout_address, $this->session->data['guest']['shipping']);
                }
                if (isset($this->session->data['shipping_country_id'])) {
                    $main_checkout_customer['country_id'] = $this->session->data['shipping_country_id'];
                    $main_checkout_address['country_id'] = $this->session->data['shipping_country_id'];
                }
                if (isset($this->session->data['shipping_zone_id'])) {
                    $main_checkout_customer['zone_id'] = $this->session->data['shipping_zone_id'];
                    $main_checkout_address['zone_id'] = $this->session->data['shipping_zone_id'];
                }
                if (isset($this->session->data['shipping_postcode'])) {
                    $main_checkout_customer['postcode'] = $this->session->data['shipping_postcode'];
                    $main_checkout_address['postcode'] = $this->session->data['shipping_postcode'];
                }
            }
        }

        $this->data['checkout_customer_fields'] = $this->simple->load_fields(Simple::SET_CHECKOUT_CUSTOMER, array('group' => $this->simple->customer_group_id, 'shipping' => $shipping_method_code, 'payment' => $payment_method_code), $ignore_post_customer, $main_checkout_customer, $custom_checkout_customer);
        $this->simple->payment_address_fields = $this->data['checkout_customer_fields'];
        
        if ($simple_show_shipping_address) {
            $this->data['checkout_address_fields'] = $this->simple->load_fields(Simple::SET_CHECKOUT_ADDRESS, array('group' => $this->simple->customer_group_id, 'shipping' => $shipping_method_code, 'payment' => $payment_method_code), $ignore_post_address, $main_checkout_address, $custom_checkout_address);
            $this->simple->shipping_address_fields = $this->data['checkout_address_fields'];
        } else {
            $this->simple->shipping_address_fields = $this->simple->payment_address_fields;
        }   
    }

    private function get_customer_address_id() {
        $form_address_id = isset($this->request->post[Simple::SET_CHECKOUT_CUSTOMER]['address_id']) ? $this->request->post[Simple::SET_CHECKOUT_CUSTOMER]['address_id'] : (!empty($this->data['address']['address_id']) ? $this->data['address']['address_id'] : -1);
        $selected_address_id = isset($this->request->post['customer_address_id']) ? $this->request->post['customer_address_id'] : -1;
   
        if ($selected_address_id >= 0 && $form_address_id != $selected_address_id) {
            $address_id = $selected_address_id;
        } else {
            $address_id = $form_address_id;
        }

        return $address_id > 0 ? $address_id : 0;
    }

    private function get_customer_address_id_changed() {
        $form_address_id = isset($this->request->post[Simple::SET_CHECKOUT_CUSTOMER]['address_id']) ? $this->request->post[Simple::SET_CHECKOUT_CUSTOMER]['address_id'] : -1;
        $selected_address_id = isset($this->request->post['customer_address_id']) ? $this->request->post['customer_address_id'] : -1;

        if ($form_address_id >= 0 && $selected_address_id >= 0 && $form_address_id != $selected_address_id) {
            return true;
        }

        return false;
    }

    private function get_shipping_address_id() {
        $form_address_id = isset($this->request->post[Simple::SET_CHECKOUT_ADDRESS]['address_id']) ? $this->request->post[Simple::SET_CHECKOUT_ADDRESS]['address_id'] : (!empty($this->data['address']['address_id']) ? $this->data['address']['address_id'] : -1);
        $selected_address_id = isset($this->request->post['shipping_address_id']) ? $this->request->post['shipping_address_id'] : -1;
   
        if ($selected_address_id >= 0 && $form_address_id != $selected_address_id) {
            $address_id = $selected_address_id;
        } else {
            $address_id = $form_address_id;
        }

        return $address_id > 0 ? $address_id : 0;
    }

    private function get_shipping_address_id_changed() {
        $form_address_id = isset($this->request->post[Simple::SET_CHECKOUT_ADDRESS]['address_id']) ? $this->request->post[Simple::SET_CHECKOUT_ADDRESS]['address_id'] : -1;
        $selected_address_id = isset($this->request->post['shipping_address_id']) ? $this->request->post['shipping_address_id'] : -1;
   
        if ($form_address_id >= 0 && $selected_address_id >= 0 && $form_address_id != $selected_address_id) {
            return true;
        }

        return false;
    }
    
    public function update() {
        
        $this->load->model('account/address');
        $this->load->model('account/customer');

        $this->data['simple_show_errors'] = !empty($this->request->post['simple_create_order']) || (!empty($this->request->post['simple_step_next']) && !empty($this->request->post['simple_step']) && $this->request->post['simple_step'] == 'simplecheckout_customer');
        
        if (!empty($this->request->post['shipping_address_same'])) {
            if (isset($this->request->post[Simple::SET_CHECKOUT_CUSTOMER]['address_id'])) {
                $this->request->post[Simple::SET_CHECKOUT_ADDRESS]['address_id'] = $this->request->post[Simple::SET_CHECKOUT_CUSTOMER]['address_id'];
            } 
            
            if (isset($this->request->post['customer_address_id'])) {
                $this->request->post['shipping_address_id'] = $this->request->post['customer_address_id'];
            }   
            
            foreach (Simple::$fields_of_address as $field) {
                $this->request->post[Simple::SET_CHECKOUT_ADDRESS][$field] = $this->request->post[Simple::SET_CHECKOUT_CUSTOMER][$field];
            }
        }
        
        $this->load_cookies();
        $this->load_adresses();
        $this->load_main_address();
        $this->load_fields();
        $this->get_comment_value();

        $address = $this->prepare_address($this->simple->get_value(Simple::SET_CHECKOUT_CUSTOMER,'zone_id'), $this->simple->get_value(Simple::SET_CHECKOUT_CUSTOMER,'country_id'));
        
        $address_id = $this->get_customer_address_id();
        
        $address['address_id'] = $address_id;
        $address['firstname']  = $this->simple->get_total_value(Simple::SET_CHECKOUT_CUSTOMER,'firstname');
        $address['lastname']   = $this->simple->get_total_value(Simple::SET_CHECKOUT_CUSTOMER,'lastname');
        $address['company']    = $this->simple->get_total_value(Simple::SET_CHECKOUT_CUSTOMER,'company');
        $address['company_id'] = $this->simple->get_total_value(Simple::SET_CHECKOUT_CUSTOMER,'company_id');
        $address['tax_id']     = $this->simple->get_total_value(Simple::SET_CHECKOUT_CUSTOMER,'tax_id');
        $address['address_1']  = $this->simple->get_total_value(Simple::SET_CHECKOUT_CUSTOMER,'address_1');
        $address['address_2']  = $this->simple->get_total_value(Simple::SET_CHECKOUT_CUSTOMER,'address_2');
        $address['postcode']   = $this->simple->get_total_value(Simple::SET_CHECKOUT_CUSTOMER,'postcode');
        $address['city']       = $this->simple->get_total_value(Simple::SET_CHECKOUT_CUSTOMER,'city');

        // fix for existing other fields of address
        if ($address_id) {
            $tmp_address = $this->model_account_address->getAddress($address_id);
            $address = array_merge($tmp_address, $address);
        }

        // fix for custom fields
        //$custom_data_address = $this->simple->get_custom_data_for_object(Simple::SET_CHECKOUT_CUSTOMER, Simple::OBJECT_TYPE_ADDRESS);
        //$address = array_merge($address, $custom_data_address);
        
        $simple_show_shipping_address = $this->cart->hasShipping() ? $this->config->get('simple_show_shipping_address') : 0;
        
        $this->simple->payment_address = $address;
        $payment_address_id = $address['address_id'];
        $this->simple->shipping_address = $address;
        $shipping_address_id = $address['address_id'];
        $this->simple->show_shipping_address = $simple_show_shipping_address;
        
        if ($simple_show_shipping_address) {
            $this->simple->simple_show_shipping_address_same_init = $this->config->get('simple_show_shipping_address_same_init');
            $this->simple->simple_show_shipping_address_same_show = $this->config->get('simple_show_shipping_address_same_show');

            $this->simple->shipping_address_same = !empty($this->request->post['shipping_address_same']) ? true : $this->simple->simple_show_shipping_address_same_init;
            
            if ($this->simple->simple_show_shipping_address_same_init && !$this->simple->simple_show_shipping_address_same_show) {
                $this->simple->simple_show_shipping_address_same_show = true;
            }

            if (!$this->simple->simple_show_shipping_address_same_show) {
                $this->simple->shipping_address_same = false;
            }
        } else {
            $this->simple->shipping_address_same = true;
        }
        

        if ($simple_show_shipping_address && $this->request->server['REQUEST_METHOD'] == 'POST' && empty($this->request->post['shipping_address_same'])) {
            $address = $this->prepare_address($this->simple->get_value(Simple::SET_CHECKOUT_ADDRESS,'zone_id'), $this->simple->get_value(Simple::SET_CHECKOUT_ADDRESS,'country_id'));
        
            $address_id = $this->get_shipping_address_id();
            
            $address['address_id'] = $address_id;
            $address['firstname']  = $this->simple->get_total_value(Simple::SET_CHECKOUT_ADDRESS,'firstname');
            $address['lastname']   = $this->simple->get_total_value(Simple::SET_CHECKOUT_ADDRESS,'lastname');
            $address['company']    = $this->simple->get_total_value(Simple::SET_CHECKOUT_ADDRESS,'company');
            $address['company_id'] = $this->simple->get_total_value(Simple::SET_CHECKOUT_ADDRESS,'company_id');
            $address['tax_id']     = $this->simple->get_total_value(Simple::SET_CHECKOUT_ADDRESS,'tax_id');
            $address['address_1']  = $this->simple->get_total_value(Simple::SET_CHECKOUT_ADDRESS,'address_1');
            $address['address_2']  = $this->simple->get_total_value(Simple::SET_CHECKOUT_ADDRESS,'address_2');
            $address['postcode']   = $this->simple->get_total_value(Simple::SET_CHECKOUT_ADDRESS,'postcode');
            $address['city']       = $this->simple->get_total_value(Simple::SET_CHECKOUT_ADDRESS,'city');

            // fix for existing other fields of address
            if ($address_id) {
                $tmp_address = $this->model_account_address->getAddress($address_id);
                $address = array_merge($tmp_address, $address);
            }

            // fix for custom fields
            //$custom_data_address = $this->simple->get_custom_data_for_object(Simple::SET_CHECKOUT_ADDRESS, Simple::OBJECT_TYPE_ADDRESS);
            //$address = array_merge($address, $custom_data_address);

            $this->simple->shipping_address = $address;
            $shipping_address_id = $address['address_id'];
            
            $this->simple->shipping_address_same = false;
        }
        
        $email = $this->customer->isLogged() && $this->customer->getEmail() != '' ? $this->customer->getEmail() : $this->simple->get_value(Simple::SET_CHECKOUT_CUSTOMER,'email');
        
        $firstname = $this->simple->get_total_value(Simple::SET_CHECKOUT_CUSTOMER,'firstname');
        $lastname  = $this->simple->get_total_value(Simple::SET_CHECKOUT_CUSTOMER,'lastname');
        $telephone = $this->simple->get_total_value(Simple::SET_CHECKOUT_CUSTOMER,'telephone');
        $fax       = $this->simple->get_total_value(Simple::SET_CHECKOUT_CUSTOMER,'fax');
         
        $customer_info = array(
            'customer_id'       => $this->customer->isLogged() && $this->customer->getId() ? $this->customer->getId() : 0,
            'customer_group_id' => $this->simple->customer_group_id,
            'email'             => $email,
            'password'          => $this->simple->password,
            'password_confirm'  => $this->simple->password_confirm,
            'firstname'         => $this->customer->isLogged() && $this->customer->getFirstName() != '' ? $this->customer->getFirstName() : $firstname,
            'lastname'          => $this->customer->isLogged() && $this->customer->getLastName() != '' ? $this->customer->getLastName() : $lastname,
            'telephone'         => $this->customer->isLogged() ? (!empty($telephone) && $this->customer->getTelephone() != $telephone ? $telephone : $this->customer->getTelephone()) : $telephone,
            'fax'               => $this->customer->isLogged() ? (!empty($fax) && $this->customer->getFax() != $fax ? $fax : $this->customer->getFax()) : $fax,
            'newsletter'        => $this->simple->subscribe
        );

        // fix for custom fields
        //$custom_data_customer = $this->simple->get_custom_data_for_object(Simple::SET_CHECKOUT_CUSTOMER, Simple::OBJECT_TYPE_CUSTOMER);
        //$customer_info = array_merge($customer_info, $custom_data_customer);

        $this->simple->customer_info = $customer_info;
    
        $this->validate();
        
        $this->save_to_session();
    }
    
    private function save_to_session() {
        if (!$this->customer->isLogged() && $this->request->server['REQUEST_METHOD'] == 'POST') {
            if (isset($this->request->post[Simple::SET_CHECKOUT_CUSTOMER])) {
                $this->session->data['simple'][Simple::SET_CHECKOUT_CUSTOMER] = $this->request->post[Simple::SET_CHECKOUT_CUSTOMER];
            }
            if (isset($this->request->post[Simple::SET_CHECKOUT_ADDRESS])) {
                $this->session->data['simple'][Simple::SET_CHECKOUT_ADDRESS] = $this->request->post[Simple::SET_CHECKOUT_ADDRESS];
            }
        }

        if (!$this->customer->isLogged() && !$this->error) {
            $this->session->data['guest'] = $this->simple->customer_info;
        }
                
        $version = $this->simple->opencart_version;
        
        $address = $this->simple->shipping_address;
        
        if (!$this->customer->isLogged() && !$this->error) {
            $this->session->data['guest']['shipping'] = $address;
        }
        
        unset($this->session->data['shipping_address_id']);	
		unset($this->session->data['shipping_country_id']);	
		unset($this->session->data['shipping_zone_id']);	
		unset($this->session->data['shipping_postcode']);
                
        if (!empty($address['address_id'])) {
            $this->session->data['shipping_address_id'] = $address['address_id'];
        } 
        
        if (!empty($address['country_id'])) {
            $this->session->data['shipping_country_id'] = $address['country_id'];
        } else {
            $this->session->data['shipping_country_id'] = 0;
        }
        
        if (!empty($address['zone_id'])) {
            $this->session->data['shipping_zone_id'] = $address['zone_id'];
        } else {
            $this->session->data['shipping_zone_id'] = 0;
        }
        
        if (!empty($address['postcode'])) {
            $this->session->data['shipping_postcode'] = $address['postcode'];
		}
        
        if ($version == 152 && !empty($this->session->data['guest']['shipping']) && is_array($this->session->data['guest']['shipping'])) {
            $clear = true;
            foreach ($this->session->data['guest']['shipping'] as $key => $value) {
                if ($value) {
                    $clear = false;
                    break;
                }
            }
            if ($clear) {
                unset($this->session->data['guest']['shipping']);
            }
        }
       
        if ($this->session->data['shipping_country_id'] || $this->session->data['shipping_zone_id']) {
            if ($version > 151) {
                $this->tax->setShippingAddress($this->session->data['shipping_country_id'], $this->session->data['shipping_zone_id']);
            } else {
                $this->tax->setZone($this->session->data['shipping_country_id'], $this->session->data['shipping_zone_id']);
            	
				$this->session->data['country_id'] = $this->session->data['shipping_country_id'];
				$this->session->data['zone_id'] = $this->session->data['shipping_zone_id'];
                if (isset($this->session->data['shipping_postcode'])) {
				    $this->session->data['postcode'] = $this->session->data['shipping_postcode'];
                }
            }
        } else {
            unset($this->session->data['shipping_country_id']);
            unset($this->session->data['shipping_zone_id']);
            
            if ($version > 151) {
                $this->tax->setShippingAddress(0, 0);
            } else {
                $this->tax->setZone(0, 0);
            }
                
            if (!$this->customer->isLogged() && $this->config->get('config_tax_default') == 'shipping') {
                if ($version > 151) {
                    $this->tax->setShippingAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
                } else {
                    $this->tax->setZone($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
                }
            }
        }
        
        if (!empty($address['postcode'])) {
            $this->session->data['shipping_postcode'] = $address['postcode'];
        }
        
        $address = $this->simple->payment_address;
        
        if (!$this->customer->isLogged() && !$this->error) {
            $this->session->data['guest']['payment'] = $address;
        }
        
        unset($this->session->data['payment_address_id']);	
		unset($this->session->data['payment_country_id']);	
		unset($this->session->data['payment_zone_id']);	
        
        if (!empty($address['address_id'])) {
            $this->session->data['payment_address_id'] = $address['address_id'];
        } 
        
        if (!empty($address['country_id'])) {
            $this->session->data['payment_country_id'] = $address['country_id'];
		} else {
            $this->session->data['payment_country_id'] = 0;
		}
        
        if (!empty($address['zone_id'])) {
            $this->session->data['payment_zone_id'] = $address['zone_id'];
        } else {
            $this->session->data['payment_zone_id'] = 0;
        }
        
        if ($version == 152 && !empty($this->session->data['guest']['payment']) && is_array($this->session->data['guest']['payment'])) {
            $clear = true;
            foreach ($this->session->data['guest']['payment'] as $key => $value) {
                if ($value) {
                    $clear = false;
                    break;
                }
            }
            if ($clear) {
                unset($this->session->data['guest']['payment']);
            }
        }
        
        if ($version > 151) {
            if ($this->session->data['payment_country_id'] || $this->session->data['payment_zone_id']) {
                $this->tax->setPaymentAddress($this->session->data['payment_country_id'], $this->session->data['payment_zone_id']);
            } else {
                unset($this->session->data['payment_country_id']);
                unset($this->session->data['payment_zone_id']);
                
                $this->tax->setPaymentAddress(0, 0);
            
                if (!$this->customer->isLogged() && $this->config->get('config_tax_default') == 'payment') {
                    $this->tax->setPaymentAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
                }
            }
        }
    }
        
    private function prepare_address($zone_id, $country_id = 0) {
        $this->load->model('localisation/zone');
        $this->load->model('localisation/country');
    
        if ($zone_id) {
            $zone = $this->model_localisation_zone->getZone($zone_id);
            if ($zone) {
                if ($zone['country_id'] != $country_id) {
                    $zone_id = 0;
                } else {
                    $country = $this->model_localisation_country->getCountry($zone['country_id']);
                    
                    if ($country) {
                        return array(
                            'address_id'     => 0,
            				'firstname'      => '',
            				'lastname'       => '',
            				'company'        => '',
            				'company_id'     => '',
            				'tax_id'         => '',
            				'address_1'      => '',
            				'address_2'      => '',
            				'postcode'       => '',
            				'city'           => '',
            				'zone_id'        => $zone['zone_id'],
            				'zone'           => $zone['name'],
            				'zone_code'      => $zone['code'],
            				'country_id'     => $zone['country_id'],
            				'country'        => $country['name'],	
            				'iso_code_2'     => $country['iso_code_2'],
            				'iso_code_3'     => $country['iso_code_3'],
            				'address_format' => $country['address_format']
                        );
                    }
                }
            }
        } 
        if ($country_id && !$zone_id) {
            $country = $this->model_localisation_country->getCountry($country_id);
                
            if ($country) {
                return array(
                    'address_id'     => 0,
    				'firstname'      => '',
    				'lastname'       => '',
    				'company'        => '',
    				'company_id'     => '',
    				'tax_id'         => '',
    				'address_1'      => '',
    				'address_2'      => '',
    				'postcode'       => '',
    				'city'           => '',
    				'zone_id'        => $zone_id,
    				'zone'           => '',
    				'zone_code'      => '',
    				'country_id'     => $country['country_id'],
    				'country'        => $country['name'],	
    				'iso_code_2'     => $country['iso_code_2'],
    				'iso_code_3'     => $country['iso_code_3'],
    				'address_format' => $country['address_format']
                );
            }
        }
        
        return array(
            'address_id'     => 0,
    		'firstname'      => '',
    		'lastname'       => '',
    		'company'        => '',
    		'company_id'     => '',
			'tax_id'         => '',
			'address_1'      => '',
    		'address_2'      => '',
    		'postcode'       => '',
    		'city'           => '',
    		'zone_id'        => 0,
    		'zone'           => '',
    		'zone_code'      => '',
    		'country_id'     => 0,
    		'country'        => '',	
    		'iso_code_2'     => '',
    		'iso_code_3'     => '',
    		'address_format' => ''
        );
    }
    
    
    public function zone() {
		$output = '<option value="">' . $this->language->get('text_select') . '</option>';
		
		$this->load->model('localisation/zone');

    	$results = $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']);
        
      	foreach ($results as $result) {
        	$output .= '<option value="' . $result['zone_id'] . '"';
	    	if (isset($this->request->get['zone_id']) && ($this->request->get['zone_id'] == $result['zone_id'])) {
	      		$output .= ' selected="selected"';
	    	}
	    	$output .= '>' . $result['name'] . '</option>';
    	} 
		
		if (!$results) {
		  	$output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
		}
	
		$this->response->setOutput($output);
  	}  
    
    public function login() {
        $this->language->load('checkout/simplecheckout');
        
        $json = array();
        
        $this->data['error_login'] = '';
        $this->data['redirect'] = '';
        
        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if (!empty($this->request->post['email']) && !empty($this->request->post['password']) && $this->customer->login($this->request->post['email'], $this->request->post['password'])) {
                unset($this->session->data['guest']);
                $this->data['redirect'] = $this->url->link('checkout/simplecheckout', '', 'SSL');
            } else {
                $this->data['error_login'] = $this->language->get('error_login');
            }
        }
        
        $this->data['text_checkout_customer'] = $this->language->get('text_checkout_customer');
        $this->data['text_checkout_customer_login'] = $this->language->get('text_checkout_customer_login');
        $this->data['text_checkout_customer_cancel'] = $this->language->get('text_checkout_customer_cancel');
        $this->data['text_forgotten'] = $this->language->get('text_forgotten');
        $this->data['entry_email'] = $this->language->get('entry_email');
        $this->data['entry_password'] = $this->language->get('entry_password');
        $this->data['button_login'] = $this->language->get('button_login');

        $this->data['action'] = $this->url->link('checkout/simplecheckout_customer/login', '', 'SSL');
        $this->data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');
        
        if (isset($this->request->post['email'])) {
            $this->data['email'] = trim($this->request->post['email']);
        } else {
            $this->data['email'] = '';
        }
                    
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/simplecheckout_login.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/checkout/simplecheckout_login.tpl';
            $this->data['template'] = $this->config->get('config_template');
        } else {
            $this->template = 'default/template/checkout/simplecheckout_login.tpl';
            $this->data['template'] = 'default';
        }
            
        $this->response->setOutput($this->render());
    }
    
    private function validate() {
        $this->error = false;

        $show_shipping_address = $this->simple->show_shipping_address;
        $shipping_address_same = $this->simple->shipping_address_same;
        $register_customer = $this->simple->register;
        $email = $this->simple->get_value(Simple::SET_CHECKOUT_CUSTOMER, 'email');;

        if ($this->customer->isLogged() || (!$this->customer->isLogged() && !$register_customer && empty($email) && ($this->config->get('simple_customer_view_email') == Simple::EMAIL_NOT_SHOW || $this->config->get('simple_customer_view_email') == Simple::EMAIL_SHOW_AND_NOT_REQUIRED))) {
            if (isset($this->data['checkout_customer_fields']['main_email'])) {
                $this->data['checkout_customer_fields']['main_email']['error'] = '';
            }
            $this->simple->reset_error(Simple::SET_CHECKOUT_CUSTOMER, 'main_email');
        }

        $this->data['email_confirm_error'] = false;
        if (!$this->customer->isLogged() && $this->config->get('simple_customer_view_email_confirm') && ($this->config->get('simple_customer_view_email') == Simple::EMAIL_SHOW_AND_NOT_REQUIRED || $this->config->get('simple_customer_view_email') == Simple::EMAIL_SHOW_AND_REQUIRED)) {
            $email_confirm = isset($this->request->post['email_confirm']) ? trim($this->request->post['email_confirm']) : '';
            if ($email != $email_confirm) {
                $this->error = true;
                $this->data['email_confirm_error'] = true;
            }
        }

        if (!$this->simple->validate_fields(Simple::SET_CHECKOUT_CUSTOMER)) {
            $this->error = true;
        }

        if ($show_shipping_address && !$shipping_address_same && !$this->simple->validate_fields(Simple::SET_CHECKOUT_ADDRESS)) {
            $this->error = true;
        }

        if (!$this->customer->isLogged() && $register_customer) {
            if (!empty($email) && $this->model_account_customer->getTotalCustomersByEmail($email)) {
                $this->data['checkout_customer_fields']['main_email']['error'] = $this->language->get('error_exists');
                $this->error = true;
            }
            
            $password_length_min = $this->config->get('simple_customer_view_password_length_min');
            $password_length_min = !empty($password_length_min) ? $password_length_min : 4;
            
            $password_length_max = $this->config->get('simple_customer_view_password_length_max');
            $password_length_max = !empty($password_length_max) ? $password_length_max : 20;
            
            $password = $this->simple->password;
            $password_confirm = $this->simple->password_confirm;
            
            if (!$this->config->get('simple_customer_generate_password')) {
                if (strlen(utf8_decode($password)) < $password_length_min || strlen(utf8_decode($password)) > $password_length_max) {
                    $this->data['error_password'] = sprintf($this->language->get('error_password'), $password_length_min, $password_length_max);
                    $this->error = true;
                }
                
                if ($this->config->get('simple_customer_view_password_confirm') && $password != $password_confirm) {
                    $this->data['error_password_confirm'] = $this->language->get('error_password_confirm');
                    $this->error = true;
                }
            }
        }

        return !$this->error;
    }
    
    public function check_email() {
        $error = '';
        
        if (!$this->customer->isLogged()) {
            $register_customer = false;
            
            if ($this->config->get('simple_customer_action_register') != 2) {
                $register_customer = $this->config->get('simple_customer_action_register') == 1;
            } elseif (isset($this->request->get['register'])) {
                $register_customer = $this->request->get['register'];
            } 
            
            if (!empty($this->request->get['email'])) {
                if ($register_customer) {
                    $this->load->model('account/customer');
                    $this->language->load('checkout/simplecheckout');
                
                    if ($this->model_account_customer->getTotalCustomersByEmail($this->request->get['email'])) {
                        $error = $this->language->get('error_exists');
                    }
                }
                
                $settings = $this->config->get('simple_fields_main');

                $language_code = str_replace('-', '_', strtolower($this->config->get('config_language')));
                
                if (!empty($settings['main_email']['validation_regexp'])) {
                    $regexp = $settings['main_email']['validation_regexp'];
                    if (!preg_match($regexp, trim($this->request->get['email']))) {
                        $error = $settings['main_email']['validation_error'][$language_code];
                    }
                }
            }
        }
        
        $this->response->setOutput($error);
    }
    
    private function save_customer_data() {
        if ($this->customer->isLogged()) {
            $customer_id = $this->customer->getId();

            $customer = $this->simple->customer_info;
            $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

            $data = array(
                'firstname'         => !empty($customer_info['firstname']) ? $customer_info['firstname'] : $customer['firstname'],
                'lastname'          => !empty($customer_info['lastname']) ? $customer_info['lastname'] : $customer['lastname'],
                'telephone'         => !empty($customer_info['telephone']) ? $customer_info['telephone'] : $customer['telephone'],
                'fax'               => !empty($customer_info['fax']) ? $customer_info['fax'] : $customer['fax'],
                'email'             => $customer_info['email'],
                'customer_group_id' => $this->simple->customer_group_id
            );

            // fix for existsing other fields of customer
            $data = array_merge($customer_info, $data);

            $custom_data_customer = $this->simple->get_custom_data_for_object(Simple::SET_CHECKOUT_CUSTOMER, Simple::OBJECT_TYPE_CUSTOMER);
            
            $data = array_merge($data, $custom_data_customer);
            
            $data['simple'] = array();
            $custom_data_db = $this->simple->load_custom_data(Simple::OBJECT_TYPE_CUSTOMER, $this->customer->getId());
            $custom_data_form = $this->simple->get_custom_data(Simple::SET_CHECKOUT_CUSTOMER, Simple::OBJECT_TYPE_CUSTOMER);
            $data['simple']['customer'] = array_merge($custom_data_db, $custom_data_form);

            $this->model_account_customer->editCustomer($data);

            $this->simple->save_custom_data(Simple::SET_CHECKOUT_CUSTOMER, Simple::OBJECT_TYPE_CUSTOMER, $customer_id);
            
            if ($this->config->get('simple_customer_view_customer_type')) {
                $this->simple->edit_customer_group_id($this->simple->customer_group_id);
            }

            $payment_address = $this->simple->payment_address;
            
            if (!empty($payment_address)) {
                $custom_data_address = $this->simple->get_custom_data_for_object(Simple::SET_CHECKOUT_CUSTOMER, Simple::OBJECT_TYPE_ADDRESS);

                $payment_address = array_merge($payment_address, $custom_data_address);

                $payment_address['simple'] = array();
                if (!$payment_address['address_id']) {
                    $custom_data_db = $this->simple->load_custom_data(Simple::OBJECT_TYPE_ADDRESS, $payment_address['address_id']);
                } else {
                    $custom_data_db = array();
                }
                $custom_data_form = $this->simple->get_custom_data(Simple::SET_CHECKOUT_CUSTOMER, Simple::OBJECT_TYPE_ADDRESS);
                $payment_address['simple']['address'] = array_merge($custom_data_db, $custom_data_form);

                if (!$payment_address['address_id']) {
                    $payment_address_id = $this->model_account_address->addAddress($payment_address);
                    $payment_address['address_id'] = $payment_address_id;
                    $this->simple->payment_address = $payment_address;
                } else {
                    $this->model_account_address->editAddress($payment_address['address_id'], $payment_address);
                }

                if (!isset(self::$static_data['addresses'])) {
                    self::$static_data['addresses'] = array();
                }
                
                self::$static_data['addresses'][$payment_address['address_id']] = $payment_address;

                $this->simple->save_custom_data(Simple::SET_CHECKOUT_CUSTOMER, Simple::OBJECT_TYPE_ADDRESS, $payment_address['address_id']);
            }
            
            $shipping_address_same = $this->simple->shipping_address_same;
            $shipping_address = $this->simple->shipping_address;
                
            if (!$shipping_address_same && !empty($shipping_address)) {
                $custom_data_address = $this->simple->get_custom_data_for_object(Simple::SET_CHECKOUT_ADDRESS, Simple::OBJECT_TYPE_ADDRESS);

                $shipping_address = array_merge($shipping_address, $custom_data_address);

                $shipping_address['simple'] = array();
                if (!$shipping_address['address_id']) {
                    $custom_data_db = $this->simple->load_custom_data(Simple::OBJECT_TYPE_ADDRESS, $shipping_address['address_id']);
                } else {
                    $custom_data_db = array();
                }
                $custom_data_form = $this->simple->get_custom_data(Simple::SET_CHECKOUT_ADDRESS, Simple::OBJECT_TYPE_ADDRESS);
                $shipping_address['simple']['address'] = array_merge($custom_data_db, $custom_data_form);
            
                if (!$shipping_address['address_id']) {
                    $shipping_address_id = $this->model_account_address->addAddress($shipping_address);
                    $shipping_address['address_id'] = $shipping_address_id;
                    $this->simple->shipping_address = $shipping_address;
                } else {
                    $this->model_account_address->editAddress($shipping_address['address_id'], $shipping_address);
                }
                if (!isset(self::$static_data['addresses'])) {
                    self::$static_data['addresses'] = array();
                }
                self::$static_data['addresses'][$shipping_address['address_id']] = $shipping_address;

                $this->simple->save_custom_data(Simple::SET_CHECKOUT_ADDRESS, Simple::OBJECT_TYPE_ADDRESS, $shipping_address['address_id']);
            }
        } else {
            $register_customer = $this->simple->register;
            $customer = $this->simple->customer_info;
            $payment_address = $this->simple->payment_address;
            
            if ($register_customer && !empty($customer) && !empty($payment_address)) {

                $customer_group_id = $customer['customer_group_id'];
                if (!$this->config->get('simple_customer_view_customer_type') && $customer_group_id_after_reg = $this->config->get('simple_customer_group_id_after_reg')) {
                    $customer_group_id = $customer_group_id_after_reg;
                }

                // fix for customer_group_id
                // $config_customer_group_id = $this->config->get('config_customer_group_id');
                $this->config->set('config_customer_group_id', $customer_group_id);

                $data = array(
                    'firstname'         => $customer['firstname'],
                    'lastname'          => $customer['lastname'],
                    'email'             => $customer['email'],
                    'password'          => $customer['password'],
                    'telephone'         => $customer['telephone'],
                    'fax'               => $customer['fax'],
                    'newsletter'        => $customer['newsletter'],
                    'company'           => $payment_address['company'],
                    'company_id'        => $payment_address['company_id'],
                    'tax_id'            => $payment_address['tax_id'],
                    'address_1'         => $payment_address['address_1'],
                    'address_2'         => $payment_address['address_2'],
                    'postcode'          => $payment_address['postcode'],
                    'city'              => $payment_address['city'],
                    'country_id'        => $payment_address['country_id'],
                    'zone_id'           => $payment_address['zone_id'],
                    'customer_group_id' => $customer_group_id
                );

                $custom_data_customer = $this->simple->get_custom_data_for_object(Simple::SET_CHECKOUT_CUSTOMER, Simple::OBJECT_TYPE_CUSTOMER);
                $custom_data_address = $this->simple->get_custom_data_for_object(Simple::SET_CHECKOUT_CUSTOMER, Simple::OBJECT_TYPE_ADDRESS);

                $data = array_merge($data, $custom_data_customer, $custom_data_address);

                $data['simple'] = array();
                $data['simple']['customer'] = $this->simple->get_custom_data(Simple::SET_CHECKOUT_CUSTOMER, Simple::OBJECT_TYPE_CUSTOMER);
                $data['simple']['address']  = $this->simple->get_custom_data(Simple::SET_CHECKOUT_CUSTOMER, Simple::OBJECT_TYPE_ADDRESS);
                
                if ($data['email'] == '') {
                    $this->simple->add_error('customer');
                    $this->error = true;
                    return;
                }
                
                $this->model_account_customer->addCustomer($data);
                $this->customer->login($data['email'], $data['password']);

                // fix for customer_group_id
                // $this->config->set('config_customer_group_id', $config_customer_group_id);

                if ($this->customer->isLogged()) {
                    $new_customer_id       = $this->customer->getId();
                    $new_address_id        = $this->customer->getAddressId();
                    $new_customer_group_id = $this->customer->getCustomerGroupId();
                } else {
                    $customer_info = $this->simple->get_customer_info($data['email']);
                    
                    $new_customer_id       = $customer_info['customer_id'];
                    $new_address_id        = $customer_info['address_id'];
                    $new_customer_group_id = $customer_info['customer_group_id'];
                }
                
                $this->simple->save_custom_data(Simple::SET_CHECKOUT_CUSTOMER, Simple::OBJECT_TYPE_CUSTOMER, $new_customer_id);
                
                $customer['customer_id'] = $new_customer_id;
                $customer['customer_group_id'] = $new_customer_group_id;
                $this->simple->customer_info = $customer;
                
                $payment_address['address_id'] = $new_address_id;
                $this->simple->payment_address = $payment_address;
                
                if (!isset(self::$static_data['addresses'])) {
                    self::$static_data['addresses'] = array();
                }
                self::$static_data['addresses'][$payment_address['address_id']] = $payment_address;
                
                $this->simple->save_custom_data(Simple::SET_CHECKOUT_CUSTOMER, Simple::OBJECT_TYPE_ADDRESS, $payment_address['address_id']);

                $shipping_address_same = $this->simple->shipping_address_same;
                $shipping_address = $this->simple->shipping_address;

                if (!$shipping_address_same && !empty($shipping_address)) {
                    $custom_data_address = $this->simple->get_custom_data_for_object(Simple::SET_CHECKOUT_ADDRESS, Simple::OBJECT_TYPE_ADDRESS);

                    $shipping_address = array_merge($shipping_address, $custom_data_address);

                    $shipping_address['simple'] = array();
                    $shipping_address['simple']['address'] = $this->simple->get_custom_data(Simple::SET_CHECKOUT_ADDRESS, Simple::OBJECT_TYPE_ADDRESS);

                    $shipping_address['address_id'] = $this->model_account_address->addAddress($shipping_address);
                    $this->simple->shipping_address = $shipping_address;
                    self::$static_data['addresses'][$shipping_address['address_id']] = $shipping_address;

                    $this->simple->save_custom_data(Simple::SET_CHECKOUT_ADDRESS, Simple::OBJECT_TYPE_ADDRESS, $shipping_address['address_id']);
                }
                
                $this->session->data['simple_customer_registered'] = $this->language->get('text_account_created');
            }
        }
    }
}
?>