<?php
class agooModelDesignLayout extends Controller
{
	protected  $Layout;

   public function __call($name, array $params)
   {
        $object = 'ModelDesignLayout';
		$this->Layout =  new $object($this->registry);
        $data = call_user_func_array(array($this->Layout , $name), $params);

       $route = $this->getRouteByLayoutId($data);

       if ($route=='record/blog' || $route=='record/record') {
       	// проверяем схему в настройках для категории или записи
       	$data = $this->getLayoutAgoo($route);
       }

        return $data;
   }


	public function getLayoutAgoo($route)
	{
  		if ($this->config->get("loader_old") && !$this->config->get("blog_work")) {
	        $this->registry->set('load', $this->config->get("loader_old"));
	        $this->config->set("loader_old", false );
        }

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

	public function getRouteByLayoutId($layout_id)
	{
   		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "layout_route WHERE layout_id='" . (int)$layout_id . "' AND store_id = '" . (int) $this->config->get('config_store_id') . "' ORDER BY route ASC LIMIT 1");
        if ($query->num_rows) {
			return $query->row['route'];
		} //$query->num_rows
		else {
			return false;
		}

	}



}
?>