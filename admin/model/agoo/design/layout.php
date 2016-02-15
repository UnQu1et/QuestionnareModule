<?php
class ModelAgooDesignLayout extends Model
{
	public function getLayout($route)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "layout_route WHERE '" . $this->db->escape($route) . "' LIKE CONCAT(route, '%') AND store_id = '" . (int) $this->config->get('config_store_id') . "' ORDER BY route ASC LIMIT 1");
		$this->load->model('design/bloglayout');
		if ($route == 'record/blog' && isset($this->request->get['blog_id'])) {
			$path      = explode('_', (string) $this->request->get['blog_id']);
			$layout_id = $this->model_design_bloglayout->getBlogLayoutId(end($path));
			if ($layout_id)
				return $layout_id;
		} //$route == 'record/blog' && isset($this->request->get['blog_id'])
		if ($route == 'record/record' && isset($this->request->get['record_id'])) {
			$layout_id = $this->model_design_bloglayout->getRecordLayoutId($this->request->get['record_id']);
			if ($layout_id)
				return $layout_id;
		} //$route == 'record/record' && isset($this->request->get['record_id'])
		if ($query->num_rows) {
			return $query->row['layout_id'];
		} //$query->num_rows
		else {
			return 0;
		}
	}


	public function getLayoutRoute($route)
	{
	 $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "layout_route WHERE route = '" .  $route . "' AND store_id = '" . (int) $this->config->get('config_store_id') . "'");
	 if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
		 return false;
		}
	}


	public function getRecordLayoutId($record_id , $mark ='record')
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX .$mark."_to_layout WHERE ".$mark."_id = '" . (int) $record_id . "' AND store_id = '" . (int) $this->config->get('config_store_id') . "'");
		if ($query->num_rows) {
			return $query->row['layout_id'];
		} //$query->num_rows
		else {
			return $this->getLayoutRoute($mark."/".$mark);
		}
	}


}
?>