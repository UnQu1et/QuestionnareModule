<?php
class ControllerCatalogBlog extends Controller
{
	private $error = array();
	public function index()
	{
		$this->language->load('catalog/blog');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/blog');
		$this->getList();
	}
	public function insert()
	{
		$this->language->load('catalog/blog');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/blog');
		$this->cache->delete('blog');
		$this->cache->delete('record');
		$this->cache->delete('blogsrecord');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_blog->addBlog($this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect($this->url->link('catalog/blog', 'token=' . $this->session->data['token'], 'SSL'));
		}
		$this->getForm();
	}
	public function update()
	{
		$this->language->load('catalog/blog');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/blog');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_blog->editBlog($this->request->get['blog_id'], $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect($this->url->link('catalog/blog', 'token=' . $this->session->data['token'], 'SSL'));
		}
		$this->getForm();
	}
	public function delete()
	{
		$this->language->load('catalog/blog');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/blog');
		$this->cache->delete('blog');
		$this->cache->delete('record');
		$this->cache->delete('blogsrecord');
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $blog_id) {
				$this->model_catalog_blog->deleteBlog($blog_id);
			}
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect($this->url->link('catalog/blog', 'token=' . $this->session->data['token'], 'SSL'));
		}
		$this->getList();
	}
	private function getList()
	{
		if (file_exists(DIR_APPLICATION . 'view/stylesheet/blog.css')) {
			$this->document->addStyle('view/stylesheet/blog.css');
		}
		$this->document->addStyle('http://fonts.googleapis.com/css?family=Open+Sans&subset=latin,cyrillic-ext,latin-ext');
		$this->data['url_back'] = $this->url->link('module/blog', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_back_text'] = $this->language->get('url_back_text');
		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/blog', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);
		$this->data['insert'] = $this->url->link('catalog/blog/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link('catalog/blog/delete', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['categories'] = array();
		$results = $this->model_catalog_blog->getCategories(0);
		$this->load->model('tool/image');
		foreach ($results as $result) {
			$action = array();
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/blog/update', 'token=' . $this->session->data['token'] . '&blog_id=' . $result['blog_id'], 'SSL')
			);
			if (isset($result['image']) && $result['image'] != '' && file_exists(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', 40, 40);
			}
			$this->data['categories'][] = array(
				'blog_id' => $result['blog_id'],
				'name' => $result['name'],
				'image' => $image,
				'sort_order' => $result['sort_order'],
				'selected' => isset($this->request->post['selected']) && in_array($result['blog_id'], $this->request->post['selected']),
				'action' => $action
			);
		}
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['url_blog'] = $this->url->link('catalog/blog', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_record'] = $this->url->link('catalog/record', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_comment'] = $this->url->link('catalog/comment', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_blog_text'] = $this->language->get('url_blog_text');
		$this->data['url_record_text'] = $this->language->get('url_record_text');
		$this->data['url_comment_text'] = $this->language->get('url_comment_text');
		$this->data['url_create_text'] = $this->language->get('url_create_text');
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		$this->template = 'catalog/blog_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}
	private function getForm()
	{
		if (file_exists(DIR_APPLICATION . 'view/stylesheet/blog.css')) {
			$this->document->addStyle('view/stylesheet/blog.css');
		}
		$this->document->addStyle('http://fonts.googleapis.com/css?family=Open+Sans&subset=latin,cyrillic-ext,latin-ext');
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['url_back_text'] = $this->language->get('url_back_text');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_percent'] = $this->language->get('text_percent');
		$this->data['text_amount'] = $this->language->get('text_amount');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_short_path'] = $this->language->get('entry_short_path');
		$this->data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$this->data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
		$this->data['entry_parent'] = $this->language->get('entry_parent');
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_top'] = $this->language->get('entry_top');
		$this->data['entry_column'] = $this->language->get('entry_column');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_data'] = $this->language->get('tab_data');
		$this->data['tab_design'] = $this->language->get('tab_design');
		$this->data['tab_options'] = $this->language->get('tab_options');
		$this->data['url_back'] = $this->url->link('module/blog', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_blog'] = $this->url->link('catalog/blog', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_record'] = $this->url->link('catalog/record', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_comment'] = $this->url->link('catalog/comment', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_blog_text'] = $this->language->get('url_blog_text');
		$this->data['url_record_text'] = $this->language->get('url_record_text');
		$this->data['url_comment_text'] = $this->language->get('url_comment_text');
		$this->data['url_create_text'] = $this->language->get('url_create_text');
		$this->data['entry_what'] = $this->language->get('entry_what');
		$this->data['entry_small_dim'] = $this->language->get('entry_small_dim');
		$this->data['entry_big_dim'] = $this->language->get('entry_big_dim');
		$this->data['entry_blog_num_comments'] = $this->language->get('entry_blog_num_comments');
		$this->data['entry_blog_num_records'] = $this->language->get('entry_blog_num_records');
		$this->data['entry_blog_num_desc'] = $this->language->get('entry_blog_num_desc');
		$this->data['entry_blog_num_desc_words'] = $this->language->get('entry_blog_num_desc_words');
		$this->data['entry_blog_num_desc_pred'] = $this->language->get('entry_blog_num_desc_pred');
		$this->data['entry_blog_template'] = $this->language->get('entry_blog_template');
		$this->data['entry_blog_template_record'] = $this->language->get('entry_blog_template_record');
		$this->data['entry_devider'] = $this->language->get('entry_devider');
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = array();
		}
		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/blog', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);
		if (!isset($this->request->get['blog_id'])) {
			$this->data['action'] = $this->url->link('catalog/blog/insert', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/blog/update', 'token=' . $this->session->data['token'] . '&blog_id=' . $this->request->get['blog_id'], 'SSL');
		}
		$this->data['cancel'] = $this->url->link('catalog/blog', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['token'] = $this->session->data['token'];
		if (isset($this->request->get['blog_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$blog_info = $this->model_catalog_blog->getBlog($this->request->get['blog_id']);
		}
		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		if (isset($this->request->post['blog_description'])) {
			$this->data['blog_description'] = $this->request->post['blog_description'];
		} elseif (isset($this->request->get['blog_id'])) {
			$this->data['blog_description'] = $this->model_catalog_blog->getBlogDescriptions($this->request->get['blog_id']);
		} else {
			$this->data['blog_description'] = array();
		}
		$categories = $this->model_catalog_blog->getCategories(0);
		if (!empty($blog_info)) {
			foreach ($categories as $key => $blog) {
				if ($blog['blog_id'] == $blog_info['blog_id']) {
					unset($categories[$key]);
				}
			}
		}
		$this->data['categories'] = $categories;
		if (isset($this->request->post['parent_id'])) {
			$this->data['parent_id'] = $this->request->post['parent_id'];
		} elseif (!empty($blog_info)) {
			$this->data['parent_id'] = $blog_info['parent_id'];
		} else {
			$this->data['parent_id'] = 0;
		}
		$this->load->model('setting/store');
		$this->data['stores'] = $this->model_setting_store->getStores();
		if (isset($this->request->post['blog_store'])) {
			$this->data['blog_store'] = $this->request->post['blog_store'];
		} elseif (isset($this->request->get['blog_id'])) {
			$this->data['blog_store'] = $this->model_catalog_blog->getBlogStores($this->request->get['blog_id']);
		} else {
			$this->data['blog_store'] = array(
				0
			);
		}
		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($blog_info)) {
			$this->data['keyword'] = $blog_info['keyword'];
		} else {
			$this->data['keyword'] = '';
		}
		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (!empty($blog_info)) {
			$this->data['image'] = $blog_info['image'];
		} else {
			$this->data['image'] = '';
		}
		$this->load->model('tool/image');
		if (!empty($blog_info) && $blog_info['image'] && file_exists(DIR_IMAGE . $blog_info['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($blog_info['image'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		if (isset($this->request->post['top'])) {
			$this->data['top'] = $this->request->post['top'];
		} elseif (!empty($blog_info)) {
			$this->data['top'] = $blog_info['top'];
		} else {
			$this->data['top'] = 0;
		}
		if (isset($this->request->post['column'])) {
			$this->data['column'] = $this->request->post['column'];
		} elseif (!empty($blog_info)) {
			$this->data['column'] = $blog_info['column'];
		} else {
			$this->data['column'] = 1;
		}
		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($blog_info)) {
			$this->data['sort_order'] = $blog_info['sort_order'];
		} else {
			$this->data['sort_order'] = '';
		}
		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (!empty($blog_info)) {
			$this->data['status'] = $blog_info['status'];
		} else {
			$this->data['status'] = 1;
		}
		if (isset($this->request->post['category_status'])) {
			$this->data['category_status'] = $this->request->post['category_status'];
		} elseif (!empty($blog_info) && isset($blog_info['category_status'])) {
			$this->data['category_status'] = $blog_info['category_status'];
		} else {
			$this->data['category_status'] = 1;
		}
		if (isset($this->request->post['view_date'])) {
			$this->data['view_date'] = $this->request->post['view_date'];
		} elseif (!empty($blog_info) && isset($blog_info['view_date'])) {
			$this->data['view_date'] = $blog_info['view_date'];
		} else {
			$this->data['view_date'] = 1;
		}
		$this->load->model('sale/customer_group');
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		if (isset($this->request->post['customer_group_id'])) {
			$this->data['customer_group_id'] = $this->request->post['customer_group_id'];
		} elseif (!empty($blog_info) && isset($blog_info['customer_group_id'])) {
			$this->data['customer_group_id'] = $blog_info['customer_group_id'];
		} else {
			$this->data['customer_group_id'] = (int) $this->config->get('config_customer_group_id');
		}
		if (isset($this->request->post['blog_design'])) {
			$this->data['blog_design'] = $this->request->post['blog_design'];
		} elseif (!empty($blog_info)) {
			if (isset($blog_info['design']))
				$this->data['blog_design'] = unserialize($blog_info['design']);
			else
				$this->data['blog_design'] = Array();
		} else {
			$this->data['blog_design'] = Array();
		}
		if (!isset($this->data['blog_design']['visual_editor'])) {
			$this->data['blog_design']['visual_editor'] = 1;
		}
		if (!isset($this->data['blog_design']['category_status'])) {
			$this->data['blog_design']['category_status'] = 0;
		}
		if (!isset($this->data['blog_design']['view_date'])) {
			$this->data['blog_design']['view_date'] = 1;
		}
		if (!isset($this->data['blog_design']['view_captcha'])) {
			$this->data['blog_design']['view_captcha'] = 1;
		}
		if (!isset($this->data['blog_design']['view_share'])) {
			$this->data['blog_design']['view_share'] = 1;
		}
		if (!isset($this->data['blog_design']['view_viewed'])) {
			$this->data['blog_design']['view_viewed'] = 1;
		}
		if (!isset($this->data['blog_design']['view_rating'])) {
			$this->data['blog_design']['view_rating'] = 1;
		}
		if (!isset($this->data['blog_design']['view_comments'])) {
			$this->data['blog_design']['view_comments'] = 1;
		}
		if (isset($this->request->post['blog_layout'])) {
			$this->data['blog_layout'] = $this->request->post['blog_layout'];
		} elseif (isset($this->request->get['blog_id'])) {
			$this->data['blog_layout'] = $this->model_catalog_blog->getBlogLayouts($this->request->get['blog_id']);
		} else {
			$this->data['blog_layout'] = array();
		}
		$this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		$this->template = 'catalog/blog_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}
	private function validateForm()
	{
		if (!$this->user->hasPermission('modify', 'catalog/blog')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		foreach ($this->request->post['blog_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 2) || (utf8_strlen($value['name']) > 255)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	private function validateDelete()
	{
		if (!$this->user->hasPermission('modify', 'catalog/blog')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>