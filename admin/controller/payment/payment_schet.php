<?php
class ControllerPaymentPaymentSchet extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/payment_schet');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_schet', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->data['token'] = $this->session->data['token'];
		
        $this->data['entry_inn'] = $this->language->get('entry_inn');
		$this->data['entry_kpp'] = $this->language->get('entry_kpp');
        $this->data['entry_rs'] = $this->language->get('entry_rs');
		$this->data['entry_bankuser'] = $this->language->get('entry_bankuser');
		$this->data['entry_bik'] = $this->language->get('entry_bik');
		$this->data['entry_ks'] = $this->language->get('entry_ks');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');	
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_podpis'] = $this->language->get('entry_podpis');		

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');

		$this->data['entry_bank'] = $this->language->get('entry_bank');
		$this->data['entry_uradres'] = $this->language->get('entry_uradres');
		$this->data['entry_faktadres'] = $this->language->get('entry_faktadres');	
		$this->data['entry_tel'] = $this->language->get('entry_tel');
		$this->data['entry_mobtel'] = $this->language->get('entry_mobtel');
		$this->data['entry_punkt'] = $this->language->get('entry_punkt');
		$this->data['entry_punkton'] = $this->language->get('entry_punkton');
		$this->data['entry_total'] = $this->language->get('entry_total');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		


		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			if (isset($this->error['bank'])) {
				$this->data['error_bank'] = $this->error['bank'];
			} else {
				$this->data['error_bank'] = '';
			}
			
			if (isset($this->error['uradres'])) {
				$this->data['error_uradres'] = $this->error['uradres'];
			} else {
				$this->data['error_uradres'] = '';
			}
			
			if (isset($this->error['faktadres'])) {
				$this->data['error_faktadres'] = $this->error['faktadres'];
			} else {
				$this->data['error_faktadres'] = '';
			}

			if (isset($this->error['inn'])) {
				$this->data['error_inn'] = $this->error['inn'];
			} else {
				$this->data['error_inn'] = '';
			}

			if (isset($this->error['rs'])) {
				$this->data['error_rs'] = $this->error['rs'];
			} else {
				$this->data['error_rs'] = '';
			}

			if (isset($this->error['bankuser'])) {
				$this->data['error_bankuser'] = $this->error['bankuser'];
			} else {
				$this->data['error_bankuser'] = '';
			}

			if (isset($this->error['bik'])) {
				$this->data['error_bik'] = $this->error['bik'];
			} else {
				$this->data['error_bik'] = '';
			}

			if (isset($this->error['ks'])) {
				$this->data['error_ks'] = $this->error['ks'];
			} else {
				$this->data['error_ks'] = '';
			}

		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/payment_schet', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['action'] = $this->url->link('payment/payment_schet', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		
			//Наименование поставщика
			if (isset($this->request->post['payment_schet_bank'])) {
				$this->data['payment_schet_bank'] = $this->request->post['payment_schet_bank'];
			} else {
				$this->data['payment_schet_bank'] = $this->config->get('payment_schet_bank');
				}
			//Юридический адрес поставщика
			if (isset($this->request->post['payment_schet_uradres'])) {
				$this->data['payment_schet_uradres'] = $this->request->post['payment_schet_uradres'];
			} else {
				$this->data['payment_schet_uradres'] = $this->config->get('payment_schet_uradres');
				}
			//Фактический адрес поставщика
			if (isset($this->request->post['payment_schet_faktadres'])) {
				$this->data['payment_schet_faktadres'] = $this->request->post['payment_schet_faktadres'];
			} else {
				$this->data['payment_schet_faktadres'] = $this->config->get('payment_schet_faktadres');
				}
			//ИНН
			if (isset($this->request->post['payment_schet_inn'])) {
				$this->data['payment_schet_inn'] = $this->request->post['payment_schet_inn'];
			} else {
				$this->data['payment_schet_inn'] = $this->config->get('payment_schet_inn');
			}
			//КПП
			if (isset($this->request->post['payment_schet_kpp'])) {
				$this->data['payment_schet_kpp'] = $this->request->post['payment_schet_kpp'];
			} else {
				$this->data['payment_schet_kpp'] = $this->config->get('payment_schet_kpp');
			}
			//Расчетный счет
			if (isset($this->request->post['payment_schet_rs'])) {
				$this->data['payment_schet_rs'] = $this->request->post['payment_schet_rs'];
			} else {
				$this->data['payment_schet_rs'] = $this->config->get('payment_schet_rs');
			}
			//Наименование банка получателя платежа
			if (isset($this->request->post['payment_schet_bankuser'])) {
				$this->data['payment_schet_bankuser'] = $this->request->post['payment_schet_bankuser'];
			} else {
				$this->data['payment_schet_bankuser'] = $this->config->get('payment_schet_bankuser');
			}
			//БИК
			if (isset($this->request->post['payment_schet_bik'])) {
				$this->data['payment_schet_bik'] = $this->request->post['payment_schet_bik'];
			} else {
				$this->data['payment_schet_bik'] = $this->config->get('payment_schet_bik');
			}
			//Номер кор./сч. банка получателя платежа
			if (isset($this->request->post['payment_schet_ks'])) {
				$this->data['payment_schet_ks'] = $this->request->post['payment_schet_ks'];
			} else {
				$this->data['payment_schet_ks'] = $this->config->get('payment_schet_ks');
			}
			//Телефон / факс поставщика
			if (isset($this->request->post['payment_schet_tel'])) {
				$this->data['payment_schet_tel'] = $this->request->post['payment_schet_tel'];
			} else {
				$this->data['payment_schet_tel'] = $this->config->get('payment_schet_tel');
			}
			//Мобильный телефон поставщика
			if (isset($this->request->post['payment_schet_mobtel'])) {
				$this->data['payment_schet_mobtel'] = $this->request->post['payment_schet_mobtel'];
			} else {
				$this->data['payment_schet_mobtel'] = $this->config->get('payment_schet_mobtel');
			}
			//Пункты договора
			if (isset($this->request->post['payment_schet_punkt'])) {
				$this->data['payment_schet_punkt'] = $this->request->post['payment_schet_punkt'];
			} else {
				$this->data['payment_schet_punkt'] = $this->config->get('payment_schet_punkt');
			}
			
			if (isset($this->request->post['config_punkton'])) {
			$this->data['config_punkton'] = $this->request->post['config_punkton'];
			} else {
				$this->data['config_punkton'] = $this->config->get('config_punkton');
			}	

			if (isset($this->request->post['payment_schet_image'])) {
				$this->data['payment_schet_image'] = $this->request->post['payment_schet_image'];
			} else {
				$this->data['payment_schet_image'] = $this->config->get('payment_schet_image');
			}
						
			if (isset($this->request->post['payment_schet_podpis'])) {
				$this->data['payment_schet_podpis'] = $this->request->post['payment_schet_podpis'];
			} else {
				$this->data['payment_schet_podpis'] = $this->config->get('payment_schet_podpis');
			}
			
		if (isset($this->request->post['payment_schet_total'])) {
			$this->data['payment_schet_total'] = $this->request->post['payment_schet_total'];
		} else {
			$this->data['payment_schet_total'] = $this->config->get('payment_schet_total');
		}

		if (isset($this->request->post['payment_schet_order_status_id'])) {
			$this->data['payment_schet_order_status_id'] = $this->request->post['payment_schet_order_status_id'];
		} else {
			$this->data['payment_schet_order_status_id'] = $this->config->get('payment_schet_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_schet_geo_zone_id'])) {
			$this->data['payment_schet_geo_zone_id'] = $this->request->post['payment_schet_geo_zone_id'];
		} else {
			$this->data['payment_schet_geo_zone_id'] = $this->config->get('payment_schet_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_schet_status'])) {
			$this->data['payment_schet_status'] = $this->request->post['payment_schet_status'];
		} else {
			$this->data['payment_schet_status'] = $this->config->get('payment_schet_status');
		}

		if (isset($this->request->post['payment_schet_sort_order'])) {
			$this->data['payment_schet_sort_order'] = $this->request->post['payment_schet_sort_order'];
		} else {
			$this->data['payment_schet_sort_order'] = $this->config->get('payment_schet_sort_order');
		}


		$this->template = 'payment/payment_schet.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);

		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/payment_schet')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

			if (!$this->request->post['payment_schet_bank']) {
				$this->error['bank'] = $this->language->get('error_bank');
			}
			if (!$this->request->post['payment_schet_faktadres']) {
				$this->error['faktadres'] = $this->language->get('error_bank');
			}
			if (!$this->request->post['payment_schet_uradres']) {
				$this->error['uradres'] = $this->language->get('error_bank');
			}
			if (!$this->request->post['payment_schet_inn']) {
				$this->error['inn'] = $this->language->get('error_bank');
			}
			if (!$this->request->post['payment_schet_rs']) {
				$this->error['rs'] = $this->language->get('error_bank');
			}
			if (!$this->request->post['payment_schet_bankuser']) {
				$this->error['bankuser'] = $this->language->get('error_bank');
			}
			if (!$this->request->post['payment_schet_bik']) {
				$this->error['bik'] = $this->language->get('error_bank');
			}
			if (!$this->request->post['payment_schet_ks']) {
				$this->error['ks'] = $this->language->get('error_bank');
			}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>