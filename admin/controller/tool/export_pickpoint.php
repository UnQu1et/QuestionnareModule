<?php

class ControllerToolExportPickpoint extends Model {
	
	public function export() {
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
		

			// получить все заказы
			$this->load->model('sale/pickpoint');
			$this->load->model('sale/order');
			$this->load->model('setting/setting');

			$orders = $this->model_sale_pickpoint->getPickPointOrders();

			$xml = '<?xml version="1.0" encoding="UTF-8"?>';
			$xml .= "<documents>";

			$start_order = $this->request->post['export_from'];
			//$this->model_setting_setting->editSetting('pickpoint', array('pickpoint_start'=>$start_order));


			if ($start_order=="") $start_order=1;

			$last_order = "";
			foreach($orders->rows as $row)
			{
				
				if ($row['order_id']<$start_order) continue;

				$order_info = $this->model_sale_order->getOrder($row['order_id']);
				
				$products = $this->model_sale_order->getOrderProducts($row['order_id']);
				$desc="";
				foreach($products as $product)
				{
					if ($desc == "")
						$desc = $product['name'];
					else
						$desc .= "," . $product['name'];

				}

				$xml .= "<document>";
			    	$xml .= "<fio>".$order_info['shipping_firstname']." ". $order_info['shipping_lastname'] ."</fio>";
			    	$xml .= "<sms_phone>".$order_info['telephone']."</sms_phone>";
			    	$xml .= "<email>".$order_info['email']."</email>";
			    	$xml .= "<additional_phones/>";
			    	$xml .= "<order_id>".$row['order_id']."</order_id>";
			    	$xml .= "<summ_rub>".$order_info['total']."</summ_rub>";
			    	$xml .= "<terminal_id>".$row['terminal_id']."</terminal_id>";
			    	$xml .= "<type_service>STDCOD</type_service>";
			    	$xml .= "<type_reception>CUR</type_reception>";
			    	$xml .= "<embed>".$desc."</embed>";

			    	$xml .= "<size_x></size_x>";
			    	$xml .= "<size_y></size_y>";
			    	$xml .= "<size_z></size_z>";

				$xml .= "</document>";

				$last_order = $row['order_id'];
			}

			$xml .= "</documents>";

					
			if ($last_order!="") $this->model_setting_setting->editSetting('_pickpoint', array('pickpoint_start'=>$last_order+1));
					
			header('Content-Type: text/xml');
			header('Content-Disposition: attachment; filename=pickpoint_'.$start_order.'-'.$last_order.'.xml');
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: '.strlen($xml));
			echo $xml;

		}


	}


}
?>