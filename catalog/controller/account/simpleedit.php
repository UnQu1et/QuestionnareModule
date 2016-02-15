<?php

/*
  @author   Dmitriy Kubarev
  @link http://www.simpleopencart.com
  @link http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/

require_once(DIR_SYSTEM . 'library/simple/simple.php');

class ControllerAccountSimpleEdit extends Controller {
    private $error = array();

    public function index() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/edit', '', 'SSL');

            $this->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $this->language->load('account/simpleregister');
        $this->language->load('account/edit');
        
        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('account/customer');

        $this->simple = new Simple($this->registry);

        $this->data['simple_account_view_customer_type'] = $this->config->get('simple_account_view_customer_type');

        $this->data['simple_type_of_selection_of_group'] = $this->config->get('simple_type_of_selection_of_group');
        $this->data['simple_type_of_selection_of_group'] = !empty($this->data['simple_type_of_selection_of_group']) ? $this->data['simple_type_of_selection_of_group'] : 'select';

        $this->data['customer_groups'] = $this->simple->get_customer_groups();
        $this->data['customer_group_id'] = $this->customer->getCustomerGroupId();
  
        if ($this->data['simple_account_view_customer_type'] && isset($this->request->post['customer_group_id']) && array_key_exists($this->request->post['customer_group_id'], $this->data['customer_groups'])) {
            $this->data['customer_group_id'] = $this->request->post['customer_group_id'];
        }

        $this->data['simple_edit_account'] = !empty($this->request->post['simple_edit_account']);

        $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
        $custom_data = $this->simple->load_custom_data(Simple::OBJECT_TYPE_CUSTOMER, $this->customer->getId());

        $this->data['customer_fields'] = $this->simple->load_fields(Simple::SET_ACCOUNT_INFO, array('group' => $this->data['customer_group_id']), !$this->data['simple_edit_account'], $customer_info, $custom_data);
        
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->data['simple_edit_account'] && $this->validate()) {
            $data = array(
                'firstname'         => $this->simple->get_total_value(Simple::SET_ACCOUNT_INFO, 'firstname'),
                'lastname'          => $this->simple->get_total_value(Simple::SET_ACCOUNT_INFO, 'lastname'),
                'email'             => $this->simple->get_value(Simple::SET_ACCOUNT_INFO, 'email'),
                'telephone'         => $this->simple->get_total_value(Simple::SET_ACCOUNT_INFO, 'telephone'),
                'fax'               => $this->simple->get_total_value(Simple::SET_ACCOUNT_INFO, 'fax'),
                'customer_group_id' => $this->data['customer_group_id']
            );

            // fix for existing other fields of customer
            $data = array_merge($customer_info, $data);

            $custom_data_customer = $this->simple->get_custom_data_for_object(Simple::SET_ACCOUNT_INFO, Simple::OBJECT_TYPE_CUSTOMER);

            $data = array_merge($data, $custom_data_customer);

            $data['simple'] = array();
            $data['simple']['customer'] = $this->simple->get_custom_data(Simple::SET_ACCOUNT_INFO, Simple::OBJECT_TYPE_CUSTOMER);

            $this->model_account_customer->editCustomer($data);

            if ($this->data['simple_account_view_customer_type']) {
                $this->simple->edit_customer_group_id($this->data['customer_group_id']);
            }

            $this->simple->save_custom_data(Simple::SET_ACCOUNT_INFO, Simple::OBJECT_TYPE_CUSTOMER, $this->customer->getId());
            
            $this->session->data['success'] = $this->language->get('text_success');

            $this->redirect($this->url->link('account/account', '', 'SSL'));
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
            'text'      => $this->language->get('text_edit'),
            'href'      => $this->url->link('account/simpleedit', '', 'SSL'),           
            'separator' => $this->language->get('text_separator')
        );
        
        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_your_details'] = $this->language->get('text_your_details');

        $this->data['button_continue'] = $this->language->get('button_continue');
        $this->data['button_back'] = $this->language->get('button_back');
        $this->data['entry_customer_type'] = $this->language->get('entry_customer_type');

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        $this->data['action'] = $this->url->link('account/simpleedit', '', 'SSL');
        $this->data['back'] = $this->url->link('account/account', '', 'SSL');

        $this->data['language_code'] = $this->simple->get_language_code();

        $this->data['simple'] = $this->simple;

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/simpleedit.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/account/simpleedit.tpl';
            $this->data['template'] = $this->config->get('config_template');
        } else {
            $this->template = 'default/template/account/simpleedit.tpl';
            $this->data['template'] = 'default';
        }

        $this->simple->add_static($this->data['template'], 'simpleedit');
        
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

        if (!$this->simple->validate_fields(Simple::SET_ACCOUNT_INFO)) {
            $error = true;
        }

        $email = $this->simple->get_value(Simple::SET_ACCOUNT_INFO, 'email');
        if (!empty($email) && $this->customer->getEmail() != $email && $this->model_account_customer->getTotalCustomersByEmail($email)) {
            $this->data['customer_fields']['main_email']['error'] = $this->language->get('error_exists');
            $error = true;
        }

        return !$error;
    }
}
?>