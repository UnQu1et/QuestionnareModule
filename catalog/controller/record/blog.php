<?php
class ControllerRecordBlog extends Controller
{
	public function index()
	{
		$this->getChild('common/seoblog');
		$this->language->load('record/blog');
		$this->load->model('catalog/blog');
		$this->load->model('catalog/record');
		$this->load->model('tool/image');
		if ($this->config->get('generallist') != '') {
			$this->data['settings_general'] = $this->config->get('generallist');
		} //$this->config->get('generallist') != ''
		else {
			$this->data['settings_general'] = Array();
			$this->config->set('generallist', $this->data['settings_general']);
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
		if (!class_exists('User')) {
			require_once(DIR_SYSTEM . 'library/user.php');
			$this->registry->set('user', new User($this->registry));
		} //!class_exists('User')
		if ($this->user->isLogged()) {
			$this->data['userLogged'] = true;
		} //$this->user->isLogged()
		else {
			$this->data['userLogged'] = false;
		}
		$this->load->model('setting/setting');
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$settings_admin = $this->model_setting_setting->getSetting('blogadmin', 'https_admin_path');
		} //isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))
		else {
			$settings_admin = $this->model_setting_setting->getSetting('blogadmin', 'http_admin_path');
		}
		foreach ($settings_admin as $key => $value) {
			$this->data['admin_path'] = $value;
		} //$settings_admin as $key => $value
		if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/blog.css')) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/blog.css');
		} //file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/blog.css')
		else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/blog.css');
		}
		$sort_data = array(
			'rating',
			'comments',
			'popular',
			'latest',
			'sort'
		);
		$sort      = 'p.sort_order';
		if (isset($this->data['settings_general']['order']) && in_array($this->data['settings_general']['order'], $sort_data)) {
			if ($this->data['settings_general']['order'] == 'rating') {
				$sort = 'rating';
			} //$this->data['settings_general']['order'] == 'rating'
			if ($this->data['settings_general']['order'] == 'comments') {
				$sort = 'comments';
			} //$this->data['settings_general']['order'] == 'comments'
			if ($this->data['settings_general']['order'] == 'latest') {
				$sort = 'p.date_available';
			} //$this->data['settings_general']['order'] == 'latest'
			if ($this->data['settings_general']['order'] == 'sort') {
				$sort = 'p.sort_order';
			} //$this->data['settings_general']['order'] == 'sort'
			if ($this->data['settings_general']['order'] == 'popular') {
				$sort = 'p.viewed';
			} //$this->data['settings_general']['order'] == 'popular'
		} //isset($this->data['settings_general']['order']) && in_array($this->data['settings_general']['order'], $sort_data)
		$order = 'DESC';
		if (isset($this->data['settings_general']['order_ad'])) {
			if (strtoupper($this->data['settings_general']['order_ad']) == 'ASC') {
				$order = 'ASC';
			} //strtoupper($this->data['settings_general']['order_ad']) == 'ASC'
			if (strtoupper($this->data['settings_general']['order']) == 'DESC') {
				$order = 'DESC';
			} //strtoupper($this->data['settings_general']['order']) == 'DESC'
		} //isset($this->data['settings_general']['order_ad'])
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} //isset($this->request->get['sort'])
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} //isset($this->request->get['order'])
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} //isset($this->request->get['page'])
		else {
			$page = 1;
		}
		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} //isset($this->request->get['limit'])
		else {
			if ($this->config->get('blog_num_records') != '') {
				$limit = $this->config->get('blog_num_records');
			} //$this->config->get('blog_num_records') != ''
			else {
				$limit = $this->config->get('config_catalog_limit');
				$this->config->set('blog_num_records', $limit);
			}
		}
		$this->data['breadcrumbs']   = array();
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home'),
			'separator' => false
		);
		if (isset($this->request->get['blog_id'])) {
			$path  = '';
			$parts = explode('_', (string) $this->request->get['blog_id']);
			foreach ($parts as $path_id) {
				if (!$path) {
					$path = $path_id;
				} //!$path
				else {
					$path .= '_' . $path_id;
				}
				$blog_info = $this->model_catalog_blog->getBlog($path_id);
				if ($blog_info) {
					$this->data['breadcrumbs'][] = array(
						'text' => $blog_info['name'],
						'href' => $this->url->link('record/blog', 'blog_id=' . $path),
						'separator' => $this->language->get('text_separator')
					);
				} //$blog_info
			} //$parts as $path_id
			$blog_id = array_pop($parts);
		} //isset($this->request->get['blog_id'])
		else {
			$blog_id = 0;
		}
		$blog_info = $this->model_catalog_blog->getBlog($blog_id);
		if ($blog_info) {
			$blog_page = $this->config->get('blog_page');
			if ($blog_page) {
				$paging = " ".$this->language->get('text_blog_page')." ".$blog_page;
			} else  {
				$paging ='';
			}


			if (isset($blog_info['meta_title']) && $blog_info['meta_title']!='') {
				$this->document->setTitle($blog_info['meta_title']);
			} else {
				$this->document->setTitle($blog_info['name'].$paging);
			}

			if (isset($blog_info['meta_h1']) && $blog_info['meta_h1']!='') {
			 $this->data['heading_title']   = $blog_info['meta_h1'];
			} else {
			  $this->data['heading_title']   = $blog_info['name'];
			}

			$this->data['name']   = $blog_info['name'];

			$this->document->setDescription($blog_info['meta_description'].$paging);
			$this->document->setKeywords($blog_info['meta_keyword']);



			$this->data['blog_href']       = $this->url->link('record/blog', 'blog_id=' . $blog_id);
			$this->data['text_refine']     = $this->language->get('text_refine');
			$this->data['text_empty']      = $this->language->get('text_empty');
			$this->data['text_quantity']   = $this->language->get('text_quantity');
			$this->data['text_model']      = $this->language->get('text_model');
			$this->data['text_price']      = $this->language->get('text_price');
			$this->data['text_tax']        = $this->language->get('text_tax');
			$this->data['text_points']     = $this->language->get('text_points');
			$this->data['text_display']    = $this->language->get('text_display');
			$this->data['text_list']       = $this->language->get('text_list');
			$this->data['text_grid']       = $this->language->get('text_grid');
			$this->data['text_sort']       = $this->language->get('text_sort');
			$this->data['text_limit']      = $this->language->get('text_limit');
			$this->data['text_comments']   = $this->language->get('text_comments');
			$this->data['text_viewed']     = $this->language->get('text_viewed');
			$this->data['button_cart']     = $this->language->get('button_cart');
			$this->data['button_wishlist'] = $this->language->get('button_wishlist');
			$this->data['button_continue'] = $this->language->get('button_continue');
			if ($blog_info['design'] != '') {
				$this->data['blog_design'] = unserialize($blog_info['design']);
			} //$blog_info['design'] != ''
			else {
				$this->data['blog_design'] = Array();
			}
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
			if (isset($this->data['blog_design']['order_ad'])) {
				if (strtoupper($this->data['blog_design']['order_ad']) == 'ASC') {
					$order = 'ASC';
				} //strtoupper($this->data['blog_design']['order_ad']) == 'ASC'
				if (strtoupper($this->data['blog_design']['order']) == 'DESC') {
					$order = 'DESC';
				} //strtoupper($this->data['blog_design']['order']) == 'DESC'
			} //isset($this->data['blog_design']['order_ad'])
			if ($blog_info['image']) {
				if (isset($this->data['blog_design']['blog_big']) && $this->data['blog_design']['blog_big']['width'] != '' && $this->data['blog_design']['blog_big']['height'] != '') {
					$dimensions = $this->data['blog_design']['blog_big'];
				} //isset($this->data['blog_design']['blog_big']) && $this->data['blog_design']['blog_big']['width'] != '' && $this->data['blog_design']['blog_big']['height'] != ''
				else {
					$dimensions = $this->config->get('blog_big');
				}

				if (!isset($dimensions['width']) || $dimensions['width'] == '')
					$dimensions['width'] = 300;
				if (!isset($dimensions['height']) || $dimensions['height'] == '')
					$dimensions['height'] = 200;

				$this->data['thumb']     = $this->model_tool_image->resize($blog_info['image'], $dimensions['width'], $dimensions['height']);
				$this->data['thumb_dim'] = $dimensions;
			} //$blog_info['image']
			else {
				$this->data['thumb']     = '';
				$this->data['thumb_dim'] = false;
			}

			if ($blog_info['description']) {
				$this->data['description'] = html_entity_decode($blog_info['description'], ENT_QUOTES, 'UTF-8');
			} //$blog_info['description']
			else
				$this->data['description'] = false;
			if (isset($blog_info['sdescription']) && $blog_info['sdescription'] != '') {
				$this->data['sdescription'] = html_entity_decode($blog_info['sdescription'], ENT_QUOTES, 'UTF-8');
			} //isset($blog_info['sdescription']) && $blog_info['sdescription'] != ''
			else
				$this->data['sdescription'] = false;
			$url = '';
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
				$sort = $this->request->get['sort'];
			} //isset($this->request->get['sort'])
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
				$order = $this->request->get['order'];
			} //isset($this->request->get['order'])
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			} //isset($this->request->get['limit'])
			$this->data['categories'] = array();
			$results                  = $this->model_catalog_blog->getBlogies($blog_id);
			foreach ($results as $result) {
				$data         = array(
					'filter_blog_id' => $result['blog_id'],
					'filter_sub_blog' => true
				);
				$record_total = $this->model_catalog_record->getTotalRecords($data);
				if ($result['image']) {
					if (isset($this->data['blog_design']['blog_small']) && $this->data['blog_design']['blog_small']['width'] != '' && $this->data['blog_design']['blog_small']['height'] != '') {
						$dimensions = $this->data['blog_design']['blog_small'];
					} //isset($this->data['blog_design']['blog_small']) && $this->data['blog_design']['blog_small']['width'] != '' && $this->data['blog_design']['blog_small']['height'] != ''
					else {
						$dimensions = $this->config->get('blog_small');
					}
					if ($dimensions['width'] == '') {
						if ($this->config->get('config_image_category_width') != '')
							$dimensions['width'] = $this->config->get('config_image_category_width');
						else
							$dimensions['width'] = 100;
					} //$dimensions['width'] == ''
					if ($dimensions['height'] == '') {
						if ($this->config->get('config_image_category_height') != '')
							$dimensions['height'] = $this->config->get('config_image_category_height');
						else
							$dimensions['height'] = 100;
					} //$dimensions['height'] == ''
					$image                   = $this->model_tool_image->resize($result['image'], $dimensions['width'], $dimensions['height']);
					$this->data['image_dim'] = $dimensions;
				} //$result['image']
				else {
					$image                   = '';
					$this->data['image_dim'] = false;
				}
				$this->data['categories'][] = array(
					'name' => $result['name'],
					'meta_description' => $result['meta_description'],
					'total' => $record_total,
					'thumb' => $image,
					'href' => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . '_' . $result['blog_id'] . $url)
				);
			} //$results as $result
			if (isset($this->data['blog_design']['blog_num_records']) && $this->data['blog_design']['blog_num_records'] != '' && !isset($this->request->get['limit'])) {
				$limit = $this->data['blog_design']['blog_num_records'];
			} //isset($this->data['blog_design']['blog_num_records']) && $this->data['blog_design']['blog_num_records'] != '' && !isset($this->request->get['limit'])
			$this->data['records'] = array();
			$data                  = array(
				'filter_blog_id' => $blog_id,
				'sort' => $sort,
				'order' => $order,
				'start' => ($page - 1) * $limit,
				'limit' => $limit
			);
			if (isset($this->data['blog_design'])) {
				$this->data['settings_blog'] = $this->data['blog_design'];
			} //isset($this->data['blog_design'])
			$record_total = $this->model_catalog_record->getTotalRecords($data);
			$results      = $this->model_catalog_record->getRecords($data);
			foreach ($results as $result) {
				if ($result['image']) {
					if (isset($this->data['blog_design']['blog_small']) && $this->data['blog_design']['blog_small']['width'] != '' && $this->data['blog_design']['blog_small']['height'] != '') {
						$dimensions = $this->data['blog_design']['blog_small'];
					} //isset($this->data['blog_design']['blog_small']) && $this->data['blog_design']['blog_small']['width'] != '' && $this->data['blog_design']['blog_small']['height'] != ''
					else {
						$dimensions = $this->config->get('blog_small');
					}

					if (!isset($this->data['blog_design']['images']))
					   $this->data['blog_design']['images'] =array();


				if (!isset($dimensions['width']) || $dimensions['width'] == '')
					$dimensions['width'] = 300;
				if (!isset($dimensions['height']) || $dimensions['height'] == '')
					$dimensions['height'] = 200;

					$image                   = $this->model_tool_image->resize($result['image'], $dimensions['width'], $dimensions['height']);
					$this->data['image_dim'] = $dimensions;
				} //$result['image']
				else {
					$image                   = false;
					$this->data['image_dim'] = false;
				}
				if ($this->config->get('config_comment_status')) {
					$rating = (int) $result['rating'];
				} //$this->config->get('config_comment_status')
				else {
					$rating = false;
				}

			if ($result['description']) {
				$result['description_full'] = html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8');
			} //$blog_info['description']
			else
				$result['description_full'] = false;


				if (!isset($result['sdescription'])) {
					$result['sdescription'] = '';
				} //!isset($result['sdescription'])
				if ($result['description'] && $result['sdescription'] == '') {
					$flag_desc = 'pred';
					$amount    = 1;
					if (isset($this->data['blog_design']['blog_num_desc'])) {
						$this->data['blog_num_desc'] = $this->data['blog_design']['blog_num_desc'];
					} //isset($this->data['blog_design']['blog_num_desc'])
					else {
						$this->data['blog_num_desc'] = $this->config->get('blog_num_desc');
					}
					if ($this->data['blog_num_desc'] == '') {
						$this->data['blog_num_desc'] = 50;
					} //$this->data['blog_num_desc'] == ''
					else {
						$amount    = $this->data['blog_num_desc'];
						$flag_desc = 'symbols';
					}
					if (isset($this->data['blog_design']['blog_num_desc_words'])) {
						$this->data['blog_num_desc_words'] = $this->data['blog_design']['blog_num_desc_words'];
					} //isset($this->data['blog_design']['blog_num_desc_words'])
					else {
						$this->data['blog_num_desc_words'] = $this->config->get('blog_num_desc_words');
					}
					if ($this->data['blog_num_desc_words'] == '') {
						$this->data['blog_num_desc_words'] = 10;
					} //$this->data['blog_num_desc_words'] == ''
					else {
						$amount    = $this->data['blog_num_desc_words'];
						$flag_desc = 'words';
					}
					if (isset($this->data['blog_design']['blog_num_desc_pred'])) {
						$this->data['blog_num_desc_pred'] = $this->data['blog_design']['blog_num_desc_pred'];
					} //isset($this->data['blog_design']['blog_num_desc_pred'])
					else {
						$this->data['blog_num_desc_pred'] = $this->config->get('blog_num_desc_pred');
					}
					if ($this->data['blog_num_desc_pred'] == '') {
						$this->data['blog_num_desc_pred'] = 3;
					} //$this->data['blog_num_desc_pred'] == ''
					else {
						$amount    = $this->data['blog_num_desc_pred'];
						$flag_desc = 'pred';
					}
					switch ($flag_desc) {
						case 'symbols':
							$pattern = ('/((.*?)\S){0,' . $amount . '}/isu');
							preg_match_all($pattern, strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), $out);
							$description = $out[0][0];
							break;
						case 'words':
							$pattern = ('/((.*?)\x20){0,' . $amount . '}/isu');
							preg_match_all($pattern, strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), $out);
							$description = $out[0][0];
							break;
						case 'pred':
							$pattern = ('/((.*?)\.){0,' . $amount . '}/isu');
							preg_match_all($pattern, strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), $out);
							$description = $out[0][0];
							break;
					} //$flag_desc
				} //$result['description'] && $result['sdescription'] == ''
				else {
					$description = false;
				}
				if (isset($result['sdescription']) && $result['sdescription'] != '') {
					$description = html_entity_decode($result['sdescription'], ENT_QUOTES, 'UTF-8');
				} //isset($result['sdescription']) && $result['sdescription'] != ''
				if ($this->rdate($this->language->get('text_date')) == $this->rdate($this->language->get('text_date'), strtotime($result['date_available']))) {
					$date_str = $this->language->get('text_today');
				} //$this->rdate($this->language->get('text_date')) == $this->rdate($this->language->get('text_date'), strtotime($result['date_available']))
				else {
					$date_str = $this->language->get('text_date');
				}
				$date_available  = $this->rdate($date_str . $this->language->get('text_hours'), strtotime($result['date_available']));
				$blog_href       = $this->model_catalog_blog->getPathByrecord($result['record_id']);

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
				$popup = $http_image . $result['image'];


				if (!isset($this->data['blog_design']['category_status'])) {
					$this->data['blog_design']['category_status'] = 0;
				} //!isset($this->data['blog_design']['category_status'])
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

				if (!isset($this->data['blog_design']['images'])) {
					$this->data['blog_design']['images'] = array();
				}

				$this->data['records'][] = array(
					'record_id' => $result['record_id'],
					'thumb' => $image,
					'images' => $this->getRecordImages($result['record_id'], $this->data['blog_design']['images']),
					'popup' => $popup,
					'name' => $result['name'],
					'description' => $description,
					'description_full'=> $result['description_full'],
					'attribute_groups' => $this->model_catalog_record->getRecordAttributes($result['record_id']),
					'rating' => $result['rating'],
					'date_added' => $result['date_added'],
					'date_available' => $date_available,
					'datetime_available' => $result['date_available'],
					'date_end' => $result['date_end'],
					'viewed' => $result['viewed'],
					'comments' => (int) $result['comments'],
					'href' => $this->url->link('record/record', 'record_id=' . $result['record_id'] . '&blog_id=' . $blog_href['path']),
					'blog_href' => $this->url->link('record/blog', 'blog_id=' . $blog_href['path']),
					'blog_name' => $blog_href['name'],
					'settings' => $this->data['settings_general'],
					'settings_blog' => $this->data['blog_design'],
					'settings_comment' => unserialize($result['comment'])
				);
			} //$results as $result
			$url = '';
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			} //isset($this->request->get['limit'])
			$this->data['sorts']   = array();
			$this->data['sorts'][] = array(
				'text' => $this->language->get('text_default'),
				'value' => 'p.sort_order-ASC',
				'href' => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . '&sort=p.sort_order&order=ASC' . $url)
			);
			$this->data['sorts'][] = array(
				'text' => $this->language->get('text_date_added_desc'),
				'value' => 'p.date_available-DESC',
				'href' => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . '&sort=p.date_available&order=DESC' . $url)
			);
			$this->data['sorts'][] = array(
				'text' => $this->language->get('text_date_added_asc'),
				'value' => 'p.date_available-ASC',
				'href' => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . '&sort=p.date_available&order=ASC' . $url)
			);
			$this->data['sorts'][] = array(
				'text' => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href' => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . '&sort=pd.name&order=ASC' . $url)
			);
			$this->data['sorts'][] = array(
				'text' => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href' => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . '&sort=pd.name&order=DESC' . $url)
			);
			$this->data['sorts'][] = array(
				'text' => $this->language->get('text_rating_desc'),
				'value' => 'rating-DESC',
				'href' => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . '&sort=rating&order=DESC' . $url)
			);
			$this->data['sorts'][] = array(
				'text' => $this->language->get('text_rating_asc'),
				'value' => 'rating-ASC',
				'href' => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . '&sort=rating&order=ASC' . $url)
			);
			$url                   = '';
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			} //isset($this->request->get['sort'])
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			} //isset($this->request->get['order'])
			$this->data['limits']   = array();
			$this->data['limits'][] = array(
				'text' => $limit,
				'value' => $limit,
				'href' => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . $url . '&limit=' . $limit)
			);
			$this->data['limits'][] = array(
				'text' => 25,
				'value' => 25,
				'href' => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . $url . '&limit=25')
			);
			$this->data['limits'][] = array(
				'text' => 50,
				'value' => 50,
				'href' => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . $url . '&limit=50')
			);
			$this->data['limits'][] = array(
				'text' => 75,
				'value' => 75,
				'href' => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . $url . '&limit=75')
			);
			$this->data['limits'][] = array(
				'text' => 100,
				'value' => 100,
				'href' => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . $url . '&limit=100')
			);
			$url                    = '';
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			} //isset($this->request->get['sort'])
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			} //isset($this->request->get['order'])
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			} //isset($this->request->get['limit'])

			$this->data['sort']       = $sort;
			$this->data['order']      = $order;
			$this->data['limit']      = $limit;
			$this->data['continue']   = $this->url->link('common/home');



			$pagination               = new Pagination();
			$pagination->total        = $record_total;
			$pagination->page         = $page;
			$pagination->limit        = $limit;
			$pagination->text         = $this->language->get('text_pagination');
			$pagination->url          = $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . $url . '&page={page}');
			$this->data['pagination'] = $pagination->render();



			if (isset($this->data['blog_design']['blog_template']) && $this->data['blog_design']['blog_template'] != '') {
				$template = $this->data['blog_design']['blog_template'];
			} //isset($this->data['blog_design']['blog_template']) && $this->data['blog_design']['blog_template'] != ''
			else {
				$template = 'blog.tpl';
			}
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/agoodonut/blog/' . $template)) {
				$this->template = $this->config->get('config_template') . '/template/agoodonut/blog/' . $template;
			} //file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/record/' . $template)
			else {
				if (file_exists(DIR_TEMPLATE . 'default/template/agoodonut/blog/' . $template)) {
					$this->template = 'default/template/agoodonut/blog/' . $template;
				} //file_exists(DIR_TEMPLATE . 'default/template/record/' . $template)
				else {
					$this->template = 'default/template/agoodonut/blog/blog.tpl';
				}
			}
			$this->children        = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);
			$this->data['url_rss'] = $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . '&rss=2.0');
			if (isset($this->request->get['rss'])) {
				$this->response->addHeader("Content-type: text/xml");
				$this->data['url_self'] = $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id']);
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/agoodonut/blog/blogrss.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/agoodonut/blog/blogrss.tpl';
				} //file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/record/blogrss.tpl')
				else {
					if (file_exists(DIR_TEMPLATE . 'default/template/agoodonut/blog/blogrss.tpl')) {
						$this->template = 'default/template/agoodonut/blog/blogrss.tpl';
					} //file_exists(DIR_TEMPLATE . 'default/template/record/blogrss.tpl')
					else {
						$this->template = '';
					}
				}
				$this->children             = array();
				$this->data['header']       = '';
				$this->data['column_left']  = '';
				$this->data['column_right'] = '';
				$this->data['content_top']  = '';
				$this->data['footer']       = '';
				$this->data['lang']         = $this->config->get('config_language');
			} //isset($this->request->get['rss'])
			$this->data['theme'] = $this->config->get('config_template');
			$this->response->setOutput($this->render());
		} //$blog_info
		else {
			$url = '';
			if (isset($this->request->get['blog_id'])) {
				$url .= '&blog_id=' . $this->request->get['blog_id'];
			} //isset($this->request->get['blog_id'])
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			} //isset($this->request->get['sort'])
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			} //isset($this->request->get['order'])
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			} //isset($this->request->get['page'])
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			} //isset($this->request->get['limit'])
			$this->data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('record/blog', $url),
				'separator' => $this->language->get('text_separator')
			);
			$this->document->setTitle($this->language->get('text_error'));
			$this->data['heading_title']   = $this->language->get('text_error');
			$this->data['text_error']      = $this->language->get('text_error');
			$this->data['button_continue'] = $this->language->get('button_continue');
			$this->data['continue']        = $this->url->link('common/home');
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
}
?>