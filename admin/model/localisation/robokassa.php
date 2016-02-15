<?php  /* robokassa metka */
class ModelLocalisationRobokassa extends Model {
	public function updateExtentions( $data=array() ) {
	
		$this->db->query("DELETE FROM " . DB_PREFIX . "extension WHERE `type`='payment' AND `code` LIKE 'robokassa%' AND `code`!='robokassa'");
		
		if($data)
		{
			foreach($data as $number)
			{
				$this->db->query("INSERT INTO " . DB_PREFIX . "extension (`type`, `code`) 
				VALUES ('payment', 'robokassa".$number."')");
			}
		}
	}

}
?>