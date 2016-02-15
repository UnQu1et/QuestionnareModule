<?php
class ControllerPaymentPickPoint2 extends Controller {
	protected function index() {
    		$this->data['button_confirm'] = $this->language->get('button_confirm');

	    	$this->language->load('payment/pickpoint2');

		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$this->data['continue'] = $this->url->link('checkout/success');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/pickpoint2.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/pickpoint2.tpl';
		} else {
			$this->template = 'default/template/payment/pickpoint2.tpl';
		}	
		
		$this->render();
	}
	
	public function confirm() {
		$this->load->model('checkout/order');		
		$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('pickpoint2_order_status_id'));
		//$this->load->model('payment/pickpoint2');
		//$this->model_payment_pickpoint2->setPickPointPhone($this->session->data['order_id'],$this->request->post);

	}
}
?>