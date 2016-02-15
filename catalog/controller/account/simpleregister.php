<?php

/*
  @author	Dmitriy Kubarev
  @link	http://www.simpleopencart.com
  @link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/

require_once(DIR_SYSTEM . 'library/simple/simple.php');

class ControllerAccountSimpleRegister extends Controller {

    private $error = array();

    public function index() {
        if ($this->customer->isLogged()) {
            $this->redirect($this->url->link('account/account', '', 'SSL'));
        }

        $this->language->load('account/simpleregister');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/customer');

        $this->data['simple_registration_password_confirm']   = $this->config->get('simple_registration_password_confirm');
        $this->data['simple_registration_captcha']            = $this->config->get('simple_registration_captcha');
        $this->data['simple_registration_subscribe']          = $this->config->get('simple_registration_subscribe');
        $this->data['simple_registration_subscribe_init']     = $this->config->get('simple_registration_subscribe_init');
        $this->data['simple_registration_view_customer_type'] = $this->config->get('simple_registration_view_customer_type');
        $this->data['simple_registration_generate_password']  = $this->config->get('simple_registration_generate_password');
        $this->data['simple_registration_view_email_confirm'] = $this->config->get('simple_registration_view_email_confirm');
        
        $this->data['simple_type_of_selection_of_group'] = $this->config->get('simple_type_of_selection_of_group');
        $this->data['simple_type_of_selection_of_group'] = !empty($this->data['simple_type_of_selection_of_group']) ? $this->data['simple_type_of_selection_of_group'] : 'select';

        $this->data['error_warning']          = '';
        $this->data['error_password']         = '';
        $this->data['error_password_confirm'] = '';
        $this->data['error_captcha']          = '';

        $this->simple = new Simple($this->registry);

        $this->data['customer_groups'] = array();

        $this->data['customer_group_id'] = $this->config->get('config_customer_group_id');

        if ($this->data['simple_registration_view_customer_type']) {
            $this->data['customer_groups'] = $this->simple->get_customer_groups();
            
            if (isset($this->request->post['customer_group_id']) && array_key_exists($this->request->post['customer_group_id'], $this->data['customer_groups'])) {
                $this->data['customer_group_id'] = $this->request->post['customer_group_id'];
            }
        }

        $this->data['simple_create_account'] = !empty($this->request->post['simple_create_account']);

        $this->data['customer_fields'] = $this->simple->load_fields(Simple::SET_REGISTRATION, array('group' => $this->data['customer_group_id']));

        $this->data['email_confirm_error'] = false;

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->data['simple_create_account'] && $this->validate()) {

            unset($this->session->data['guest']);

            $customer_group_id = $this->data['customer_group_id'];
            if (!$this->data['simple_registration_view_customer_type'] && $customer_group_id_after_reg = $this->config->get('simple_customer_group_id_after_reg')) {
                $customer_group_id = $customer_group_id_after_reg;
            }

            // fix for customer_group_id
            // $config_customer_group_id = $this->config->get('config_customer_group_id');
            $this->config->set('config_customer_group_id', $customer_group_id);

            $data = array(
                'firstname'         => $this->simple->get_total_value(Simple::SET_REGISTRATION, 'firstname'),
                'lastname'          => $this->simple->get_total_value(Simple::SET_REGISTRATION, 'lastname'),
                'email'             => $this->simple->get_value(Simple::SET_REGISTRATION, 'email'),
                'password'          => trim($this->request->post['password']),
                'telephone'         => $this->simple->get_total_value(Simple::SET_REGISTRATION, 'telephone'),
                'fax'               => $this->simple->get_total_value(Simple::SET_REGISTRATION, 'fax'),
                'newsletter'        => isset($this->request->post['subscribe']) ? $this->request->post['subscribe'] : ($this->data['simple_registration_subscribe'] == Simple::SUBSCRIBE_YES),
                'company'           => $this->simple->get_total_value(Simple::SET_REGISTRATION, 'company'),
                'company_id'        => $this->simple->get_total_value(Simple::SET_REGISTRATION, 'company_id'),
                'tax_id'            => $this->simple->get_total_value(Simple::SET_REGISTRATION, 'tax_id'),
                'address_1'         => $this->simple->get_total_value(Simple::SET_REGISTRATION, 'address_1'),
                'address_2'         => $this->simple->get_total_value(Simple::SET_REGISTRATION, 'address_2'),
                'postcode'          => $this->simple->get_total_value(Simple::SET_REGISTRATION, 'postcode'),
                'city'              => $this->simple->get_total_value(Simple::SET_REGISTRATION, 'city'),
                'country_id'        => $this->simple->get_value(Simple::SET_REGISTRATION, 'country_id'),
                'zone_id'           => $this->simple->get_value(Simple::SET_REGISTRATION, 'zone_id'),
                'customer_group_id' => $customer_group_id
            );

            $custom_data_customer = $this->simple->get_custom_data_for_object(Simple::SET_REGISTRATION, Simple::OBJECT_TYPE_CUSTOMER);
            $custom_data_address = $this->simple->get_custom_data_for_object(Simple::SET_REGISTRATION, Simple::OBJECT_TYPE_ADDRESS);

            $data = array_merge($data, $custom_data_customer, $custom_data_address);

            $data['simple'] = array();
            $data['simple']['customer'] = $this->simple->get_custom_data(Simple::SET_REGISTRATION, Simple::OBJECT_TYPE_CUSTOMER);
            $data['simple']['address']  = $this->simple->get_custom_data(Simple::SET_REGISTRATION, Simple::OBJECT_TYPE_ADDRESS);

            $this->model_account_customer->addCustomer($data);

            // fix for customer_group_id
            // $this->config->set('config_customer_group_id', $config_customer_group_id);

            $this->customer->login($data['email'], $data['password']);

            // Default Shipping Address
            if ($this->config->get('config_tax_customer') == 'shipping') {
                $this->session->data['shipping_country_id'] = $data['country_id'];
                $this->session->data['shipping_zone_id'] = $data['zone_id'];
                $this->session->data['shipping_postcode'] = $data['postcode'];
            }

            // Default Payment Address
            if ($this->config->get('config_tax_customer') == 'payment') {
                $this->session->data['payment_country_id'] = $data['country_id'];
                $this->session->data['payment_zone_id'] = $data['zone_id'];
            }

            if ($this->customer->isLogged()) {
                $this->simple->save_custom_data(Simple::SET_REGISTRATION, Simple::OBJECT_TYPE_CUSTOMER, $this->customer->getId());
                $this->simple->save_custom_data(Simple::SET_REGISTRATION, Simple::OBJECT_TYPE_ADDRESS, $this->customer->getAddressId());
            } else {
                $customer_info = $this->simple->get_customer_info($data['email']);
                $this->simple->save_custom_data(Simple::SET_REGISTRATION, Simple::OBJECT_TYPE_CUSTOMER, $customer_info['customer_id'], $customer_info['customer_id']);
                $this->simple->save_custom_data(Simple::SET_REGISTRATION, Simple::OBJECT_TYPE_ADDRESS, $customer_info['address_id'], $customer_info['customer_id']);
            }

            $this->redirect($this->url->link('account/success'));
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/account', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_register'),
            'href' => $this->url->link('account/simpleregister', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_account_already']   = sprintf($this->language->get('text_account_already'), $this->url->link('account/login', '', 'SSL'));
        $this->data['text_your_details']      = $this->language->get('text_your_details');
        $this->data['text_company_details']   = $this->language->get('text_company_details');
        $this->data['text_newsletter']        = $this->language->get('text_newsletter');
        $this->data['text_yes']               = $this->language->get('text_yes');
        $this->data['text_no']                = $this->language->get('text_no');
        $this->data['text_select']            = $this->language->get('text_select');
        
        $this->data['entry_password']         = $this->language->get('entry_password');
        $this->data['entry_password_confirm'] = $this->language->get('entry_password_confirm');
        $this->data['entry_newsletter']       = $this->language->get('entry_newsletter');
        $this->data['entry_captcha']          = $this->language->get('entry_captcha');
        $this->data['button_continue']        = $this->language->get('button_continue');
        $this->data['entry_customer_type']    = $this->language->get('entry_customer_type');

        $this->data['entry_email_confirm']    = $this->language->get('entry_email_confirm');
        $this->data['error_email_confirm']    = $this->language->get('error_email_confirm');

        $this->data['action'] = $this->url->link('account/simpleregister', '', 'SSL');

        $this->data['simple_registration_agreement_checkbox'] = false;
        $this->data['simple_registration_agreement_checkbox_init'] = 0;

        $this->data['text_agree'] = '';

        if ($this->config->get('simple_registration_agreement_id')) {
            $this->load->model('catalog/information');

            $information_info = $this->model_catalog_information->getInformation($this->config->get('simple_registration_agreement_id'));

            if ($information_info) {
                $this->data['simple_registration_agreement_checkbox'] = $this->config->get('simple_registration_agreement_checkbox');
                $this->data['simple_registration_agreement_checkbox_init'] = $this->config->get('simple_registration_agreement_checkbox_init');

                $current_theme = $this->config->get('config_template');

                $text = ($current_theme == 'shoppica' || $current_theme == 'shoppica2') ? 'text_agree_shoppica' : 'text_agree';
                $this->data['text_agree'] = sprintf($this->language->get($text), $this->url->link('information/information/info', 'information_id=' . $this->config->get('simple_registration_agreement_id'), 'SSL'), $information_info['title'], $information_info['title']);
            }
        }

        if (isset($this->request->post['agree'])) {
            $this->data['agree'] = $this->request->post['agree'];
        } else {
            $this->data['agree'] = $this->data['simple_registration_agreement_checkbox_init'];
        }

        if (isset($this->request->post['subscribe'])) {
            $this->data['subscribe'] = $this->request->post['subscribe'];
        } else {
            $this->data['subscribe'] = $this->data['simple_registration_subscribe_init'];
        }

        if (isset($this->request->post['password'])) {
            $this->data['password'] = trim($this->request->post['password']);
        } elseif ($this->data['simple_registration_generate_password']) {
            $eng = "qwertyuiopasdfghjklzxcvbnm1234567890";
            $min_length = $this->config->get('simple_registration_password_length_min');
            $this->data['password'] = '';
            while ($min_length) {
                $this->data['password'] .= $eng[rand(0, 35)];
                $min_length--;
            }
        } else {
            $this->data['password'] = '';
        }

        if (isset($this->request->post['password_confirm'])) {
            $this->data['password_confirm'] = trim($this->request->post['password_confirm']);
        } elseif ($this->data['simple_registration_generate_password']) {
            $this->data['password_confirm'] = $this->data['password'];
        } else {
            $this->data['password_confirm'] = '';
        }

        $this->data['email_confirm'] = isset($this->request->post['email_confirm']) ? trim($this->request->post['email_confirm']) : '';

        $this->data['language_code'] = $this->simple->get_language_code();

        $this->data['simple'] = $this->simple;

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/simpleregister.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/account/simpleregister.tpl';
            $this->data['template'] = $this->config->get('config_template');
        } else {
            $this->template = 'default/template/account/simpleregister.tpl';
            $this->data['template'] = 'default';
        }

        $this->simple->add_static($this->data['template'], 'simpleregister');

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

    public function captcha() {
        $this->load->library('captcha');

        $captcha = new Captcha();

        $this->session->data['captcha'] = $captcha->getCode();

        $captcha->showImage();
    }

    private function validate() {
        $error = false;

        if (!$this->simple->validate_fields(Simple::SET_REGISTRATION)) {
            $error = true;
        }

        $email = $this->simple->get_value(Simple::SET_REGISTRATION, 'email');
        if (!empty($email) && $this->model_account_customer->getTotalCustomersByEmail($email)) {
            $this->data['customer_fields']['main_email']['error'] = $this->language->get('error_exists');
            $error = true;
        }

        if ($this->config->get('simple_registration_view_email_confirm')) {
            $email_confirm = isset($this->request->post['email_confirm']) ? trim($this->request->post['email_confirm']) : '';
            if ($email != $email_confirm) {
                $this->error = true;
                $this->data['email_confirm_error'] = true;
            }
        }

        $password_length_min = $this->config->get('simple_registration_password_length_min');
        $password_length_min = !empty($password_length_min) ? $password_length_min : 4;

        $password_length_max = $this->config->get('simple_registration_password_length_max');
        $password_length_max = !empty($password_length_max) ? $password_length_max : 20;

        $password = !empty($this->request->post['password']) ? trim($this->request->post['password']) : '';
        $password_confirm = !empty($this->request->post['password_confirm']) ? trim($this->request->post['password_confirm']) : '';

        if (!$this->config->get('simple_registration_generate_password')) {
            if (strlen(utf8_decode($password)) < $password_length_min || strlen(utf8_decode($password)) > $password_length_max) {
                $this->data['error_password'] = sprintf($this->language->get('error_password'), $password_length_min, $password_length_max);
                $error = true;
            }

            if ($this->data['simple_registration_password_confirm'] && $password != $password_confirm) {
                $this->data['error_password_confirm'] = $this->language->get('error_password_confirm');
                $error = true;
            }
        }

        if ($this->config->get('simple_registration_captcha') && (empty($this->session->data['captcha']) || (isset($this->request->post['captcha']) && $this->session->data['captcha'] != $this->request->post['captcha']))) {
            $this->data['error_captcha'] = $this->language->get('error_captcha');
            $error = true;
        }

        if ($this->config->get('simple_registration_agreement_id')) {
            $this->load->model('catalog/information');

            $information_info = $this->model_catalog_information->getInformation($this->config->get('simple_registration_agreement_id'));

            if ($information_info) {
                $agreement_checkbox = $this->config->get('simple_registration_agreement_checkbox');
                if (!empty($agreement_checkbox) && empty($this->request->post['agree'])) {
                    $this->data['error_warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
                    $error = true;
                }
            }
        }

        return !$error;
    }

    public function geo() {
        $this->load->model('tool/simplegeo');

        $term = $this->request->get['term'];

        $this->response->setOutput(json_encode($this->model_tool_simplegeo->getGeoList($term)));
    }

}

?>