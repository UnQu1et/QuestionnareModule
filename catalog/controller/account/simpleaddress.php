<?php 

/*
  @author   Dmitriy Kubarev
  @link http://www.simpleopencart.com
  @link http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/

require_once(DIR_SYSTEM . 'library/simple/simple.php');

class ControllerAccountSimpleAddress extends Controller {
    private $error = array();
      
    public function insert() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/address', '', 'SSL');
            $this->redirect($this->url->link('account/login', '', 'SSL')); 
        } 

        $this->language->load('account/address');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/address');

        $this->simple = new Simple($this->registry);

        $this->data['simple_edit_address'] = !empty($this->request->post['simple_edit_address']);

        $this->data['address_fields'] = $this->simple->load_fields(Simple::SET_ACCOUNT_ADDRESS, array('group' => $this->customer->getCustomerGroupId()));
        
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->data['simple_edit_address'] && $this->validate()) {
            $data = array(
                'firstname'  => $this->simple->get_total_value(Simple::SET_ACCOUNT_ADDRESS, 'firstname'),
                'lastname'   => $this->simple->get_total_value(Simple::SET_ACCOUNT_ADDRESS, 'lastname'),
                'company'    => $this->simple->get_total_value(Simple::SET_ACCOUNT_ADDRESS, 'company'),
                'company_id' => $this->simple->get_total_value(Simple::SET_ACCOUNT_ADDRESS, 'company_id'),
                'tax_id'     => $this->simple->get_total_value(Simple::SET_ACCOUNT_ADDRESS, 'tax_id'),
                'address_1'  => $this->simple->get_total_value(Simple::SET_ACCOUNT_ADDRESS, 'address_1'),
                'address_2'  => $this->simple->get_total_value(Simple::SET_ACCOUNT_ADDRESS, 'address_2'),
                'postcode'   => $this->simple->get_total_value(Simple::SET_ACCOUNT_ADDRESS, 'postcode'),
                'city'       => $this->simple->get_total_value(Simple::SET_ACCOUNT_ADDRESS, 'city'),
                'country_id' => $this->simple->get_value(Simple::SET_ACCOUNT_ADDRESS, 'country_id'),
                'zone_id'    => $this->simple->get_value(Simple::SET_ACCOUNT_ADDRESS, 'zone_id'),
            );

            $custom_data_address = $this->simple->get_custom_data_for_object(Simple::SET_ACCOUNT_ADDRESS, Simple::OBJECT_TYPE_ADDRESS);

            $data = array_merge($data, $custom_data_address);

            $data['simple'] = array();
            $data['simple']['address'] = $this->simple->get_custom_data(Simple::SET_ACCOUNT_ADDRESS, Simple::OBJECT_TYPE_ADDRESS);

            $address_id = $this->model_account_address->addAddress($data);

            $this->simple->save_custom_data(Simple::SET_ACCOUNT_ADDRESS, Simple::OBJECT_TYPE_ADDRESS, $address_id);

            $this->session->data['success'] = $this->language->get('text_insert');

            $this->redirect($this->url->link('account/address', '', 'SSL'));
        } 

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home'),         
            'separator' => false
        ); 

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_account'),
            'href'      => $this->url->link('account/account', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('account/address', '', 'SSL'),          
            'separator' => $this->language->get('text_separator')
        );
        
        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_edit_address'),
            'href'      => $this->url->link('account/simpleaddress/insert', '', 'SSL'),               
            'separator' => $this->language->get('text_separator')
        );
    

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_edit_address'] = $this->language->get('text_edit_address');

        $this->data['button_continue'] = $this->language->get('button_continue');
        $this->data['button_back'] = $this->language->get('button_back');

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        $this->data['action'] = $this->url->link('account/simpleaddress/insert', '', 'SSL');
        $this->data['back'] = $this->url->link('account/account', '', 'SSL');

        $this->data['language_code'] = $this->simple->get_language_code();
        
        $this->data['simple'] = $this->simple;

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/simpleaddress.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/account/simpleaddress.tpl';
            $this->data['template'] = $this->config->get('config_template');
        } else {
            $this->template = 'default/template/account/simpleaddress.tpl';
            $this->data['template'] = 'default';
        }

        $this->simple->add_static($this->data['template'], 'simpleaddress');
        
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

    public function update() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/address', '', 'SSL');
            $this->redirect($this->url->link('account/login', '', 'SSL')); 
        } 

        if (empty($this->request->get['address_id'])) {
            $this->redirect($this->url->link('account/address', '', 'SSL')); 
        }
        
        $this->language->load('account/address');

        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('account/address');

        $this->simple = new Simple($this->registry);
        
        $address_info = $this->model_account_address->getAddress($this->request->get['address_id']);

        if (empty($address_info)) {
            $this->redirect($this->url->link('account/address', '', 'SSL')); 
        }

        $this->data['simple_edit_address'] = !empty($this->request->post['simple_edit_address']);

        $custom_data = $this->simple->load_custom_data(Simple::OBJECT_TYPE_ADDRESS, $this->request->get['address_id']);

        $this->data['address_fields'] = $this->simple->load_fields(Simple::SET_ACCOUNT_ADDRESS, array('group' => $this->customer->getCustomerGroupId()), false, $address_info, $custom_data);
        
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->data['simple_edit_address'] && $this->validate()) {
            $data = array(
                'firstname'  => $this->simple->get_total_value(Simple::SET_ACCOUNT_ADDRESS, 'firstname'),
                'lastname'   => $this->simple->get_total_value(Simple::SET_ACCOUNT_ADDRESS, 'lastname'),
                'company'    => $this->simple->get_total_value(Simple::SET_ACCOUNT_ADDRESS, 'company'),
                'company_id' => $this->simple->get_total_value(Simple::SET_ACCOUNT_ADDRESS, 'company_id'),
                'tax_id'     => $this->simple->get_total_value(Simple::SET_ACCOUNT_ADDRESS, 'tax_id'),
                'address_1'  => $this->simple->get_total_value(Simple::SET_ACCOUNT_ADDRESS, 'address_1'),
                'address_2'  => $this->simple->get_total_value(Simple::SET_ACCOUNT_ADDRESS, 'address_2'),
                'postcode'   => $this->simple->get_total_value(Simple::SET_ACCOUNT_ADDRESS, 'postcode'),
                'city'       => $this->simple->get_total_value(Simple::SET_ACCOUNT_ADDRESS, 'city'),
                'country_id' => $this->simple->get_value(Simple::SET_ACCOUNT_ADDRESS, 'country_id'),
                'zone_id'    => $this->simple->get_value(Simple::SET_ACCOUNT_ADDRESS, 'zone_id'),
            );

            // fix for existing other fields of address
            $data = array_merge($address_info, $data);

            $data['simple'] = array();
            $data['simple']['address'] = $this->simple->get_custom_data(Simple::SET_ACCOUNT_ADDRESS, Simple::OBJECT_TYPE_ADDRESS);
               
            $this->model_account_address->editAddress($this->request->get['address_id'], $data);
            
            $this->simple->save_custom_data(Simple::SET_ACCOUNT_ADDRESS, Simple::OBJECT_TYPE_ADDRESS, $this->request->get['address_id']);

            // Default Shipping Address
            if (isset($this->session->data['shipping_address_id']) && ($this->request->get['address_id'] == $this->session->data['shipping_address_id'])) {
                $this->session->data['shipping_country_id'] = $data['country_id'];
                $this->session->data['shipping_zone_id'] = $data['zone_id'];
                $this->session->data['shipping_postcode'] = $data['postcode'];
                
                unset($this->session->data['shipping_method']);    
                unset($this->session->data['shipping_methods']);
            }
            
            // Default Payment Address
            if (isset($this->session->data['payment_address_id']) && ($this->request->get['address_id'] == $this->session->data['payment_address_id'])) {
                $this->session->data['payment_country_id'] = $data['country_id'];
                $this->session->data['payment_zone_id'] = $data['zone_id'];
                  
                unset($this->session->data['payment_method']);
                unset($this->session->data['payment_methods']);
            }
            
            $this->session->data['success'] = $this->language->get('text_update');
      
            $this->redirect($this->url->link('account/address', '', 'SSL'));
        } 
      
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home'),         
            'separator' => false
        ); 

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_account'),
            'href'      => $this->url->link('account/account', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('account/address', '', 'SSL'),          
            'separator' => $this->language->get('text_separator')
        );
        
        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_edit_address'),
            'href'      => $this->url->link('account/simpleaddress/update', 'address_id=' . $this->request->get['address_id'], 'SSL'),            
            'separator' => $this->language->get('text_separator')
        );
    
        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_edit_address'] = $this->language->get('text_edit_address');

        $this->data['button_continue'] = $this->language->get('button_continue');
        $this->data['button_back'] = $this->language->get('button_back');

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        $this->data['action'] = $this->url->link('account/simpleaddress/update', 'address_id=' . $this->request->get['address_id'], 'SSL');
        $this->data['back'] = $this->url->link('account/account', '', 'SSL');

        $this->data['language_code'] = $this->simple->get_language_code();
        
        $this->data['simple'] = $this->simple;

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/simpleaddress.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/account/simpleaddress.tpl';
            $this->data['template'] = $this->config->get('config_template');
        } else {
            $this->template = 'default/template/account/simpleaddress.tpl';
            $this->data['template'] = 'default';
        }

        $this->simple->add_static($this->data['template'], 'simpleaddress');
        
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

    private function validate() {
        $error = false;

        if (!$this->simple->validate_fields(Simple::SET_ACCOUNT_ADDRESS)) {
            $error = true;
        }

        return !$error;
    }
}
?>