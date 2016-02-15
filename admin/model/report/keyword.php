<?php

define('DIR_CSVPRICE_PRO', DIR_SYSTEM . 'csvprice_pro');

class ModelReportKeyword extends Model {
	
	private $CSV_SEPARATOR = ';';
	private $CSV_ENCLOSURE = '"';

	public function getSearchedWordsReport($data) {
	
		$sql = "SELECT * FROM `report_search` order by {$data['sort']} {$data['order']}"  ;
		$query = $this->db->query($sql);

		return $query->rows;
	}
	public function getTotalSearchedWords() {
		$query = $this->db->query("SELECT * FROM `report_search`");
		
		return $query->num_rows;
	}
	public function reset() {
		$this->db->query("delete from `report_search` ");
	}
	
	public function get_tmp_dir() {
		return DIR_CSVPRICE_PRO;
	}
	
	public function delete($ids) {
		$ids = implode(',',$ids);
		$this->db->query("delete from `report_search` WHERE report_search_id IN({$ids}) ");
	}
	
	//-------------------------------------------------------------------------
	// Export Data to CSV
	//-------------------------------------------------------------------------
	public function export($data) {
	
		$data['file_format'] = 'csv';
		$output = '';
		setlocale(LC_ALL, 'ru_RU.UTF-8');
		
		$sql = "SELECT manufacturer,term,quantity,result,ipadd,time FROM `report_search` order by time DESC"  ;
		
		$query = $this->db->query($sql);
		
			//EXPORT CSV FORMAT
			
			$charset = ini_get('default_charset');
			ini_set('default_charset', 'UTF-8');
			$tmp = $this->get_tmp_dir();
			$uid = uniqid();
			$tmp_dir = $tmp . '/' . $uid . '/';
			$file = $tmp . '/' . $uid.'.csv';
			
			if (($handle = fopen($file, 'w')) !== FALSE) {
				// CSV Caption Title
				fputcsv($handle, $ods_title, $this->CSV_SEPARATOR, $this->CSV_ENCLOSURE);
				
				foreach ($query->rows as $fields) {
					
					foreach ($fields as $fields_k => $fields_v) {
					
						$fields[$fields_k] = str_replace("\n\r",'',$fields[$fields_k]);
						$fields[$fields_k] = str_replace("\r\n",'',$fields[$fields_k]);
						$fields[$fields_k] = str_replace("\n",'',$fields[$fields_k]);						
						$fields[$fields_k] = str_replace("<br/>"," ### ",$fields[$fields_k]);						
						$fields[$fields_k] = strip_tags($fields[$fields_k]);						
						$fields[$fields_k] = iconv("UTF-8","windows-1251",$fields[$fields_k]);
					}
					
					if(isset($fields['sku']) AND $fields['sku'] != '' ) {
						$fields['sku'] = '*'.$fields['sku'];
					}
					if(isset($fields['description']) AND $fields['description'] != '' ) {
						$fields['description'] = htmlspecialchars_decode($fields['description']);
					}
						fputcsv($handle, $fields, $this->CSV_SEPARATOR, $this->CSV_ENCLOSURE);
				}
				fclose($handle);
			} else {
				return '';
			}
				
			if (($output = file_get_contents($file)) !== FALSE ) {
				unlink($file);
				return $output;
			} else {
				return '';
			}
			
	}
}
?>