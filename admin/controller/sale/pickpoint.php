<?php
class ControllerSalePickpoint extends Controller {


			public function pickpoint() {

				$json = array();
		
				$this->load->language('sale/pickpoint');
				
				$this->load->model('sale/pickpoint');

			     	if (!$this->user->hasPermission('modify', 'sale/pickpoint')) {
					$json = array(
						'mes'              => $this->language->get('error_permission'),
						'status'            => false		
					);

				} elseif (isset($this->request->get['order_id'])) {

					$this->model_sale_pickpoint->setPickPointOrder($this->request->get['order_id'], $this->request->get['order_pickpoint_id']);
					
					$json = array(
						'mes'              => $this->language->get('text_ok'),
						'status'            => true		
					);

						
				}		

				$this->response->setOutput(json_encode($json));
				
			}

}
?>