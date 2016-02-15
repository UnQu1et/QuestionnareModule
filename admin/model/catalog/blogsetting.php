<?php
class ModelCatalogBlogSetting extends Model
{
	public function getSetting($group, $store_id = 0)
	{
		$data  = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int) $store_id . "' AND `group` = '" . $group . "'");
		foreach ($query->rows as $result) {
			if (!$result['serialized']) {
				$data[$result['key']] = $result['value'];
			} //!$result['serialized']
			else {
				$data[$result['key']] = unserialize($setting['value']);
			}
		} //$query->rows as $result
		return $data;
	}
	public function editSetting($group, $data, $store_id = 0)
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int) $store_id . "' AND `group` = '" . $group . "'");
		foreach ($data as $key => $value) {
			if (!is_array($value)) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int) $store_id . "', `group` = '" . $group . "', `key` = '" . $key . "', `value` = '" . $value . "'");
			} //!is_array($value)
			else {
				$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int) $store_id . "', `group` = '" . $group . "', `key` = '" . $key . "', `value` = '" . (serialize($value)) . "', serialized = '1'");
			}
		} //$data as $key => $value
	}
	public function deleteSetting($group, $store_id = 0)
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int) $store_id . "' AND `group` = '" . $group . "'");
	}
}
?>