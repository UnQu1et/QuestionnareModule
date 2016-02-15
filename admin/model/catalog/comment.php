<?php
class ModelCatalogComment extends Model
{
	public function addComment($data)
	{
		$parent_id = 0;
		$record_id = $data['record_id'];
		$sql       = "
		SELECT r.*, p.*, pp.sorthex as sorthex_parent
		FROM " . DB_PREFIX . "comment r
		LEFT JOIN " . DB_PREFIX . "record p ON (r.record_id = p.record_id)
		LEFT JOIN " . DB_PREFIX . "record_description pd ON (p.record_id = pd.record_id)
		LEFT JOIN " . DB_PREFIX . "comment pp ON (r.parent_id = pp.comment_id)
		WHERE p.record_id = '" . (int) $record_id . "'
		AND r.parent_id = '" . (int) $parent_id . "'
		AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "'
		ORDER BY r.sorthex DESC
		LIMIT 1";
		$query     = $this->db->query($sql);
		if (count($query->rows) > 0) {
			foreach ($query->rows as $comment) {
				$sorthex        = $comment['sorthex'];
				$sorthex_parent = $comment['sorthex_parent'];
				$sorthex        = substr($sorthex, strlen($sorthex_parent), 4);
			} //$query->rows as $comment
			$sorthex = $sorthex_parent . (str_pad(dechex($sortdec = hexdec($sorthex) + 1), 4, "0", STR_PAD_LEFT));
		} //count($query->rows) > 0
		else {
			if ($parent_id == 0) {
				$sorthex = '0000';
			} //$parent_id == 0
			else {
				$queryparent = $this->db->query("
				SELECT c.sorthex
				FROM " . DB_PREFIX . "comment c
				WHERE c.comment_id = '" . (int) $parent_id . "'
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
		$this->db->query("INSERT INTO " . DB_PREFIX . "comment
		SET author = '" . $this->db->escape($data['author']) . "',
		record_id = '" . $this->db->escape($data['record_id']) . "',
		sorthex   = '" . $sorthex . "',
		text = '" . $data['text'] . "',
		rating = '" . (int) $data['rating'] . "',
		status = '" . (int) $data['status'] . "', date_added = NOW()");

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



		$this->cache->delete('product');
		$this->cache->delete('record');
		$this->cache->delete('blog');
		$this->cache->delete('blog.module.view');
		return $comment_id = $this->db->getLastId();
	}
	public function getFields($data = array())
	{
		 $sql   = "SELECT * FROM  " . DB_PREFIX . "review_fields rf
		WHERE
		review_id = '" . (int)$data['review_id'] . "'
		AND
		mark = '" . $data['mark'] . "'
		LIMIT 1";
		$query = $this->db->query($sql);
		return $query->rows;
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

	public function editComment($comment_id, $data)
	{
		$this->db->query("UPDATE " . DB_PREFIX . "comment SET
		author = '" . $this->db->escape($data['author']) . "',
		record_id = '" . $this->db->escape($data['record_id']) . "',
		text = '" . $data['text'] . "',
		rating = '" . (int) $data['rating'] . "',
		status = '" . (int) $data['status'] . "',
		date_added = '" . $this->db->escape($data['date_available']) . "'
		WHERE comment_id = '" . (int) $comment_id . "'");


		if (isset($data['af'])) {
		$sql_add='';
			$af_bool = $this->table_exists(DB_PREFIX . "review_fields");
			if ($af_bool) {
			   $i=1;
				foreach ($data['af'] as $num => $value) {

						$sql_add .= " " . $this->db->escape(strip_tags($num)) . " = '" . $this->db->escape(strip_tags($value))."' ";

                     	if ($i!=count($data['af'])) $sql_add .=",";


                    $i++;
				}
				if (substr($sql_add, -1)==',') {
				 $sql_add = substr($sql_add, 0, strlen($sql_add)-1);
				}

				if ($sql_add != "") {

					 $sql   = "SELECT * FROM  " . DB_PREFIX . "review_fields rf
						WHERE
						review_id = '" . (int) $comment_id . "'
						AND
						mark = '" . $data['mark'] . "'
						LIMIT 1";
						$query = $this->db->query($sql);

                      if (count($query->rows) > 0) {
						$sql = "UPDATE " . DB_PREFIX . "review_fields SET " . $sql_add . " WHERE review_id='" . (int) $comment_id . "' AND mark='".$data['mark']."' ";

                      } else {
			  		$sql = "INSERT INTO " . DB_PREFIX . "review_fields SET " . $sql_add . ", review_id='" . (int) $comment_id . "', mark='".$data['mark']."' ";

					}
					$this->db->query($sql);
				} //$sql_add != ""
			} //$af_bool
		} //isset($data['af'])




		$this->cache->delete('product');
		$this->cache->delete('record');
		$this->cache->delete('blog');
		$this->cache->delete('blog.module.view');
	}


	public function deleteComment($comment_id)
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "comment WHERE comment_id = '" . (int) $comment_id . "'");
		$this->cache->delete('product');
		$this->cache->delete('record');
		$this->cache->delete('blog');
		$this->cache->delete('blog.module.view');
	}
	public function getComment($comment_id)
	{
		$query = $this->db->query("SELECT DISTINCT *, (SELECT pd.name FROM " . DB_PREFIX . "record_description pd WHERE pd.record_id = r.record_id AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "') AS record FROM " . DB_PREFIX . "comment r WHERE r.comment_id = '" . (int) $comment_id . "'");
		return $query->row;
	}
	public function getComments($data = array())
	{
		$sql       = "SELECT SQL_CALC_FOUND_ROWS r.comment_id, r.text, pd.name, r.author, r.rating, r.status, r.date_added


		FROM " . DB_PREFIX . "comment r
		LEFT JOIN " . DB_PREFIX . "record_description pd ON (r.record_id = pd.record_id)
		WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";
		$sort_data = array(
			'pd.name',
			'r.author',
			'r.rating',
			'r.status',
			'r.date_added'
		);
		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(pd.name) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		} //!empty($data['filter_name'])
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} //isset($data['sort']) && in_array($data['sort'], $sort_data)
		else {
			$sql .= " ORDER BY r.date_added";
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
		$query       = $this->db->query($sql);
		$sql         = "SELECT FOUND_ROWS()";
		$query_found = $this->db->query($sql);
		foreach ($query->rows as $num => $value) {
			$query->rows[$num]['total'] = $query_found->row['FOUND_ROWS()'];
		} //$query->rows as $num => $value
		return $query->rows;
	}
	public function getTotalComments($data)
	{
		$sql   = "SELECT COUNT(*) AS total
		FROM " . DB_PREFIX . "comment r
		";
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	public function getTotalCommentsAwaitingApproval()
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "comment WHERE status = '0'");
		return $query->row['total'];
	}
	public function getRecordIdbyCommentId($comment_id)
	{
		$sql   = "SELECT DISTINCT * FROM " . DB_PREFIX . "comment r WHERE r.comment_id = '" . (int) $comment_id . "'";
		$query = $this->db->query($sql);
		return $query->row['record_id'];
	}
	public function getProductbyReviewId($comment_id)
	{
		$sql   = "SELECT DISTINCT * FROM " . DB_PREFIX . "review r
		LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id)
		LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)
		LEFT JOIN " . DB_PREFIX . "product_to_store ps ON (p.product_id = ps.product_id)

		WHERE r.review_id = '" . (int) $comment_id . "'
		AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "'
        AND ps.store_id = '" . (int) $this->config->get('config_store_id') . "'";

		$query = $this->db->query($sql);
		return $query->row;
	}



}
?>