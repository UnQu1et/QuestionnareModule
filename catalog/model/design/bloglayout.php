<?php
class ModelDesignBlogLayout extends Model
{
	public function getLayout($layout_id)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "layout WHERE layout_id = '" . (int) $layout_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");
		return $query->row;
	}
	public function getLayouts($data = array())
	{
		$sql       = "SELECT * FROM " . DB_PREFIX . "layout";
		$sort_data = array(
			'name'
		);
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} //isset($data['sort']) && in_array($data['sort'], $sort_data)
		else {
			$sql .= " ORDER BY name";
		}
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} //isset($data['order']) && ($data['order'] == 'DESC')
		else {
			$sql .= " ASC";
		}
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			} //$data['start'] < 0
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			} //$data['limit'] < 1
			$sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
		} //isset($data['start']) || isset($data['limit'])
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function getLayoutRoutes($layout_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "layout_route WHERE layout_id = '" . (int) $layout_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");
		return $query->rows;
	}
	public function getTotalLayouts()
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "layout");
		return $query->row['total'];
	}
	public function getRecordLayoutId($record_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "record_to_layout WHERE record_id = '" . (int) $record_id . "' AND store_id = '" . (int) $this->config->get('config_store_id') . "'");
		if ($query->num_rows) {
			return $query->row['layout_id'];
		} //$query->num_rows
		else {
			return false;
		}
	}
	public function getBlogLayoutId($blog_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_to_layout WHERE blog_id = '" . (int) $blog_id . "' AND store_id = '" . (int) $this->config->get('config_store_id') . "'");
		if ($query->num_rows) {
			return $query->row['layout_id'];
		} //$query->num_rows
		else {
			return false;
		}
	}
}
?>