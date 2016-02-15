<?php
class ControllerRecordRecord extends Controller
{
	private $error = array();
	private $code;
	public function index()
	{
		$this->data['blog_version'] = '';
		$this->load->model('setting/setting');
		$settings_admin = $this->model_setting_setting->getSetting('blogversion', 'blog_version');
		foreach ($settings_admin as $key => $value) {
			$this->data['blog_version'] = $value;
		} //$settings_admin as $key => $value
		if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/blog.css')) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/blog.css');
		} //file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/blog.css')
		else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/blog.css');
		}
		$this->document->addScript('catalog/view/javascript/jquery/tabs.js');
		$this->document->addScript('catalog/view/javascript/blog/blog.comment.js');
		$this->language->load('record/record');
		$this->data['breadcrumbs']   = array();
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home'),
			'separator' => false
		);
		if ($this->config->get('generallist') != '') {
			$this->data['settings_general'] = $this->config->get('generallist');
		} //$this->config->get('generallist') != ''
		else {
			$this->data['settings_general'] = Array();
		}
		if (!isset($this->data['settings_general']['colorbox_theme'])) {
			$this->data['settings_general']['colorbox_theme'] = 0;
		} //!isset($this->data['settings_general']['colorbox_theme'])
		$scripts       = $this->document->getScripts();
		$colorbox_flag = false;
		foreach ($scripts as $num => $val) {
			if (utf8_strpos($val, 'colorbox') !== FALSE) {
				$colorbox_flag = true;
			} //utf8_strpos($val, 'colorbox') !== FALSE
		} //$scripts as $num => $val
		if (!$colorbox_flag) {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {
				$product_file = file_get_contents(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl');
			} //file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')
			else {
				if (file_exists(DIR_TEMPLATE . 'default/template/common/header.tpl')) {
					$product_file = file_get_contents(DIR_TEMPLATE . 'default/template/common/header.tpl');
				} //file_exists(DIR_TEMPLATE . 'default/template/common/header.tpl')
				else {
					$product_file = "";
				}
			}
			$findme = 'colorbox';
			$pos    = strpos($product_file, $findme);
			if ($pos !== false) {
				$colorbox_flag = true;
			} //$pos !== false
		} //!$colorbox_flag
		if (!$colorbox_flag) {
			$this->document->addScript('catalog/view/javascript/blog/colorbox/jquery.colorbox.js');
			$this->document->addScript('catalog/view/javascript/blog/colorbox/lang/jquery.colorbox-' . $this->config->get('config_language') . '.js');
			$this->document->addStyle('catalog/view/javascript/blog/colorbox/css/' . $this->data['settings_general']['colorbox_theme'] . '/colorbox.css');
		} //!$colorbox_flag
		$this->data['imagebox'] = 'colorbox';
		if ($this->data['imagebox'] == 'colorbox') {
			$this->document->addScript('catalog/view/javascript/blog/blog.color.js');
		} //$this->data['imagebox'] == 'colorbox'
		$array_dir_image = str_split(DIR_IMAGE);
		$array_dir_app   = str_split(DIR_APPLICATION);
		$i               = 0;
		$dir_root        = '';
		while ($array_dir_image[$i] == $array_dir_app[$i]) {
			$dir_root .= $array_dir_image[$i];
			$i++;
		} //$array_dir_image[$i] == $array_dir_app[$i]
		$dir_image = str_replace($dir_root, '', DIR_IMAGE);
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$http_image = HTTPS_SERVER . $dir_image;
		} //isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))
		else {
			$http_image = HTTP_SERVER . $dir_image;
		}
		$this->load->model('catalog/blog');
		if (isset($this->request->get['record_id'])) {
			$record_id                     = $this->request->get['record_id'];
			$blog_info                     = $this->model_catalog_blog->getPathByrecord($record_id);
			$this->request->get['blog_id'] = $blog_info['path'];
		} //isset($this->request->get['record_id'])
		else {
			$record_id = false;
		}
		if (isset($blog_info['path'])) {
			$path = '';
			foreach (explode('_', $blog_info['path']) as $path_id) {
				$blog_id = $path_id;
			} //explode('_', $blog_info['path']) as $path_id
		} //isset($blog_info['path'])
		else {
			$blog_id = 0;
		}
		if (isset($this->request->get['blog_id'])) {
			$path = '';
			foreach (explode('_', $this->request->get['blog_id']) as $path_id) {
				if (!$path) {
					$path = $path_id;
				} //!$path
				else {
					$path .= '_' . $path_id;
				}
				$path_id . "->" . $this->request->get['blog_id'];
				$blog_info = $this->model_catalog_blog->getBlog($path_id);
				if ($blog_info) {
					$this->data['breadcrumbs'][] = array(
						'text' => $blog_info['name'],
						'href' => $this->url->link('record/blog', 'blog_id=' . $path),
						'separator' => $this->language->get('text_separator')
					);
				} //$blog_info
			} //explode('_', $this->request->get['blog_id']) as $path_id
		} //isset($this->request->get['blog_id'])
		else {
			$path = '';
		}
		$sort_data = array(
			'rating',
			'comments',
			'popular',
			'latest',
			'sort'
		);
		if (isset($blog_info['design']) && $blog_info['design'] != '') {
			$this->data['blog_design'] = unserialize($blog_info['design']);
		} //isset($blog_info['design']) && $blog_info['design'] != ''
		else {
			$this->data['blog_design'] = Array();
		}
		$sort = 'p.sort_order';
		if (isset($this->data['blog_design']['order']) && in_array($this->data['blog_design']['order'], $sort_data)) {
			if ($this->data['blog_design']['order'] == 'rating') {
				$sort = 'rating';
			} //$this->data['blog_design']['order'] == 'rating'
			if ($this->data['blog_design']['order'] == 'comments') {
				$sort = 'comments';
			} //$this->data['blog_design']['order'] == 'comments'
			if ($this->data['blog_design']['order'] == 'latest') {
				$sort = 'p.date_available';
			} //$this->data['blog_design']['order'] == 'latest'
			if ($this->data['blog_design']['order'] == 'sort') {
				$sort = 'p.sort_order';
			} //$this->data['blog_design']['order'] == 'sort'
			if ($this->data['blog_design']['order'] == 'popular') {
				$sort = 'p.viewed';
			} //$this->data['blog_design']['order'] == 'popular'
		} //isset($this->data['blog_design']['order']) && in_array($this->data['blog_design']['order'], $sort_data)
		$order = 'DESC';
		if (isset($this->data['blog_design']['order_ad'])) {
			if (strtoupper($this->data['blog_design']['order_ad']) == 'ASC') {
				$order = 'ASC';
			} //strtoupper($this->data['blog_design']['order_ad']) == 'ASC'
			if (strtoupper($this->data['blog_design']['order']) == 'DESC') {
				$order = 'DESC';
			} //strtoupper($this->data['blog_design']['order']) == 'DESC'
		} //isset($this->data['blog_design']['order_ad'])
		$this->load->model('catalog/record');
		$data                = array(
			'filter_blog_id' => $blog_id,
			'sort' => $sort,
			'order' => $order
		);
		$result_blog_records = $this->model_catalog_record->getRecords($data);
		if ($result_blog_records) {
			$previousKey                           = false;
			$nextKey                               = false;
			$next_flag                             = false;
			$this->data['record_previous']['url']  = '';
			$this->data['record_previous']['name'] = '';
			$this->data['record_next']['url']      = '';
			$this->data['record_next']['name']     = '';
			foreach ($result_blog_records as $num => $rec) {
				if ($next_flag) {
					$this->data['record_next']['url']  = $this->url->link('record/record', '&record_id=' . $result_blog_records[$num]['record_id']);
					$this->data['record_next']['name'] = $result_blog_records[$num]['name'];
					$next_flag                         = false;
				} //$next_flag
				if ($rec['record_id'] == $record_id) {
					$next_flag = true;
					if ($previousKey) {
						if (isset($result_blog_records[$previousKey])) {
							$this->data['record_previous']['url']  = $this->url->link('record/record', '&record_id=' . $result_blog_records[$previousKey]['record_id']);
							$this->data['record_previous']['name'] = $result_blog_records[$previousKey]['name'];
						} //isset($result_blog_records[$previousKey])
					} //$previousKey
				} //$rec['record_id'] == $record_id
				$previousKey = $num;
			} //$result_blog_records as $num => $rec
		} //$result_blog_records
		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_tag'])) {
			$url = '';
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			} //isset($this->request->get['filter_name'])
			if (isset($this->request->get['filter_tag'])) {
				$url .= '&filter_tag=' . $this->request->get['filter_tag'];
			} //isset($this->request->get['filter_tag'])
			if (isset($this->request->get['filter_description'])) {
				$url .= '&filter_description=' . $this->request->get['filter_description'];
			} //isset($this->request->get['filter_description'])
			if (isset($this->request->get['filter_blog_id'])) {
				$url .= '&filter_blog_id=' . $this->request->get['filter_blog_id'];
			} //isset($this->request->get['filter_blog_id'])
			$this->data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_search'),
				'href' => $this->url->link('record/search', $url),
				'separator' => $this->language->get('text_separator')
			);
		} //isset($this->request->get['filter_name']) || isset($this->request->get['filter_tag'])
		if (isset($this->request->get['record_id'])) {
			$record_id = $this->request->get['record_id'];
		} //isset($this->request->get['record_id'])
		else {
			$record_id = 0;
		}
		$this->load->model('catalog/record');
		$record_info = $this->model_catalog_record->getRecord($record_id);
		if ($record_info) {
			$url = '';
			if (isset($this->request->get['blog_id'])) {
				$url .= '&blog_id=' . $this->request->get['blog_id'];
			} //isset($this->request->get['blog_id'])
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			} //isset($this->request->get['filter_name'])
			if (isset($this->request->get['filter_tag'])) {
				$url .= '&filter_tag=' . $this->request->get['filter_tag'];
			} //isset($this->request->get['filter_tag'])
			if (isset($this->request->get['filter_description'])) {
				$url .= '&filter_description=' . $this->request->get['filter_description'];
			} //isset($this->request->get['filter_description'])
			if (isset($this->request->get['filter_blog_id'])) {
				$url .= '&filter_blog_id=' . $this->request->get['filter_blog_id'];
			} //isset($this->request->get['filter_blog_id'])
			$this->data['breadcrumbs'][] = array(
				'text' => $record_info['name'],
				'href' => $this->url->link('record/record', '&record_id=' . $this->request->get['record_id']),
				'separator' => $this->language->get('text_separator')
			);






			if (isset($record_info['meta_title']) && $record_info['meta_title']!='') {
			 $this->document->setTitle($record_info['meta_title']);
			} else {
			  $this->document->setTitle($record_info['name']);
			}

			if (isset($record_info['meta_h1']) && $record_info['meta_h1']!='') {
			 $this->data['heading_title']   = $record_info['meta_h1'];
			} else {
			  $this->data['heading_title']   = $record_info['name'];
			}


			$this->data['name']   = $record_info['name'];

			$this->document->setDescription($record_info['meta_description']);
			$this->document->setKeywords($record_info['meta_keyword']);
			//$this->document->addLink($this->url->link('record/record', 'record_id=' . $this->request->get['record_id']), 'canonical');

			if ($record_info['image']) {
				$this->data['popup'] = $http_image . $record_info['image'];
			} //$record_info['image']
			$this->load->model('catalog/comment');
			$this->data['text_welcome']         = sprintf($this->language->get('text_welcome'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'));
			$this->data['text_select']          = $this->language->get('text_select');
			$this->data['text_model']           = $this->language->get('text_model');
			$this->data['text_reward']          = $this->language->get('text_reward');
			$this->data['text_points']          = $this->language->get('text_points');
			$this->data['text_stock']           = $this->language->get('text_stock');
			$this->data['text_price']           = $this->language->get('text_price');
			$this->data['text_tax']             = $this->language->get('text_tax');
			$this->data['text_option']          = $this->language->get('text_option');
			$this->data['text_qty']             = $this->language->get('text_qty');
			$this->data['text_or']              = $this->language->get('text_or');
			$this->data['text_write']           = $this->language->get('text_write');
			$this->data['text_note']            = $this->language->get('text_note');
			$this->data['text_share']           = $this->language->get('text_share');
			$this->data['text_wait']            = $this->language->get('text_wait');
			$this->data['text_tags']            = $this->language->get('text_tags');
			$this->data['text_viewed']          = $this->language->get('text_viewed');
			$this->data['entry_name']           = $this->language->get('entry_name');
			$this->data['entry_comment']        = $this->language->get('entry_comment');
			$this->data['entry_rating']         = $this->language->get('entry_rating');
			$this->data['entry_good']           = $this->language->get('entry_good');
			$this->data['entry_bad']            = $this->language->get('entry_bad');
			$this->data['entry_captcha']        = $this->language->get('entry_captcha');
			$this->data['entry_captcha_title']  = $this->language->get('entry_captcha_title');
			$this->data['entry_captcha_update'] = $this->language->get('entry_captcha_update');
			$this->data['button_cart']          = $this->language->get('button_cart');
			$this->data['button_wishlist']      = $this->language->get('button_wishlist');
			$this->data['button_compare']       = $this->language->get('button_compare');
			$this->data['button_upload']        = $this->language->get('button_upload');
			$this->data['button_write']         = $this->language->get('button_write');
			$this->data['tab_description']      = $this->language->get('tab_description');
			$this->data['tab_attribute']        = $this->language->get('tab_attribute');
			$this->data['tab_advertising']      = $this->language->get('tab_advertising');
			$this->data['comment_count']        = $this->model_catalog_comment->getTotalCommentsByRecordId($this->request->get['record_id']);
			$this->data['tab_comment']          = $this->language->get('tab_comment');
			$this->data['tab_images']           = $this->language->get('tab_images');
			$this->data['tab_related']          = $this->language->get('tab_related');
			$this->data['tab_product_related']  = $this->language->get('tab_product_related');
			$this->data['record_id']            = $this->request->get['record_id'];
			$this->data['model']                = $record_info['model'];
			$this->data['reward']               = $record_info['reward'];
			$this->data['points']               = $record_info['points'];
			$this->load->model('tool/image');
			if ($record_info['image']) {
				$this->data['popup'] = $http_image . $record_info['image'];
			} //$record_info['image']
			else {
				$this->data['popup'] = '';
			}
			if ($record_info['image']) {
				$this->data['thumb'] = $this->model_tool_image->resize($record_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
			} //$record_info['image']
			else {
				$this->data['thumb'] = '';
			}
			$this->data['images'] = array();

			if (!isset($this->data['blog_design']['images'])) $this->data['blog_design']['images']=array();
			$this->data['images'] = $this->getRecordImages($this->request->get['record_id'], $this->data['blog_design']['images']);

			/*
			$results              = $this->model_catalog_record->getRecordImages($this->request->get['record_id']);

			foreach ($results as $result) {
				$this->data['images'][] = array(
					'popup' => $http_image . $result['image'],
					'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'))
				);
			}
			*/
			//$results as $result
			$this->data['options'] = array();
			foreach ($this->model_catalog_record->getRecordOptions($this->request->get['record_id']) as $option) {
				if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {
					$option_value_data = array();
					foreach ($option['option_value'] as $option_value) {
						if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
							$option_value_data[] = array(
								'record_option_value_id' => $option_value['record_option_value_id'],
								'option_value_id' => $option_value['option_value_id'],
								'name' => $option_value['name'],
								'image' => $this->model_tool_image->resize($option_value['image'], 50, 50),
								'price' => (float) $option_value['price'] ? $this->currency->format($this->tax->calculate($option_value['price'], $record_info['tax_class_id'], $this->config->get('config_tax'))) : false,
								'price_prefix' => $option_value['price_prefix']
							);
						} //!$option_value['subtract'] || ($option_value['quantity'] > 0)
					} //$option['option_value'] as $option_value
					$this->data['options'][] = array(
						'record_option_id' => $option['record_option_id'],
						'option_id' => $option['option_id'],
						'name' => $option['name'],
						'type' => $option['type'],
						'option_value' => $option_value_data,
						'required' => $option['required']
					);
				} //$option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image'
				elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
					$this->data['options'][] = array(
						'record_option_id' => $option['record_option_id'],
						'option_id' => $option['option_id'],
						'name' => $option['name'],
						'type' => $option['type'],
						'option_value' => $option['option_value'],
						'required' => $option['required']
					);
				} //$option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time'
			} //$this->model_catalog_record->getRecordOptions($this->request->get['record_id']) as $option
			$this->data['href']           = $this->url->link('record/record', 'record_id=' . $this->request->get['record_id']);
			$this->data['text_comments']  = sprintf($this->language->get('text_comments'), (int) $record_info['comments']);
			$this->data['comments']       = (int) $record_info['comments'];
			$record_comment               = unserialize($record_info['comment']);
			$this->data['record_comment'] = $record_comment;
			$this->data['comment_status'] = $record_comment['status'];
			if ($this->customer->isLogged()) {
				$this->data['text_login']     = $this->customer->getFirstName() . " " . $this->customer->getLastName();
				$this->data['captcha_status'] = false;
				$this->data['customer_id']    = $this->customer->getId();
			} //$this->customer->isLogged()
			else {
				$this->data['text_login']     = $this->language->get('text_anonymus');
				$this->data['captcha_status'] = true;
				$this->data['customer_id']    = false;
				$this->data['signer_code']    = 'customer_id';
				$this->language->load('account/login');
				$this->data['text_new_customer']            = $this->language->get('text_new_customer');
				$this->data['text_register']                = $this->language->get('text_register');
				$this->data['text_register_account']        = $this->language->get('text_register_account');
				$this->data['text_returning_customer']      = $this->language->get('text_returning_customer');
				$this->data['text_i_am_returning_customer'] = $this->language->get('text_i_am_returning_customer');
				$this->data['text_forgotten']               = $this->language->get('text_forgotten');
				$this->data['entry_email']                  = $this->language->get('entry_email');
				$this->data['entry_password']               = $this->language->get('entry_password');
				$this->data['button_continue']              = $this->language->get('button_continue');
				$this->data['button_login']                 = $this->language->get('button_login');
				if (isset($this->error['warning'])) {
					$this->data['error_warning'] = $this->error['warning'];
				} //isset($this->error['warning'])
				else {
					$this->data['error_warning'] = '';
				}
				if (isset($this->session->data['success'])) {
					$this->data['success'] = $this->session->data['success'];
					unset($this->session->data['success']);
				} //isset($this->session->data['success'])
				else {
					$this->data['success'] = '';
				}
				if (isset($this->request->post['email'])) {
					$this->data['email'] = $this->request->post['email'];
				} //isset($this->request->post['email'])
				else {
					$this->data['email'] = '';
				}
				if (isset($this->request->post['password'])) {
					$this->data['password'] = $this->request->post['password'];
				} //isset($this->request->post['password'])
				else {
					$this->data['password'] = '';
				}
				$this->data['action']    = $this->url->link('account/login', '', 'SSL');
				$this->data['register']  = $this->url->link('account/register', '', 'SSL');
				$this->data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');
				if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
					$this->data['redirect'] = $this->request->post['redirect'];
				} //isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)
				elseif (isset($this->session->data['redirect'])) {
					$this->data['redirect'] = $this->session->data['redirect'];
					unset($this->session->data['redirect']);
				} //isset($this->session->data['redirect'])
				else {
					$this->data['redirect'] = $this->data['href'];
				}
			}
			$this->language->load('agoo/signer/signer');
			if ($this->request->get['record_id'] && isset($this->data['customer_id'])) {
				$this->load->model('agoo/signer/signer');
				$this->data['signer_status'] = $this->model_agoo_signer_signer->getStatus($this->request->get['record_id'], $this->data['customer_id'], 'record_id');
			} //$this->request->get['record_id'] && isset($this->data['customer_id'])
			$this->data['viewed'] = $record_info['viewed'];
			if (!isset($record_info['date_available']))
				$record_info['date_available'] = $record_info['date_added'];
			if ($this->rdate($this->language->get('text_date')) == $this->rdate($this->language->get('text_date'), strtotime($record_info['date_available']))) {
				$date_str = $this->language->get('text_today');
			} //$this->rdate($this->language->get('text_date')) == $this->rdate($this->language->get('text_date'), strtotime($record_info['date_available']))
			else {
				$date_str = $this->language->get('text_date');
			}
			if (!isset($this->data['blog_design']['view_date'])) {
				$this->data['blog_design']['view_date'] = 1;
			} //!isset($this->data['blog_design']['view_date'])
			if (!isset($this->data['blog_design']['view_share'])) {
				$this->data['blog_design']['view_share'] = 1;
			} //!isset($this->data['blog_design']['view_share'])
			if (!isset($this->data['blog_design']['view_viewed'])) {
				$this->data['blog_design']['view_viewed'] = 1;
			} //!isset($this->data['blog_design']['view_viewed'])
			if (!isset($this->data['blog_design']['view_rating'])) {
				$this->data['blog_design']['view_rating'] = 1;
			} //!isset($this->data['blog_design']['view_rating'])
			if (!isset($this->data['blog_design']['view_comments'])) {
				$this->data['blog_design']['view_comments'] = 1;
			} //!isset($this->data['blog_design']['view_comments'])
			if (isset($this->data['blog_design']['view_captcha']) && !$this->data['blog_design']['view_captcha']) {
				$this->data['captcha_status'] = false;
			} //isset($this->data['blog_design']['view_captcha']) && !$this->data['blog_design']['view_captcha']
			$this->data['settings_blog']    = $this->data['blog_design'];
			$date_added                     = $this->rdate($date_str . $this->language->get('text_hours'), strtotime($record_info['date_available']));
			$this->data['date_added']       = $date_added;
			$this->data['rating']           = (int) $record_info['rating'];
			$this->data['description']      = html_entity_decode($record_info['description'], ENT_QUOTES, 'UTF-8');
			$this->data['attribute_groups'] = $this->model_catalog_record->getRecordAttributes($this->request->get['record_id']);
			$this->data['products']         = array();
			$results                        = $this->model_catalog_record->getProductRelated($this->request->get['record_id'], 'product_id');



			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
				} //$result['image']
				else {
					$image = false;
				}
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				} //($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')
				else {
					$price = false;
				}
				if ((float) $result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				} //(float) $result['special']
				else {
					$special = false;
				}
				if ($this->config->get('config_review_status')) {
					$rating = (int) $result['rating'];
				} //$this->config->get('config_review_status')
				else {
					$rating = false;
				}
				$this->data['products'][] = array(
					'product_id' => $result['product_id'],
					'thumb' => $image,
					'name' => $result['name'],
					'price' => $price,
					'special' => $special,
					'rating' => $rating,
					'reviews' => sprintf($this->language->get('text_reviews'), (int) $result['reviews']),
					'href' => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			} //$results as $result
			$this->data['records'] = array();
			$results               = $this->model_catalog_record->getRecordRelated($this->request->get['record_id']);
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
				} //$result['image']
				else {
					$image = false;
				}
				if ($result['comment']) {
					$rating = (int) $result['rating'];
				} //$result['comment']
				else {
					$rating = false;
				}
				$record_comment_info     = unserialize($result['comment']);
				$this->data['records'][] = array(
					'record_id' => $result['record_id'],
					'thumb' => $image,
					'name' => $result['name'],
					'viewed' => $result['viewed'],
					'rating' => $rating,
					'comment_status' => $record_comment_info['status'],
					'comments' => sprintf($this->language->get('text_comments'), (int) $result['comments']),
					'href' => $this->url->link('record/record', 'record_id=' . $result['record_id'])
				);
			} //$results as $result
			$this->data['tags'] = array();
			$results            = $this->model_catalog_record->getRecordTags($this->request->get['record_id']);
			foreach ($results as $result) {
				$this->data['tags'][] = array(
					'tag' => trim($result['tag']),
					'href' => $this->url->link('record/search', 'filter_tag=' . $result['tag'])
				);
			} //$results as $result
			$this->model_catalog_record->updateViewed($this->request->get['record_id']);
			if (isset($this->data['blog_design']['blog_template_record']) && $this->data['blog_design']['blog_template_record'] != '') {
				$template = $this->data['blog_design']['blog_template_record'];
			} //isset($this->data['blog_design']['blog_template_record']) && $this->data['blog_design']['blog_template_record'] != ''
			else {
				$template = 'record.tpl';
			}

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/agoodonut/record/' . $template)) {
				$this->template = $this->config->get('config_template') . '/template/agoodonut/record/' . $template;
			} //file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/record/' . $template)
			else {
				if (file_exists(DIR_TEMPLATE . 'default/template/agoodonut/record/' . $template)) {
					$this->template = 'default/template/agoodonut/record/' . $template;
				} //file_exists(DIR_TEMPLATE . 'default/template/record/' . $template)
				else {
					$this->template = 'default/template/agoodonut/record/record.tpl';
				}
			}
			$this->children      = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);
			$this->data['theme'] = $this->config->get('config_template');
			$html_record         = $this->render();
			$this->response->setOutput($html_record);
		} //$record_info
		else {
			$url = '';
			if (isset($this->request->get['blog_id'])) {
			} //isset($this->request->get['blog_id'])
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			} //isset($this->request->get['filter_name'])
			if (isset($this->request->get['filter_tag'])) {
				$url .= '&filter_tag=' . $this->request->get['filter_tag'];
			} //isset($this->request->get['filter_tag'])
			if (isset($this->request->get['filter_description'])) {
				$url .= '&filter_description=' . $this->request->get['filter_description'];
			} //isset($this->request->get['filter_description'])
			if (isset($this->request->get['filter_blog_id'])) {
				$url .= '&filter_blog_id=' . $this->request->get['filter_blog_id'];
			} //isset($this->request->get['filter_blog_id'])
			$this->data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('record/record', $url . '&record_id=' . $record_id),
				'separator' => $this->language->get('text_separator')
			);
			$this->document->setTitle($this->language->get('text_error'));
			$this->data['heading_title'] = $this->language->get('text_error');
			$this->data['text_error']    = $this->language->get('text_error');
			$this->data['button_write']  = $this->language->get('button_write');
			$this->data['continue']      = $this->url->link('common/home');
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
			} //file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')
			else {
				$this->template = 'default/template/error/not_found.tpl';
			}
			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);
			return ($this->render());
		}
	}


	private function getRecordImages($record_id, $settings) {
					$images = array();

					if (!isset($settings['width']) || $settings['width']=='' )    $settings['width']=$this->config->get('config_image_additional_width');;
					if (!isset($settings['height']) || $settings['height']=='' )  $settings['height']=$this->config->get('config_image_additional_height');

					$width = $settings['width'];
					$height = $settings['height'];

					$results              = $this->model_catalog_record->getRecordImages($record_id);
					foreach ($results as $res) {

						$image_options = unserialize(base64_decode($res['options']));

						if (isset($image_options['title'][$this->config->get('config_language_id')])) {
						$image_title = html_entity_decode($image_options['title'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
						} else {
						 $image_title = $this->getHttpImage() . $res['image'];
						}

						if (isset($image_options['description'][$this->config->get('config_language_id')])) {
							$image_description = $description = html_entity_decode($image_options['description'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8'); ;
						} else {
							$image_description ="";
						}

						if (isset($image_options['url'])) {
							$image_url = $image_options['url'];
						} else {
							$image_url = "";
						}


						$images[] = array(
							'popup' => $this->getHttpImage() . $res['image'],
							'title' => $image_title,
							'description' => $image_description,
							'url'=> $image_url,
							'options' => $image_options,
							'thumb' => $this->model_tool_image->resize($res['image'], $width , $height)
						);
					}
					return $images;
	}

	private function getHttpImage()
	{
		$array_dir_image = str_split(DIR_IMAGE);
		$array_dir_app   = str_split(DIR_APPLICATION);
		$i               = 0;
		$dir_root        = '';
		while ($array_dir_image[$i] == $array_dir_app[$i]) {
			$dir_root .= $array_dir_image[$i];
			$i++;
		} //$array_dir_image[$i] == $array_dir_app[$i]
		$dir_image = str_replace($dir_root, '', DIR_IMAGE);
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$http_image = HTTPS_SERVER . $dir_image;
		} //isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))
		else {
			$http_image = HTTP_SERVER . $dir_image;
		}
	 return $http_image;
	}


	private function getBlogSettingsbyRecordID($record_id)
	{
		$this->load->model('catalog/blog');
		if (isset($record_id)) {
			$blog_path = $this->model_catalog_blog->getPathByrecord($record_id);
			$blog_id   = $blog_path['path'];
		} //isset($record_id)
		if (isset($blog_id)) {
			foreach (explode('_', $blog_id) as $path_id) {
				$path_id = $path_id;
			} //explode('_', $blog_id) as $path_id
		} //isset($blog_id)
		$blog_info = $this->model_catalog_blog->getBlog($path_id);
		if (isset($blog_info['design']) && $blog_info['design'] != '') {
			$this->data['blog_design'] = unserialize($blog_info['design']);
		} //isset($blog_info['design']) && $blog_info['design'] != ''
		else {
			$this->data['blog_design'] = Array();
		}
		return $this->data['blog_design'];
	}
	public function captchadel()
	{

	}
	public function captcham()
	{
		$this->load->library('captcham5');
		$this->language->load('record/record');
		$this->data['entry_captcha']        = $this->language->get('entry_captcha');
		$this->data['entry_captcha_title']  = $this->language->get('entry_captcha_title');
		$this->data['entry_captcha_update'] = $this->language->get('entry_captcha_update');
		if ($this->customer->isLogged()) {
			$this->data['captcha_status'] = false;
		}
		else {
			$this->data['captcha_status']   = true;
			$captcha                        = new Captcha();
			$this->session->data['captcha'] = $this->code = $captcha->getCode();

			$this->data['captcha_keys']     = "";
			for ($i = 0; $i < strlen($this->session->data['captcha']); $i++) {
				$k   = rand(0, 1);
				$pos = strpos($this->data['captcha_keys'], $this->session->data['captcha'][$i]);
				if ($pos === false) {
					if ($k == 1)
						$this->data['captcha_keys'] = $this->data['captcha_keys'] . $this->session->data['captcha'][$i];
					else
						$this->data['captcha_keys'] = $this->session->data['captcha'][$i] . $this->data['captcha_keys'];
				}
			}
		}
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/agoodonut/module/captcham5.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/agoodonut/module/captcham5.tpl';
		}
		else {
			$this->template = 'default/template/agoodonut/module/captcham5.tpl';
		}
		$this->data['theme'] = $this->config->get('config_template');
		$this->response->setOutput($this->render());
	}

	public function captcham5()
	{
		$this->load->library('captcham5');
		$this->language->load('record/record');
		$this->data['entry_captcha']        = $this->language->get('entry_captcha');
		$this->data['entry_captcha_title']  = $this->language->get('entry_captcha_title');
		$this->data['entry_captcha_update'] = $this->language->get('entry_captcha_update');
		if ($this->customer->isLogged()) {
			$this->data['captcha_status'] = false;
		}
		else {
			$this->data['captcha_status']   = true;
			$captcha                        = new Captcha();

			if (!isset($this->session->data['captcha'])) {
			 $this->session->data['captcha'] = '';
			}
			$captcha->setCode($this->session->data['captcha']);

			$this->data['captcha_keys']     = "";
			for ($i = 0; $i < strlen($this->session->data['captcha']); $i++) {
				$k   = rand(0, 1);
				$pos = strpos($this->data['captcha_keys'], $this->session->data['captcha'][$i]);
				if ($pos === false) {
					if ($k == 1)
						$this->data['captcha_keys'] = $this->data['captcha_keys'] . $this->session->data['captcha'][$i];
					else
						$this->data['captcha_keys'] = $this->session->data['captcha'][$i] . $this->data['captcha_keys'];
				}
			}
		}


		$this->data['theme'] = $this->config->get('config_template');
		$captcha->showImage();

	}


	public function comments_vote()
	{
		$json             = array();
		$json['messages'] = 'ok';
		$this->language->load('record/record');
		$this->load->model('catalog/record');
		if (isset($this->request->post['comment_id'])) {
			$comment_id = $this->request->post['comment_id'];
		} //isset($this->request->post['comment_id'])
		else {
			$comment_id = 0;
		}
		if (isset($this->request->post['delta'])) {
			$delta = $this->request->post['delta'];
			if ($delta > 1) {
				$delta = 1;
			} //$delta > 1
			if ($delta < -1) {
				$delta = -1;
			} //$delta < -1
		} //isset($this->request->post['delta'])
		else {
			$delta = 0;
		}
		if ($this->customer->isLogged()) {
			$customer_id = $this->customer->getId();
		} //$this->customer->isLogged()
		else {
			$customer_id = false;
		}
		$json['customer_id']       = $customer_id;
		$this->data['comment_id']  = $comment_id;
		$this->data['customer_id'] = $customer_id;
		$this->data['delta']       = $delta;
		$this->load->model('catalog/comment');
		$record_info = $this->model_catalog_comment->getRecordbyComment($this->data);
		if (isset($record_info['record_id']) && $record_info['record_id'] != '') {
			$this->data['record_id'] = $record_info['record_id'];
		} //isset($record_info['record_id']) && $record_info['record_id'] != ''
		else {
			$this->data['record_id'] = '';
		}
		$check_rate     = $this->model_catalog_comment->checkRate($this->data);
		$check_rate_num = $this->model_catalog_comment->checkRateNum($this->data);
		$rating_num     = 0;
		if (isset($this->data['record_id']) && $this->data['record_id'] != '') {
			$this->load->model('catalog/record');
			$record_info = $this->model_catalog_record->getRecord($this->data['record_id']);
			if (isset($record_info) && $record_info) {
				if (isset($record_info['comment']) && $record_info['comment'] != '') {
					$record_settings = unserialize($record_info['comment']);
					if (isset($record_settings['rating_num']) && $record_settings['rating_num'] != '') {
						$rating_num = $record_settings['rating_num'];
					} //isset($record_settings['rating_num']) && $record_settings['rating_num'] != ''
					else {
						$rating_num = 10000;
					}
				} //isset($record_info['comment']) && $record_info['comment'] != ''
			} //isset($record_info) && $record_info
		} //isset($this->data['record_id']) && $this->data['record_id'] != ''
		if (isset($check_rate_num['rating_num']) && $check_rate_num['rating_num'] != '') {
			$voted_num = $check_rate_num['rating_num'];
		} //isset($check_rate_num['rating_num']) && $check_rate_num['rating_num'] != ''
		else {
			$voted_num = $rating_num - 1;
		}
		if ((count($check_rate) < 1) && $customer_id && ($voted_num < $rating_num)) {
			$this->model_catalog_comment->addRate($this->data);
			$rate_info       = $this->model_catalog_comment->getRatesByCommentId($comment_id);
			$json['success'] = $rate_info[0];
			$blog_plus            = "";
			if ($json['success']['rate_delta'] > 0)
				$blog_plus = "+";
			$json['success']['rate_delta'] = sprintf($blog_plus . "%d", $json['success']['rate_delta']);
		} //(count($check_rate) < 1) && $customer_id && ($voted_num < $rating_num)
		else {
			if ($customer_id) {
				$json['messages'] = '';
				$json['success']  = $this->language->get('text_voted');
			} //$customer_id
			else {
				$json['messages'] = '';
				$json['success']  = $this->language->get('text_vote_reg');
			}
		}
		return $this->response->setOutput(json_encode($json));
	}
	public function upload()
	{
		$this->language->load('record/record');
		$json = array();
		if (!empty($this->request->files['file']['name'])) {
			$filename = basename(html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8'));
			if ((strlen($filename) < 3) || (strlen($filename) > 128)) {
				$json['error'] = $this->language->get('error_filename');
			} //(strlen($filename) < 3) || (strlen($filename) > 128)
			$allowed   = array();
			$filetypes = explode(',', $this->config->get('config_upload_allowed'));
			foreach ($filetypes as $filetype) {
				$allowed[] = trim($filetype);
			} //$filetypes as $filetype
			if (!in_array(substr(strrchr($filename, '.'), 1), $allowed)) {
				$json['error'] = $this->language->get('error_filetype');
			} //!in_array(substr(strrchr($filename, '.'), 1), $allowed)
			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			} //$this->request->files['file']['error'] != UPLOAD_ERR_OK
		} //!empty($this->request->files['file']['name'])
		else {
			$json['error'] = $this->language->get('error_upload');
		}
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && !isset($json['error'])) {
			if (is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
				$file = basename($filename) . '.' . md5(rand());
				$this->load->library('encryption');
				$encryption   = new Encryption($this->config->get('config_encryption'));
				$json['file'] = $encryption->encrypt($file);
				move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . $file);
			} //is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])
			$json['success'] = $this->language->get('text_upload');
		} //($this->request->server['REQUEST_METHOD'] == 'POST') && !isset($json['error'])
		$this->response->setOutput(json_encode($json));
	}
	public function rdate($param, $time = 0)
	{
		$this->language->load('record/blog');
		if (intval($time) == 0)
			$time = time();
		$MonthNames = array(
			$this->language->get('text_january'),
			$this->language->get('text_february'),
			$this->language->get('text_march'),
			$this->language->get('text_april'),
			$this->language->get('text_may'),
			$this->language->get('text_june'),
			$this->language->get('text_july'),
			$this->language->get('text_august'),
			$this->language->get('text_september'),
			$this->language->get('text_october'),
			$this->language->get('text_november'),
			$this->language->get('text_december')
		);
		if (strpos($param, 'M') === false)
			return date($param, $time);
		else {
			$str_begin  = date(utf8_substr($param, 0, utf8_strpos($param, 'M')), $time);
			$str_middle = $MonthNames[date('n', $time) - 1];
			$str_end    = date(utf8_substr($param, utf8_strpos($param, 'M') + 1, utf8_strlen($param)), $time);
			$str_date   = $str_begin . $str_middle . $str_end;
			return $str_date;
		}
	}

	public function getThemeFile($file) {
			$themefile = false;
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/'.$file)) {
				$themefile = $this->config->get('config_template') . '/'.$file;
			} //file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')
			else {
				if (file_exists(DIR_TEMPLATE . 'default/'.$file)) {
					$themefile = 'default/'.$file;
				}
			}
	  return $themefile;
	}


}


if (!class_exists('cmp_my_comment')) {
	class cmp_my_comment
	{
		var $key;
		var $ord;
		function __construct($key, $ord)
		{
			$this->key = $key;
			$this->ord = $ord;
		}
		function my_cmp($a, $b)
		{
			$key = $this->key;
			$ord = $this->ord;
			if ($key == 'date_available') {
				if (strtotime($a[$key]) > strtotime($b[$key])) {
					if ($ord == 'asc')
						return 1;
					if ($ord == 'desc')
						return -1;
				} //strtotime($a[$key]) > strtotime($b[$key])
				if (strtotime($b[$key]) > strtotime($a[$key])) {
					if ($ord == 'asc')
						return -1;
					if ($ord == 'desc')
						return 1;
				} //strtotime($b[$key]) > strtotime($a[$key])
			} //$key == 'date_available'
			if ($a[$key] > $b[$key]) {
				if ($ord == 'asc')
					return 1;
				if ($ord == 'desc')
					return -1;
			} //$a[$key] > $b[$key]
			if ($b[$key] > $a[$key]) {
				if ($ord == 'asc')
					return -1;
				if ($ord == 'desc')
					return 1;
			} //$b[$key] > $a[$key]
			return 0;
		}
	}
} //!class_exists('cmp_my_comment')
?>