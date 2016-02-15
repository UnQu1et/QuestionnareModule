<?php
class ControllerCatalogComment extends Controller
{
	private $error = array();
	public function index()
	{
		$this->language->load('catalog/comment');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/blogcomment');
		$this->getList();
	}
	public function cont($cont)
	{
		$file = DIR_CATALOG . 'controller/' . $cont . '.php';
		$class = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', $cont);
		if (file_exists($file)) {
			include_once($file);
			$this->registry->set('controller_' . str_replace('/', '_', $cont), new $class($this->registry));
		} else {
			trigger_error('Error: Could not load controller ' . $cont . '!');
			exit();
		}
	}
	public function insert()
	{
		$this->language->load('catalog/comment');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/blogcomment');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			if (isset($this->request->post['text'])) {
				$this->request->post['text'] = strip_tags($this->request->post['text']);
				$this->request->post['text'] = $this->db->escape($this->request->post['text']);
				$this->request->post['name'] = strip_tags($this->db->escape($this->request->post['author']));
			}
			$this->request->post['mark'] = 'record_id';
			$comment_id = $this->model_catalog_blogcomment->addComment($this->request->post);
			$record_id = $this->model_catalog_blogcomment->getRecordIdbyCommentId($comment_id);
			$this->load->model('catalog/record');
			$record_info = $this->model_catalog_record->getRecord($record_id);
			$this->cont('record/treecomments');
			$this->data['mark'] = 'record_id';
			if (isset($this->request->post['thislist'])) {
				$str = base64_decode($this->request->post['thislist']);
				$this->data['thislist'] = unserialize($str);
			} else {
				$this->data['thislist'] = Array();
			}
			$this->data['thislist']['signer'] = true;
			$this->controller_record_treecomments->signer($record_id, $record_info, $this->data['thislist'], $this->data['mark']);
			$this->session->data['success'] = $this->language->get('text_success');
			$url = '';
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('catalog/comment', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		$this->getForm();
	}
	public function update()
	{
		$this->language->load('catalog/comment');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/blogcomment');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			if (isset($this->request->post['text'])) {
				$this->request->post['text'] = strip_tags($this->request->post['text']);
				$this->request->post['text'] = $this->db->escape($this->request->post['text']);
				$this->request->post['name'] = strip_tags($this->db->escape($this->request->post['author']));
			}
			$this->request->post['mark'] = 'record_id';
			$this->model_catalog_blogcomment->editComment($this->request->get['comment_id'], $this->request->post);
			$record_id = $this->request->post['record_id'];
			$this->load->model('catalog/record');
			$record_info = $this->model_catalog_record->getRecord($record_id);
			$this->cont('record/treecomments');
			$this->data['mark'] = 'record_id';
			if (isset($this->request->post['thislist'])) {
				$str = base64_decode($this->request->post['thislist']);
				$this->data['thislist'] = unserialize($str);
			} else {
				$this->data['thislist'] = Array();
			}
			$this->data['thislist']['signer'] = true;
			$this->session->data['success'] = $this->language->get('text_success');
			$url = '';
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('catalog/comment', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		$this->getForm();
	}
	public function delete()
	{
		$this->language->load('catalog/comment');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/blogcomment');
		$this->cache->delete('blog');
		$this->cache->delete('record');
		$this->cache->delete('blogsrecord');
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $comment_id) {
				$this->model_catalog_blogcomment->deleteComment($comment_id);
			}
			$this->session->data['success'] = $this->language->get('text_success');
			$url = '';
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('catalog/comment', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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
		$this->data['token'] = $this->session->data['token'];
		$this->data['button_filter'] = $this->language->get('button_filter');
		$this->data['entry_comment_text'] = $this->language->get('entry_comment_text');
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = NULL;
		}
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'r.date_added';
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
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/comment', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);
		$this->data['insert'] = $this->url->link('catalog/comment/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/comment/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['comments'] = array();
		$data = array(
			'filter_name' => $filter_name,
			'sort' => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		$results = $this->model_catalog_blogcomment->getComments($data);
		$loader_old = $this->registry->get('load');
		$this->load->library('agoo/loader');
		$agooloader = new agooLoader($this->registry);
		$this->registry->set('load', $agooloader);
		$this->load->model('catalog/treecomments', DIR_CATALOG);
		foreach ($results as $result) {
			$comment_total = $result['total'];
			$action = array();
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/comment/update', 'token=' . $this->session->data['token'] . '&comment_id=' . $result['comment_id'] . $url, 'SSL')
			);
			$this->data['karma'] = $this->model_catalog_treecomments->getRatesByCommentId($result['comment_id'], 'record_id', true);
			$this->data['karma_all'] = $this->model_catalog_treecomments->getRatesByCommentId($result['comment_id'], 'record_id');
			$this->data['comments'][] = array(
				'comment_id' => $result['comment_id'],
				'karma' => $this->data['karma'],
				'karma_all' => $this->data['karma_all'],
				'name' => $result['name'],
				'text' => $result['text'],
				'author' => $result['author'],
				'rating' => $result['rating'],
				'status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'selected' => isset($this->request->post['selected']) && in_array($result['comment_id'], $this->request->post['selected']),
				'action' => $action
			);
		}
		$this->registry->set('load', $loader_old);
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['column_comment_id'] = $this->language->get('column_comment_id');
		$this->data['column_record'] = $this->language->get('column_record');
		$this->data['column_author'] = $this->language->get('column_author');
		$this->data['column_rating'] = $this->language->get('column_rating');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
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
		$this->data['filter_name'] = $filter_name;
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
		$url = '';
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		$this->data['sort_record'] = $this->url->link('catalog/comment', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
		$this->data['sort_author'] = $this->url->link('catalog/comment', 'token=' . $this->session->data['token'] . '&sort=r.author' . $url, 'SSL');
		$this->data['sort_rating'] = $this->url->link('catalog/comment', 'token=' . $this->session->data['token'] . '&sort=r.rating' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('catalog/comment', 'token=' . $this->session->data['token'] . '&sort=r.status' . $url, 'SSL');
		$this->data['sort_date_added'] = $this->url->link('catalog/comment', 'token=' . $this->session->data['token'] . '&sort=r.date_added' . $url, 'SSL');
		$url = '';
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		if (!isset($comment_total))
			$comment_total = 0;
		$pagination = new Pagination();
		$pagination->total = $comment_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/comment', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		$this->data['pagination'] = $pagination->render();
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		$this->data['filter_name'] = $filter_name;
		$this->template = 'catalog/comment_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}
	private function getForm()
	{
		$this->load->model('catalog/blogcomment');
		if (file_exists(DIR_APPLICATION . 'view/stylesheet/blog.css')) {
			$this->document->addStyle('view/stylesheet/blog.css');
		}
		$this->document->addStyle('http://fonts.googleapis.com/css?family=Open+Sans&subset=latin,cyrillic-ext,latin-ext');
		$this->document->addScript('view/javascript/wysibb/jquery.wysibb.min.js');
		$this->document->addStyle('view/javascript/wysibb/theme/default/wbbtheme.css');
		$this->data['config_language'] = $this->config->get('config_language');
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['entry_date_available'] = $this->language->get('entry_date_available');
		$this->data['entry_record'] = $this->language->get('entry_record');
		$this->data['entry_author'] = $this->language->get('entry_author');
		$this->data['entry_rating'] = $this->language->get('entry_rating');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_text'] = $this->language->get('entry_text');
		$this->data['entry_good'] = $this->language->get('entry_good');
		$this->data['entry_bad'] = $this->language->get('entry_bad');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['url_back'] = $this->url->link('module/blog', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_blog'] = $this->url->link('catalog/blog', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_record'] = $this->url->link('catalog/record', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_comment'] = $this->url->link('catalog/comment', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_blog_text'] = $this->language->get('url_blog_text');
		$this->data['url_record_text'] = $this->language->get('url_record_text');
		$this->data['url_comment_text'] = $this->language->get('url_comment_text');
		$this->data['url_create_text'] = $this->language->get('url_create_text');
		$this->data['url_back_text'] = $this->language->get('url_back_text');
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		if (isset($this->error['date_available'])) {
			$this->data['error_date_available'] = $this->error['date_available'];
		} else {
			$this->data['error_date_available'] = '';
		}
		if (isset($this->error['record'])) {
			$this->data['error_record'] = $this->error['record'];
		} else {
			$this->data['error_record'] = '';
		}
		if (isset($this->error['author'])) {
			$this->data['error_author'] = $this->error['author'];
		} else {
			$this->data['error_author'] = '';
		}
		if (isset($this->error['text'])) {
			$this->data['error_text'] = $this->error['text'];
		} else {
			$this->data['error_text'] = '';
		}
		if (isset($this->error['rating'])) {
			$this->data['error_rating'] = $this->error['rating'];
		} else {
			$this->data['error_rating'] = '';
		}
		$url = '';
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/comment', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);
		if (!isset($this->request->get['comment_id'])) {
			$this->data['action'] = $this->url->link('catalog/comment/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/comment/update', 'token=' . $this->session->data['token'] . '&comment_id=' . $this->request->get['comment_id'] . $url, 'SSL');
		}
		$this->data['cancel'] = $this->url->link('catalog/comment', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['token'] = $this->session->data['token'];
		if (isset($this->request->get['comment_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$comment_info = $this->model_catalog_blogcomment->getComment($this->request->get['comment_id']);
		}
		$this->load->model('catalog/record');
		if (isset($this->request->post['date_available'])) {
			$this->data['date_available'] = $this->request->post['date_available'];
		} elseif (!empty($comment_info)) {
			$this->data['date_available'] = date('Y-m-d H:i:s', strtotime($comment_info['date_added']));
		} else {
			$this->data['date_available'] = date('Y-m-d H:i:s');
		}
		if (isset($this->request->post['record_id'])) {
			$this->data['record_id'] = $this->request->post['record_id'];
		} elseif (!empty($comment_info)) {
			$this->data['record_id'] = $comment_info['record_id'];
		} else {
			$this->data['record_id'] = '';
		}
		if (isset($this->request->post['record'])) {
			$this->data['record'] = $this->request->post['record'];
		} elseif (!empty($comment_info)) {
			$this->data['record'] = $comment_info['record'];
		} else {
			$this->data['record'] = '';
		}
		if (isset($this->request->post['author'])) {
			$this->data['author'] = $this->request->post['author'];
		} elseif (!empty($comment_info)) {
			$this->data['author'] = $comment_info['author'];
		} else {
			$this->data['author'] = '';
		}
		if (isset($this->request->post['text'])) {
			$this->data['text'] = $this->request->post['text'];
		} elseif (!empty($comment_info)) {
			$this->data['text'] = $comment_info['text'];
		} else {
			$this->data['text'] = '';
		}
		$this->data['thislist']['signer'] = true;
		if (isset($this->request->post['rating'])) {
			$this->data['rating'] = $this->request->post['rating'];
		} elseif (!empty($comment_info)) {
			$this->data['rating'] = $comment_info['rating'];
		} else {
			$this->data['rating'] = '';
		}
		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (!empty($comment_info)) {
			$this->data['status'] = $comment_info['status'];
		} else {
			$this->data['status'] = '';
		}
		if (isset($this->request->post['af'])) {
			$this->data['af'] = $this->request->post['af'];
		} else {
			if (isset($this->request->get['comment_id'])) {
				$review_id = $this->request->get['comment_id'];
				$data = array(
					'review_id' => $this->request->get['comment_id'],
					'mark' => 'record_id'
				);
				$af = $this->model_catalog_blogcomment->getFields($data);
				foreach ($af as $val) {
					$this->data['af'] = $val;
				}
			} else {
				$review_id = false;
			}
		}
		$this->load->model('agoo/design/layout');
		$layout_id = $this->model_agoo_design_layout->getRecordLayoutId($this->data['record_id']);
		$mylist = $this->config->get('mylist');
		if (!function_exists('comp_field')) {
			function comp_field($a, $b)
			{
				if (!isset($a['field_order']) || $a['field_order'] == '')
					$a['field_order'] = '9999999';
				if (!isset($b['field_order']) || $b['field_order'] == '')
					$b['field_order'] = '9999999';
				$a['field_order'] = (int) $a['field_order'];
				$b['field_order'] = (int) $b['field_order'];
				if ($a['field_order'] > $b['field_order'])
					return 1;
				if ($b['field_order'] > $a['field_order'])
					return -1;
				return 0;
			}
		}
		if (!function_exists('group_by_key')) {
			function group_by_key($array)
			{
				$result = array();
				foreach ($array as $row) {
					if (!isset($result[$row['field_name']])) {
						$result[$row['field_name']] = $row;
					} else {
						$result[$row['field_name']] = $row;
					}
				}
				return array_values($result);
			}
		}
		$addfields = array();
		foreach ($mylist as $num => $val) {
			if (isset($val['addfields'])) {
				$addfields = array_merge($addfields, $val['addfields']);
			}
		}
		$addfields = group_by_key($addfields);
		usort($addfields, 'comp_field');
		$this->data['fields'] = $addfields;
		if (isset($this->data['af'])) {
			foreach ($this->data['fields'] as $num => $field) {
				foreach ($this->data['af'] as $anum => $af) {
					if ($field['field_name'] == $anum) {
						$this->data['fields'][$num]['value'] = $af;
					}
				}
			}
		}
		$loader_old = $this->registry->get('load');
		$this->load->library('agoo/loader');
		$agooloader = new agooLoader($this->registry);
		$this->registry->set('load', $agooloader);
		$this->load->model('catalog/treecomments', DIR_CATALOG);
		$customer_id = -1;
		if (!isset($this->request->get['comment_id'])) {
			$comment_id = false;
		} else {
			$comment_id = $this->request->get['comment_id'];
		}
		$this->data['karma'] = $this->model_catalog_treecomments->getRatesByCommentId($comment_id, 'record_id', true);
		$this->data['karma_all'] = $this->model_catalog_treecomments->getRatesByCommentId($comment_id, 'record_id');
		$this->registry->set('load', $loader_old);
		$this->template = 'catalog/comment_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}
	private function validateForm()
	{
		if (!$this->user->hasPermission('modify', 'catalog/comment')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!$this->request->post['record_id']) {
			$this->error['record'] = $this->language->get('error_record');
		}
		if ((utf8_strlen($this->request->post['author']) < 3) || (utf8_strlen($this->request->post['author']) > 64)) {
			$this->error['author'] = $this->language->get('error_author');
		}
		if (utf8_strlen($this->request->post['text']) < 1) {
			$this->error['text'] = $this->language->get('error_text');
		}
		if (!isset($this->request->post['rating'])) {
			$this->error['rating'] = $this->language->get('error_rating');
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	private function validateDelete()
	{
		if (!$this->user->hasPermission('modify', 'catalog/comment')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function autocomplete()
	{
		$json = array();
		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/record');
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}
			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}
			$data = array(
				'filter_name' => $filter_name,
				'start' => 0,
				'limit' => $limit
			);
			$results = $this->model_catalog_record->getRecords($data);
			foreach ($results as $result) {
				$option_data = array();
				$record_options = $this->model_catalog_record->getRecordOptions($result['record_id']);
				foreach ($record_options as $record_option) {
					if ($record_option['type'] == 'select' || $record_option['type'] == 'radio' || $record_option['type'] == 'checkbox' || $record_option['type'] == 'image') {
						$option_value_data = array();
						foreach ($record_option['record_option_value'] as $record_option_value) {
							$option_value_data[] = array(
								'record_option_value_id' => $record_option_value['record_option_value_id'],
								'option_value_id' => $record_option_value['option_value_id'],
								'name' => $record_option_value['name']
							);
						}
						$option_data[] = array(
							'record_option_id' => $record_option['record_option_id'],
							'option_id' => $record_option['option_id'],
							'name' => $record_option['name'],
							'type' => $record_option['type'],
							'option_value' => $option_value_data,
							'required' => $record_option['required']
						);
					} else {
						$option_data[] = array(
							'record_option_id' => $record_option['record_option_id'],
							'option_id' => $record_option['option_id'],
							'name' => $record_option['name'],
							'type' => $record_option['type'],
							'option_value' => $record_option['option_value'],
							'required' => $record_option['required']
						);
					}
				}
				$json[] = array(
					'record_id' => $result['record_id'],
					'name' => html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'),
					'blog' => $result['blog_name'],
					'option' => $option_data,
					'price' => $result['price']
				);
			}
		}
		$this->response->setOutput(json_encode($json));
	}
	private function signer($record_id, $record_info)
	{
		if (isset($record_info['comment'])) {
			$record_settings = unserialize($record_info['comment']);
		} else {
			$record_settings = array();
		}
		if (isset($record_settings['signer']) && $record_settings['signer']) {
			$this->data['record_id'] = $record_id;
			$this->data['record_info'] = $record_info;
			$this->getChild('common/seoblog');
			$this->data['record_info']['link'] = $this->url->link('record/record', '&record_id=' . $this->data['record_id']);
			$this->language->load('agoo/signer/signer');
			if (!class_exists('Customer')) {
				require_once(DIR_SYSTEM . 'library/customer.php');
			}
			$this->registry->set('customer', new Customer($this->registry));
			$this->data['login'] = $this->customer->getFirstName() . " " . $this->customer->getLastName();
			$this->data['customer_id'] = $this->customer->getId();
			if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
				$server = $this->config->get('config_ssl');
			} else {
				$server = $this->config->get('config_url');
			}
			if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
				$this->data['logo'] = $server . 'image/' . $this->config->get('config_logo');
			} else {
				$this->data['logo'] = false;
			}
			$this->load->model('agoo/signer/signer');
			$record_signers = $this->model_agoo_signer_signer->getRecordStatus($this->data['record_id']);
			if ($record_signers) {
				foreach ($record_signers as $par => $singers) {
					$this->template = 'module/blog_signer_mail.tpl';
					require_once(DIR_SYSTEM . 'library/bbcode.class.php');
					$this->request->post['text'] = strip_tags($this->request->post['text']);
					$text = BBCode::parse($this->request->post['text']);
					if ($this->rdate($this->language->get('text_date')) == $this->rdate($this->language->get('text_date'), strtotime(date('Y-m-d H:i:s')))) {
						$date_str = $this->language->get('text_today');
					} else {
						$date_str = $this->language->get('text_date');
					}
					$date_added = $this->rdate($date_str . $this->language->get('text_hours'), strtotime(date('Y-m-d H:i:s')));
					$customer = $this->model_agoo_signer_signer->getCustomer($singers['customer_id']);
					$this->request->post['name'] = $this->request->post['author'];
					foreach ($this->request->post as $comment) {
						$this->data['data'] = array(
							'text' => $text,
							'comment' => $this->request->post,
							'record' => $this->data['record_info'],
							'date' => $date_added,
							'shop' => $this->config->get('config_name'),
							'signers' => serialize($singers),
							'signer_customer' => $customer
						);
					}
					$html = $this->render();
					$message = $html;
					$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));
					$mail = new Mail();
					$mail->protocol = $this->config->get('config_mail_protocol');
					$mail->parameter = $this->config->get('config_mail_parameter');
					$mail->hostname = $this->config->get('config_smtp_host');
					$mail->username = $this->config->get('config_smtp_username');
					$mail->password = $this->config->get('config_smtp_password');
					$mail->port = $this->config->get('config_smtp_port');
					$mail->timeout = $this->config->get('config_smtp_timeout');
					$mail->setTo($customer['email']);
					$mail->setFrom($this->config->get('config_email'));
					$mail->setSender($this->config->get('config_name'));
					$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
					$mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
					$mail->send();
				}
			}
		}
	}
	public function rdate($param, $time = 0)
	{
		$this->language->load('module/blog');
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
			$str_begin = date(utf8_substr($param, 0, utf8_strpos($param, 'M')), $time);
			$str_middle = $MonthNames[date('n', $time) - 1];
			$str_end = date(utf8_substr($param, utf8_strpos($param, 'M') + 1, utf8_strlen($param)), $time);
			$str_date = $str_begin . $str_middle . $str_end;
			return $str_date;
		}
	}
}
?>