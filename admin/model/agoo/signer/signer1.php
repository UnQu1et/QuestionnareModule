<?php
class ModelagooSignerSigner extends Model
{
	public function getStatus($id, $customer_id)
	{
		$pointer = 'record_id';
		$sql     = "SELECT * FROM `" . DB_PREFIX . "agoo_signer` WHERE id =" . (int) $id . " AND  customer_id =" . (int) $customer_id . " AND pointer='" . $pointer . "' LIMIT 1";
		$query   = $this->db->query($sql);
		if ($query)
			return $query->row;
		else
			return false;
	}
	public function getRecordStatus($id)
	{
		$pointer = 'record_id';
		$sql     = "SELECT * FROM `" . DB_PREFIX . "agoo_signer` WHERE id =" . (int) $id . " AND pointer='" . $pointer . "'";
		$query   = $this->db->query($sql);
		return $query->rows;
	}
	public function getCustomer($customer_id)
	{
		$sql   = "SELECT * FROM `" . DB_PREFIX . "customer` WHERE customer_id =" . (int) $customer_id . " LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row;
	}
	public function setStatus($id, $customer_id)
	{
		$pointer  = 'record_id';
		$datetime = date('Y-m-d H:i:s');
		$sql      = "INSERT INTO `" . DB_PREFIX . "agoo_signer` SET  pointer='" . $pointer . "', id =" . (int) $id . ", customer_id =" . (int) $customer_id . ", date='" . $datetime . "' ";
		$query    = $this->db->query($sql);
		if ($query) {
			return true;
		} //$query
		else {
			return false;
		}
	}
	public function removeStatus($id, $customer_id)
	{
		$pointer = 'record_id';
		$sql     = "DELETE FROM `" . DB_PREFIX . "agoo_signer` WHERE id =" . (int) $id . " AND  customer_id =" . (int) $customer_id . " AND pointer='" . $pointer . "'";
		$query   = $this->db->query($sql);
		if ($query) {
			return true;
		} //$query
		else {
			return false;
		}
	}
}
?>