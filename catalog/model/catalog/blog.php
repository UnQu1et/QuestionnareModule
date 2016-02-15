<?php
class ModelCatalogBlog extends Model
{
	public function getBlog($blog_id)
	{

		if ($this->customer->isLogged()) {
			$customer_query = " AND (c.customer_group_id = '" . (int) $this->customer->getCustomerGroupId() . "' OR c.customer_group_id = '" . (int) $this->config->get('config_customer_group_id') . "')";
		} //$this->customer->isLogged()
		else {
			$customer_query = " AND c.customer_group_id = '" . (int) $this->config->get('config_customer_group_id') . "'";
		}
		$cache_name = 'blog.blogis.' . (int) $this->config->get('config_language_id') . "." . (int) $this->config->get('config_store_id').".".(int) $this->customer->getCustomerGroupId();
		$cdata      = $this->cache->get($cache_name);
		if (!isset($cdata[$blog_id])) {
			$query           = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "blog c
			LEFT JOIN " . DB_PREFIX . "blog_description cd ON (c.blog_id = cd.blog_id)
			LEFT JOIN " . DB_PREFIX . "blog_to_store c2s ON (c.blog_id = c2s.blog_id)
			WHERE c.blog_id = '" . (int) $blog_id . "' AND cd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int) $this->config->get('config_store_id') . "'
			AND c.status = '1'
			" . $customer_query . "
			");
			$cdata[$blog_id] = $query->row;
			$this->cache->set($cache_name, $cdata);
		} //!isset($cdata[$blog_id])
		return $cdata[$blog_id];
	}
	public function getBlogies($parent_id = 0)
	{
		if ($this->customer->isLogged()) {
			$customer_query = " AND (c.customer_group_id = '" . (int) $this->customer->getCustomerGroupId() . "' OR c.customer_group_id = '" . (int) $this->config->get('config_customer_group_id') . "')";
		} //$this->customer->isLogged()
		else {
			$customer_query = " AND c.customer_group_id = '" . (int) $this->config->get('config_customer_group_id') . "'";
		}
		$query = $this->db->query("
		SELECT * FROM " . DB_PREFIX . "blog c
		LEFT JOIN " . DB_PREFIX . "blog_description cd ON (c.blog_id = cd.blog_id)
		LEFT JOIN " . DB_PREFIX . "blog_to_store c2s ON (c.blog_id = c2s.blog_id)
		WHERE c.parent_id = '" . (int) $parent_id . "' AND cd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int) $this->config->get('config_store_id') . "'  AND c.status = '1'
		" . $customer_query . "
		ORDER BY c.sort_order, LCASE(cd.name)");
		return $query->rows;
	}
	public function getBlogs()
	{
		if ($this->customer->isLogged()) {
			$customer_query = " AND (c.customer_group_id = '" . (int) $this->customer->getCustomerGroupId() . "' OR c.customer_group_id = '" . (int) $this->config->get('config_customer_group_id') . "')";
		} //$this->customer->isLogged()
		else {
			$customer_query = " AND c.customer_group_id = '" . (int) $this->config->get('config_customer_group_id') . "'";
		}
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog c LEFT JOIN " . DB_PREFIX . "blog_description cd ON (c.blog_id = cd.blog_id) LEFT JOIN " . DB_PREFIX . "blog_to_store c2s ON (c.blog_id = c2s.blog_id) WHERE cd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int) $this->config->get('config_store_id') . "'  AND c.status = '1'
		" . $customer_query . "
		ORDER BY c.sort_order, LCASE(cd.name)");
		return $query->rows;
	}
	public function getPathByrecord($record_id)
	{
		$cache_file = 'record.blogs.' . (int) $this->config->get('config_store_id') . '.' . (int) $this->config->get('config_language_id');

		$record_id = (int) $record_id;
		if ($record_id < 1)
			return false;
		static $path = null;
		if (!is_array($path)) {
			$path = $this->cache->get($cache_file);
			if (!is_array($path))
				$path = array();
		} //!is_array($path)
		if (!isset($path[$record_id])) {
			$sql              = "SELECT r2b.blog_id,
			IF(r.blog_main=r2b.blog_id, 1, 0) as blog_main

			FROM " . DB_PREFIX . "record_to_blog r2b
			LEFT JOIN " . DB_PREFIX . "record r  ON (r.record_id = r2b.record_id)
			LEFT JOIN " . DB_PREFIX . "record_description pd ON (r.record_id = pd.record_id)
			WHERE
			pd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND
			r2b.record_id = '" . (int)$record_id . "' ORDER BY blog_main DESC LIMIT 1";
			$query            = $this->db->query($sql);
			$path[$record_id] = $this->getPathByblog($query->num_rows ? (int) $query->row['blog_id'] : 0);
			$this->cache->set($cache_file, $path);
		} //!isset($path[$record_id])
		return $path[$record_id];
	}
	public function getPathByBlog($blog_id)
	{
		$cache_file = 'blog.blogs.' . (int) $this->config->get('config_store_id') . '.' . (int) $this->config->get('config_language_id');
		if (strpos($blog_id, '_') !== false) {
			$abid    = explode('_', $blog_id);
			$blog_id = $abid[count($abid) - 1];
		} //strpos($blog_id, '_') !== false
		$blog_id = (int) $blog_id;
		if ($blog_id < 1)
			return false;
		static $path = null;
		if (!is_array($path)) {
			$path = $this->cache->get($cache_file);
			if (!is_array($path))
				$path = array();
		} //!is_array($path)
		if (!isset($path[$blog_id])) {
			$max_level = 10;
			$sql       = "SELECT td.name as name, CONCAT_WS('_'";
			for ($i = $max_level - 1; $i >= 0; --$i) {
				$sql .= ",t$i.blog_id";
			} //$i = $max_level - 1; $i >= 0; --$i
			$sql .= ") AS path FROM " . DB_PREFIX . "blog t0";
			for ($i = 1; $i < $max_level; ++$i) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "blog t$i ON (t$i.blog_id = t" . ($i - 1) . ".parent_id)";
			} //$i = 1; $i < $max_level; ++$i
			$sql .= "LEFT JOIN " . DB_PREFIX . "blog_description td ON ( td.blog_id = t0.blog_id )";
			$sql .= " WHERE
			td.language_id = '" . (int) $this->config->get('config_language_id') . "' AND
			t0.blog_id = '" . (int)$blog_id . "'";
			$query                  = $this->db->query($sql);
			$path[$blog_id]['path'] = $query->num_rows ? $query->row['path'] : false;
			$path[$blog_id]['name'] = $query->num_rows ? $query->row['name'] : false;
			$this->cache->set($cache_file, $path);
		} //!isset($path[$blog_id])
		return $path[$blog_id];
	}
	public function getPathByCategory($category_id)
	{
		$cache_file = 'category.blog.' . (int) $this->config->get('config_store_id') . '.' . (int) $this->config->get('config_language_id');

		$category_id = (int) $category_id;
		if ($category_id < 1)
			return false;
		static $path = null;
		if (!is_array($path)) {
			$path = $this->cache->get($cache_file);
			if (!is_array($path))
				$path = array();
		} //!is_array($path)
		if (!isset($path[$category_id])) {
			$max_level = 10;
			$sql       = "SELECT td.name as name, CONCAT_WS('_'";
			for ($i = $max_level - 1; $i >= 0; --$i) {
				$sql .= ",t$i.category_id";
			} //$i = $max_level - 1; $i >= 0; --$i
			$sql .= ") AS path FROM " . DB_PREFIX . "category t0";
			for ($i = 1; $i < $max_level; ++$i) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "category t$i ON (t$i.category_id = t" . ($i - 1) . ".parent_id)";
			} //$i = 1; $i < $max_level; ++$i
			$sql .= "LEFT JOIN " . DB_PREFIX . "category_description td ON ( td.category_id = t0.category_id )";
			$sql .= " WHERE
			td.language_id = '" . (int) $this->config->get('config_language_id') . "' AND
			t0.category_id = '" . (int)$category_id . "'";
			$query                      = $this->db->query($sql);
			$path[$category_id]['path'] = $query->num_rows ? $query->row['path'] : false;
			$path[$category_id]['name'] = $query->num_rows ? $query->row['name'] : false;
			$this->cache->set($cache_file, $path);
		} //!isset($path[$category_id])
		return $path[$category_id];
	}
	public function getBlogiesByParentId($blog_id)
	{
		$blog_data  = array();
		$blog_query = $this->db->query("SELECT blog_id FROM " . DB_PREFIX . "blog WHERE parent_id = '" . (int) $blog_id . "'");
		foreach ($blog_query->rows as $blog) {
			$blog_data[] = $blog['blog_id'];
			$children    = $this->getBlogiesByParentId($blog['blog_id']);
			if ($children) {
				$blog_data = array_merge($children, $blog_data);
			} //$children
		} //$blog_query->rows as $blog
		return $blog_data;
	}
	public function getBlogLayoutId($blog_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_to_layout WHERE blog_id = '" . (int) $blog_id . "' AND store_id = '" . (int) $this->config->get('config_store_id') . "'");
		if ($query->num_rows) {
			return $query->row['layout_id'];
		} //$query->num_rows
		else {
			return $this->config->get('config_layout_blog');
		}
	}
	public function getTotalBlogiesByBlogId($parent_id = 0)
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blog c
		LEFT JOIN " . DB_PREFIX . "blog_to_store c2s ON (c.blog_id = c2s.blog_id) WHERE c.parent_id = '" . (int) $parent_id . "' AND c2s.store_id = '" . (int) $this->config->get('config_store_id') . "' AND c.status = '1'");
		return $query->row['total'];
	}
}
?>