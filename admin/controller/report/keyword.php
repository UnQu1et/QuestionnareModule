<?php
class ControllerReportKeyword extends Controller {

	public function index() {   
		$this->load->language('report/keyword');

		$this->document->setTitle($this->language->get('heading_title'));
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'time';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		
		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		
		$this->data['sort_date'] = $this->url->link('report/keyword', 'token=' . $this->session->data['token'] . '&sort=time' . $url, 'SSL');
		$this->data['sort_name'] = $this->url->link('report/keyword', 'token=' . $this->session->data['token'] . '&sort=result' . $url, 'SSL');
		$this->data['sort_manufacturer'] = $this->url->link('report/keyword', 'token=' . $this->session->data['token'] . '&sort=manufacturer' . $url, 'SSL');
		$this->data['sort_quantity'] = $this->url->link('report/keyword', 'token=' . $this->session->data['token'] . '&sort=quantity' . $url, 'SSL');
		$this->data['sort_term'] = $this->url->link('report/keyword', 'token=' . $this->session->data['token'] . '&sort=term' . $url, 'SSL');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('report/keyword', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);		
		
		$this->load->model('report/keyword');
		
		$this->data['action_export'] = $this->url->link('report/keyword/download', 'token=' . $this->session->data['token'], 'SSL');
		
		$data = array(
			'filter_order_id'        => $filter_order_id,
			'filter_customer'	     => $filter_customer,
			'filter_order_status_id' => $filter_order_status_id,
			'filter_total'           => $filter_total,
			'filter_date_added'      => $filter_date_added,
			'filter_date_modified'   => $filter_date_modified,
			'sort'                   => $sort,
			'order'                  => $order,
			'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);
		
		$product_total = $this->model_report_keyword->getTotalSearchedWords($data);
		
		$this->data['products'] = array();		

		$results = $this->model_report_keyword->getSearchedWordsReport($data);
		
		foreach ($results as $result) {
		
			$this->data['products'][] = array(
				'report_search_id'     => $result['report_search_id'],
				'manufacturer'     => $result['manufacturer'],
				'quantity'     => $result['quantity'],
				'name'     => $result['term'],
				'model'    => $result['time'],
				'result' => $result['result'],
				'total'    => $result['ipadd']
			);
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['button_export'] = $this->language->get('button_export');
		
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_user'] = $this->language->get('column_user');
		$this->data['column_manufacturer'] = $this->language->get('column_manufacturer');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_results'] = $this->language->get('column_results');
		$this->data['column_total'] = $this->language->get('column_total');
		
		//$this->data['button_reset'] = $this->language->get('button_reset');

		//$this->data['reset'] = HTTPS_SERVER . 'index.php?route=report/keyword/reset&token=' . $this->session->data['token'] . $url;
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=report/keyword/delete&token=' . $this->session->data['token'] . $url;
		
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'index.php?route=report/keyword&token=' . $this->session->data['token'] . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();		
		
		$this->template = 'report/keyword.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	public function download() {
	
		$this->load->model('report/keyword');
	
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			
			$config['csv_export']['file_format'] = 'csv';
			
			
			$price_export = 'report_history_export_'. $config['csv_export']['limit_start'].'-'. $config['csv_export']['limit_end'] . '_' . (string)(date('Y-m-d-Hi')) . '.' . $config['csv_export']['file_format'];

			/*// Remove old file
			$tmp = $this->model_tool_csvprice_pro->get_tmp_dir();
			
			if(file_exists($tmp . '/' . $price_export)) {
				unlink($tmp . '/' . $price_export);
			}*/
			
			$output = $this->model_report_keyword->export($data);
			
			if( is_array($output) AND isset($output['error']) ) {
				$this->load->language('module/csvprice_pro');
				$this->session->data['error'] = $this->language->get($output['error']);
				$this->redirect($this->url->link('module/csvprice_pro', 'token=' . $this->session->data['token'], 'SSL'));
			}
			
			if($config['csv_export']['gzcompress']) {
				$output = gzcompress($output);
			}

			$this->response->addheader('Pragma: public');
			$this->response->addheader('Connection: Keep-Alive');
			$this->response->addheader('Expires: 0');
			$this->response->addheader('Content-Description: File Transfer');
			$this->response->addheader('Content-Type: application/octet-stream');
			if($config['csv_export']['gzcompress']) {
				$this->response->addheader('Content-Encoding: gzip');
			}
			$this->response->addheader('Content-Disposition: attachment; filename='.$price_export);
			$this->response->addheader('Content-Transfer-Encoding: binary');
			$this->response->addheader('Content-Length: '. strlen($output));
			$this->response->setOutput($output);

		} else {
			return $this->forward('error/permission');
		}
	}
	
	public function delete() {
	
		if (isset($this->request->post['selected'])) {
		
		$this->load->language('report/keyword');
		
		$this->load->model('report/keyword');
		
		$this->model_report_keyword->delete($this->request->post['selected']);
		
		$this->session->data['success'] = $this->language->get('text_success');
		
		$url = '';
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->redirect(HTTPS_SERVER . 'index.php?route=report/keyword&token=' . $this->session->data['token'] . $url);
		}
	}
	
	public function reset() {
		/*
		$this->load->language('report/keyword');
		
		$this->load->model('report/keyword');
		
		$this->model_report_keyword->reset();
		
		$this->session->data['success'] = $this->language->get('text_success');
		
		$url = '';
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->redirect(HTTPS_SERVER . 'index.php?route=report/keyword&token=' . $this->session->data['token'] . $url);
		*/
	}
	
}
?>