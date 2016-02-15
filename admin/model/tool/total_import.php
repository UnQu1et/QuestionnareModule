<?php
#####################################################################################
#  Module TOTAL IMPORT PRO for Opencart 1.5.x From HostJars opencart.hostjars.com 	#
#####################################################################################

class ModelToolTotalImport extends Model 
{
	private $xml_product;
	private $xml_existing_fields = array();
	private $total_items_added = 0;
	private $total_items_updated = 0;
	private $total_items_missed = 0;	//wrong number of fields in CSV row
	private $total_items_ready = 0;		//in hj_import db ready for store import
	private $file_encoding = 'UTF-8';	//file encoding of input file
	
	/*ONIK*/
	// оптимизируем вставку
	private $buffer_data = array();	//file encoding of input file
	private $max_insert_to_one_sql = 50;	//file encoding of input file
	private $count_sql = 0;	//file encoding of input file
	/*ONIK*/
	
	public $user_agent = 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3';
	public $cookie = 'sunsky_cookie.ini';
	 
	public function checkUpdates() {
	
		define('CURRENT_VERSION', 12);
		return 0;
		//$latest = @file_get_contents('http://demo.hostjars.com/version.php?mod=TotalImportPRO');
	}
	
	public function getExistingProducts($identifier='model',$data = false) {
	
		if(!$data['tovartype_id']){
			return array();
		}
	
		$sql = "SELECT p." . $identifier . " FROM " . DB_PREFIX . "product as p";
		
		if($data['tovartype_id'] != false){
			$sql .= " WHERE tovartype_id = '{$data['tovartype_id']}'";
		}
		
		$query = $this->db->query($sql);
		
		$prod_array = array();
		foreach ($query->rows as $row) {
			$prod_array[$row[$identifier]] = 0;
		}
		return $prod_array;
	}
	
	public function disableProduct($product_id) {
		$query = $this->db->query('UPDATE ' . DB_PREFIX . 'product SET status = 0 WHERE product_id = ' . (int)$product_id);
	}
	
	public function statusNoneProduct($product_id) {
		$query = $this->db->query('UPDATE ' . DB_PREFIX . 'product SET stock_status_id = 5 WHERE product_id = ' . (int)$product_id);
	}
	
	public function zeroQuantityProduct($product_id) {
		$query = $this->db->query('UPDATE ' . DB_PREFIX . 'product SET quantity = 0 WHERE product_id = ' . (int)$product_id);
	}
	
	public function getManufacturerId($manufacturer_name) {
		$query = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer WHERE name = '" . $this->db->escape($manufacturer_name) . "'");
		return (isset($query->row['manufacturer_id'])) ? $query->row['manufacturer_id'] : 0;
	}
	
	public function getCategoryId($category_name, $parentid) {
		$query = $this->db->query("SELECT c.category_id FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE (cd.name = '" . $this->db->escape($category_name) . "' OR cd.name = '" . $this->db->escape(htmlentities($category_name)) . "') AND c.parent_id = '" . (int)$parentid . "'");
		return (isset($query->row['category_id'])) ? $query->row['category_id'] : 0;
	}
	
	public function getDownloadIdByName($download_file) {
		$query = $this->db->query("SELECT d.download_id FROM " . DB_PREFIX . "download d LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE (dd.name = '" . $this->db->escape($download_file) . "' OR dd.name = '" . $this->db->escape(htmlentities($download_file)) . "')");
		return (isset($query->row['download_id'])) ? $query->row['download_id'] : 0;
	}
	
	public function getAttributeId($attribute_name, $attribute_group) {
		$query = $this->db->query("SELECT a.attribute_id FROM " . DB_PREFIX . "attribute a LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE ad.name = '" . $this->db->escape($attribute_name) . "' AND a.attribute_group_id = '" . (int)$attribute_group . "'");
		return (isset($query->row['attribute_id'])) ? $query->row['attribute_id'] : 0;
	}
	
	public function getAttributeGroupId($attribute_name) {
		$query = $this->db->query("SELECT attribute_group_id FROM " . DB_PREFIX . "attribute_group_description WHERE name = '" . $this->db->escape($attribute_name) . "'");
		return (isset($query->row['attribute_group_id'])) ? $query->row['attribute_group_id'] : 0;
	}
	
	public function getProductId($id_field, $id_value, $tovartype_id = false) {
		if ($id_field == 'name') {
		
			$sql = "SELECT pd.product_id FROM " . DB_PREFIX . "product_description as pd";
			
			if($tovartype_id !== false AND is_numeric($tovartype_id)){
				$sql .= " LEFT JOIN " . DB_PREFIX . "product as p ON(p.product_id = pd.product_id) ";
			}
		
				$sql .= " WHERE pd.name = '" . $this->db->escape($id_value) . "'";
			
			if($tovartype_id !== false AND is_numeric($tovartype_id)){
				$sql .= " AND p.tovartype_id = ".(int)$tovartype_id." ";
			}
			
			$query = $this->db->query($sql);
		} else {
		
			$sql = "SELECT product_id FROM " . DB_PREFIX . "product WHERE " . $this->db->escape($id_field) . " = '" . $this->db->escape($id_value) . "'";
			
			if($tovartype_id !== false AND is_numeric($tovartype_id)){
				$sql .= " AND tovartype_id = ".(int)$tovartype_id." ";
			}
		
			$query = $this->db->query($sql);
		}
		return (isset($query->row['product_id'])) ?	$query->row['product_id'] : 0;
	}
	
	public function getOptions($options) {
		$all_values = array();
		foreach ($options as $option) {
			if ($option) {
				$sql = 'SELECT `' . $option . '` FROM ' . DB_PREFIX . 'hj_import';
				$query = $this->db->query($sql);
				$values = array();
				$exists = array();
				foreach ($query->rows as $row) {
					$opt_values = explode('|', $row[$option]);
					foreach ($opt_values as $opt_value) {
						$opt_value_details = explode(':', $opt_value);
						if(!in_array($opt_value_details[0], $exists)) {
							if (count($opt_value_details) == 5) { //we have a sort order
								$values[] = $opt_value_details[0] . '|' . $opt_value_details[4];
								$exists[] = $opt_value_details[0];
							} else {
								$values[] = $opt_value_details[0];
								$exists[] = $opt_value_details[0];
							}
						}
					}
				}
				$all_values[$option] = array_unique($values);
			}
		}
		return $all_values;
	}
	
	public function getOptionIdByName($name) {
		$query = $this->db->query("SELECT option_id FROM " . DB_PREFIX . "option_description WHERE name='" . $this->db->escape($name) . "'");
		return (count($query->rows)) ? $query->rows[0]['option_id'] : 0;
	}

	public function getOptionValueIdByName($name, $option_id) {
		$query = $this->db->query("SELECT option_value_id FROM " . DB_PREFIX . "option_value_description WHERE name='" . $this->db->escape($name) . "' AND option_id='" . (int)$option_id . "'");
		return (count($query->rows)) ? $query->rows[0]['option_value_id'] : 0;
	}

	public function emptyTables() {
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product_attribute");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "attribute");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "attribute_description");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "attribute_group");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "attribute_group_description");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product_description");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product_discount");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product_image");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product_option");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product_option_value");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product_related");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product_reward");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product_special");
		if(version_compare($this->getVersion(), '1.5.4', '<')) {
			$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product_tag");
		}
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product_to_category");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product_to_download");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product_to_layout");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product_to_store");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "manufacturer");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "manufacturer_to_store");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "category");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "category_description");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "category_to_layout");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "category_to_store");
		if(version_compare($this->getVersion(), '1.5.5', '>=')) {
			$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "category_path");
		}
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "review");
		
		// Special query to delete any product related SEO Keywords
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query LIKE 'product_id=%'");
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query LIKE 'category_id=%'");
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query LIKE 'manufacturer_id=%'");
		
		$this->cache->delete('product');
		
	}

	//Functions to adjust data on the way in:
	
	public function getOperations($hide_func=true) {
		$operations = array(
			'multiplyPrice' => array(
				'name' => $this->language->get('operation_multiply_price'),
				'function' => ($hide_func) ? '' : 'multiply', 
				'inputs'=>array(
					array('type'=>'field', 'prepend'=>$this->language->get('operation_multiply')),
					array('type'=>'text', 'prepend'=>$this->language->get('operation_by'))
				),
				'label' => $this->language->get('operation_label_most_popular'),
			),
			'addPrice' => array(
				'name' => $this->language->get('operation_add_price'),
				'function' => ($hide_func) ? '' : 'add', 
				'inputs'=>array(
					array('type'=>'text', 'prepend'=>$this->language->get('operation_add')),
					array('type'=>'field', 'prepend'=>$this->language->get('operation_to'))
				),
				'label' => $this->language->get('operation_label_most_popular'),
			),
			'splitFieldsCategory' => array(
				'name' => $this->language->get('operation_split_fields_category'),
				'function' => ($hide_func) ? '' : 'splitFields', 
				'inputs'=>array(
					array('type'=>'field', 'prepend'=>$this->language->get('operation_split')),
					array('type'=>'text', 'prepend'=>$this->language->get('operation_on')),
				),
				'label' => $this->language->get('operation_label_most_popular'),
			),
			'appendImage' => array(
				'name' => $this->language->get('operation_append_image'),
				'function' => ($hide_func) ? '' : "appendText",
				'inputs'=>array(
					array('type'=>'text', 'prepend'=>$this->language->get('operation_append')),
					array('type'=>'field', 'prepend'=>$this->language->get('operation_after'))
				),
				'label' => $this->language->get('operation_label_most_popular'),
			),
			'prependImage' => array( 
				'name' => $this->language->get('operation_prepend_image'),
				'function' => ($hide_func) ? '' : 'prependText',
				'inputs'=>array(
					array('type'=>'text', 'prepend'=>$this->language->get('operation_prepend')),
					array('type'=>'field', 'prepend'=>$this->language->get('operation_to'))
				),
				'label' => $this->language->get('operation_label_most_popular'),
			),
			'append' => array(
				'name' => $this->language->get('operation_append_text'),
				'function' => ($hide_func) ? '' : "appendText",
				'inputs'=>array(
					array('type'=>'text', 'prepend'=>$this->language->get('operation_append')),
					array('type'=>'field', 'prepend'=>$this->language->get('operation_after'))
				),
				'label' => $this->language->get('operation_label_advanced'),
			),
			'prepend' => array( 
				'name' => $this->language->get('operation_prepend_text'),
				'function' => ($hide_func) ? '' : 'prependText',
				'inputs'=>array(
					array('type'=>'text', 'prepend'=>$this->language->get('operation_prepend')),
					array('type'=>'field', 'prepend'=>$this->language->get('operation_to'))
				),
				'label' => $this->language->get('operation_label_advanced'),
			),
			'multiply' => array(
				'name' => $this->language->get('operation_multiply_field'),
				'function' => ($hide_func) ? '' : 'multiply', 
				'inputs'=>array(
					array('type'=>'field', 'prepend'=>$this->language->get('operation_multiply')),
					array('type'=>'text', 'prepend'=>$this->language->get('operation_by'))
				),
				'label' => $this->language->get('operation_label_advanced'),
			),
			'add' => array(
				'name' => $this->language->get('operation_add_field'),
				'function' => ($hide_func) ? '' : 'add', 
				'inputs'=>array(
					array('type'=>'text', 'prepend'=>$this->language->get('operation_add'),),
					array('type'=>'field', 'prepend'=>$this->language->get('operation_to'),)
				),
				'label' => $this->language->get('operation_label_advanced'),
			),
			'splitFields' => array(
				'name' => $this->language->get('operation_split_fields'),
				'function' => ($hide_func) ? '' : 'splitFields', 
				'inputs'=>array(
					array('type'=>'field', 'prepend'=>$this->language->get('operation_split')),
					array('type'=>'text', 'prepend'=>$this->language->get('operation_on')),
				),
				'label' => $this->language->get('operation_label_advanced'),
			),
			'replace' => array( 
				'name'=> $this->language->get('operation_replace_text'),
				'function' => ($hide_func) ? '' : 'replaceText',
				'inputs'=>array(
					array('type'=>'text', 'prepend'=>$this->language->get('operation_replace')),
					array('type'=>'text', 'prepend'=>$this->language->get('operation_with')),
					array('type'=>'field', 'prepend'=>$this->language->get('operation_in'))
				),
				'label' => $this->language->get('operation_label_advanced'),
			),
			'replaceNewLines' => array( 
				'name'=> $this->language->get('operation_replace_newlines'),
				'function' => ($hide_func) ? '' : 'replaceNewLines',
				'inputs'=>array(
					array('type'=>'field', 'prepend'=>$this->language->get('operation_in'))
				),
				'label' => $this->language->get('operation_label_advanced'),
			),
			'remove' => array( 
				'name'=> $this->language->get('operation_remove_text'),
				'function' => ($hide_func) ? '' : 'removeText',
				'inputs'=>array(
					array('type'=>'text', 'prepend'=>$this->language->get('operation_remove')),
					array('type'=>'field', 'prepend'=>$this->language->get('operation_in'))
				),
				'label' => $this->language->get('operation_label_advanced'),
			),
			'deleteRow' => array(
				'name' => $this->language->get('operation_delete_row_equals'),
				'function' => ($hide_func) ? '' : 'deleteRowsWhere', 
				'inputs'=>array(
					array('type'=>'field', 'prepend'=>$this->language->get('operation_exclude_products')),
					array('type'=>'text', 'prepend'=>$this->language->get('operation_equals'))
				),
				'label' => $this->language->get('operation_label_most_popular'),
			),
			'deleteRowWhereNot' => array(
				'name' => $this->language->get('operation_delete_row_not_equal'),
				'function' => ($hide_func) ? '' : 'deleteRowsWhereNot', 
				'inputs'=>array(
					array('type'=>'field', 'prepend'=>$this->language->get('operation_exclude_products')),
					array('type'=>'text', 'prepend'=>$this->language->get('operation_does_not_equal'))
				),
				'label' => $this->language->get('operation_label_most_popular'),
			),
			'deleteRowContains' => array(
				'name' => $this->language->get('operation_delete_row_containing'),
				'function' => ($hide_func) ? '' : 'deleteRowsWhereContains', 
				'inputs'=>array(
					array('type'=>'field', 'prepend'=>$this->language->get('operation_exclude_products')),
					array('type'=>'text', 'prepend'=>$this->language->get('operation_contains'))
				),
				'label' => $this->language->get('operation_label_advanced'),
			),
			'deleteRowWhereNotContains' => array(
				'name' => $this->language->get('operation_delete_row_not_containing'),
				'function' => ($hide_func) ? '' : 'deleteRowsWhereNotContains', 
				'inputs'=>array(
					array('type'=>'field', 'prepend'=>$this->language->get('operation_exclude_products')),
					array('type'=>'text', 'prepend'=>$this->language->get('operation_does_not_contain'))
				),
				'label' => $this->language->get('operation_label_advanced'),
			),
			'duplicateField' => array(
				'name' => $this->language->get('operation_duplicate_feed'),
				'function' => ($hide_func) ? '' : 'duplicateField', 
				'inputs'=>array(
					array('type'=>'field', 'prepend'=>$this->language->get('operation_duplicate')),
					array('type'=>'text', 'prepend'=>$this->language->get('operation_to'))
				),
				'label' => $this->language->get('operation_label_advanced'),
			),
			'mergeColumns' => array(
				'name' => $this->language->get('operation_merge_columns'),
				'function' => ($hide_func) ? '' : 'mergeColumns', 
				'inputs'=>array(
					array('type'=>'field', 'prepend'=>$this->language->get('operation_append')),
					array('type'=>'field', 'prepend'=>$this->language->get('operation_to')),
					array('type'=>'text', 'prepend'=>$this->language->get('operation_separated_by'))
				),
				'label' => $this->language->get('operation_label_advanced'),
			),
			'mergeRows' => array(
				'name' => $this->language->get('operation_merge_rows'),
				'function' => ($hide_func) ? '' : 'mergeRows', 
				'inputs'=>array(
					array('type'=>'field', 'prepend'=>$this->language->get('operation_common_field')),
					array('type'=>'field', 'prepend'=>$this->language->get('operation_merge_the_following'), 'option' => 'addMore'),
				),
				
				'label' => $this->language->get('operation_label_advanced'),
			),
		);
		return $operations;
	}
	
	public function runAdjustments(&$adjustments) {
		$operations = $this->getOperations(false);
		foreach ($adjustments as $adjustment) {
			//ensure all adjustment values are decoder for operations
			$adjustment = array_map('html_entity_decode', $adjustment);
			$op_name = array_shift($adjustment);
			//run each adjustment
			if (is_callable(array($this, $operations[$op_name]['function']))) {
				$adjustment_fields = array();
				$adjustment_fields[] = $adjustment;
				if (!in_array($this->language->get('text_select'), $adjustment)) {call_user_func_array(array($this, $operations[$op_name]['function']), $adjustment_fields);}
			}
		}
	}
	
	/**
	 * @param (mixed) array(text to append to, field to adjust)
	 */
	public function appendText($adjustment) {
		$append_text = $adjustment[0];
		$field = $adjustment[1];
		$this->db->query('UPDATE ' . DB_PREFIX . "hj_import SET `" .$field . "` = CONCAT( `" . $field . "`, '" . $this->db->escape($append_text) . "' ) WHERE `" .$field . "` != ''");
	}
	
	/**
	 * @param (mixed) array(text to prepend to, field to adjust)
	 */
	public function prependText($adjustment) {
		$prepend_text = $adjustment[0];
		$field = $adjustment[1];
		$this->db->query('UPDATE ' . DB_PREFIX . "hj_import SET `" . $field . "` = CONCAT( '" . $this->db->escape($prepend_text) . "', `" . $field . "` ) WHERE `" .$field . "` != ''");
	}
	
	/**
	 * @param (mixed) array(text to remove, field to adjust)
	 */
	public function removeText($adjustment) {
		$remove_text = $adjustment[0];
		$field = $adjustment[1];
		$this->db->query('UPDATE ' . DB_PREFIX . "hj_import SET `" .$field . "` = REPLACE( `" . $field . "`, '" . $this->db->escape($remove_text) . "', '' )");
	}
	
	/**
	 * @param (mixed) array(text to find, text to replace with, field to adjust)
	 */
	public function replaceText($adjustment) {
		$str = $adjustment[0];
		$replacement = $adjustment[1];
		$field = $adjustment[2];
		$this->db->query('UPDATE ' . DB_PREFIX . "hj_import SET `" . $field . "` = REPLACE( `" . $field . "`, '" . $this->db->escape($str) . "', '" . $this->db->escape($replacement) . "' )");
	}
	
	/**
	* @param (mixed) array(field to adjust)
	*/
	public function replaceNewLines($adjustment) {
		$new_lines = array("\r\n", "\n", "\r");
		$replacement = "<br />";
		$field = $adjustment[0];
		foreach($new_lines as $str) {
			$this->db->query('UPDATE ' . DB_PREFIX . "hj_import SET `" . $field . "` = REPLACE( `" . $field . "`, '" . $this->db->escape($str) . "', '" . $this->db->escape($replacement) . "' )");
		}
	}
	
	/**
	* @param (mixed) array(field to adjust, multiplication factor)
	*/
	public function multiply($adjustment) {
		$field = $adjustment[0];
		$multiplier = $adjustment[1];
		$this->db->query('UPDATE ' . DB_PREFIX . "hj_import SET `" . $field . "` = (`" . $field . "` * " . (float)$multiplier . " )");
	}
	
	/**
	* @param (mixed) array(field to  add value, adjust)
	*/
	public function add($adjustment) {
		$add = $adjustment[0];
		$field = $adjustment[1];
		$this->db->query('UPDATE ' . DB_PREFIX . "hj_import SET `" . $field . "` = (`" . $field . "` + " . (float)$add . " )");
	}
	
	/**
	* @param (mixed) array(field to  adjust, new field)
	*/
	public function duplicateField($adjustment) {
		$field = $adjustment[0];
		$newfield = $adjustment[1];
		$this->db->query('ALTER TABLE ' . DB_PREFIX . "hj_import ADD `" . $newfield . "` BLOB");
		$this->db->query('UPDATE ' . DB_PREFIX . "hj_import SET `" . $newfield . "` = (`" . $field . "`)");
	}
	
	/**
	* @param (mixed) array(field to adjust)
	*/
	public function lowerCase($adjustment) {
		$field = $adjustment[0];
		$this->db->query('UPDATE ' . DB_PREFIX . "hj_import SET `" . $field . "` = LCASE( `" . $field . "` )");
	}
	
	/**
	* @param (mixed) array(field to adjust)
	*/
	public function upperCase($adjustment) {
		$field = $adjustment[0];
		$this->db->query('UPDATE ' . DB_PREFIX . "hj_import SET `" . $field . "` = UCASE( " . $field . " )");
	}

	/**
	* @param (mixed) array(field to adjust)
	*/
//	public function capitalize(&$adjust) {
//		$this->db->query('UPDATE ' . DB_PREFIX . "hj_import SET `" . $adjust[0] . "` = CAP_FIRST( '" . $adjust[0] . "' )");
//	}
	
	/**
	* @param (mixed) array(field to adjust, text to look for)
	*/
	public function deleteRowsWhereContains($adjustment) {
		$field = $adjustment[0];
		$value = $adjustment[1];
		$this->db->query('DELETE FROM ' . DB_PREFIX . "hj_import WHERE `" . $field . "` LIKE '%" . $this->db->escape($value) . "%'");
	}
	
	/**
	* @param (mixed) array(field to adjust, text to look for)
	*/
	public function deleteRowsWhereNotContains($adjustment) {
		$field = $adjustment[0];
		$value = $adjustment[1];
		$this->db->query('DELETE FROM ' . DB_PREFIX . "hj_import WHERE `" . $field . "` NOT LIKE '%" . $this->db->escape($value) . "%'");
	}
	/**
	* @param (mixed) array(field to adjust, text to look for)
	*/
	public function deleteRowsWhere($adjustment) {
		$field = $adjustment[0];
		$value = $adjustment[1];
		$this->db->query('DELETE FROM ' . DB_PREFIX . "hj_import WHERE `" . $field . "` = '" . $this->db->escape($value) . "'");
	}
	
	/**
	* @param (mixed) array(field to adjust, text to look for)
	*/
	public function deleteRowsWhereNot($adjustment) {
		$field = $adjustment[0];
		$value = $adjustment[1];
		$this->db->query('DELETE FROM ' . DB_PREFIX . "hj_import WHERE `" . $field . "` != '" . $this->db->escape($value) . "'");
	}
	
	/**
	* @param (mixed) array(field to adjust, text to look for)
	*/
	public function mergeColumns($adjustment) {
		$field1 = $adjustment[0];
		$field2 = $adjustment[1];
		$separator = $adjustment[2];
		$this->db->query('UPDATE ' . DB_PREFIX . "hj_import SET `" . $field2 . "` = CONCAT(`" . $field2 . "`, '" . $this->db->escape($separator) . "', `" . $field1 . "`)");
	}
	
	/**
	* @param (mixed) array(field to adjust, text to look for)
	*/
	public function mergeRows($adjustment) {
		$common_field = array_shift($adjustment);
		
		//get all unique product id's
		$sql = 'SELECT DISTINCT `' . $this->db->escape($common_field) . '` FROM ' . DB_PREFIX . 'hj_import ORDER BY `' . $this->db->escape($common_field) . '` DESC';
		$query = $this->db->query($sql);
		foreach($query->rows as $model) {
			$unique_products[] = $model[$common_field];
		}
		foreach($unique_products as $unique) {
			//get each of the adjustment values and concatonate
			$sql = 'SELECT *, '; 
			foreach($adjustment as $adjust) {
				$sql .= "GROUP_CONCAT(`".$this->db->escape($adjust) . "` SEPARATOR '|') as `" . $this->db->escape($adjust) . "`, ";
			}
			$sql = substr($sql, 0, -2);
			$sql .= " FROM `" . DB_PREFIX ."hj_import` WHERE  `" .$this->db->escape($common_field)."`='". $this->db->escape($unique) ."' GROUP BY `". $this->db->escape($common_field) . "`";
			$query = $this->db->query($sql);
			
			$current_products = $query->row;
			
			//update the first product with the set sku
			$sql = 'UPDATE `' . DB_PREFIX . "hj_import` SET ";
			foreach($adjustment as $adjust) {
				$sql .= "`" . $this->db->escape($adjust) . "` = '". $this->db->escape($current_products[$adjust]) . "', ";
			}
			$sql = substr($sql, 0, -2);
			$sql .= " WHERE `hj_id` = '" . $this->db->escape($current_products['hj_id']) . "'";
			$query = $this->db->query($sql);
			
			//remove all additional products
			$sql = 'DELETE FROM `' . DB_PREFIX . "hj_import` WHERE ";
			$sql .= "`hj_id` != '" . $this->db->escape($current_products['hj_id']) . "' AND `". $this->db->escape($common_field) . "` = '" . $this->db->escape($unique) . "'";
			$query = $this->db->query($sql);
		}
		
	}
	
	public function getNextProductOrdered($start, $update_column) {
		$query = $this->db->query('SELECT * FROM ' . DB_PREFIX . 'hj_import ORDER BY ' . $this->db->escape($update_column) . ' DESC LIMIT ' . (int)$start . ', 1');
		return (isset($query->row)) ? $query->row : 0;
	}
	
	/**
	* @param (mixed) array(field name, separator to split on, and new column prefix)
	*/
	public function splitFields($adjustment) {
		$field1 = $adjustment[0];
		$separator = $adjustment[1];
		
		//get the max number of columns required
		$sql = 'SELECT MAX(LENGTH(`' . $field1 . "`) - LENGTH(REPLACE(`" . $field1 . "`, '" . $separator . "', ''))) AS 'new_columns' FROM `" . DB_PREFIX . "hj_import`";
		$query = $this->db->query($sql);
		$new_columns = $query->row['new_columns'] + 1;
		
		//create the new columns
		for($i = 1; $i <= $new_columns; $i++){
			$new_field = $field1 . "_split_" . $i;
			if(!$this->columnExists(DB_PREFIX . 'hj_import', $new_field)) {
				$new_field = array($new_field);
				$this->alterImportTable($new_field);
			}
		}
		
		//update each column with the correct values
		$sql = 'SELECT `hj_id`, `' . $field1 . '` FROM `' . DB_PREFIX . 'hj_import`';
		$query = $this->db->query($sql);
		foreach($query->rows as $row) {
			$values = explode($separator, $row[$field1]);
			//create the new columns
			for($i = 1; $i <= count($values); $i++){
				$new_field = $field1 . "_split_" . $i;
				$sql = 'UPDATE `' . DB_PREFIX . "hj_import` SET `" . $this->db->escape($new_field) . "` = '". $this->db->escape($values[$i-1]) . "' WHERE `hj_id` = '" . $this->db->escape($row['hj_id']) . "'";
				$query = $this->db->query($sql);
			}
		}
	}
	
	//Internal database table functions:
	
	public function createEmptyTable($headings) {
		$this->db->query('DROP TABLE IF EXISTS ' . DB_PREFIX . 'hj_import');
		$sql = 'CREATE TABLE ' . DB_PREFIX . 'hj_import (hj_id INT(11) AUTO_INCREMENT, ';
		foreach ($headings as $heading) {
			$sql .= "`" . $heading . "` BLOB, ";
		}
		$sql .= 'PRIMARY KEY (hj_id))';
		$query = $this->db->query($sql);
	}
	
	public function alterImportTable($new_fields) {
		if (!empty($new_fields)) {
			$sql = "ALTER TABLE " . DB_PREFIX . "hj_import ADD COLUMN ";
			$fields_sql = array();
			foreach ($new_fields as $field) {
				$fields_sql[] = '`' . $this->db->escape($field) . "` BLOB NOT NULL ";
			}
			$sql .= '(' . $this->db->escape(implode(', ', $fields_sql)) . ')';
			$sql = str_replace(', )', ')', $sql);
			$this->db->query($sql);
		}
	}
	
	public function columnExists($table, $column_name){
		$sql = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS
		WHERE TABLE_NAME = '" . $table . "' AND TABLE_SCHEMA = '" . DB_DATABASE . " '
		AND COLUMN_NAME = '" . $column_name . "'";
		$query = $this->db->query($sql);
		return (isset($query->row['COLUMN_NAME']) && $query->row['COLUMN_NAME'] == $column_name) ? true : false;
	}
	
	public function dbReady() {	
		//$query = $this->db->query("SHOW FULL TABLES WHERE `Tables_in_" . DB_DATABASE . "` = '" . DB_PREFIX . "hj_import'");
		return 1;
	}
	
	
	
	public function insertProduct($product) {
		$sql = 'INSERT INTO ' . DB_PREFIX . 'hj_import SET ';
		$values = array();
		foreach ($product as $key => $value) {
			if($this->file_encoding != 'UTF-8') {
				$value = iconv($this->file_encoding, 'UTF-8//TRANSLIT', $value);
				$key = iconv($this->file_encoding, 'UTF-8//TRANSLIT', $key);
			}
			$key = trim($key);
			$value = trim($value);
			$values[] = '`' . $key . "`='" . $this->db->escape($value) . "'";
		}
		$sql .= implode(',', $values);
		$query = $this->db->query($sql);
	}
	
	public function getNextProduct($start=0) {
		$query = $this->db->query('SELECT * FROM ' . DB_PREFIX . 'hj_import LIMIT ' . (int)$start . ', 1');
		return $query->row;
	}
	
	public function getSavedSettingNames() {
	
		//create db if doesn't exist.
		$sql = 'CREATE TABLE IF NOT EXISTS ' . DB_PREFIX . 'hj_import_settings (`id` INT(11) AUTO_INCREMENT, `group` VARCHAR(255), `step` INT(11), `name` BLOB, `value` BLOB, PRIMARY KEY (id))';
		$this->db->query($sql);
		$query = $this->db->query("SELECT DISTINCT(`group`) FROM " . DB_PREFIX . "hj_import_settings");
		$names = array();
		foreach ($query->rows as $row) {
			$names[] = $row['group'];
		}
		
		$sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'hj_import_category` (
				`id`  int(11) NOT NULL AUTO_INCREMENT ,
				`category_id`  int(11) NULL DEFAULT NULL ,
				`category_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
				PRIMARY KEY (`id`),
				UNIQUE INDEX `uniqe_category_name` (`category_name`)
				)
				ENGINE=MyISAM
				DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
				AUTO_INCREMENT=82
				CHECKSUM=0
				ROW_FORMAT=DYNAMIC
				DELAY_KEY_WRITE=0
				;';
				
		$this->db->query($sql);
		
		/* price_list_name */
		/*$sql = "show columns FROM `" . DB_PREFIX . "product` WHERE `Field` = 'price_list_name'";

		$query = $this->db->query($sql);
		
		if(count($query->row) == 0){
			$sql = "ALTER TABLE `" . DB_PREFIX . "product` ADD `price_list_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Имя прайса из которого импортировался данный товар'";			
			$this->db->query($sql);
		}*/
		/* price_list_name */
		
		return $names;
	}
	
	public function saveSettings($name) {
		
		$this->load->model('setting/setting');
		$settings = array(
			$this->model_setting_setting->getSetting('import_step1'),
			$this->model_setting_setting->getSetting('import_step2'),
			$this->model_setting_setting->getSetting('import_step3'),
			$this->model_setting_setting->getSetting('import_step4'),
			$this->model_setting_setting->getSetting('import_step5')
		);
		
		//get settings from step1-5 and save them with a name in $data
		$this->db->query('DELETE FROM ' . DB_PREFIX . "hj_import_settings WHERE `group` = '" . $this->db->escape($name) . "'");
		for ($i=0; $i<count($settings); $i++) {
			foreach ($settings[$i] as $key => $value) {
				$this->db->query('INSERT INTO ' . DB_PREFIX . "hj_import_settings SET `group` = '" . $this->db->escape($name) . "', `step` = '" . (int)($i+1) . "', `name` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'"); 
			}
		}
	}
	
	public function loadSettings($name) {
		$this->load->model('setting/setting');
		for ($i=1; $i<=5; $i++) {
			$settings = array(); 
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "hj_import_settings WHERE `group` = '" . $this->db->escape($name) . "' AND `step` = " . $i);
			foreach ($query->rows as $result) {
				$settings[$result['name']] = $result['value'];
			}
			$this->model_setting_setting->editSetting('import_step' . $i, $settings);
		}
	}
	
	public function deleteSettings($group) {
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "hj_import_settings WHERE `group` = '" . $this->db->escape($group) . "'");
	}
	
	
	public function fetchFeed(&$settings, $unzip_feed=false) {
		$success = false;
		$filename = DIR_APPLICATION . 'feed.txt';
		if(isset($settings['source'])) {
			if ($settings['source'] == 'file') {
				if (defined('CLI_INITIATED')) {
					$success = true; //we will do it with whatever feed is on the filesystem, no need to fetch.
				} elseif (is_uploaded_file($this->request->files['feed_file']['tmp_name'])) {
					if ($this->request->files['feed_file']['error'] == UPLOAD_ERR_OK) {
						$success = move_uploaded_file($this->request->files['feed_file']['tmp_name'], $filename);
					} else {
						$success = false;
					}
				}
			} elseif ($settings['source'] == 'url') {
				$ch = curl_init();
				$fp = fopen($filename, 'w');
				$url = str_replace('&amp;', '&', $settings['feed_url']);
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_FILE, $fp);
				if (ini_get('open_basedir') == '' && ini_get('safe_mode') == 'Off') {
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				}
				curl_setopt($ch, CURLOPT_MAXREDIRS, 2);
				curl_exec($ch);
				$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				curl_close($ch);
				fclose($fp);
				$success = ($httpCode != '404');
			} elseif ($settings['source'] == 'site_price') {
				
				$path_array = explode("\n",$settings['site_price_path']);
				
								
				foreach($path_array as $path_array_v){
					$path_array_v = trim($path_array_v);
					if(!$path_array_v){continue;}
					
					preg_match("#\{(.+?)\}\=(.+)#",$path_array_v,$match);
					$download_url = $match[1];
					$name_url = $match[2];
					
					$headers = array(
						"Host: www.sunsky-online.com",
						"User-Agent: Mozilla/5.0 (X11; Linux i686; rv:10.0.1) Gecko/20100101 Firefox/10.0.1 SeaMonkey/2.7.1",
						"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
						"Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3",
						"Accept-Encoding: zip, xls",
						"Referer: http://www.sunsky-online.com/download/product!quot.do",
						"Connection: keep-alive",
					);
					
					$download_url = htmlspecialchars_decode($download_url);
					
					if($settings['method_site_price'] == 'get'){
						$ch = curl_init($download_url);
						curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
					}else{
						$download_url = explode('?',$download_url);
						$ch = curl_init($download_url[0]);
						curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
						curl_setopt($ch, CURLOPT_POST, 1);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $download_url[1]);
					}
					
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_HEADER, false);
					
					curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
					curl_setopt($ch, CURLOPT_AUTOREFERER, false); 
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1200);
					curl_setopt($ch, CURLOPT_TIMEOUT, 1200);
					curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux i686; rv:10.0.1) Gecko/20100101 Firefox/10.0.1 SeaMonkey/2.7.1");
					curl_setopt($ch, CURLOPT_COOKIEFILE, $_SERVER['DOCUMENT_ROOT'].'/'.$this->cookie);
					curl_setopt($ch, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'].'/'.$this->cookie);
					
					if($settings['gzip_feed']){
						curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
					}
					
					
					$result = curl_exec($ch);
					$filename = $_SERVER['DOCUMENT_ROOT'].'/sunsky/'.$name_url;
					//$result = gzdeflate ($result);					
					file_put_contents($filename,$result);
					
					
					//Various excel formats supported by PHPExcel library
					$excel_readers = array(
						'Excel5' ,
						'Excel2003XML' ,
						'Excel2007'
					);
					 
					require_once(DIR_SYSTEM.'PHPExcel/Classes/PHPExcel.php');
					 
					$reader = PHPExcel_IOFactory::createReader('Excel5');
					$reader->setReadDataOnly(true);
					 
					$excel = $reader->load($filename);
					$writer = PHPExcel_IOFactory::createWriter($excel, 'CSV')->setDelimiter(';')
																  ->setEnclosure('"')
																  ->setLineEnding("\r\n")
																  ->setSheetIndex(0)
																  ->save($_SERVER['DOCUMENT_ROOT'].'/sunsky/'.$name_url);
					
					unset($writer,$excel);
					 
				}
				
				
				
				$success = ($httpCode != '404');
			} elseif ($settings['source'] == 'ftp' && $settings['feed_ftpuser'] && $settings['feed_ftpserver'] && $settings['feed_ftppass'] && $settings['feed_ftppath']) {
				$success = $this->fetchFtp($settings['feed_ftpserver'], $settings['feed_ftpuser'], $settings['feed_ftppass'], $settings['feed_ftppath'], $filename);
			} elseif ($settings['source'] == 'filepath') {
				$success = copy($settings['feed_filepath'], $filename);
			} elseif ($settings['source'] == 'db') {
				//@todo implement direct from DB.
			}
		
			if ($unzip_feed) {				
				$temp_file = $this->unzip($filename);
				rename($temp_file, $filename);		
			}
			
		}
		
		return ($success) ? $filename : '';
	}
	

	function curl_redir_exec($ch){  
		static $curl_loops = 0;  
		static $curl_max_loops = 20;  
		if ($curl_loops   >= $curl_max_loops)  
		{  
		$curl_loops = 0;  
			return FALSE;  
		}  
		curl_setopt($ch, CURLOPT_HEADER, true);  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
		$data = curl_exec($ch);  
		list($header, $data) = explode("\r\n\r\n", $data, 2);  
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
		if ($http_code == 301 || $http_code == 302)  
		{  
		$matches = array();  
			preg_match('/Location:(.*?)\n/', $header, $matches);  
		$url = @parse_url(trim(array_pop($matches)));  
			if (!$url)  
		{  
			//couldn't process the url to redirect to  
			$curl_loops = 0;  
			return $data;  
			}  
		$last_url = parse_url(curl_getinfo($ch, CURLINFO_EFFECTIVE_URL));  
		if (!$url['scheme'])  
			$url['scheme'] = $last_url['scheme'];  
		if (!$url['host'])  
			$url['host'] = $last_url['host'];  
		if (!$url['path'])  
			$url['path'] = $last_url['path'];  
		$new_url = $url['scheme'] . '://' . $url['host'] . $url['path'] . ($url['query']?'?'.$url['query']:'');  
		curl_setopt($ch, CURLOPT_URL, $new_url);  
		//debug('Redirecting to', $new_url);  
		return curl_redir_exec($ch);  
		}   else {  
			$curl_loops=0;  
			return $data;  
			}  
	} 
	
	private function fetchFtp($server, $user, $pass, $remote_file, $local_file) {
		$conn_id = ftp_connect($server);
		$login_result = ftp_login($conn_id, $user, $pass);
		$success = ftp_get($conn_id, $local_file, $remote_file, FTP_BINARY);
		ftp_close($conn_id);
		return $success;
	}
	/*
	 * 
	 * function fetchImage
	 * 
	 * @desc - fetches an image from a URL. If the URL contains a ? character the new image's filename
	 * will be the md5 of the full URL. If not, it will be the last portion of the URL (after the last /).
	 * If the image URL returns a 404 the image will be deleted and an empty string returned.
	 * 
	 * @param (string) the image url to fetch
	 * @return (string) the name of the fetched file on disk relative to the image/ dir or empty string on 404
	 * 
	 */
	public function fetchImage($image_url, $folder='') {
		if (strpos($image_url, 'http') !== 0) {
			return '';
		}
		
		if($folder != '') {
			$new_folder = DIR_IMAGE . 'data/' . $folder;
			if (!file_exists($new_folder)) {
				mkdir($new_folder, 0777, true);
			}
		}
		
		if (strstr($image_url, '?')) {
			if($folder != '' && $folder != '/') {
				$filename = 'data/' . $folder . '/' . md5($image_url) . '.jpg';
			} else {
				$filename = 'data/' . md5($image_url) . '.jpg';
			}
		} else {
			$url_parts = explode('/', $image_url);
			
			if($folder != '' && $folder != '/') {
				$filename = 'data/' . $folder . '/' . end($url_parts);
			} else {
				$filename = 'data/' . end($url_parts);
			}
			
		}

		if (!file_exists(DIR_IMAGE . $filename)) {
			$fp = fopen(DIR_IMAGE . $filename, 'w');
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_URL, $image_url);
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_exec($ch);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			fclose($fp);
			$file_info = getimagesize(DIR_IMAGE . $filename);
			if($httpCode == 404 || empty($file_info) || strpos($file_info['mime'], 'image/') !== 0) {
				unlink(DIR_IMAGE . $filename);
				$filename = '';
			}
		}
		return $filename;
	}
	
	
	public function importFile($filename, &$settings) {
		if(!empty($settings['file_encoding']) && $settings['file_encoding'] != 'UTF-8') {
			$this->file_encoding = $settings['file_encoding'];
		}
		if ($settings['format'] == 'csv') {
			if ($settings['delimiter'] == '\t' ) {
				$settings['delimiter'] = "\t";
			} elseif ($settings['delimiter'] == '') {
				$settings['delimiter'] = ',';
			}
			$csv_options = array();
			if(!empty($settings['safe_headers'])) {
				$csv_options['safe_headers'] = $settings['safe_headers'];
			}
			if(!empty($settings['has_headers'])) {
				$csv_options['has_headers'] = $settings['has_headers'];
			}
			$this->importCSV($filename, $settings['delimiter'], $csv_options);
		} elseif ($settings['format'] == 'xml') {
		
			$this->table_created = false;			
			$this->importXML($filename, $settings['xml_product_tag']);
		}
		
		return array('total_items_ready'=>$this->total_items_ready, 'total_items_missed'=>$this->total_items_missed);
	}
	
	private function importXML($filename, $product_tag) {
		$this->product_tag = $product_tag;
		$this->xml_data = '';
		$fh = fopen($filename, 'r');
		$xml_parser = xml_parser_create($this->file_encoding);
		
		xml_set_object($xml_parser, $this);
		xml_set_element_handler($xml_parser, 'startTag', 'endTag');
		xml_set_character_data_handler($xml_parser, 'cData');
		xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, false);
		while ($data = fread($fh, 4096)) {				
			if (!xml_parse($xml_parser, $data, feof($fh))) {			
				return false;
				//debug
				//die(sprintf("XML error: %s at line %d", xml_error_string(xml_get_error_code($xml_parser)), xml_get_current_line_number($xml_parser)));
			}
		}
		xml_parser_free($xml_parser);
		/*ONIK*/
		// добавляем ост. товар
		$this->quickInsertProduct(array(),true);
		/*ONIK*/	
		return true;
	}
	
	private function importCSV($filename, $delimiter, $csv_options) {
		$fh = fopen($filename, 'r');
		if (!empty($csv_options['safe_headers']) || empty($csv_options['has_headers'])) {
			$count = count(fgetcsv($fh, 0, $delimiter));
			//if there are no file headers, reset the file read after doing the count
			if (empty($csv_options['has_headers'])) {
				$fh = fopen($filename, 'r');
			}
			for ($i = 0; $i < $count; $i++) {
				$headings[$i] = 'column_' . $i;
			}
		} else {
			$headings = array_map('trim', fgetcsv($fh, 0, $delimiter)); //trim white space from all headings for db insertion.
		}
		
		for ($i=0; $i<count($headings); $i++) {
			if (empty($headings[$i])) {
				$headings[$i] = ' column_' . ($i+1);
			}
		}
		
		$this->createEmptyTable($headings);
		$num_cols = count($headings);
		//most complicated do-while ever written.
		do {
			//miss items that have incorrect column count:
			while (($row = fgetcsv($fh, 0, $delimiter)) !== FALSE && count($row) != $num_cols) {
				$this->total_items_missed++;
			}
			if ($row) {
				$this->quickInsertProduct(array_combine($headings, $row));
				
				$this->total_items_ready++;
			}
		} while ($row);
		/*ONIK*/
		// добавляем ост. товар
		$this->quickInsertProduct(array(),true);
		/*ONIK*/
	}
	
	private function unzip($file) {
		$filename = $file;
		
		$zip = zip_open($file);
		
		if (is_resource($zip)) {
			$zip_entry = zip_read($zip);
			$filename = zip_entry_name($zip_entry);
			$fp = fopen($filename, 'w');
			if (zip_entry_open($zip, $zip_entry, 'r')) {
				$buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
				fwrite($fp,"$buf");
				zip_entry_close($zip_entry);
				fclose($fp);
			}
			zip_close($zip);
		}
		return $filename;
	}
	
	public function fileUploadErrorMessage($error_code) {
		switch ($error_code) { 
			case UPLOAD_ERR_INI_SIZE: 
				return 'The uploaded file exceeds the upload_max_filesize directive in php.ini'; 
			case UPLOAD_ERR_FORM_SIZE: 
				return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form'; 
			case UPLOAD_ERR_PARTIAL: 
				return 'The uploaded file was only partially uploaded'; 
			case UPLOAD_ERR_NO_FILE: 
				return 'No file was uploaded'; 
			case UPLOAD_ERR_NO_TMP_DIR: 
				return 'Missing a temporary folder'; 
			case UPLOAD_ERR_CANT_WRITE: 
				return 'Failed to write file to disk'; 
			case UPLOAD_ERR_EXTENSION: 
				return 'File upload stopped by extension'; 
			default: 
				return 'Unknown upload error'; 
		} 
	} 

	/*
	*
	* XML parser support functions:
	*
	* startTag
	* endTag
	* cData
	*
	*/
	private function startTag ($parser, $name, $attr) {
		if (strcmp($name, $this->product_tag) == 0) {
			$this->xml_product = array();
		}
		//Get attributes
		foreach ($attr as $key=>$value) {
			if (!isset($this->xml_product[$name.'_attr_'.$key])) {
				$this->xml_product[$name.'_attr_'.$key] = $value;
			} else {
				$this->xml_product[$name.'_attr_'.$key] .= '^' . $value;
			}
		}
	}
	
	private function endTag ($parser, $name) {
		if (strcmp($name, $this->product_tag) == 0) {
			if (!$this->table_created) {
				$this->createEmptyTable(array_keys($this->xml_product));
				$this->xml_existing_fields = array_keys($this->xml_product);
				$this->table_created = true;
			}
			$new_columns = array_diff(array_keys($this->xml_product), $this->xml_existing_fields);
			if (!empty($new_columns)) {
				$this->alterImportTable($new_columns);
				$this->xml_existing_fields = array_unique(array_merge($this->xml_existing_fields, $new_columns));
			}
			$this->quickInsertProduct($this->xml_product);
			$this->total_items_ready++;
		} else {
			if (isset($this->xml_product[$name])) {
				$this->xml_product[$name] .= '^' . $this->xml_data;
			} else {
				$this->xml_product[$name] = $this->xml_data;
			}
		}
		$this->xml_data = '';
		
	}
	
	private function cData($parser, $content) {
		$this->xml_data .= $content;
	}
	
	public function makeSeoKeyword($text='') {
		
			$str = htmlspecialchars_decode($text);
			$str = trim($str);
			$tr = array( 
			"А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","І"=>"I","і"=>"i",
			"Д"=>"D","Е"=>"E","Ж"=>"J","З"=>"Z","И"=>"I",
			"Й"=>"I","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
			"О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
			"У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH",
			"Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"",
			"Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
			"в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
			"з"=>"z","и"=>"i","й"=>"i","к"=>"k","л"=>"l",
			"м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
			"с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
			"ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
			"ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya",
				"!"=>"","@"=>"","#"=>"","$"=>"","%"=>"","^"=>"",
				"&"=>"","*"=>"","("=>"",")"=>"","_"=>"","="=>"",
				"+"=>"","~"=>"","'"=>"","\""=>"","?"=>"",";"=>"",
				"<"=>"",">"=>"","/"=>"","|"=>"","\\"=>"",":"=>"",
				"{"=>"","}"=>"","["=>"","]"=>"",'.'=>"",' '=>"-",
				'…'=>"",','=>'','«'=>'','»'=>'','–'=>'','—'=>'',
				"і"=>"i","є"=>"e","с"=>"c","с"=>"c","ё"=>"e",

			);
			
		$result = mb_strtolower(strtr($str,$tr));

		$result = str_replace('---','-',$result);
		$result = str_replace('--','-',$result);
		
		//if the alias is taken, set it to blank
		if($this->checkUrlAlias($result)) {
			$result = '';
		}	
		return $result;
	}
	
	//checks if the URL alias is in use, if not it uses it
	public function checkUrlAlias($text) {
		$query = $this->db->query("SELECT keyword FROM `" . DB_PREFIX . "url_alias` WHERE keyword = '" . $this->db->escape($text) . "'");
		return (isset($query->row['keyword'])) ? true : false;
	}
	
	
	public function getVersion() {
		$this->load->model('catalog/product');
		$this->load->model('catalog/category');
		if(method_exists($this->model_catalog_product, 'getProductTags')) {
			return '1.5.3';
		}elseif(method_exists($this->model_catalog_category, 'getCategoryFilters'))	{
			return '1.5.5';
		}else{
			return '1.5.4';
		}
	}
	
	/*ONIK*/
	/**
	* Быстрая вставка, оптимизация
	* 
	* @param mixed $product
	* @param mixed $end_metka
	*/
	public function quickInsertProduct($product,$end_metka = false) {
	
		$sql = 'INSERT INTO ' . DB_PREFIX . 'hj_import SET ';
		$values = array();
		/*ONIK*/
		if($end_metka == false){
			$this->count_sql++;
			$this->buffer_data[] = $product;
		}else{
			$this->count_sql = $this->max_insert_to_one_sql;			
		}
		
		
		if($this->count_sql == $this->max_insert_to_one_sql AND count($this->buffer_data) > 0){
			
			$sql = 'INSERT INTO ' . DB_PREFIX . 'hj_import ';
			$name_metka = true;
			$sql .= " ( ";
			
			$buffer_data_cols = array();
			foreach ($this->buffer_data as $key => $_value) {
				foreach ($this->buffer_data[$key] as $key_val => $__value) {
					$buffer_data_cols[$key_val] = 1;
				}				
			}
			
			foreach ($buffer_data_cols as $key => $value) {
				if($name_metka == true){
					$sql .= '`'.iconv($this->file_encoding, 'UTF-8//TRANSLIT', $key).'`';
					$name_metka = false;
				}else{
					$sql .= ',`'.iconv($this->file_encoding, 'UTF-8//TRANSLIT', $key).'`';
				}		
			}
			$sql .= " ) ";
			
			$sql .= " VALUES ";
			
			
			$val_metka = true;
			foreach ($this->buffer_data as $key => $v) {
			
				foreach($buffer_data_cols as $check_key => $_v){
					$product_data[$check_key] = $this->buffer_data[$key][$check_key];
				}
				
				$this->buffer_data[$key] = $product_data;
				
				if(count($this->buffer_data[$key]) == 0){ continue; }
				
				$name_metka = true;
				
				if($val_metka == true){
					$sql .= ' ( ';
					$val_metka = false;
				}else{
					$sql .= ' ,( ';
				}				
				foreach ($this->buffer_data[$key] as $value) {					
					$value = trim($value);
					if($name_metka == true){
						$sql .= "'".trim($this->db->escape(iconv($this->file_encoding, 'UTF-8//TRANSLIT', $value)))."'";
						$name_metka = false;
					}else{
						$sql .= ",'".trim($this->db->escape(iconv($this->file_encoding, 'UTF-8//TRANSLIT', $value)))."'";
					}			
				}
				$sql .= " ) ";				
			}	
				
			
			
			$this->count_sql = 0;
			$this->buffer_data = array();
			
			$query = $this->db->query($sql);
		}	
		
	}
	/*ONIK*/
	
}
?>