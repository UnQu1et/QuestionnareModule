<?php
/* All rights reserved belong to the module, the module developers http://opencartadmin.com */
// http://opencartadmin.com © 2011-2013 All Rights Reserved
// Distribution, without the author's consent is prohibited
// Commercial license
class ControllerRecordTreeComments extends Controller
{
	private $error = array();
	public function rdate($param, $time = 0)
	{
		//$this->language->load('record/blog');
		if (intval($time) == 0)
			$time = time();
		$MonthNames = array(
			$this->language->get('text_january'),
			$this->language->get('text_february'),
			$this->language->get('text_march'),
			$this->language->get('text_april'),
			$this->language->get('text_may'),
			$this->language->get('text_june'),
			$this->language->get('text_july'),
			$this->language->get('text_august'),
			$this->language->get('text_september'),
			$this->language->get('text_october'),
			$this->language->get('text_november'),
			$this->language->get('text_december')
		);
		if (strpos($param, 'M') === false)
			return date($param, $time);
		else {
			$str_begin  = date(utf8_substr($param, 0, utf8_strpos($param, 'M')), $time);
			$str_middle = $MonthNames[date('n', $time) - 1];
			$str_end    = date(utf8_substr($param, utf8_strpos($param, 'M') + 1, utf8_strlen($param)), $time);
			$str_date   = $str_begin . $str_middle . $str_end;
			return $str_date;
		}
	}

	public function comment()
	{
            if (isset($this->request->get['prefix'])) {
             $this->data['prefix']=$this->request->get['prefix'];
            } else {

            if ($this->registry->get("prefix")!='') {
						$this->data['prefix'] = $this->registry->get("prefix");
			} else  {
            			$this->data['prefix']='';
            		}
            }



			if (isset($this->request->post['thislist'])) {
				$str                    = base64_decode($this->request->post['thislist']);
				$this->data['thislist'] = unserialize($str);
			} else {

			    $numargs = func_num_args();
			    if ($numargs >= 1) {
			        $this->data['thislist']= func_get_arg(0);
			    } else {
				  $this->data['thislist'] = Array();
				}
			}

			$this->language->load('record/blog');
			$this->language->load('record/record');




                 if (isset($this->data['thislist']['langfile']) && $this->data['thislist']['langfile']!='') {
                   $this->language->load($this->data['thislist']['langfile']);
                 }




		if (isset($this->request->get['product_id']) || isset($this->request->get['record_id']) || isset($this->data['thislist']['recordid'])) {
			$comments_settings = Array();
			$record_info    = Array();
            $record_info['comment']    = Array();
			$this->data['mark'] = false;

			if (isset($this->request->get['product_id'])) {
				$this->data['mark']       = 'product_id';
				$this->data['product_id'] = $this->request->get['product_id'];
				$mark_route               = 'product/product';


			}

			if (isset($this->request->get['record_id'])) {
				$this->data['mark']       = 'record_id';
				$this->data['product_id'] = $this->request->get['record_id'];
				$mark_route               = 'record/record';

		}

            if (isset($this->data['thislist']['recordid']) && $this->data['thislist']['recordid']!='') {

				$this->data['mark']       = 'record_id';
				$this->data['product_id'] = $this->data['thislist']['recordid'];
				$mark_route               = 'record/record';


            }

         $this->data['url'] =  $this->url->link($mark_route, $this->data['mark'].'=' . $this->data['product_id']);
         $this->data['mark_id'] = $this->data['product_id'];




			$this->data['entry_sorting']      = $this->language->get('entry_sorting');
			$this->data['text_sorting_desc']  = $this->language->get('text_sorting_desc');
			$this->data['text_sorting_asc']   = $this->language->get('text_sorting_asc');
			$this->data['text_rollup']        = $this->language->get('text_rollup');
			$this->data['text_rollup_down']   = $this->language->get('text_rollup_down');
			$this->data['text_no_comments']   = $this->language->get('text_no_comments');
			$this->data['text_reply_button']  = $this->language->get('text_reply_button');
			$this->data['text_edit_button']   = $this->language->get('text_edit_button');
			$this->data['text_delete_button'] = $this->language->get('text_delete_button');
			if ($this->customer->isLogged()) {
				$this->data['text_login']     = $this->customer->getFirstName() . " " . $this->customer->getLastName();
				$this->data['captcha_status'] = false;
				$this->data['customer_id']    = $this->customer->getId();
			} else {
				$this->data['text_login']     = $this->language->get('text_anonymus');
				$this->data['captcha_status'] = true;
			}

           	$this->load->model('catalog/treecomments');
			$this->load->model('catalog/product');
			$this->load->model('catalog/record');

			if ($this->data['mark'] == 'product_id') {

				$mark_info = $this->model_catalog_product->getProduct($this->data['mark_id']);
				$b_path    = $this->model_catalog_treecomments->getPathByProduct($this->data['mark_id']);
				//$this->data['mark_info'] = $mark_info;
			}

			if ($this->data['mark'] == 'record_id') {
				$mark_info = $this->model_catalog_record->getRecord($this->data['mark_id']);
				$this->load->model('catalog/blog');
				$b_path = $this->model_catalog_blog->getPathByRecord($this->data['mark_id']);
				$record_info    = $this->model_catalog_record->getRecord($this->data['mark_id']);
			    //$this->data['mark_info'] = $record_info;
			}


  			$category_path = $b_path['path'];
			if (isset($category_path)) {
				if (strpos($category_path, '_') !== false) {
					$abid        = explode('_', $category_path);
					$category_id = $abid[count($abid) - 1];
				} else {
					$category_id = (int) $category_path;
				}
				$category_id = (int) $category_id;
			}




			if (!isset($category_id))
				$category_id = 0;


			$category_info = $this->model_catalog_treecomments->getCategory($category_id, $this->data['mark']);
			if (isset($category_info['design']) && $category_info['design'] != '') {
				$this->data['category_design'] = unserialize($category_info['design']);
			} else {
				$this->data['category_design'] = Array();
			}


		if ($this->config->get('generallist') != '') {
			$this->data['settings_general'] = $this->config->get('generallist');
		} //$this->config->get('generallist') != ''
		else {
			$this->data['settings_general'] = Array();
		}

		if (!isset($this->data['settings_general']['colorbox_theme'])) {
			$this->data['settings_general']['colorbox_theme'] = 0;
		} //!isset($this->data['settings_general']['colorbox_theme'])

        $get = $this->request->get;



        if (isset($this->data['settings_general']['get_pagination']))
        $get_pagination = $this->data['settings_general']['get_pagination'];
        else
        $get_pagination = 'tracking';

           	if (isset($get['mylist_position'])) {
				$this->data['mylist_position'] = $get['mylist_position'];
			} else {


			$this->data['mylist_position'] = $this->registry->get('mylist_position');

            }

            $cmswidget = $this->data['mylist_position'];
            $cmswidget_flag = false;

			if (isset($get[$get_pagination])) {
				$tracking = $get[$get_pagination];
			} else {
				$tracking = '';
			}
			if ($tracking != '') {
				$parts = explode('_', trim(utf8_strtolower($tracking)));

				foreach ($parts as $num => $val) {
					 $aval = explode("-", $val);
					 if (isset($aval[0]) && $aval[0]=='cmswidget') {

                        if (isset($aval[1]) && $aval[1]==$cmswidget) {
                    		$cmswidget_flag = true;
                    	}
	                 }

				}


                if ($cmswidget_flag) {
					foreach ($parts as $num => $val) {
						 $aval = explode("-", $val);
						 if (isset($aval[0])) {
	                    	$getquery = $aval[0];
	                        if (isset($aval[1])) {
	                    		$getpar = $aval[1];
		                   	 	$get[$getquery] = $getpar;
	                    	}
		                 }

					}
				}


			}




			if (isset($get['wpage']) && isset($get['cmswidget']) && $get['cmswidget']==$cmswidget) {
				$page = $get['wpage'];
			} else {
				$page = 1;
			}

			if (isset($get['ajax']) && $get['ajax'] == '1' && isset($get['page'])) {
				$page = $get['wpage'] = $get['page'];
			}

			$this->data['wpage'] = $this->data['page'] = $page;


           	if (isset($record_info['comment']) && !empty($record_info['comment'])) {
           		$comments_settings_record = unserialize($record_info['comment']);
           	} else {
           	 $comments_settings_record = Array();
           	}

			$comments_settings = $comments_settings_record + $this->data['thislist'];


			$this->data['sorting'] = 'desc';
			$comments_order        = 'comment_id';


			if (isset($comments_settings['order_ad']) && $comments_settings['order_ad'] != '') {
				$this->data['sorting'] = strtolower($comments_settings['order_ad']);
			}
			if (isset($comments_settings['order']) && $comments_settings['order'] != '') {
				$this->data['order'] = strtolower($comments_settings['order']);
			}
			if (isset($this->data['order']) && $this->data['order'] == 'sort') {
				$comments_order = 'comment_id';
			}
			if (isset($this->data['order']) && $this->data['order'] == 'date') {
				$comments_order = 'date_available';
			}
			if (isset($this->data['order']) && $this->data['order'] == 'rating') {
				$comments_order = 'rating';
			}
			if (isset($this->data['order']) && $this->data['order'] == 'rate') {
				$comments_order = 'delta';
			}
			if (isset($get['sorting'])) {
				if ($get['sorting'] == 'none') {
					$this->data['sorting'] = $this->data['sorting'];
				} else
					$this->data['sorting'] = $get['sorting'];
			}







			if (isset($this->data['thislist']['view_captcha']) && $this->data['thislist']['view_captcha'] == 0) {
				$this->data['captcha_status'] = false;
			}
			if (((isset($this->data['thislist']['visual_editor']) && isset($this->data['thislist']['comment_must']) && $this->data['thislist']['comment_must'] && $this->data['thislist']['visual_editor'])) || !isset($this->data['thislist']['visual_editor'])) {
				$this->data['visual_editor'] = true;
				$this->document->addScript('catalog/view/javascript/wysibb/jquery.wysibb.min.js');
				$this->document->addStyle('catalog/view/javascript/wysibb/theme/default/wbbtheme.css');
				$this->document->addScript('catalog/view/javascript/blog/blog.bbimage.js');
				require_once(DIR_SYSTEM . 'library/bbcode.class.php');
				//$this->document->addScript('catalog/view/javascript/blog/rating/jquery.MetaData.js');
				$this->document->addScript('catalog/view/javascript/blog/rating/jquery.rating.js');
				$this->document->addStyle('catalog/view/javascript/blog/rating/jquery.rating.css');
			} else {
				$this->data['visual_editor'] = false;
			}
			$thislist = $this->data['thislist'];

			$this->data['record_comment'] = $thislist;

			if (isset($thislist['order_ad']) && $thislist['order_ad'] != '') {
				$this->data['sorting'] = strtolower($thislist['order_ad']);
			}
			if (isset($get['sorting'])) {
				if ($get['sorting'] == 'none') {
					$this->data['sorting'] = $this->data['sorting'];
				} else
					$this->data['sorting'] = $get['sorting'];
			}

			$this->data['comments'] = array();
			$comment_total          = $this->model_catalog_treecomments->getTotalCommentsByMarkId($this->data['mark_id'], $this->data['mark']);

			if (isset($thislist['number_comments']))
				$this->data['number_comments'] = $thislist['number_comments'];
			else
				$this->data['number_comments'] = '';
			if ($this->data['number_comments'] == '')
				$this->data['number_comments'] = 10;



			$mark    = $this->data['mark'];
			$data    = array(
				$mark => $this->data['mark_id'],
				'start' => ($page - 1) * $this->data['number_comments'],
				'limit' => $this->data['number_comments']
			);
			$results = $this->model_catalog_treecomments->getCommentsByMarkId($data, $mark);



			if ($this->customer->isLogged()) {
				$customer_id = $this->customer->getId();
			} else {
				$customer_id = -1;
			}

             $this->data[$this->data['mark']] = $this->data['mark_id'];






			$results_rates = $this->model_catalog_treecomments->getRatesByMarkId($this->data['mark_id'], $customer_id, $this->data['mark']);
			if ($customer_id == -1) {
			 $customer_id = false;
			}




			if (count($results) > 0) {

				if (!function_exists('sdesc')) {
					function sdesc($a, $b)
					{
						return (strcmp($a['sorthex'], $b['sorthex']));
					}
				}

				$resa = NULL;
				foreach ($results as $num => $res1) {
					$resa[$num] = $res1;
					if (isset($results_rates[$res1['review_id']])) {
						$resa[$num]['delta']            = $results_rates[$res1['review_id']]['rate_delta'];
						$resa[$num]['rate_count']       = $results_rates[$res1['review_id']]['rate_count'];
						$resa[$num]['rate_count_blog_plus']  = $results_rates[$res1['review_id']]['rate_delta_blog_plus'];
						$resa[$num]['rate_count_blog_minus'] = $results_rates[$res1['review_id']]['rate_delta_blog_minus'];
						$resa[$num]['customer_delta']   = $results_rates[$res1['review_id']]['customer_delta'];
					} else {
						$resa[$num]['customer_delta']   = 0;
						$resa[$num]['delta']            = 0;
						$resa[$num]['rate_count']       = 0;
						$resa[$num]['rate_count_blog_plus']  = 0;
						$resa[$num]['rate_count_blog_minus'] = 0;
					}
					$resa[$num]['hsort'] = '';
					$mmm                 = NULL;
					$kkk                 = '';
					$wh                  = strlen($res1['sorthex']) / 4;
					for ($i = 0; $i < $wh; $i++) {
						$mmm[$i] = str_pad(dechex(65535 - hexdec(substr($res1['sorthex'], $i * 4, 4))), 4, "F", STR_PAD_LEFT);
						$sortmy  = substr($res1['sorthex'], $i * 4, 4);
						$kkk     = $kkk . $sortmy;
					}
					$ssorthex = '';
					if (is_array($mmm)) {
						foreach ($mmm as $num1 => $val) {
							$ssorthex = $ssorthex . $val;
						}
					}
					if ($this->data['sorting'] != 'asc') {
						$resa[$num]['sorthex'] = $ssorthex;
					} else {
						$resa[$num]['sorthex'] = $kkk;
					}
					$resa[$num]['hsort'] = $kkk;
				}
				$results = NULL;
				$results = $resa;
				uasort($results, 'sdesc');

 				if (!function_exists('comp_field')) {
					function comp_field($a, $b)
					{
						if (!isset($a['field_order']) || $a['field_order']=='') $a['field_order']='9999999';
						if (!isset($b['field_order']) || $b['field_order']=='') $b['field_order']='9999999';

						$a['field_order'] = (int)$a['field_order'];
						$b['field_order'] = (int)$b['field_order'];

						if ($a['field_order'] > $b['field_order'])
							return 1;
						if ($b['field_order'] > $a['field_order'])
							return -1;
						return 0;
					}
				}




 		//$this->load->model('catalog/fields');
		//$fields_db = $this->model_catalog_fields->getFieldsDBlang();
/*
print_r("<PRE>");
print_r($fields_db);
print_r("</PRE>");
*/

  				$this->data['fields'] = array();
				if (isset($thislist['addfields'])) {
					usort($thislist['addfields'], 'comp_field');
					$this->data['fields'] = $thislist['addfields'];
				}

				$i = 0;
				foreach ($results as $num => $result) {

			        $f=0;
			        $addfields = array();
					foreach ($result as $field_key=>$field) {

							foreach ($this->data['fields'] as $num_db => $field_db) {

			                  if (trim($field_key) == trim($field_db['field_name']))
			                  {
			                    $field_db['value'] = $field_db['text'] = $result[$field_key];
			                    $addfields[$f]  = $field_db;
			                    break;
			                  } else {
			                  	//$fields_list[$i] = $field;
			                  }
							}

							$f++;
					}
					usort($addfields, 'comp_field');

//***************************************************************************************************


					if (!isset($result['date_available']))
						$result['date_available'] = $result['date_added'];

					if ($this->rdate($this->language->get('text_date')) == $this->rdate($this->language->get('text_date'), strtotime($result['date_available']))) {
						$date_str = $this->language->get('text_today');
					} else {
						$date_str = $this->language->get('text_date');
					}
					$date_added = $this->rdate($date_str . $this->language->get('text_hours'), strtotime($result['date_added']));





					$text = strip_tags($result['text']);
					if ($this->data['visual_editor']) {
						if (isset($this->data['thislist']['bbwidth']) && $this->data['thislist']['bbwidth'] != '') {
							BBCode::$width = $this->data['thislist']['bbwidth'];
						}
						$text = BBCode::parse($text);
					}
					$this->data['comments'][] = array(
						'comment_id' => $result['review_id'],
						'sorthex' => $result['sorthex'],
						'customer_id' => $result['customer_id'],
						'customer' => $customer_id,
						'customer_delta' => $result['customer_delta'],
						'level' => (strlen($result['sorthex']) / 4) - 1,
						'parent_id' => $result['parent_id'],
						'author' => $result['author'],
						'text' => $text,
						'rating' => (int) $result['rating'],
						'rating_mark' => (int) $result['rating_mark'],
						'hsort' => $result['hsort'],
						'myarray' => $mmm,
						'fields' => $addfields,
						'delta' => $result['delta'],
						'rate_count' => $result['rate_count'],
						'rate_count_blog_plus' => $result['rate_count_blog_plus'],
						'rate_count_blog_minus' => $result['rate_count_blog_minus'],
						'comments' => sprintf($this->language->get('text_comments'), (int) $comment_total),
						'date_added' => $date_added,
						'date_available' => $result['date_available']
					);
					$i++;
				}
			}



			if (!function_exists('compare')) {
				function compare($a, $b)
				{
					if ($a['comment_id'] > $b['comment_id'])
						return 1;
					if ($b['comment_id'] > $a['comment_id'])
						return -1;
					return 0;
				}
			}
			if (!function_exists('compared')) {
				function compared($a, $b)
				{
					if ($a['comment_id'] > $b['comment_id'])
						return -1;
					if ($b['comment_id'] > $a['comment_id'])
						return 1;
					return 0;
				}
			}

			if (!function_exists('my_sort_div_mark')) {
				function my_sort_div_mark($data, $parent = 0, $sorting, $field, $lev = -1)
				{
					$arr = $data[$parent];
					usort($arr, array(
						new cmp_my_comment($field, $sorting),
						"my_cmp"
					));
					$lev = $lev + 1;
					for ($i = 0; $i < count($arr); $i++) {
						$arr[$i]['level']               = $lev;
						$z[]                            = $arr[$i];
						$z[count($z) - 1]['flag_start'] = 1;
						$z[count($z) - 1]['flag_end']   = 0;
						if (isset($data[$arr[$i]['comment_id']])) {
							$m = my_sort_div_mark($data, $arr[$i]['comment_id'], $sorting, $field, $lev);
							$z = array_merge($z, $m);
						}
						if (isset($z[count($z) - 1]['flag_end']))
							$z[count($z) - 1]['flag_end']++;
						else
							$z[count($z) - 1]['flag_end'] = 1;
					}
					return $z;
				}
			}

			if (count($this->data['comments']) > 0) {
				for ($i = 0, $c = count($this->data['comments']); $i < $c; $i++) {
					$new_arr[$this->data['comments'][$i]['parent_id']][] = $this->data['comments'][$i];
				}
				$mycomments = my_sort_div_mark($new_arr, 0, $this->data['sorting'], $comments_order);



				$i          = 0;
				foreach ($mycomments as $num => $result) {
					if (($i >= (($page - 1) * $this->data['number_comments'])) && ($i < ((($page - 1) * $this->data['number_comments']) + $this->data['number_comments']))) {
						$this->data['mycomments'][$i] = $result;
					}
					$i++;
				}
			} else {
				$this->data['mycomments'] = Array();
			}

            if (!isset($this->data['mycomments'])) {
            	$this->data['mycomments'] = array();
            }

			$this->data['comments'] = $this->data['mycomments'];
            $this->data['karma_voted'] = false;

			 if (!$customer_id)
			 {
                  if (isset($_COOKIE["karma_".$this->data['mark']])) {
			           	 $karma_cookie= unserialize(base64_decode($_COOKIE["karma_".$this->data['mark']]));
			      } else {
			           	 $karma_cookie = Array();
			      }

					 foreach ($karma_cookie as $id => $mark_id) {
	            	if ($mark_id == $this->data['mark_id']) {


	            	 $this->data['karma_voted'] = true;

	            	}
				 }
			 } else {

	           $check_rate_num  = $this->model_catalog_treecomments->checkRateNum($this->data, $this->data['mark']);

              		foreach ($check_rate_num as $id => $mark_id) {
	            	if ($id == $this->data['mark'] && $mark_id == $this->data['mark_id']) {

		            	 $this->data['karma_voted'] = true;

		            	}
	            	}

			 }


             $url_end ="";
             foreach ($this->request->get as $get_key => $get_val) {
              if ($get_key!='route' && $get_key!='prefix' && $get_key!='_route_' && $get_key!='wpage' &&  $get_key!='cmswidget' &&  $get_key!=$get_pagination) {
              	$url_end.= "&".(string)$get_key."=". (string)$get_val;
              }
             }

             $this->data['cmswidget'] = $cmswidget;


	         $link_url = $this->url->link($mark_route, $this->data['mark'] . '=' . $this->data['mark_id'] . '&'.$get_pagination.'=cmswidget-'.$cmswidget.'_sorting-' . $this->data['sorting'] . '_wpage-{page}'.'#cmswidget-'.$cmswidget);





			$pagination             = new Pagination();
			$pagination->total      = $comment_total;
			$pagination->page       = $page;
			$pagination->limit      = $this->data['number_comments'];
			$pagination->text       = $this->language->get('text_pagination');
			//$pagination->url = $this->url->link($mark_route, $this->data['mark'] . '=' . $this->data['mark_id'] . '&'.$get_pagination.'=sorting-' . $this->data['sorting'] . '_page-{page}&cpage={page}');

			 $pagination->url = $link_url;

			$this->data['pagination'] = $pagination->render();



            //if ($page > 1 ) {
			// $this->data['url'] = $this->url->link($mark_route, $this->data['mark'] . '=' . $this->data['mark_id'] . '&'.$get_pagination.'=sorting-' . $this->data['sorting'] . '_page-'.$page.'&cpage='.$page);
			//} else {
			// $this->data['url'] = $this->url->link($mark_route, $this->data['mark'] . '=' . $this->data['mark_id'] . '&'.$get_pagination.'=sorting-' . $this->data['sorting']);
			//}


			$template = 'treecomment.tpl';

			if (isset($thislist['blog_template_comment']) && $thislist['blog_template_comment'] != '') {
				$template = $thislist['blog_template_comment'];
			}

			if (isset($this->data['category_design']['blog_template_comment']) && $this->data['category_design']['blog_template_comment'] != '') {
				$template = $this->data['category_design']['blog_template_comment'];
			}

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/agoodonut/widgets/treecomments/treecomment/' . $template)) {
				$this->template = $this->config->get('config_template') . '/template/agoodonut/widgets/treecomments/treecomment/' . $template;
			} else {
				if (file_exists(DIR_TEMPLATE . 'default/template/agoodonut/widgets/treecomments/treecomment/' . $template)) {
					$this->template = 'default/template/agoodonut/widgets/treecomments/treecomment/' . $template;
				} else {
					$this->template = 'default/template/agoodonut/widgets/treecomments/treecomment/treecomment.tpl';
				}
			}

			$this->data['text_wait']       = $this->language->get('text_wait');
			$this->data['theme']           = $this->config->get('config_template');
			$this->data['mylist']          = $this->config->get('mylist');
			$this->data['settings_widget'] = $this->data['thislist'];


			$html = $this->render();

			if (isset($get['ajax']) && $get['ajax'] == 1) {
				$this->response->setOutput($html);
			} else {
				return $html;
			}
		}
	}





	public function write()
	{
        $this->request->post['rating_mark'] = 0;
        $this->data['settings'] = array();
		$this->data['mark'] = false;
		$product_id         = 0;

		if (isset($this->request->get['product_id'])) {
			$this->data['mark'] = 'product_id';
			$mark_route         = 'product/product';
			$product_id         = $this->request->get['product_id'];

		}

		if (isset($this->request->get['record_id'])) {
			$this->data['mark'] = 'record_id';
			$mark_route         = 'record/record';
			$product_id         = $this->request->get['record_id'];

			$this->load->model('catalog/blog');
			$blog_info                     = $this->model_catalog_blog->getPathByrecord($product_id);
			$this->request->get['blog_id'] = $blog_info['path'];


			if (isset($blog_info['path'])) {
				$path = '';
				foreach (explode('_', $blog_info['path']) as $path_id) {
					$blog_id = $path_id;
				}
			  	$blog_info = $this->model_catalog_blog->getBlog($blog_id);
			}
			else {
				$blog_id = false;
			}

			if (isset($blog_info['design']) && $blog_info['design'] != '') {
				$this->data['settings_blog'] = unserialize($blog_info['design']);
			} else {
				$this->data['settings_blog'] = Array();
			}

			$this->load->model('catalog/record');
			$record_info = $this->model_catalog_record->getRecord($product_id);
            if ($record_info) {
            	$this->data['settings_record'] = unserialize($record_info['comment']);
            } else {
           		$this->data['settings_record'] = array();
            }

           //$this->data['settings'] = $this->data['settings_blog'] + $this->data['settings_record'];
           $this->data['settings'] = array_merge($this->data['settings_blog'], $this->data['settings_record']);

		}



		$json = array();
		$html = "<script>var wdata = new Array()
							wdata['code'] 	 = 'error'
							wdata['message'] = 'Error'</script>";

		$this->load->model('catalog/treecomments');


		if (isset($this->request->get['parent'])) {
			if ($this->request->get['parent'] == '')
				$this->request->get['parent'] = 0;
		} else {
			$this->request->get['parent'] = 0;
		}
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
		}
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_catalog_limit');
		}
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
			$captcha_status    = false;
			$customer_id = $this->customer->getId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
			$captcha_status    = true;
			$customer = false;
			$customer_id = -1;
		}

		$this->data['mylist'] = $this->config->get('mylist');
		if (isset($this->request->get['mylist_position'])) {
			$mylist_position = $this->request->get['mylist_position'];
		} else {
			$mylist_position = 0;
		}
		$set_thislist = Array(
			'status' => '1',
			'status_reg' => '0',
			'status_now' => '0'
		);
		if (isset($this->data['mylist'][$mylist_position]) && !empty($this->data['mylist'][$mylist_position])) {
			$thislist = $this->data['mylist'][$mylist_position];
		} else {
			$thislist = Array(
				'status' => '1',
				'status_reg' => '0',
				'status_now' => '0',
				'rating_must' => '1'
			);
		}



		$thislist                                     = $thislist + $set_thislist;
		$thislist                                     = $this->data['settings']+$thislist;

		$k                                            = serialize($thislist);
		$this->data['comment_status']                 = $thislist['status'];
		$this->data['comment_status_reg']             = $thislist['status_reg'];
		$this->data['comment_status_now']             = $thislist['status_now'];
		$this->data['comment']                        = $thislist;

		$this->request->post['comment']['status']     = $thislist['status'];
		$this->request->post['comment']['status_reg'] = $thislist['status_reg'];
		$this->request->post['comment']['status_now'] = $thislist['status_now'];
		$this->request->post['status']                = $thislist['status_now'];

		$this->language->load('record/record');
        $this->language->load('product/product');

         if (isset($thislist['langfile']) && $thislist['langfile']!='') {
               $this->language->load($thislist['langfile']);
         } else {
            $this->language->load('record/blog');
         }

 				if (!function_exists('comp_field')) {
					function comp_field($a, $b)
					{
						if (!isset($a['field_order']) || $a['field_order']=='') $a['field_order']='9999999';
						if (!isset($b['field_order']) || $b['field_order']=='') $b['field_order']='9999999';

						$a['field_order'] = (int)$a['field_order'];
						$b['field_order'] = (int)$b['field_order'];

						if ($a['field_order'] > $b['field_order'])
							return 1;
						if ($b['field_order'] > $a['field_order'])
							return -1;
						return 0;
					}
				}

				if (isset($thislist['fields_view']))
					$this->data['fields_view'] = $thislist['fields_view'];
				else
					$this->data['fields_view'] = 0;

				if (isset($thislist['addfields'])) {
					usort($thislist['addfields'], 'comp_field');
					$this->data['fields'] = $thislist['addfields'];
				} else {
					$this->data['fields'] = array();
				}


 		$this->load->model('catalog/fields');
		$fields_db = $this->model_catalog_fields->getFieldsDBlang();





			foreach ($this->data['fields'] as $num => $field) {
				foreach ($fields_db as $num_db => $field_db) {
					if ($field['field_name']==$field_db['field_name']) {

			         foreach ($field_db as $num_1=>$field_1) {

			         	if (!isset($this->data['fields'][$num][$num_1]) || $field_db[$num_1] == '') {
			               $this->data['fields'][$num][$num_1] = $field_1;
			         	} else {

			         	}

			         }
					}
				}
			}







		if (!isset($this->request->post['name']) || ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 33))) {
			$json['error'] = $this->language->get('error_name');
			$html          = "<script>var wdata = new Array()
							wdata['code'] 	 = 'error'
							wdata['message'] = '" . $this->language->get('error_name') . "'</script>";
		} else {
			$json['login'] = $this->request->post['name'];
		}

		if ((isset($thislist['comment_must']) && $thislist['comment_must']) || !isset($thislist['comment_must'])) {

			if (!isset($this->request->post['text']) || (utf8_strlen($this->request->post['text']) < 3 || utf8_strlen($this->request->post['text']) > 1000)) {
				$json['error'] = $this->language->get('error_text');
				$html          = "<script>var wdata = new Array()
								wdata['code'] 	 = 'error'
								wdata['message'] = '" . $this->language->get('error_text') . "'</script>";
			}
		}

		if (!isset($this->request->post['rating']) && $thislist['rating_must']==1) {
			$json['error'] = $this->language->get('error_rating');
			$html          = "<script>var wdata = new Array()
							wdata['code'] 	 = 'error'
							wdata['message'] = '" . $this->language->get('error_rating') . "'</script>";
		}

		if (isset($thislist['rating_must']) && $thislist['rating_must']==0 && !isset($this->request->post['rating'])) {
          $this->request->post['rating'] = 5;
          $this->request->post['rating_mark'] = 1;
		}


		if (!isset($this->session->data['captcha']) || (isset($this->request->post['captcha']) && strtolower($this->session->data['captcha']) != strtolower($this->request->post['captcha']))) {
			if ($captcha_status) {
				$json['error'] = $this->language->get('error_captcha');
				$html          = "<script>var wdata = new Array()
							wdata['code'] 	 = 'error'
							wdata['message'] = '" . $this->language->get('error_captcha') . "'</script>";
			}
		}

		if ($thislist['status_reg'] && !$this->customer->isLogged()) {
			$error_reg     = sprintf($this->language->get('error_reg'), $this->url->link('account/login'), $this->url->link('account/register'));
			$json['error'] = $error_reg;

			$html = "<script>var wdata = new Array()
							wdata['code'] 	 = 'error'
							wdata['message'] = '" . $error_reg  . "'</script>";
		}

		/*

print_r("<PRE>");
print_r($this->data['fields']);
print_r($this->request->post['af']);
print_r("</PRE>");
          */
$error = '';
foreach ($this->data['fields'] as $num => $field) {

	if (isset($this->request->post['af'][$field['field_name']]) && $this->request->post['af'][$field['field_name']]=='' && isset($field['field_must']) && $field['field_must']) {

	 		if (isset($field['field_error'][$this->config->get('config_language_id')])) {
	 			$error = $error.(preg_replace("/(\r\n)+/i", "",  html_entity_decode($field['field_error'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8')))."<br>" ;
	 			$error = preg_replace("/(\')+/i", '"', $error);
	 		}

	 		$json['error'] = $error;
			$html          = "<script>var wdata = new Array()
							wdata['code'] 	 = 'error'
							wdata['message'] = '" . $error . "'</script>";
	}

}



		if (!isset($json['login']) || $json['login'] == '') {
			if ($this->customer->isLogged()) {
				$json['login']       = $this->customer->getFirstName() . " " . $this->customer->getLastName();
				$json['customer_id'] = $this->data['customer_id'] = $this->customer->getId();
			} else {
				$json['login'] = $this->language->get('text_anonymus');
			}
		}
		$this->load->model('catalog/treecomments');

		$this->data['karma_voted'] = false;

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && !isset($json['error'])) {


			$comment_id  = $this->model_catalog_treecomments->addComment($this->request->get[$this->data['mark']], $this->request->post, $this->request->get, $this->data['mark']);


   				 if ($customer_id == -1) {

                  if (isset($_COOKIE["karma_".$this->data['mark']])) {
			           	 $karma_cookie= unserialize(base64_decode($_COOKIE["karma_".$this->data['mark']]));
			      } else {
			           	 $karma_cookie = Array();
			      }

                 $karma_cookie[$comment_id] = $product_id;

				 foreach ($karma_cookie as $id => $mark_id) {
	            	if ($mark_id == $product_id) {
	            	 $this->data['karma_voted'] = true;
	            	}

  				 }

				  setcookie("karma_".$this->data['mark'], base64_encode(serialize($karma_cookie)), time() + 60 * 60 * 24 * 555, '/', $this->request->server['HTTP_HOST']);
			  }

			$this->data['comment_count'] = $this->model_catalog_treecomments->getTotalCommentsByMarkId($product_id, $this->data['mark']);

			if ($this->data['mark'] == 'product_id') {
				$this->load->model('catalog/product');
				$mark_info = $this->model_catalog_product->getProduct($product_id);
			}

			if ($this->data['mark'] == 'record_id') {
				$this->load->model('catalog/record');
				$mark_info = $this->model_catalog_record->getRecord($product_id);

			}



			$this->signer($product_id, $mark_info, $thislist, $this->data['mark']);



			$review_count = sprintf($this->language->get('tab_review'), $this->data['comment_count']);

			if ($thislist['status_now']) {
				$json['success'] = $this->language->get('text_success_now');
			 	$html            = "<script>var wdata = new Array();
							wdata['code'] 	 = 'success';
							wdata['message'] = '" . $this->language->get('text_success_now') . "';
							wdata['login'] = '" . $json['login'] . "';
							wdata['review_count'] = '" . $review_count . "';
							</script>";
			} else {
				$json['success'] = $this->language->get('text_success');
				$html            = "<script>var wdata = new Array();
							wdata['code'] 	 = 'success';
							wdata['message'] = '" . $this->language->get('text_success') . "';
							wdata['login'] = '" . $json['login'] . "';
							wdata['review_count'] = '" . $review_count . "';
							</script>";
			}
		}

		$this->response->setOutput($html);

	}


	public function signer($product_id, $record_info, $settings, $mark)
	{
		$record_settings = $settings;

        if (!isset($record_info['name'])) $record_info['name'] = '';


		if ($settings['signer'] || $settings['comments_email'] != '') {
			$this->data['product_id']  = $product_id;
			$this->data['record_info'] = $record_info;
			$this->getChild('common/seoblog');
			if ($mark == 'product_id')
				$route = 'product/product';
			if ($mark == 'record_id')
				$route = 'record/record';


                 $this->language->load('agoo/signer/signer');
                 if (isset($settings['langfile']) && $settings['langfile']!='') {
                   $this->language->load($settings['langfile']);
                 }


			$this->data['record_info']['link'] = $this->url->link($route, '&' . $mark . '=' . $this->data['product_id']);
            if (!class_exists('Customer')) {
				require_once(DIR_SYSTEM . 'library/customer.php');
			}
			$this->registry->set('customer', new Customer($this->registry));
			$this->data['login']       = $this->customer->getFirstName() . " " . $this->customer->getLastName();
			$this->data['customer_id'] = $this->customer->getId();

			if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {

				if (defined('HTTPS_SERVER')) {
                  $server = HTTPS_SERVER;
				}
				if (defined('HTTPS_CATALOG')) {
                  $server = HTTPS_CATALOG;
				}

				// $server = $this->config->get('config_ssl');
			} else {

				if (defined('HTTP_SERVER')) {
                  $server = HTTP_SERVER;
				}
				if (defined('HTTP_CATALOG')) {
                  $server = HTTP_CATALOG;
				}


				//$server = $this->config->get('config_url');
			}
			if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
				$this->data['logo'] = $server . 'image/' . $this->config->get('config_logo');
			} else {
				$this->data['logo'] = false;
			}

			$this->load->model('agoo/signer/signer');
			$record_signers = $this->model_agoo_signer_signer->getStatusId($this->data['product_id'], $mark);

			if ($settings['comments_email'] != '') {

				$comments_email = explode(";", $settings['comments_email']);
   		        foreach ($comments_email as $num =>$email) {
   		        	$email = trim($email);
					array_push($record_signers, Array(
						'id' => $this->data['product_id'],
						'pointer' => $mark,
						'customer_id' => $email,
						'admin'	=> true
					));
				}
			}

			if ($record_signers) {
				foreach ($record_signers as $par => $singers) {
					$template = '/template/agoodonut/module/blog_signer_mail.tpl';
					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . $template)) {
						$this->template = $this->config->get('config_template') . $template;
					} else {
						$this->template = 'default' . $template;
					}
					$this->data['theme'] = $this->config->get('config_template');
					require_once(DIR_SYSTEM . 'library/bbcode.class.php');
					if (isset($this->request->post['text'])) {
					$this->request->post['text'] = strip_tags($this->request->post['text']);
					} else {
					$this->request->post['text'] ='';
					}
					$text                        = BBCode::parse($this->request->post['text']);
					if ($this->rdate($this->language->get('text_date')) == $this->rdate($this->language->get('text_date'), strtotime(date('Y-m-d H:i:s')))) {
						$date_str = $this->language->get('text_today');
					} else {
						$date_str = $this->language->get('text_date');
					}
					$date_added = $this->rdate($date_str . $this->language->get('text_hours'), strtotime(date('Y-m-d H:i:s')));


					if (isset($singers['admin']) && $singers['admin']) {
						$customer['email']     = $singers['customer_id'];
						$customer['firstname'] = 'admin';
						$customer['lastname']  = '';
					} else {
						$customer = $this->model_agoo_signer_signer->getCustomer($singers['customer_id']);
					}


                    $fields =Array();
					if (isset($this->request->post['af'])) {

					/*
					print_r("<PRE>");
					print_r($this->request->post);
					print_r("</PRE>");

					print_r("<PRE>");
					print_r($record_settings['addfields']);
					print_r("</PRE>");
                      */
						$f_name ='';
							foreach ($this->request->post['af'] as $num => $value) {
								if ($value != '') {
									foreach ($record_settings['addfields'] as $nm => $vl) {
										if ($vl['field_name'] == $num) {
										  $f_name =	$vl['field_description'][$this->config->get('config_language_id')];
										}
								}
                                    $fields[$this->db->escape(strip_tags($num))]['field_name'] = $f_name;
									$fields[$this->db->escape(strip_tags($num))]['text'] = $this->db->escape(strip_tags($value));
								}
							}
					}



					foreach ($this->request->post as $comment) {
						$this->data['data'] = array(
							'text' => $text,
							'settings' => $record_settings,
							'fields' => $fields,
							'comment' => $this->request->post,
							'record' => $this->data['record_info'],
							'date' => $date_added,
							'shop' => $this->config->get('config_name'),
							'signers' => serialize($singers),
							'signer_customer' => $customer
						);
					}
					$html    = $this->render();
					$message = $html;
					$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));

					if (($singers['customer_id'] != $this->data['customer_id']) && isset($customer['email'])) {
						$mail            = new Mail();
						$mail->protocol  = $this->config->get('config_mail_protocol');
						$mail->parameter = $this->config->get('config_mail_parameter');
						$mail->hostname  = $this->config->get('config_smtp_host');
						$mail->username  = $this->config->get('config_smtp_username');
						$mail->password  = $this->config->get('config_smtp_password');
						$mail->port      = $this->config->get('config_smtp_port');
						$mail->timeout   = $this->config->get('config_smtp_timeout');
						$mail->setTo($customer['email']);
						$mail->setFrom($this->config->get('config_email'));
						$mail->setSender($this->config->get('config_name'));
						$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
						$mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
						$mail->send();
					}
				}
			}
		}
	}


	public function comments_vote()
	{
		if (isset($this->request->get['mark']) && $this->request->get['mark'] != '') {
			$this->data['mark'] = $this->request->get['mark'];
		} else {
			$this->data['mark'] = false;
		}

		$html = "<script>var cvdata = new Array()
							 cvdata['code']    = 'error'
							 cvdata['message'] = 'Error'</script>";

		$json             = array();
		$json['messages'] = 'ok';


		$this->load->model('catalog/treecomments');

		if (isset($this->request->post['comment_id'])) {
			$comment_id = $this->request->post['comment_id'];
		} else {
			$comment_id = 0;
		}

		if (isset($this->request->post['delta'])) {
			$delta = $this->request->post['delta'];
			if ($delta > 1) {
				$delta = 1;
			}
			if ($delta < -1) {
				$delta = -1;
			}
		} else {
			$delta = 0;
		}
		if ($this->customer->isLogged()) {
			$customer_id = $this->customer->getId();
		} else {
			$customer_id = false;
		}
		$json['customer_id'] = $customer_id;




		$this->data['comment_id']  = $comment_id;
		$this->data['customer_id'] = $customer_id;
		$this->data['delta']       = $delta;
		$this->data['mylist']      = $this->config->get('mylist');
		if (isset($this->request->get['mylist_position'])) {
			$mylist_position = $this->request->get['mylist_position'];
		} else {
			$mylist_position = 0;
		}
		$set_thislist = Array(
			'status' => '1',
			'status_reg' => '0',
			'status_now' => '0',
			'rating_num' => ''
		);

		if (isset($this->data['mylist'][$mylist_position]) && !empty($this->data['mylist'][$mylist_position])) {
			$thislist = $this->data['mylist'][$mylist_position];
		} else {
			$thislist = Array(
				'status' => '1',
				'status_reg' => '0',
				'status_now' => '0',
				'rating_num' => ''
			);
		}



		 $thislist =  $thislist + $set_thislist;

               	 $this->language->load('record/record');
                 $this->language->load('agoo/signer/signer');
                 if (isset($thislist['langfile']) && $thislist['langfile']!='') {
                   $this->language->load($thislist['langfile']);
                 }


		$this->load->model('catalog/treecomments');


		$mark_info = $this->model_catalog_treecomments->getMarkbyComment($this->data, $this->data['mark']);



		if (isset($mark_info[$this->data['mark']]) && $mark_info[$this->data['mark']] != '') {
			$this->data[$this->data['mark']] = $mark_info[$this->data['mark']];
		} else {
			$this->data[$this->data['mark']] = '';
		}

	 	$check_rate_num['rating_num'] =0;
		$rating_num      = 0;

		$check_rate      = $this->model_catalog_treecomments->checkRate($this->data, $this->data['mark']);

		$check_rate_self = $this->model_catalog_treecomments->getCommentSelf($this->data, $this->data['mark']);

		$check_rate_num  = $this->model_catalog_treecomments->checkRateNum($this->data, $this->data['mark']);





		$record_settings = $thislist;


		if (isset($thislist['karma_reg']) && $thislist['karma_reg']==0) {
	 	   // set true for $customer_id
	 	   $customer_id = -1;

           if (isset($_COOKIE["karma_".$this->data['mark']])) {
           	 $karma_cookie= unserialize(base64_decode($_COOKIE["karma_".$this->data['mark']]));
           } else {
           	 $karma_cookie = Array();
           }

			 $check_rate_num['rating_num'] =0;
			 $num = 0;

			 foreach ($karma_cookie as $id => $mark_id) {
            	if ($mark_id == $this->data[$this->data['mark']]) {
            	 $num++;
            	 $check_rate_num['rating_num'] = $num;
            	}


			 }

			  $check_rate_self = Array();
			  $check_rate = Array();

          if (!isset($karma_cookie[$comment_id])) {
              $karma_cookie[$comment_id] = $this->data[$this->data['mark']];
			  setcookie("karma_".$this->data['mark'], base64_encode(serialize($karma_cookie)), time() + (60 * 60 * 24 * 555), '/', $this->request->server['HTTP_HOST']);
		  } else {
		  	  $check_rate = Array($comment_id);
		  }

		}

		if (isset($record_settings['rating_num']) && $record_settings['rating_num'] != '') {
			$rating_num = $record_settings['rating_num'];
		} else {
			$rating_num = 10000;
		}

		if (isset($check_rate_num['rating_num']) && $check_rate_num['rating_num'] != '') {
			$voted_num = $check_rate_num['rating_num'];
		} else {
			$voted_num = $rating_num - 1;
		}



		if ((count($check_rate) < 1) && (count($check_rate_self) < 1) && $customer_id && ($voted_num < $rating_num)) {

			$this->model_catalog_treecomments->addRate($this->data, $this->data['mark']);
			$rate_info = $this->model_catalog_treecomments->getRatesByCommentId($comment_id, $this->data['mark']);



			$json['success'] = $rate_info[0];

			$blog_plus = "";
			if ($json['success']['rate_delta'] > 0)
				$blog_plus = "+";
			$json['success']['rate_delta'] = sprintf($blog_plus . "%d", $json['success']['rate_delta']);




		} else {
			if (count($check_rate_self) > 0) {
				$json['messages'] = '';
				$json['success']  = $this->language->get('text_vote_self');
			} else {
				if ($customer_id) {
					$json['messages'] = '';
					$json['success']  = $this->language->get('text_voted');
				} else {
					$json['messages'] = '';
					$json['success']  = $this->language->get('text_vote_reg');
				}
			}





		}
		return $this->response->setOutput(json_encode($json));
	}
    public function getThemeFile($file) {
     		$themefile = false;
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/'.$file)) {
				$themefile = $this->config->get('config_template') . '/'.$file;
			} //file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')
			else {
				if (file_exists(DIR_TEMPLATE . 'default/'.$file)) {
					$themefile = 'default/'.$file;
				}
			}
      return $themefile;
    }


}
if (!class_exists('cmp_my_comment')) {
	class cmp_my_comment
	{
		var $key;
		var $ord;
		function __construct($key, $ord)
		{
			$this->key = $key;
			$this->ord = $ord;
		}
		function my_cmp($a, $b)
		{
			$key = $this->key;
			$ord = $this->ord;
			if ($key == 'date_available') {
				if (strtotime($a[$key]) > strtotime($b[$key])) {
					if ($ord == 'asc')
						return 1;
					if ($ord == 'desc')
						return -1;
				}
				if (strtotime($b[$key]) > strtotime($a[$key])) {
					if ($ord == 'asc')
						return -1;
					if ($ord == 'desc')
						return 1;
				}
			}
			if ($a[$key] > $b[$key]) {
				if ($ord == 'asc')
					return 1;
				if ($ord == 'desc')
					return -1;
			}
			if ($b[$key] > $a[$key]) {
				if ($ord == 'asc')
					return -1;
				if ($ord == 'desc')
					return 1;
			}
			return 0;
		}
	}
}

?>