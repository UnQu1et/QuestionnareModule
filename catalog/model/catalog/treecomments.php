<?php
class ModelCatalogTreeComments extends Model
{
	public function getCommentsByMarkId( $data = Array(), $mark='product_id')
	{
        if (!empty($data)) {

        if ($mark == 'product_id') {
         $prefix_table_main = 'review';
         $prefix_table_pr = 'product';
         $p_price = "p.price,";
        }

        if ($mark == 'record_id') {
         $prefix_table_main = 'comment';
         $prefix_table_pr = 'record';
         $p_price = "";
        }

		$mark_id = $data[$mark];
		$start = $data['start'];
		$limit = $data['limit'];

		$sql_rf        = '';
		$sql_rf_select = "";

		$rf            = $this->table_exists(DB_PREFIX . "review_fields");
		//if ($rf && $mark=='product_id')

		{
			$sql_rf        = " LEFT JOIN " . DB_PREFIX . "review_fields r_f ON (r.".$prefix_table_main."_id = r_f.review_id AND r_f.mark = '".$mark."' )";
			$sql_rf_select = "r_f.*,";
		} //$rf

		$sql   = "
		SELECT " . $sql_rf_select . " r.*,
		r.".$prefix_table_main."_id as review_id, r.".$prefix_table_main."_id as comment_id,
		p.".$mark.", pd.name, ".$p_price." p.image, r.date_added as date_added,
		r.date_added as date_added, r.date_added as date_available,
		(r.sorthex) as hsort
		FROM " . DB_PREFIX . $prefix_table_main." r
		LEFT JOIN " . DB_PREFIX . $prefix_table_pr." p ON (r.".$mark." = p.".$mark.")
		LEFT JOIN " . DB_PREFIX . $prefix_table_pr."_description pd ON (p.".$mark." = pd.".$mark.")
		" . $sql_rf . "
		WHERE p.".$mark." = '" . (int) $mark_id . "'
		AND p.status = '1'
		AND r.status = '1'
		AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "'
		";
		$query = $this->db->query($sql);
		return $query->rows;
		} else {
		 return false;
		}
	}

	public function getTotalCommentsByMarkId($mark_id, $mark='product_id')
	{
        if ($mark == 'product_id') {
         $prefix_table_main = 'review';
         $prefix_table_pr = 'product';
        }

        if ($mark == 'record_id') {
         $prefix_table_main = 'comment';
         $prefix_table_pr = 'record';
        }

		$sql   = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . $prefix_table_main." r
		LEFT JOIN " . DB_PREFIX . $prefix_table_pr." p ON (r.".$mark." = p.".$mark.")
		LEFT JOIN " . DB_PREFIX . $prefix_table_pr."_description pd ON (p.".$mark." = pd.".$mark.")
		WHERE p.".$mark." = '" . (int) $mark_id . "'  AND p.status = '1' AND r.status = '1'
		AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";
		$query = $this->db->query($sql);
		return $query->row['total'];
	}

	public function getRatesByMarkId($mark_id, $customer_id, $mark='product_id')
	{
        if ($mark == 'product_id') {
         $prefix_table_main = 'review';
         $prefix_table_pr = 'product';
        }

        if ($mark == 'record_id') {
         $prefix_table_main = 'comment';
         $prefix_table_pr = 'record';
        }

		$sql   = "
			  SELECT
				rc.*,
				rc.".$prefix_table_main."_id as cid,
				rc.".$prefix_table_main."_id as review_id,
                IF ((SELECT urc.delta  FROM " . DB_PREFIX . "rate_".$prefix_table_main." urc WHERE urc.customer_id = '" . (int)$customer_id . "' AND urc.".$prefix_table_main."_id = rc.".$prefix_table_main."_id LIMIT 1)  < 0, -1 ,
                IF ((SELECT urc.delta  FROM " . DB_PREFIX . "rate_".$prefix_table_main." urc WHERE urc.customer_id = '" . (int)$customer_id . "' AND urc.".$prefix_table_main."_id = rc.".$prefix_table_main."_id LIMIT 1)  > 0, 1 ,  0 ) ) as customer_delta ,
      			COUNT(rc.".$prefix_table_main."_id) as rate_count,
				SUM(rc.delta) as rate_delta,
				SUM(rc.delta > 0) as rate_delta_blog_plus,
				SUM(rc.delta < 0) as rate_delta_blog_minus
			   FROM
			     " . DB_PREFIX . "rate_".$prefix_table_main." rc
			   LEFT JOIN " . DB_PREFIX . $prefix_table_main." c on (rc.".$prefix_table_main."_id = c.".$prefix_table_main."_id)
			   LEFT JOIN " . DB_PREFIX . $prefix_table_pr." r on (r.".$mark." = c.".$mark.")
			   LEFT JOIN " . DB_PREFIX . $prefix_table_pr."_description pd ON (r.".$mark." = pd.".$mark.")
				   WHERE
			     r.".$mark."= " . (int) $mark_id . "

				AND r.status = '1'
				AND c.status = '1'

				AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "'
			    GROUP BY rc.".$prefix_table_main."_id
			   ";
		$query = $this->db->query($sql);
		if (count($query->rows) > 0) {
			foreach ($query->rows as $rates) {
				$rate[$rates['cid']] = $rates;
			} //$query->rows as $rates
			return $rate;
		} //count($query->rows) > 0
	}


	public function getRatesByCommentId($review_id, $mark='product_id', $all=false)
	{

        if ($mark == 'product_id') {
         $prefix_table_main = 'review';
         $prefix_table_pr = 'product';
        }

        if ($mark == 'record_id') {
         $prefix_table_main = 'comment';
         $prefix_table_pr = 'record';
        }
        if (!$all) {
				$group = "GROUP BY rc.".$prefix_table_main."_id" ;
				$sql_counter = ",
				rc.".$prefix_table_main."_id as cid,
				rc.".$prefix_table_main."_id as review_id,
				COUNT(rc.".$prefix_table_main."_id) as rate_count,
				SUM(rc.delta) as rate_delta,
				SUM(rc.delta > 0) as rate_delta_blog_plus,
				SUM(rc.delta < 0) as rate_delta_blog_minus";
			} else {
			$group = "";
			$sql_counter = "";
			}

		$sql   = "
			  SELECT
				rc.*
				".$sql_counter."
			   FROM
			     " . DB_PREFIX . "rate_".$prefix_table_main." rc
			   WHERE
			     rc.".$prefix_table_main."_id= " . (int) $review_id . "
			    ".$group."
			   ";
		$query = $this->db->query($sql);
		if (count($query->rows) > 0) {
			foreach ($query->rows as $rates) {
				if (!$all) {
					$rate[$rates['cid']] = $rates;
				} else {
                  $rate[] = $rates;
				}
			} //$query->rows as $rates
		} //count($query->rows) > 0
		return $query->rows;
	}



	public function addComment($mark_id, $data, $data_get, $mark='product_id')
	{

        if ($mark == 'product_id') {
         $prefix_table_main = 'review';
         $prefix_table_pr = 'product';
        }

        if ($mark == 'record_id') {
         $prefix_table_main = 'comment';
         $prefix_table_pr = 'record';
        }


		if (isset($data_get['parent'])) {
			$parent_id = $data_get['parent'];
		} //isset($data_get['parent'])
		else {
			$parent_id = 0;
		}
		$sql   = "
		SELECT r.*, p.*, pp.sorthex as sorthex_parent
		FROM " . DB_PREFIX . $prefix_table_main." r
		LEFT JOIN " . DB_PREFIX . $prefix_table_pr." p ON (r.".$mark." = p.".$mark.")
		LEFT JOIN " . DB_PREFIX . $prefix_table_pr."_description pd ON (p.".$mark." = pd.".$mark.")
		LEFT JOIN " . DB_PREFIX . $prefix_table_main." pp ON (r.parent_id = pp.".$prefix_table_main."_id)
		WHERE p.".$mark." = '" . (int) $mark_id . "'
		AND r.parent_id = '" . (int) $parent_id . "'
		AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "'
		ORDER BY r.sorthex DESC
		LIMIT 1";
		$query = $this->db->query($sql);
		if (count($query->rows) > 0) {
			foreach ($query->rows as $review) {
				$sorthex        = $review['sorthex'];
				$sorthex_parent = $review['sorthex_parent'];
				$sorthex        = substr($sorthex, strlen($sorthex_parent), 4);
			} //$query->rows as $review
			$sorthex = $sorthex_parent . (str_pad(dechex($sortdec = hexdec($sorthex) + 1), 4, "0", STR_PAD_LEFT));
		} //count($query->rows) > 0
		else {
			if ($parent_id == 0) {
				$sorthex = '0000';
			} //$parent_id == 0
			else {
				$queryparent = $this->db->query("
				SELECT c.sorthex
				FROM " . DB_PREFIX . $prefix_table_main." c
				WHERE c.".$prefix_table_main."_id = '" . (int) $parent_id . "'
				ORDER BY c.sorthex DESC
				LIMIT 1");
				if (count($queryparent->rows) > 0) {
					foreach ($queryparent->rows as $parent) {
						$sorthex = $parent['sorthex'];
					} //$queryparent->rows as $parent
					$sorthex = $sorthex . "0000";
				} //count($queryparent->rows) > 0
			}

		}

		if (!isset($data['text'])) {
			$data['text']='';
		}

		$sql = "INSERT INTO " . DB_PREFIX . $prefix_table_main." SET
		author = '" . $this->db->escape($data['name']) . "',
		customer_id = '" . (int) $this->customer->getId() . "',
		".$mark." = '" . (int) $mark_id . "',
		sorthex   = '" . $sorthex . "',
		parent_id = '" . (int) $parent_id . "',
		text = '" . $this->db->escape(strip_tags($data['text'])) . "',
		status = '" . $this->db->escape(strip_tags($data['status'])) . "',
		rating = '" . (int) $data['rating'] . "',
		rating_mark = '" . (int) $data['rating_mark'] . "',
		date_added = NOW()";
		$this->db->query($sql);
		$review_id = $this->db->getLastId();
		$sql_add   = "";

		if (isset($data['af'])) {
			$af_bool = $this->table_exists(DB_PREFIX . "review_fields");
			if ($af_bool) {
				foreach ($data['af'] as $num => $value) {
					if ($value != '') {
						$sql_add .= " " . $this->db->escape(strip_tags($num)) . " = '" . $this->db->escape(strip_tags($value)) . "',";
					} //$value != ''
				} //$data['af'] as $num => $value
				if ($sql_add != "") {
					$sql = "INSERT INTO " . DB_PREFIX . "review_fields SET " . $sql_add . " review_id='" . (int) $review_id . "', mark='".$mark."' ";
					$this->db->query($sql);
				} //$sql_add != ""
			} //$af_bool
		} //isset($data['af'])

		$this->cache->delete('blog.module.view');
		$this->cache->delete('product');
		$this->cache->delete('record');
		$this->cache->delete('blog');
		return $review_id;
	}


	public function checkRate($data = array(), $mark ='product_id')
	{
        if ($mark == 'product_id') {
         $prefix_table_main = 'review';
         $prefix_table_pr = 'product';
        }

        if ($mark == 'record_id') {
         $prefix_table_main = 'comment';
         $prefix_table_pr = 'record';
        }

		$sql   = "SELECT * FROM  " . DB_PREFIX . "rate_".$prefix_table_main." rc
		WHERE
		customer_id = '" . (int)$data['customer_id'] . "'
		AND
		".$prefix_table_main."_id = '" . (int)$data['comment_id'] . "'
		LIMIT 1";
		$query = $this->db->query($sql);
		return $query->rows;
	}


	public function getCommentSelf($data = array(), $mark ='product_id')
	{
        if ($mark == 'product_id') {
         $prefix_table_main = 'review';
         $prefix_table_pr = 'product';
        }

        if ($mark == 'record_id') {
         $prefix_table_main = 'comment';
         $prefix_table_pr = 'record';
        }

		$sql   = "SELECT * FROM  " . DB_PREFIX . $prefix_table_main." c
		WHERE
		c.customer_id = '" . (int)$data['customer_id'] . "'
		AND
		".$prefix_table_main."_id = '" . (int)$data['comment_id'] . "'
		";
		$query = $this->db->query($sql);
		return $query->rows;
	}




	public function getCategory($category_id, $mark='product_id')
	{
        if ($mark == 'product_id') {
         $prefix_table_main = 'category';
         $prefix_table_pr = 'category_id';
        }

        if ($mark == 'record_id') {
         $prefix_table_main = 'blog';
         $prefix_table_pr = 'blog_id';
        }

		$row = $this->cache->get('category.'.$mark.'.cati');
		if (!isset($row[$category_id])) {
			$query             = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . $prefix_table_main." c
			LEFT JOIN " . DB_PREFIX . $prefix_table_main."_description cd ON (c.".$prefix_table_pr." = cd.".$prefix_table_pr.")
			LEFT JOIN " . DB_PREFIX . $prefix_table_main."_to_store c2s ON (c.".$prefix_table_pr." = c2s.".$prefix_table_pr.")
			WHERE c.".$prefix_table_pr." = '" . (int) $category_id . "' AND cd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int) $this->config->get('config_store_id') . "'
			AND c.status = '1'
			");
			$row[$category_id] = $query->row;
			$this->cache->set('category.'.$mark.'.cati', $row[$category_id]);
		}
		return $row[$category_id];
	}

	public function getPathByProduct($mark_id='product_id')
	{
		$mark_id = (int) $mark_id;
		if ($mark_id < 1)
			return false;
		static $path = null;
		if (!is_array($path)) {
			$path = $this->cache->get('product.categories');
			if (!is_array($path))
				$path = array();
		} //!is_array($path)
		if (!isset($path[$mark_id])) {
			$sql               = "SELECT category_id FROM " . DB_PREFIX . "product_to_category
			WHERE product_id = '" . (int)$mark_id . "' ORDER BY category_id DESC LIMIT 1";
			$query             = $this->db->query($sql);
			$path[$mark_id] = $this->getPathByCategory($query->num_rows ? (int) $query->row['category_id'] : 0);
			$this->cache->set('product.categories', $path);
		} //!isset($path[$mark_id])
		return $path[$mark_id];
	}


	public function getPathByCategory($category_id)
	{
		if (strpos($category_id, '_') !== false) {
			$abid        = explode('_', $category_id);
			$category_id = $abid[count($abid) - 1];
		} //strpos($category_id, '_') !== false
		$category_id = (int) $category_id;
		if ($category_id < 1)
			return false;
		static $path = null;
		if (!is_array($path)) {
			$path = $this->cache->get('category.categories');
			if (!is_array($path))
				$path = array();
		} //!is_array($path)
		if (!isset($path[$category_id])) {
			$max_level = 10;
			$sql       = "SELECT td.name as name, CONCAT_WS('_'";
			for ($i = $max_level - 1; $i >= 0; --$i) {
				$sql .= ",t$i.category_id";
			} //$i = $max_level - 1; $i >= 0; --$i
			$sql .= ") AS path FROM " . DB_PREFIX . "category t0";
			for ($i = 1; $i < $max_level; ++$i) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "category t$i ON (t$i.category_id = t" . ($i - 1) . ".parent_id)";
			} //$i = 1; $i < $max_level; ++$i
			$sql .= "LEFT JOIN " . DB_PREFIX . "category_description td ON ( td.category_id = t0.category_id )";
			$sql .= " WHERE t0.category_id = '" . (int)$category_id . "'";
			$query                      = $this->db->query($sql);
			$path[$category_id]['path'] = $query->num_rows ? $query->row['path'] : false;
			$path[$category_id]['name'] = $query->num_rows ? $query->row['name'] : false;
			$this->cache->set('category.categories', $path);
		} //!isset($path[$category_id])
		return $path[$category_id];
	}



	public function checkRateNum($data = array(), $mark ='product_id')
	{
        if ($mark == 'product_id') {
         $prefix_table_main = 'review';
         $prefix_table_pr = 'product';
        }

        if ($mark == 'record_id') {
         $prefix_table_main = 'comment';
         $prefix_table_pr = 'record';
        }

		$sql               = "SELECT *,
        COUNT(c.". $prefix_table_pr."_id) as rating_num
		FROM  " . DB_PREFIX . $prefix_table_main." c
		LEFT JOIN " . DB_PREFIX . "rate_".$prefix_table_main." rc ON (rc.".$prefix_table_main."_id = c.".$prefix_table_main."_id)
		WHERE
		rc.customer_id = '" . (int)$data['customer_id'] . "'
		AND
		c.". $prefix_table_pr."_id = '" . (int)$data[$prefix_table_pr.'_id'] . "'
		GROUP BY c.". $prefix_table_pr."_id
		LIMIT 1";
		$query             = $this->db->query($sql);
		$query->row['sql'] = $sql;
		return $query->row;
	}


	public function getMarkbyComment($data = array(), $mark ='product_id')
	{
        if ($mark == 'product_id') {
         $prefix_table_main = 'review';
         $prefix_table_pr = 'product';
        }

        if ($mark == 'record_id') {
         $prefix_table_main = 'comment';
         $prefix_table_pr = 'record';
        }

		$sql               = "SELECT c.".$mark." as ".$mark.", c.".$mark." as product_id
		FROM  " . DB_PREFIX . $prefix_table_main." c
		WHERE
		c.".$prefix_table_main."_id = '" . (int)$data['comment_id'] . "'
		LIMIT 1";
		$query             = $this->db->query($sql);
		$query->row['sql'] = $sql;
		return $query->row;
	}




	public function addRate($data = array(), $mark ='product_id')
	{
        if ($mark == 'product_id') {
         $prefix_table_main = 'review';
         $prefix_table_pr = 'product';
        }

        if ($mark == 'record_id') {
         $prefix_table_main = 'comment';
         $prefix_table_pr = 'record';
        }

		$sql   = "INSERT INTO " . DB_PREFIX . "rate_".$prefix_table_main." SET
		customer_id = '" . (int) $data['customer_id'] . "',
		".$prefix_table_main."_id = '" . (int) $data['comment_id'] . "',
		delta = '" . (int) $data['delta'] . "' ";
		$query = $this->db->query($sql);

		$this->cache->delete('blog.module.view');
		$this->cache->delete('product');
		$this->cache->delete('record');
		$this->cache->delete('blog');


		return $sql;
	}



	public function getAverageRating($mark_id, $mark ='product_id')
	{
        if ($mark == 'product_id') {
         $prefix_table_main = 'review';
         $prefix_table_pr = 'product';
        }

        if ($mark == 'record_id') {
         $prefix_table_main = 'comment';
         $prefix_table_pr = 'record';
        }

		$query = $this->db->query("SELECT AVG(rating) AS total FROM " . DB_PREFIX . $prefix_table_main."
		WHERE status = '1' AND rating_mark = '0' AND ".$mark." = '" . (int) $mark_id . "' GROUP BY ".$mark."");
		if (isset($query->row['total'])) {
			return (int) $query->row['total'];
		} //isset($query->row['total'])
		else {
			return 0;
		}
	}
	public function getTotalComments($mark ='product_id')
	{
        if ($mark == 'product_id') {
         $prefix_table_main = 'review';
         $prefix_table_pr = 'product';
        }

        if ($mark == 'record_id') {
         $prefix_table_main = 'comment';
         $prefix_table_pr = 'record';
        }

		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . $prefix_table_main." r
		LEFT JOIN " . DB_PREFIX . $prefix_table_pr." p ON (r.".$mark." = p.".$mark.")
		WHERE p.date_available <= NOW() AND p.status = '1' AND r.status = '1'");
		return $query->row['total'];
	}

	public function table_exists($tableName)
	{
		$like   = addcslashes($tableName, '%_\\');
		$result = $this->db->query("SHOW TABLES LIKE '" . $this->db->escape($like) . "';");
		$found  = $result->num_rows > 0;
		return $found;
	}
	public function field_exists($tableName, $field)
	{
		$r = $this->db->query("SELECT `" . $field . "` FROM `" . DB_PREFIX . $tableName . "` WHERE 0");
		return $r;
	}
}
?>