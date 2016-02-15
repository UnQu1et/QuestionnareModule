<?php
class ModelAccountDownload extends Model {
	public function getDownload($order_download_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_download od LEFT JOIN `" . DB_PREFIX . "order` o ON (od.order_id = o.order_id) WHERE o.customer_id = '" . (int)$this->customer->getId(). "' AND o.order_status_id > '0' AND o.order_status_id = '" . (int)$this->config->get('config_complete_status_id') . "' AND od.order_download_id = '" . (int)$order_download_id . "' AND od.remaining > 0");

		return $query->row;
	}
	
	public function getDownloads($start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}
		
		if ($limit < 1) {
			$limit = 20;
		}	
		
		$query = $this->db->query("SELECT o.order_id, o.date_added, od.order_download_id, od.name, od.filename, od.remaining FROM " . DB_PREFIX . "order_download od LEFT JOIN `" . DB_PREFIX . "order` o ON (od.order_id = o.order_id) WHERE o.customer_id = '" . (int)$this->customer->getId() . "' AND o.order_status_id > '0' AND o.order_status_id = '" . (int)$this->config->get('config_complete_status_id') . "' AND od.remaining > 0 ORDER BY o.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);
	
		return $query->rows;
	}
	
	public function updateRemaining($order_download_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "order_download SET remaining = (remaining - 1) WHERE order_download_id = '" . (int)$order_download_id . "'");
	}
	
	public function getTotalDownloads() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_download od LEFT JOIN `" . DB_PREFIX . "order` o ON (od.order_id = o.order_id) WHERE o.customer_id = '" . (int)$this->customer->getId() . "' AND o.order_status_id > '0' AND o.order_status_id = '" . (int)$this->config->get('config_complete_status_id') . "' AND od.remaining > 0");
		
		return $query->row['total'];
	}	
	
	public function getPageDownloads($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "download d LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE dd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$sql .= " AND d.mask = 'download'";
		if($data['information_id']){
			$sql .= " AND d.information_id = ".(int)$data['information_id']."";
		}
		

		$sort_data = array(
			'dd.name',
			'd.date_added',
			'd.remaining'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY dd.name";	
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}			

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}
}
?>
