<?php
class ModelCatalogRecord extends Model
{
	public function addRecord($data)
	{



		if ($data['sort_order'] = '') {
			$sort_order = (int) $data['sort_order'];
		} //$data['sort_order'] = ''
		else {
			if (isset($data['record_blog'])) {
				foreach ($data['record_blog'] as $blog_id) {
					$sql        = "SELECT MAX(sort_order) as maxis
			     FROM " . DB_PREFIX . "record re
			     INNER  JOIN " . DB_PREFIX . "record_to_blog r_t_b ON r_t_b.record_id=re.record_id WHERE r_t_b.blog_id='" . (int) $blog_id . "'
			     ";
					$query      = $this->db->query($sql);
					$sort_order = (int) $query->row['maxis'] + 1;
				} //$data['record_blog'] as $blog_id
			} //isset($data['record_blog'])
			else {
				$sort_order = 1;
			}
		}
		if (isset($data['date_end']) && $data['date_end'] != '') {
			$date_end = "date_end = '" . $this->db->escape($data['date_end']) . "',";
		} //isset($data['date_end']) && $data['date_end'] != ''
		else {
			$date_end = "date_end = '2033-03-03 00:00:00',";
		}

		if (!isset($data['blog_main']))
			$data['blog_main'] = '';

		if (!isset($sort_order))
			$sort_order = '10000';

		$sql = "INSERT INTO " . DB_PREFIX . "record SET
		date_available = '" . $this->db->escape($data['date_available']) . "'," . $date_end . "
        blog_main ='" . $data['blog_main'] . "',
		comment = '" . serialize($data['comment']) . "',
		status = '" . (int) $data['status'] . "',
		customer_group_id = '" . $data['customer_group_id'] . "',
		sort_order = '" . $sort_order . "', date_added = NOW()";
		$this->db->query($sql);
		$record_id = $this->db->getLastId();
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "record SET image = '" . $this->db->escape($data['image']) . "' WHERE record_id = '" . (int) $record_id . "'");
		} //isset($data['image'])
		foreach ($data['record_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "record_description SET record_id = '" . (int) $record_id . "', language_id = '" . (int) $language_id . "',
			name = '" . $this->db->escape($value['name']) . "',
			meta_title = '" . $this->db->escape($value['meta_title']) . "',
			meta_h1 = '" . $this->db->escape($value['meta_h1']) . "',
			meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "',
			meta_description = '" . $this->db->escape($value['meta_description']) . "',
			sdescription = '" . $this->db->escape($value['sdescription']) . "'" . ", description = '" . $this->db->escape($value['description']) . "'");
		} //$data['record_description'] as $language_id => $value
		if (isset($data['record_store'])) {
			foreach ($data['record_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "record_to_store SET record_id = '" . (int) $record_id . "', store_id = '" . (int) $store_id . "'");
			} //$data['record_store'] as $store_id
		} //isset($data['record_store'])
		if (isset($data['record_attribute'])) {
			foreach ($data['record_attribute'] as $record_attribute) {
				if ($record_attribute['attribute_id']) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "record_attribute WHERE record_id = '" . (int) $record_id . "' AND attribute_id = '" . (int) $record_attribute['attribute_id'] . "'");
					foreach ($record_attribute['record_attribute_description'] as $language_id => $record_attribute_description) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "record_attribute SET record_id = '" . (int) $record_id . "', attribute_id = '" . (int) $record_attribute['attribute_id'] . "', language_id = '" . (int) $language_id . "', text = '" . $this->db->escape($record_attribute_description['text']) . "'");
					} //$record_attribute['record_attribute_description'] as $language_id => $record_attribute_description
				} //$record_attribute['attribute_id']
			} //$data['record_attribute'] as $record_attribute
		} //isset($data['record_attribute'])
		if (isset($data['record_option'])) {
			foreach ($data['record_option'] as $record_option) {
				if ($record_option['type'] == 'select' || $record_option['type'] == 'radio' || $record_option['type'] == 'checkbox' || $record_option['type'] == 'image') {
					$this->db->query("INSERT INTO " . DB_PREFIX . "record_option SET record_id = '" . (int) $record_id . "', option_id = '" . (int) $record_option['option_id'] . "', required = '" . (int) $record_option['required'] . "'");
					$record_option_id = $this->db->getLastId();
					if (isset($record_option['record_option_value'])) {
						foreach ($record_option['record_option_value'] as $record_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "record_option_value SET record_option_id = '" . (int) $record_option_id . "', record_id = '" . (int) $record_id . "', option_id = '" . (int) $record_option['option_id'] . "', option_value_id = '" . $this->db->escape($record_option_value['option_value_id']) . "', quantity = '" . (int) $record_option_value['quantity'] . "', subtract = '" . (int) $record_option_value['subtract'] . "', price = '" . (float) $record_option_value['price'] . "', price_prefix = '" . $this->db->escape($record_option_value['price_prefix']) . "', points = '" . (int) $record_option_value['points'] . "', points_prefix = '" . $this->db->escape($record_option_value['points_prefix']) . "', weight = '" . (float) $record_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($record_option_value['weight_prefix']) . "'");
						} //$record_option['record_option_value'] as $record_option_value
					} //isset($record_option['record_option_value'])
				} //$record_option['type'] == 'select' || $record_option['type'] == 'radio' || $record_option['type'] == 'checkbox' || $record_option['type'] == 'image'
				else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "record_option SET record_id = '" . (int) $record_id . "', option_id = '" . (int) $record_option['option_id'] . "', option_value = '" . $this->db->escape($record_option['option_value']) . "', required = '" . (int) $record_option['required'] . "'");
				}
			} //$data['record_option'] as $record_option
		} //isset($data['record_option'])
		if (isset($data['record_special'])) {
			foreach ($data['record_special'] as $record_special) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "record_special SET record_id = '" . (int) $record_id . "', customer_group_id = '" . (int) $record_special['customer_group_id'] . "', priority = '" . (int) $record_special['priority'] . "', price = '" . (float) $record_special['price'] . "', date_start = '" . $this->db->escape($record_special['date_start']) . "', date_end = '" . $this->db->escape($record_special['date_end']) . "'");
			} //$data['record_special'] as $record_special
		} //isset($data['record_special'])
		if (isset($data['record_image'])) {
			foreach ($data['record_image'] as $record_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "record_image SET record_id = '" . (int) $record_id . "',
				options ='".base64_encode(serialize($record_image['options']))."',
				image = '" . $this->db->escape($record_image['image']) . "', sort_order = '" . (int) $record_image['sort_order'] . "'");
			} //$data['record_image'] as $record_image
		} //isset($data['record_image'])
		if (isset($data['record_download'])) {
			foreach ($data['record_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "record_to_download SET record_id = '" . (int) $record_id . "', download_id = '" . (int) $download_id . "'");
			} //$data['record_download'] as $download_id
		} //isset($data['record_download'])
		if (isset($data['record_blog'])) {
			foreach ($data['record_blog'] as $blog_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "record_to_blog SET record_id = '" . (int) $record_id . "', blog_id = '" . (int) $blog_id . "'");
			} //$data['record_blog'] as $blog_id
		} //isset($data['record_blog'])



		$this->db->query("DELETE FROM " . DB_PREFIX . "record_related WHERE pointer_id = '" . (int) $record_id . "'  AND pointer='record_id'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "record_related WHERE related_id = '" . (int) $record_id . "'  AND pointer='record_id'");
		if (isset($data['record_related'])) {
			foreach ($data['record_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "record_related WHERE pointer_id = '" . (int) $related_id . "'  AND pointer='record_id'");

				$this->db->query("INSERT INTO " . DB_PREFIX . "record_related SET pointer_id = '" . (int) $record_id . "', related_id = '" . (int) $related_id . "' , pointer='record_id'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "record_related SET pointer_id = '" . (int) $related_id . "', related_id = '" . (int) $record_id . "' , pointer='record_id'");
			}
		}


		$this->db->query("DELETE FROM " . DB_PREFIX . "record_related WHERE pointer_id = '" . (int) $record_id . "'  AND pointer='product_id'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "record_related WHERE related_id = '" . (int) $record_id . "'  AND pointer='product_id'");
		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $product_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "record_related WHERE pointer_id = '" . (int) $product_id . "'  AND pointer='product_id'");

				$this->db->query("INSERT INTO " . DB_PREFIX . "record_related SET pointer_id = '" . (int) $record_id . "', related_id = '" . (int) $product_id . "' , pointer='product_id'");
                $this->db->query("INSERT INTO " . DB_PREFIX . "record_related SET pointer_id = '" . (int) $product_id . "', related_id = '" . (int) $record_id . "' , pointer='product_id'");

			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "record_related WHERE pointer_id = '" . (int) $record_id . "'  AND pointer='blog_id'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "record_related WHERE related_id = '" . (int) $record_id . "'  AND pointer='blog_id'");
		if (isset($data['blog_related'])) {

			foreach ($data['blog_related'] as $blog_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "record_related WHERE pointer_id = '" . (int) $blog_id . "'  AND pointer='blog_id'");

				$this->db->query("INSERT INTO " . DB_PREFIX . "record_related SET pointer_id = '" . (int) $record_id . "', related_id = '" . (int) $blog_id . "' , pointer='blog_id'");
                $this->db->query("INSERT INTO " . DB_PREFIX . "record_related SET pointer_id = '" . (int) $blog_id . "', related_id = '" . (int) $record_id . "' , pointer='blog_id'");
			}
		}



		$this->db->query("DELETE FROM " . DB_PREFIX . "record_related WHERE pointer_id = '" . (int) $record_id . "'  AND pointer='category_id'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "record_related WHERE related_id = '" . (int) $record_id . "'  AND pointer='category_id'");
		if (isset($data['category_related'])) {
			foreach ($data['category_related'] as $category_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "record_related WHERE pointer_id = '" . (int) $category_id . "'  AND pointer='category_id'");

				$this->db->query("INSERT INTO " . DB_PREFIX . "record_related SET pointer_id = '" . (int) $record_id . "', related_id = '" . (int) $category_id . "' , pointer='category_id'");
                $this->db->query("INSERT INTO " . DB_PREFIX . "record_related SET pointer_id = '" . (int) $category_id . "', related_id = '" . (int) $record_id . "' , pointer='category_id'");
			}
		}






		if (isset($data['record_reward'])) {
			foreach ($data['record_reward'] as $customer_group_id => $record_reward) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "record_reward SET record_id = '" . (int) $record_id . "', customer_group_id = '" . (int) $customer_group_id . "', points = '" . (int) $record_reward['points'] . "'");
			} //$data['record_reward'] as $customer_group_id => $record_reward
		} //isset($data['record_reward'])
		if (isset($data['record_layout'])) {
			foreach ($data['record_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "record_to_layout SET record_id = '" . (int) $record_id . "', store_id = '" . (int) $store_id . "', layout_id = '" . (int) $layout['layout_id'] . "'");
				} //$layout['layout_id']
			} //$data['record_layout'] as $store_id => $layout
		} //isset($data['record_layout'])
		foreach ($data['record_tag'] as $language_id => $value) {
			if ($value) {
				$tags = explode(',', $value);
				foreach ($tags as $tag) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "record_tag SET record_id = '" . (int) $record_id . "', language_id = '" . (int) $language_id . "', tag = '" . $this->db->escape(trim($tag)) . "'");
				} //$tags as $tag
			} //$value
		} //$data['record_tag'] as $language_id => $value
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias_blog SET query = 'record_id=" . (int) $record_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		} //$data['keyword']
		$this->cache->delete('record');
		$this->cache->delete('blogsrecord');
		$this->cache->delete('blog.module.view');
	}


	public function editRecord($record_id, $data)
	{
		if (isset($data['date_end']) && $data['date_end'] != '') {
			$date_end = "date_end = '" . $this->db->escape($data['date_end']) . "',";
		} //isset($data['date_end']) && $data['date_end'] != ''
		else {
			$date_end = '';
		}
		if (!isset($data['blog_main'])) {
			$data['blog_main'] = '';
		} //!isset($data['blog_main'])

/*
print_r("<PRE>");
print_r($data);
print_r("</PRE>");
*/

		$sql = "UPDATE " . DB_PREFIX . "record SET
		date_available = '" . $this->db->escape($data['date_available']) . "'," . $date_end . "
        blog_main ='" . $data['blog_main'] . "',
		comment  = '" . serialize($data['comment']) . "',
		status = '" . (int) $data['status'] . "', sort_order = '" . (int) $data['sort_order'] . "',
		customer_group_id = '" . $data['customer_group_id'] . "',
		date_modified = NOW() WHERE record_id = '" . (int) $record_id . "'";
		$this->db->query($sql);
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "record SET image = '" . $this->db->escape($data['image']) . "' WHERE record_id = '" . (int) $record_id . "'");
		} //isset($data['image'])
		$this->db->query("DELETE FROM " . DB_PREFIX . "record_description WHERE record_id = '" . (int) $record_id . "'");
		foreach ($data['record_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "record_description SET record_id = '" . (int) $record_id . "', language_id = '" . (int) $language_id . "',
			name = '" . $this->db->escape($value['name']) . "',
			meta_title = '" . $this->db->escape($value['meta_title']) . "',
			meta_h1 = '" . $this->db->escape($value['meta_h1']) . "',
			meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "'" . ", sdescription = '" . $this->db->escape($value['sdescription']) . "'");
		} //$data['record_description'] as $language_id => $value
		$this->db->query("DELETE FROM " . DB_PREFIX . "record_to_store WHERE record_id = '" . (int) $record_id . "'");
		if (isset($data['record_store'])) {
			foreach ($data['record_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "record_to_store SET record_id = '" . (int) $record_id . "', store_id = '" . (int) $store_id . "'");
			} //$data['record_store'] as $store_id
		} //isset($data['record_store'])
		$this->db->query("DELETE FROM " . DB_PREFIX . "record_attribute WHERE record_id = '" . (int) $record_id . "'");
		if (!empty($data['record_attribute'])) {
			foreach ($data['record_attribute'] as $record_attribute) {
				if ($record_attribute['attribute_id']) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "record_attribute WHERE record_id = '" . (int) $record_id . "' AND attribute_id = '" . (int) $record_attribute['attribute_id'] . "'");
					foreach ($record_attribute['record_attribute_description'] as $language_id => $record_attribute_description) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "record_attribute SET record_id = '" . (int) $record_id . "', attribute_id = '" . (int) $record_attribute['attribute_id'] . "', language_id = '" . (int) $language_id . "', text = '" . $this->db->escape($record_attribute_description['text']) . "'");
					} //$record_attribute['record_attribute_description'] as $language_id => $record_attribute_description
				} //$record_attribute['attribute_id']
			} //$data['record_attribute'] as $record_attribute
		} //!empty($data['record_attribute'])
		$this->db->query("DELETE FROM " . DB_PREFIX . "record_option WHERE record_id = '" . (int) $record_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "record_option_value WHERE record_id = '" . (int) $record_id . "'");
		if (isset($data['record_option'])) {
			foreach ($data['record_option'] as $record_option) {
				if ($record_option['type'] == 'select' || $record_option['type'] == 'radio' || $record_option['type'] == 'checkbox' || $record_option['type'] == 'image') {
					$this->db->query("INSERT INTO " . DB_PREFIX . "record_option SET record_option_id = '" . (int) $record_option['record_option_id'] . "', record_id = '" . (int) $record_id . "', option_id = '" . (int) $record_option['option_id'] . "', required = '" . (int) $record_option['required'] . "'");
					$record_option_id = $this->db->getLastId();
					if (isset($record_option['record_option_value'])) {
						foreach ($record_option['record_option_value'] as $record_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "record_option_value SET record_option_value_id = '" . (int) $record_option_value['record_option_value_id'] . "', record_option_id = '" . (int) $record_option_id . "', record_id = '" . (int) $record_id . "', option_id = '" . (int) $record_option['option_id'] . "', option_value_id = '" . $this->db->escape($record_option_value['option_value_id']) . "', quantity = '" . (int) $record_option_value['quantity'] . "', subtract = '" . (int) $record_option_value['subtract'] . "', price = '" . (float) $record_option_value['price'] . "', price_prefix = '" . $this->db->escape($record_option_value['price_prefix']) . "', points = '" . (int) $record_option_value['points'] . "', points_prefix = '" . $this->db->escape($record_option_value['points_prefix']) . "', weight = '" . (float) $record_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($record_option_value['weight_prefix']) . "'");
						} //$record_option['record_option_value'] as $record_option_value
					} //isset($record_option['record_option_value'])
				} //$record_option['type'] == 'select' || $record_option['type'] == 'radio' || $record_option['type'] == 'checkbox' || $record_option['type'] == 'image'
				else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "record_option SET record_option_id = '" . (int) $record_option['record_option_id'] . "', record_id = '" . (int) $record_id . "', option_id = '" . (int) $record_option['option_id'] . "', option_value = '" . $this->db->escape($record_option['option_value']) . "', required = '" . (int) $record_option['required'] . "'");
				}
			} //$data['record_option'] as $record_option
		} //isset($data['record_option'])
		$this->db->query("DELETE FROM " . DB_PREFIX . "record_special WHERE record_id = '" . (int) $record_id . "'");
		if (isset($data['record_special'])) {
			foreach ($data['record_special'] as $record_special) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "record_special SET record_id = '" . (int) $record_id . "', customer_group_id = '" . (int) $record_special['customer_group_id'] . "', priority = '" . (int) $record_special['priority'] . "', price = '" . (float) $record_special['price'] . "', date_start = '" . $this->db->escape($record_special['date_start']) . "', date_end = '" . $this->db->escape($record_special['date_end']) . "'");
			} //$data['record_special'] as $record_special
		} //isset($data['record_special'])

		$this->db->query("DELETE FROM " . DB_PREFIX . "record_image WHERE record_id = '" . (int) $record_id . "'");
		if (isset($data['record_image'])) {
			foreach ($data['record_image'] as $record_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "record_image SET record_id = '" . (int) $record_id . "',
				options ='".base64_encode(serialize($record_image['options']))."',
				image = '" . $this->db->escape($record_image['image']) . "',
				sort_order = '" . (int) $record_image['sort_order'] . "'");
			} //$data['record_image'] as $record_image
		} //isset($data['record_image'])



		$this->db->query("DELETE FROM " . DB_PREFIX . "record_to_download WHERE record_id = '" . (int) $record_id . "'");
		if (isset($data['record_download'])) {
			foreach ($data['record_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "record_to_download SET record_id = '" . (int) $record_id . "', download_id = '" . (int) $download_id . "'");
			} //$data['record_download'] as $download_id
		} //isset($data['record_download'])
		$this->db->query("DELETE FROM " . DB_PREFIX . "record_to_blog WHERE record_id = '" . (int) $record_id . "'");
		if (isset($data['record_blog'])) {
			foreach ($data['record_blog'] as $blog_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "record_to_blog SET record_id = '" . (int) $record_id . "', blog_id = '" . (int) $blog_id . "'");
			} //$data['record_blog'] as $blog_id
		} //isset($data['record_blog'])


 /***************************************************/

		$this->db->query("DELETE FROM " . DB_PREFIX . "record_related WHERE pointer_id = '" . (int) $record_id . "'  AND pointer='record_id'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "record_related WHERE related_id = '" . (int) $record_id . "'  AND pointer='record_id'");
		if (isset($data['record_related'])) {
			foreach ($data['record_related'] as $related_id) {
				//$this->db->query("DELETE FROM " . DB_PREFIX . "record_related WHERE pointer_id = '" . (int) $related_id . "'  AND pointer='record_id'");

				$this->db->query("INSERT INTO " . DB_PREFIX . "record_related SET pointer_id = '" . (int) $record_id . "', related_id = '" . (int) $related_id . "' , pointer='record_id'");
				//$this->db->query("INSERT INTO " . DB_PREFIX . "record_related SET pointer_id = '" . (int) $related_id . "', related_id = '" . (int) $record_id . "' , pointer='record_id'");
			}
		}


		$this->db->query("DELETE FROM " . DB_PREFIX . "record_related WHERE pointer_id = '" . (int) $record_id . "'  AND pointer='product_id'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "record_related WHERE related_id = '" . (int) $record_id . "'  AND pointer='product_id'");
		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $product_id) {
				//$this->db->query("DELETE FROM " . DB_PREFIX . "record_related WHERE pointer_id = '" . (int) $product_id . "'  AND pointer='product_id'");

				$this->db->query("INSERT INTO " . DB_PREFIX . "record_related SET pointer_id = '" . (int) $record_id . "', related_id = '" . (int) $product_id . "' , pointer='product_id'");
               // $this->db->query("INSERT INTO " . DB_PREFIX . "record_related SET pointer_id = '" . (int) $product_id . "', related_id = '" . (int) $record_id . "' , pointer='product_id'");

			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "record_related WHERE pointer_id = '" . (int) $record_id . "'  AND pointer='blog_id'");
       // $this->db->query("DELETE FROM " . DB_PREFIX . "record_related WHERE related_id = '" . (int) $record_id . "'  AND pointer='blog_id'");
		if (isset($data['blog_related'])) {

			foreach ($data['blog_related'] as $blog_id) {
				//$this->db->query("DELETE FROM " . DB_PREFIX . "record_related WHERE pointer_id = '" . (int) $blog_id . "'  AND pointer='blog_id'");

				$this->db->query("INSERT INTO " . DB_PREFIX . "record_related SET pointer_id = '" . (int) $record_id . "', related_id = '" . (int) $blog_id . "' , pointer='blog_id'");
               // $this->db->query("INSERT INTO " . DB_PREFIX . "record_related SET pointer_id = '" . (int) $blog_id . "', related_id = '" . (int) $record_id . "' , pointer='blog_id'");
			}
		}



		$this->db->query("DELETE FROM " . DB_PREFIX . "record_related WHERE pointer_id = '" . (int) $record_id . "'  AND pointer='category_id'");
       // $this->db->query("DELETE FROM " . DB_PREFIX . "record_related WHERE related_id = '" . (int) $record_id . "'  AND pointer='category_id'");
		if (isset($data['category_related'])) {
			foreach ($data['category_related'] as $category_id) {
				//$this->db->query("DELETE FROM " . DB_PREFIX . "record_related WHERE pointer_id = '" . (int) $category_id . "'  AND pointer='category_id'");

				$this->db->query("INSERT INTO " . DB_PREFIX . "record_related SET pointer_id = '" . (int) $record_id . "', related_id = '" . (int) $category_id . "' , pointer='category_id'");
               // $this->db->query("INSERT INTO " . DB_PREFIX . "record_related SET pointer_id = '" . (int) $category_id . "', related_id = '" . (int) $record_id . "' , pointer='category_id'");
			}
		}

 /***************************************************/


		$this->db->query("DELETE FROM " . DB_PREFIX . "record_reward WHERE record_id = '" . (int) $record_id . "'");
		if (isset($data['record_reward'])) {
			foreach ($data['record_reward'] as $customer_group_id => $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "record_reward SET record_id = '" . (int) $record_id . "', customer_group_id = '" . (int) $customer_group_id . "', points = '" . (int) $value['points'] . "'");
			} //$data['record_reward'] as $customer_group_id => $value
		} //isset($data['record_reward'])
		$this->db->query("DELETE FROM " . DB_PREFIX . "record_to_layout WHERE record_id = '" . (int) $record_id . "'");
		if (isset($data['record_layout'])) {
			foreach ($data['record_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "record_to_layout SET record_id = '" . (int) $record_id . "', store_id = '" . (int) $store_id . "', layout_id = '" . (int) $layout['layout_id'] . "'");
				} //$layout['layout_id']
			} //$data['record_layout'] as $store_id => $layout
		} //isset($data['record_layout'])
		$this->db->query("DELETE FROM " . DB_PREFIX . "record_tag WHERE record_id = '" . (int) $record_id . "'");
		foreach ($data['record_tag'] as $language_id => $value) {
			if ($value) {
				$tags = explode(',', $value);
				foreach ($tags as $tag) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "record_tag SET record_id = '" . (int) $record_id . "', language_id = '" . (int) $language_id . "', tag = '" . $this->db->escape(trim($tag)) . "'");
				} //$tags as $tag
			} //$value
		} //$data['record_tag'] as $language_id => $value
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias_blog WHERE query = 'record_id=" . (int) $record_id . "'");
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias_blog SET query = 'record_id=" . (int) $record_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		} //$data['keyword']
		$this->cache->delete('record');
		$this->cache->delete('blogsrecord');
		$this->cache->delete('blog.module.view');
	}
	public function copyRecord($record_id)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "record p LEFT JOIN " . DB_PREFIX . "record_description pd ON (p.record_id = pd.record_id) WHERE p.record_id = '" . (int) $record_id . "' AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "'");
		if ($query->num_rows) {
			$data                          = array();
			$data                          = $query->row;
			$data['keyword']               = '';
			$data['status']                = '0';
			$data['comment']['status']     = '0';
			$data['comment']['status_reg'] = '0';
			$data['comment']['status_now'] = '0';
			$data                          = array_merge($data, array(
				'record_attribute' => $this->getRecordAttributes($record_id)
			));
			$data                          = array_merge($data, array(
				'record_description' => $this->getRecordDescriptions($record_id)
			));
			$data                          = array_merge($data, array(
				'record_image' => $this->getRecordImages($record_id)
			));
			$data['record_image']          = array();
			$results                       = $this->getRecordImages($record_id);
			foreach ($results as $result) {
				$data['record_image'][] = $result['image'];
			} //$results as $result
			$data = array_merge($data, array(
				'record_option' => $this->getRecordOptions($record_id)
			));

			$data = array_merge($data, array(
				'record_related' => $this->getRecordRelated($record_id, 'record_id')
			));

			$data = array_merge($data, array(
				'product_related' =>  $this->getRecordRelated($record_id, 'product_id')
			));

			$data = array_merge($data, array(
				'blog_related' =>  $this->getRecordRelated($record_id, 'blog_id')
			));

			$data = array_merge($data, array(
				'category_related' =>  $this->getRecordRelated($record_id, 'category_id')
			));


			$data = array_merge($data, array(
				'record_reward' => $this->getRecordRewards($record_id)
			));
			$data = array_merge($data, array(
				'record_special' => $this->getRecordSpecials($record_id)
			));
			$data = array_merge($data, array(
				'record_tag' => $this->getRecordTags($record_id)
			));
			$data = array_merge($data, array(
				'record_blog' => $this->getRecordCategories($record_id)
			));
			$data = array_merge($data, array(
				'record_download' => $this->getRecordDownloads($record_id)
			));
			$data = array_merge($data, array(
				'record_layout' => $this->getRecordLayouts($record_id)
			));
			$data = array_merge($data, array(
				'record_store' => $this->getRecordStores($record_id)
			));
			$this->addRecord($data);
		} //$query->num_rows
	}
	public function deleteRecord($record_id)
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "record WHERE record_id = '" . (int) $record_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "record_attribute WHERE record_id = '" . (int) $record_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "record_description WHERE record_id = '" . (int) $record_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "record_image WHERE record_id = '" . (int) $record_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "record_option WHERE record_id = '" . (int) $record_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "record_option_value WHERE record_id = '" . (int) $record_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "record_related WHERE pointer_id = '" . (int) $record_id . "'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "record_product_related WHERE record_id = '" . (int) $record_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "record_related WHERE related_id = '" . (int) $record_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "record_reward WHERE record_id = '" . (int) $record_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "record_special WHERE record_id = '" . (int) $record_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "record_tag WHERE record_id='" . (int) $record_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "record_to_blog WHERE record_id = '" . (int) $record_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "record_to_download WHERE record_id = '" . (int) $record_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "record_to_layout WHERE record_id = '" . (int) $record_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "record_to_store WHERE record_id = '" . (int) $record_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "comment WHERE record_id = '" . (int) $record_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias_blog WHERE query = 'record_id=" . (int) $record_id . "'");
		$this->cache->delete('record');
		$this->cache->delete('blogsrecord');
		$this->cache->delete('blog.module.view');
	}
	public function getRecord($record_id)
	{
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias_blog WHERE query = 'record_id=" . (int) $record_id . "') AS keyword FROM " . DB_PREFIX . "record p LEFT JOIN " . DB_PREFIX . "record_description pd ON (p.record_id = pd.record_id) WHERE p.record_id = '" . (int) $record_id . "' AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "'");
		return $query->row;
	}
	public function getRecords($data = array())
	{
		$grouping = "GROUP BY p.record_id";

		if (isset($data['ajax']) && $data['ajax']) {
		 $grouping = "GROUP BY bd.blog_id";
		}

		if ($data) {
			$sql = "SELECT  p2c.blog_id, p.*,pd.*,


			bd.name as blog_name FROM " . DB_PREFIX . "record p
			LEFT JOIN " . DB_PREFIX . "record_description pd ON (p.record_id = pd.record_id)
			LEFT JOIN " . DB_PREFIX . "record_to_blog p2c ON (p.record_id = p2c.record_id)
			LEFT JOIN " . DB_PREFIX . "blog_description bd ON (p2c.blog_id = bd.blog_id)
			";
			$sql .= " WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";
			if (!empty($data['filter_name'])) {
				$sql .= " AND LCASE(pd.name) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
			} //!empty($data['filter_name'])

			if (!empty($data['filter_blog'])) {
				$sql .= " AND LCASE(bd.name) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_blog'])) . "%'";
			} //!empty($data['filter_blog'])


			if (!empty($data['filter_price'])) {
				$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
			} //!empty($data['filter_price'])
			if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
				$sql .= " AND p.quantity = '" . $this->db->escape($data['filter_quantity']) . "'";
			} //isset($data['filter_quantity']) && !is_null($data['filter_quantity'])
			if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
				$sql .= " AND p.status = '" . (int) $data['filter_status'] . "'";
			} //isset($data['filter_status']) && !is_null($data['filter_status'])
			if (!empty($data['filter_blog_id'])) {
				if (!empty($data['filter_sub_blog'])) {
					$implode_data   = array();
					$implode_data[] = "blog_id = '" . (int) $data['filter_blog_id'] . "'";
					$this->load->model('catalog/blog');
					$categories = $this->model_catalog_blog->getCategories($data['filter_blog_id']);
					foreach ($categories as $blog) {
						$implode_data[] = "p2c.blog_id = '" . (int) $blog['blog_id'] . "'";
					} //$categories as $blog
					$sql .= " AND (" . implode(' OR ', $implode_data) . ")";
				} //!empty($data['filter_sub_blog'])
				else {
					$sql .= " AND p2c.blog_id = '" . (int) $data['filter_blog_id'] . "'";
				}
			} //!empty($data['filter_blog_id'])
			$sql .= $grouping;
			$sort_data = array(
				'pd.name',
				'p.model',
				'p.price',
				'p.quantity',
				'p.status',
				'p.date_added',
				'blog_name',
				'p.sort_order'
			);
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} //isset($data['sort']) && in_array($data['sort'], $sort_data)
			else {
				$sql .= " ORDER BY p.record_id";
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
		} //$data
		else {
			$record_data = $this->cache->get('record.' . (int) $this->config->get('config_language_id'));
			if (!$record_data) {
				$query       = $this->db->query("SELECT * FROM " . DB_PREFIX . "record p LEFT JOIN " . DB_PREFIX . "record_description pd ON (p.record_id = pd.record_id) WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY p.record_id DESC");
				$record_data = $query->rows;
				$this->cache->set('record.' . (int) $this->config->get('config_language_id'), $record_data);
			} //!$record_data
			return $record_data;
		}
	}
	public function getRecordsByBlogId($blog_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "record p LEFT JOIN " . DB_PREFIX . "record_description pd ON (p.record_id = pd.record_id) LEFT JOIN " . DB_PREFIX . "record_to_blog p2c ON (p.record_id = p2c.record_id) WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND p2c.blog_id = '" . (int) $blog_id . "' ORDER BY p.record_id DESC");
		return $query->rows;
	}
	public function getRecordDescriptions($record_id)
	{
		$record_description_data = array();
		$query                   = $this->db->query("SELECT * FROM " . DB_PREFIX . "record_description WHERE record_id = '" . (int) $record_id . "'");
		foreach ($query->rows as $result) {
			$record_description_data[$result['language_id']] = array(
				'name' => $result['name'],
				'description' => $result['description'],
				'sdescription' => $result['sdescription'],
				'meta_title' => $result['meta_title'],
				'meta_h1' => $result['meta_h1'],
				'meta_keyword' => $result['meta_keyword'],
				'meta_description' => $result['meta_description']
			);
		} //$query->rows as $result
		return $record_description_data;
	}
	public function getRecordAttributes($record_id)
	{
		$record_attribute_data  = array();
		$record_attribute_query = $this->db->query("SELECT pa.attribute_id, ad.name FROM " . DB_PREFIX . "record_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.record_id = '" . (int) $record_id . "' AND ad.language_id = '" . (int) $this->config->get('config_language_id') . "' GROUP BY pa.attribute_id");
		foreach ($record_attribute_query->rows as $record_attribute) {
			$record_attribute_description_data  = array();
			$record_attribute_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "record_attribute WHERE record_id = '" . (int) $record_id . "' AND attribute_id = '" . (int) $record_attribute['attribute_id'] . "'");
			foreach ($record_attribute_description_query->rows as $record_attribute_description) {
				$record_attribute_description_data[$record_attribute_description['language_id']] = array(
					'text' => $record_attribute_description['text']
				);
			} //$record_attribute_description_query->rows as $record_attribute_description
			$record_attribute_data[] = array(
				'attribute_id' => $record_attribute['attribute_id'],
				'name' => $record_attribute['name'],
				'record_attribute_description' => $record_attribute_description_data
			);
		} //$record_attribute_query->rows as $record_attribute
		return $record_attribute_data;
	}
	public function getRecordOptions($record_id)
	{
		$record_option_data  = array();
		$record_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "record_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.record_id = '" . (int) $record_id . "' AND od.language_id = '" . (int) $this->config->get('config_language_id') . "'");
		foreach ($record_option_query->rows as $record_option) {
			if ($record_option['type'] == 'select' || $record_option['type'] == 'radio' || $record_option['type'] == 'checkbox' || $record_option['type'] == 'image') {
				$record_option_value_data  = array();
				$record_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "record_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.record_option_id = '" . (int) $record_option['record_option_id'] . "' AND ovd.language_id = '" . (int) $this->config->get('config_language_id') . "'");
				foreach ($record_option_value_query->rows as $record_option_value) {
					$record_option_value_data[] = array(
						'record_option_value_id' => $record_option_value['record_option_value_id'],
						'option_value_id' => $record_option_value['option_value_id'],
						'name' => $record_option_value['name'],
						'image' => $record_option_value['image'],
						'quantity' => $record_option_value['quantity'],
						'subtract' => $record_option_value['subtract'],
						'price' => $record_option_value['price'],
						'price_prefix' => $record_option_value['price_prefix'],
						'points' => $record_option_value['points'],
						'points_prefix' => $record_option_value['points_prefix'],
						'weight' => $record_option_value['weight'],
						'weight_prefix' => $record_option_value['weight_prefix']
					);
				} //$record_option_value_query->rows as $record_option_value
				$record_option_data[] = array(
					'record_option_id' => $record_option['record_option_id'],
					'option_id' => $record_option['option_id'],
					'name' => $record_option['name'],
					'type' => $record_option['type'],
					'record_option_value' => $record_option_value_data,
					'required' => $record_option['required']
				);
			} //$record_option['type'] == 'select' || $record_option['type'] == 'radio' || $record_option['type'] == 'checkbox' || $record_option['type'] == 'image'
			else {
				$record_option_data[] = array(
					'record_option_id' => $record_option['record_option_id'],
					'option_id' => $record_option['option_id'],
					'name' => $record_option['name'],
					'type' => $record_option['type'],
					'option_value' => $record_option['option_value'],
					'required' => $record_option['required']
				);
			}
		} //$record_option_query->rows as $record_option
		return $record_option_data;
	}
	public function getRecordImages($record_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "record_image WHERE record_id = '" . (int) $record_id . "'");
		return $query->rows;
	}
	public function getRecordSpecials($record_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "record_special WHERE record_id = '" . (int) $record_id . "' ORDER BY priority, price");
		return $query->rows;
	}
	public function getRecordRewards($record_id)
	{
		$record_reward_data = array();
		$query              = $this->db->query("SELECT * FROM " . DB_PREFIX . "record_reward WHERE record_id = '" . (int) $record_id . "'");
		foreach ($query->rows as $result) {
			$record_reward_data[$result['customer_group_id']] = array(
				'points' => $result['points']
			);
		} //$query->rows as $result
		return $record_reward_data;
	}
	public function getRecordDownloads($record_id)
	{
		$record_download_data = array();
		$query                = $this->db->query("SELECT * FROM " . DB_PREFIX . "record_to_download WHERE record_id = '" . (int) $record_id . "'");
		foreach ($query->rows as $result) {
			$record_download_data[] = $result['download_id'];
		} //$query->rows as $result
		return $record_download_data;
	}
	public function getRecordStores($record_id)
	{
		$record_store_data = array();
		$query             = $this->db->query("SELECT * FROM " . DB_PREFIX . "record_to_store WHERE record_id = '" . (int) $record_id . "'");
		foreach ($query->rows as $result) {
			$record_store_data[] = $result['store_id'];
		} //$query->rows as $result
		return $record_store_data;
	}
	public function getRecordLayouts($record_id)
	{
		$record_layout_data = array();
		$query              = $this->db->query("SELECT * FROM " . DB_PREFIX . "record_to_layout WHERE record_id = '" . (int) $record_id . "'");
		foreach ($query->rows as $result) {
			$record_layout_data[$result['store_id']] = $result['layout_id'];
		} //$query->rows as $result
		return $record_layout_data;
	}
	public function getRecordCategories($record_id)
	{
		$record_blog_data = array();
		$query            = $this->db->query("SELECT * FROM " . DB_PREFIX . "record_to_blog WHERE record_id = '" . (int) $record_id . "'");
		foreach ($query->rows as $result) {
			$record_blog_data[] = $result['blog_id'];
		} //$query->rows as $result
		return $record_blog_data;
	}




	public function getRecordRelated($record_id, $pointer='record_id')
	{
		$record_related_data = array();
		$query               = $this->db->query("SELECT *
		FROM " . DB_PREFIX . "record_related WHERE pointer_id = '" . (int) $record_id . "' AND pointer='".$pointer."'");
		foreach ($query->rows as $result) {
			$record_related_data[] = $result['related_id'];
		} //$query->rows as $result
		return $record_related_data;
	}

	public function getProductRelated($record_id)
	{
		$product_related_data = array();
		//$query                = $this->db->query("SELECT * FROM " . DB_PREFIX . "record_product_related WHERE record_id = '" . (int) $record_id . "'");

		$query               = $this->db->query("SELECT * FROM " . DB_PREFIX . "record_related WHERE pointer_id = '" . (int) $record_id . "' AND pointer='product_id'");


		foreach ($query->rows as $result) {
			$product_related_data[] = $result['related_id'];
		} //$query->rows as $result
		return $product_related_data;
	}

	public function getBlogRelated($record_id)
	{
		$record_related_data = array();
		$query               = $this->db->query("SELECT * FROM " . DB_PREFIX . "record_related WHERE pointer_id = '" . (int) $record_id . "' AND pointer='blog_id'");
		foreach ($query->rows as $result) {
			$record_related_data[] = $result['related_id'];
		} //$query->rows as $result
		return $record_related_data;
	}

	public function getCategoryRelated($record_id)
	{
		$record_related_data = array();
		$query               = $this->db->query("SELECT * FROM " . DB_PREFIX . "record_related WHERE pointer_id = '" . (int) $record_id . "' AND pointer='category_id'");
		foreach ($query->rows as $result) {
			$record_related_data[] = $result['related_id'];
		} //$query->rows as $result
		return $record_related_data;
	}




	public function getRecordTags($record_id)
	{
		$record_tag_data = array();
		$query           = $this->db->query("SELECT * FROM " . DB_PREFIX . "record_tag WHERE record_id = '" . (int) $record_id . "'");
		$tag_data        = array();
		foreach ($query->rows as $result) {
			$tag_data[$result['language_id']][] = $result['tag'];
		} //$query->rows as $result
		foreach ($tag_data as $language => $tags) {
			$record_tag_data[$language] = implode(',', $tags);
		} //$tag_data as $language => $tags
		return $record_tag_data;
	}
	public function getTotalRecords($data = array())
	{
		$sql = "SELECT COUNT(DISTINCT p.record_id) AS total FROM " . DB_PREFIX . "record p LEFT JOIN " . DB_PREFIX . "record_description pd ON (p.record_id = pd.record_id)";
		if (!empty($data['filter_blog_id'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "record_to_blog p2c ON (p.record_id = p2c.record_id)";
		} //!empty($data['filter_blog_id'])
		$sql .= " WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";
		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(pd.name) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		} //!empty($data['filter_name'])
		if (!empty($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		} //!empty($data['filter_price'])
		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . $this->db->escape($data['filter_quantity']) . "'";
		} //isset($data['filter_quantity']) && !is_null($data['filter_quantity'])
		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int) $data['filter_status'] . "'";
		} //isset($data['filter_status']) && !is_null($data['filter_status'])
		if (!empty($data['filter_blog_id'])) {
			if (!empty($data['filter_sub_blog'])) {
				$implode_data   = array();
				$implode_data[] = "p2c.blog_id = '" . (int) $data['filter_blog_id'] . "'";
				$this->load->model('catalog/blog');
				$categories = $this->model_catalog_blog->getCategories($data['filter_blog_id']);
				foreach ($categories as $blog) {
					$implode_data[] = "p2c.blog_id = '" . (int) $blog['blog_id'] . "'";
				} //$categories as $blog
				$sql .= " AND (" . implode(' OR ', $implode_data) . ")";
			} //!empty($data['filter_sub_blog'])
			else {
				$sql .= " AND p2c.blog_id = '" . (int) $data['filter_blog_id'] . "'";
			}
		} //!empty($data['filter_blog_id'])
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	public function getTotalRecordsByTaxClassId($tax_class_id)
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "record WHERE tax_class_id = '" . (int) $tax_class_id . "'");
		return $query->row['total'];
	}
	public function getTotalRecordsByStockStatusId($stock_status_id)
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "record WHERE stock_status_id = '" . (int) $stock_status_id . "'");
		return $query->row['total'];
	}
	public function getTotalRecordsByWeightClassId($weight_class_id)
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "record WHERE weight_class_id = '" . (int) $weight_class_id . "'");
		return $query->row['total'];
	}
	public function getTotalRecordsByLengthClassId($length_class_id)
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "record WHERE length_class_id = '" . (int) $length_class_id . "'");
		return $query->row['total'];
	}
	public function getTotalRecordsByDownloadId($download_id)
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "record_to_download WHERE download_id = '" . (int) $download_id . "'");
		return $query->row['total'];
	}
	public function getTotalRecordsByManufacturerId($manufacturer_id)
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "record WHERE manufacturer_id = '" . (int) $manufacturer_id . "'");
		return $query->row['total'];
	}
	public function getTotalRecordsByAttributeId($attribute_id)
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "record_attribute WHERE attribute_id = '" . (int) $attribute_id . "'");
		return $query->row['total'];
	}
	public function getTotalRecordsByOptionId($option_id)
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "record_option WHERE option_id = '" . (int) $option_id . "'");
		return $query->row['total'];
	}
	public function getTotalRecordsByLayoutId($layout_id)
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "record_to_layout WHERE layout_id = '" . (int) $layout_id . "'");
		return $query->row['total'];
	}
}
?>