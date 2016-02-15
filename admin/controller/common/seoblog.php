<?php
class ControllerCommonSeoBlog extends Controller
{
	private $blog_design = Array();
	public function index()
	{
		if ($this->config->get('config_seo_url')) {
			$this->url->addRewrite($this);
		}
		if (isset($_GET['_route_']))
			$this->request->get['_route_'] = $_GET['_route_'];
		if (isset($_GET['route']))
			$this->request->get['route'] = $_GET['route'];
		$this->flag = 'none';
		if ((isset($this->request->get['route']) && $this->request->get['route'] == 'record/search') || (isset($this->request->get['_route_']) && $this->request->get['_route_'] == 'record/search')) {
			$this->request->get['route'] = 'record/search';
			if (isset($this->request->get['_route_'])) {
				$_route_ = $this->request->get['_route_'];
				unset($this->request->get['_route_']);
			}
			if ($this->config->get('config_seo_url')) {
				$this->validate();
			}
			if (isset($this->request->get['_route_'])) {
				$this->request->get['_route_'] = $_route_;
			}
			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 200 OK');
			return $this->flag = "search";
		}
		if (isset($this->request->get['record_id'])) {
			$this->request->get['route']   = 'record/record';
			$this->request->get['blog_id'] = $this->getPathByRecord($this->request->get['record_id']);
			if (isset($this->request->get['_route_'])) {
				$_route_ = $this->request->get['_route_'];
				unset($this->request->get['_route_']);
			}
			if ($this->config->get('config_seo_url')) {
				$this->validate();
			}
			if (isset($this->request->get['_route_'])) {
				$this->request->get['_route_'] = $_route_;
			}
			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 200 OK');
			return $this->flag = 'record';
		}
		if (isset($this->request->get['blog_id'])) {
			$this->request->get['route']   = 'record/blog';
			$this->request->get['blog_id'] = $this->getPathByBlog($this->request->get['blog_id']);
			if (isset($this->request->get['_route_'])) {
				$_route_ = $this->request->get['_route_'];
				unset($this->request->get['_route_']);
			}
			if ($this->config->get('config_seo_url')) {
				$this->validate();
			}
			if (isset($this->request->get['_route_'])) {
				$this->request->get['_route_'] = $_route_;
			}
			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 200 OK');
			return $this->flag = 'blog';
		}
		if ($this->config->get('generallist') != '') {
			$this->data['settings_general'] = $this->config->get('generallist');
		} else {
			$this->data['settings_general'] = Array();
		}
		if (isset($this->request->get['_route_'])) {
			$this->load->model('design/bloglayout');
			$this->data['layouts'] = $this->model_design_bloglayout->getLayouts();
			$route                 = $this->request->get['_route_'];
			if (isset($this->data['settings_general']['end_url_record']) && $this->data['settings_general']['end_url_record'] != '') {
				$devider = $this->data['settings_general']['end_url_record'];
				if (strrpos($route, $devider) !== false) {
					$route = substr_replace($route, '', strrpos($route, $devider), strlen($route));
				}
			}
			$route     = trim($route, '/');
			$parts     = explode('/', $route);
			$parts_end = end($parts);
			if (strpos($parts_end, 'page-') !== false) {
				list($key, $value) = explode("-", $parts_end);
				if ($value != 1) {
					$this->request->get[$key] = $value;
				}
				$title = $this->document->getTitle();
				$this->document->setTitle($title . " " . $key . " " . $value);
				$this->config->set('blog_page', $value);
				unset($parts[count($parts) - 1]);
			}
			reset($parts);
			if (isset($this->request->get['record_id']) && $this->request->get['record_id'] != '') {
				array_push($parts, 'record_id=' . $this->request->get['record_id']);
			}
			foreach ($parts as $part) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias_blog WHERE keyword = '" . $this->db->escape($part) . "'");
				if (!$query->num_rows && isset($this->request->get['record_id']) && $this->request->get['record_id'] != '') {
					$query->num_rows     = 1;
					$query->row['query'] = 'record_id=' . $this->request->get['record_id'];
				}
				if ($query->num_rows) {
					$url = explode('=', $query->row['query']);
					if (isset($url[0]) && $url[0] == 'record_id') {
						$this->request->get['record_id'] = $url[1];
						$path                            = $this->getPathByRecord($this->request->get['record_id']);
						$this->flag                      = 'record';
						$layout                          = 0;
						foreach ($this->data['layouts'] as $num => $lay) {
							if ($lay['name'] == 'Record')
								$layout = $lay['layout_id'];
						}
						$this->config->set("config_layout_id", $layout);
					} else {
						if (isset($url[0]) && $url[0] == 'blog_id') {
							$this->flag = 'blog';
							$layout     = 0;
							foreach ($this->data['layouts'] as $num => $lay) {
								if ($lay['name'] == 'Blog')
									$layout = $lay['layout_id'];
							}
							$this->config->set("config_layout_id", $layout);
							if (!isset($this->request->get['blog_id'])) {
								$this->request->get['blog_id'] = $url[1];
							} else {
								$this->request->get['blog_id'] .= '_' . $url[1];
							}
						}
					}
					if (isset($url[0]) && $url[0] == 'record/search') {
						$this->flag = 'search';
						$layout     = 0;
						foreach ($this->data['layouts'] as $num => $lay) {
							if ($lay['name'] == 'Search_Record')
								$layout = $lay['layout_id'];
						}
						$this->config->set("config_layout_id", $layout);
						if (!isset($this->request->get['record/search'])) {
							$this->request->get['route'] = 'record/search';
						} else {
							$this->request->get['route'] = 'record/search';
						}
					}
					if (isset($url[0]) && $url[0] == 'route') {
						$this->request->get['route'] = $url[1];
					}
				} else {
				}
			}
			$flg = false;
			if (isset($this->request->get['record_id'])) {
				$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 200 OK');
				$this->request->get['route'] = 'record/record';
				$flg                         = true;
			} elseif (isset($this->request->get['blog_id'])) {
				$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 200 OK');
				$this->request->get['route'] = 'record/blog';
				$flg                         = true;
			} elseif (isset($this->request->get['record/search'])) {
				$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 200 OK');
				$this->request->get['route'] = 'record/search';
				$flg                         = true;
			}
			if ($flg) {
				$_route_ = $this->request->get['_route_'];
				unset($this->request->get['_route_']);
				if ($this->config->get('config_seo_url')) {
					$this->validate();
				}
				$this->request->get['_route_'] = $_route_;
				if (isset($this->request->get['route'])) {
					$this->request->get['_route_'] = $this->request->get['route'];
				}
				return $this->flag;
			}
		}
	}
	public function rewrite($link)
	{
		if ($this->config->get('config_seo_url')) {
			$url_data = parse_url(str_replace('&amp;', '&', $link));
			$url      = '';
			$data     = array();
			if (isset($url_data['query'])) {
				parse_str($url_data['query'], $data);
			}
			foreach ($data as $num => $name) {
				if ($name != 'record_id' && $name != '' && $name != 'route' && $name != 'blog_id') {
					if (isset($data[$name]))
						unset($data[$name]);
				}
			}
			reset($data);
			if (isset($data['record_id'])) {
				$record_id = $data['record_id'];
				if ($this->config->get('config_seo_url')) {
					$path = $this->getPathByRecord($record_id);
				}
				$data['path'] = $path;
			}
			$flag_record = false;
			foreach ($data as $key => $value) {
				if (isset($data['route'])) {
					if ($key == 'blog_id') {
						$path = $this->getPathByBlog($value);
					}
					if ($key == 'path') {
						$categories = explode('_', $value);
						$new        = array_reverse($categories);
						foreach ($new as $category) {
							$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias_blog WHERE `query` = 'blog_id=" . (int) $category . "'");
							if ($query->num_rows) {
								$url = '/' . $query->row['keyword'] . $url;
							}
						}
						unset($data[$key]);
					}
					if (($data['route'] == 'record/record' && $key == 'record_id')) {
						$flag_record = true;
						$query       = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias_blog WHERE `query` = '" . $this->db->escape($key . '=' . (int) $value) . "'");
						if ($query->num_rows) {
							$url = '/' . $query->row['keyword'];
							unset($data[$key]);
						}
					} elseif ($key == 'blog_id' && !$flag_record) {
						$categories = explode('_', $value);
						if (count($categories) == 1) {
							$path       = $this->getPathByBlog($categories[0]);
							$categories = explode('_', $path);
						}
						foreach ($categories as $category) {
							$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias_blog WHERE `query` = 'blog_id=" . (int) $category . "'");
							if ($query->num_rows) {
								$url .= '/' . $query->row['keyword'];
							}
						}
						unset($data[$key]);
					}
					if ($flag_record && $key == 'blog_id') {
						unset($data[$key]);
					} else {
						if ($url == '') {
							$sql   = "SELECT * FROM " . DB_PREFIX . "url_alias_blog WHERE `query` = '" . $this->db->escape($key . '=' . $value) . "'";
							$query = $this->db->query($sql);
							if ($query->num_rows) {
								$url .= '/' . $query->row['keyword'];
							}
						}
					}
				}
			}
			if ($url) {
				unset($data['route']);
				if ($this->config->get('generallist') != '') {
					$this->data['settings_general'] = $this->config->get('generallist');
				} else {
					$this->data['settings_general'] = Array();
				}
				if (isset($this->blog_design['blog_devider']) && $this->blog_design['blog_devider'] == '1') {
					$devider = "/";
				} else {
					$devider = "";
				}
				if (strpos($url, '.') !== false) {
					$devider = "";
				} else {
					if (!$flag_record) {
						if (isset($this->blog_design['end_url_category']) && $this->blog_design['end_url_category'] != '') {
							$devider = $this->blog_design['end_url_category'];
						}
					} else {
						if (isset($this->data['settings_general']['end_url_record']) && $this->data['settings_general']['end_url_record'] != '') {
							$devider = $this->data['settings_general']['end_url_record'];
						}
					}
				}
				$query  = '';
				$paging = '';
				if ($data) {
					foreach ($data as $key => $value) {
						if ($key != 'page') {
							$query .= '&' . $key . '=' . $value;
						} else {
							if ($devider != '/')
								$paging = "/" . $key . "-" . $value;
							else
								$paging = $key . "-" . $value;
						}
					}
					if ($query) {
						$query = '?' . trim($query, '&');
					}
				}
				$link = $url_data['scheme'] . '://' . $url_data['host'] . (isset($url_data['port']) ? ':' . $url_data['port'] : '') . str_replace('/index.php', '', $url_data['path']) . $url . $devider . $paging . $query;
				return $link;
			} else {
				return $link;
			}
		} else {
			return $link;
		}
	}
	private function getPathByRecord($record_id)
	{
		if (utf8_strpos($record_id, '_') !== false) {
			$abid      = explode('_', $record_id);
			$record_id = $abid[count($abid) - 1];
		}
		$record_id = (int) $record_id;
		if ($record_id < 1)
			return false;
		static $path = null;
		if (!is_array($path)) {
			$path        = $this->cache->get('record.seopath');
			$blog_design = $this->cache->get('record.blog_design');
			if (!is_array($path))
				$path = array();
		}
		if (!isset($path[$record_id]) || !isset($blog_design[$record_id])) {
			$sql              = "SELECT r2b.blog_id as blog_id,
			IF(r.blog_main=r2b.blog_id, 1, 0) as blog_main
			FROM " . DB_PREFIX . "record_to_blog r2b
			LEFT JOIN " . DB_PREFIX . "record r  ON (r.record_id = r2b.record_id)
			WHERE r2b.record_id = '" . (int) $record_id . "' ORDER BY blog_main DESC LIMIT 1";
			$query            = $this->db->query($sql);
			$path[$record_id] = $this->getPathByBlog($query->num_rows ? (int) $query->row['blog_id'] : 0);
			if (utf8_strpos($path[$record_id], '_') !== false) {
				$abid    = explode('_', $path[$record_id]);
				$blog_id = $abid[count($abid) - 1];
			} else {
				$blog_id = (int) $path[$record_id];
			}
			$blog_id = (int) $blog_id;
			$this->load->model('catalog/blog');
			$blog_info = $this->model_catalog_blog->getBlog($blog_id);
			if (isset($blog_info['design']) && $blog_info['design'] != '') {
				$this->blog_design = unserialize($blog_info['design']);
			} else {
				$this->blog_design = Array();
			}
			if (isset($blog_info['design'])) {
				$blog_design[$record_id] = $blog_info['design'];
			} else {
				$blog_design[$record_id] = array();
			}
			$this->cache->set('record.blog_design', $blog_design);
			$this->cache->set('record.seopath', $path);
		} else {
			if (isset($blog_design[$record_id]) && is_string($blog_design[$record_id])) {
				$this->blog_design = unserialize($blog_design[$record_id]);
			} else {
				$this->blog_design = Array();
			}
		}
		if (isset($this->blog_design['blog_short_path']) && $this->blog_design['blog_short_path'] == 1)
			$path[$record_id] = '';
		return $path[$record_id];
	}
	private function getPathByBlog($blog_id)
	{
		if (utf8_strpos($blog_id, '_') !== false) {
			$abid    = explode('_', $blog_id);
			$blog_id = $abid[count($abid) - 1];
		}
		$blog_id = (int) $blog_id;
		if ($blog_id < 1)
			return false;
		static $path = null;
		$this->load->model('catalog/blog');
		$blog_info = $this->model_catalog_blog->getBlog($blog_id);
		if (isset($blog_info['design']) && $blog_info['design'] != '') {
			$this->blog_design = unserialize($blog_info['design']);
		} else {
			$this->blog_design = Array();
		}
		if (!is_array($path)) {
			$path = $this->cache->get('blog.seopath');
			if (!is_array($path))
				$path = array();
		}
		if (!isset($path[$blog_id])) {
			$max_level = 10;
			$sql       = "SELECT CONCAT_WS('_'";
			for ($i = $max_level - 1; $i >= 0; --$i) {
				$sql .= ",t$i.blog_id";
			}
			$sql .= ") AS path FROM " . DB_PREFIX . "blog t0";
			for ($i = 1; $i < $max_level; ++$i) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "blog t$i ON (t$i.blog_id = t" . ($i - 1) . ".parent_id)";
			}
			$sql .= " WHERE t0.blog_id = '" . (int) $blog_id . "'";
			$query          = $this->db->query($sql);
			$path[$blog_id] = $query->num_rows ? $query->row['path'] : false;
			$this->cache->set('blog.seopath', $path);
		}
		return $path[$blog_id];
	}
private function validate()
	{
		if (isset($this->request->get['route']) && $this->request->get['route'] == 'error/not_found') {
			return;
		}
		if (empty($this->request->get['route'])) {
			$this->request->get['route'] = 'common/home';
		}
		if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			return;
		}
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$config_url = substr($this->config->get('config_ssl'), 0, $this->strpos_offset('/', $this->config->get('config_ssl'), 3) + 1);
			$url        = str_replace('&amp;', '&',  ltrim($this->request->server['REQUEST_URI'], '/'));
			$seo        = str_replace('&amp;', '&', str_replace($config_url,'', $this->url->link($this->request->get['route'], $this->getQueryString(array(
				'route'
			)), 'SSL')));
		} else {
			$config_url = substr($this->config->get('config_url'), 0, $this->strpos_offset('/', $this->config->get('config_url'), 3) + 1);
			 $url        = str_replace('&amp;', '&',  ltrim($this->request->server['REQUEST_URI'], '/'));
			 $seo        = str_replace('&amp;', '&', str_replace($config_url,'',$this->url->link($this->request->get['route'], $this->getQueryString(array(
				'route'
			)), 'NONSSL')));
		}
		if (rawurldecode($url) != rawurldecode($seo)) {
			header($this->request->server['SERVER_PROTOCOL'] . ' 301 Moved Permanently');
			$this->response->redirect($config_url.$seo);
		}
	}
	private function strpos_offset($needle, $haystack, $occurrence)
	{
		$arr = explode($needle, $haystack);
		switch ($occurrence) {
			case $occurrence == 0:
				return false;
			case $occurrence > max(array_keys($arr)):
				return false;
			default:
				return strlen(implode($needle, array_slice($arr, 0, $occurrence)));
		}
	}
	private function getQueryString($exclude = array())
	{
		if (!is_array($exclude)) {
			$exclude = array();
		}
		return urldecode(http_build_query(array_diff_key($this->request->get, array_flip($exclude))));
	}
}
?>