<?php
class ControllerModuleFilter extends Controller {
	protected function index($setting) {
		$this->language->load('module/filter');
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_apply'] = $this->language->get('text_apply');
		$this->data['text_all'] = $this->language->get('text_all');
		$this->data['entry_text_select'] = $this->language->get('entry_text_select');
		$this->data['text_reset_filter'] = $this->language->get('text_reset_filter');
		$this->data['count_enabled'] = $this->config->get('count_enabled');
		$this->data['currency_symbol_left'] = $this->currency->getSymbolLeft($this->currency->getCode());
		$this->data['currency_symbol_right'] = $this->currency->getSymbolRight($this->currency->getCode());
		$this->data['count_symbols'] = $this->currency->getDecimalPlace($this->currency->getCode());
		
		$this->load->model('catalog/filter');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		
		// Получить id группы фильтра
		$filter_group_id = $setting['filter_group_id'];
		
		// Получить id категории
		if (isset($this->request->get['path'])) {
			$parts = explode('_', $this->request->get['path']);
			$categorie_id = end($parts);
		} 
		else {
			$categorie_id = 0;
		}
		
		// Получить все фильтры данной группы для данной категории
		$filter_group_options = $this->model_catalog_filter->getOptionByFilterGroupsId($filter_group_id, $categorie_id);
		
		$this->data['filter_error'] = '';
		
		if (!empty($filter_group_options)) {
		
			// Выбрать уникальные типы фильтров
			// Для уменьшения количества циклов, в цикл добвалена реструктуризация ключей массива фильтров по индекску
			$filter_types = array();
			$filter_group_options_by_index = array();
			foreach ($filter_group_options as $filter_group_option) {
				$filter_types_parts = explode('_', $filter_group_option['type_index']);
				$filter_types[reset($filter_types_parts)][$filter_group_option['type_index']] = end($filter_types_parts);
				// Реструктуризация
				$filter_group_options_by_index[$filter_group_option['type_index']] = $filter_group_option;
			}
			
			$categories_id[] = $categorie_id; 
			$categories_id = array_merge_recursive($categories_id, $this->model_catalog_filter->getChildrenCategorie($categorie_id));
			
			
			// Получить соотвествия для фильтров в данной категории	
			$filterItemNames = $this->getFilterItemNames($filter_types, $categories_id);
			
			 if (!empty($this->request->get['filter'])) {
				$filter = $this->request->get['filter'];
			} else {
				$filter = '';
			}
	   
			$sep_par = ';'; // разделитель пар опций -> значений: opt1:val1,val2,val3;opt2:val1,val2,val3 ...
			$sep_opt = ':'; // разделитель внутри пары опция -> значения: opt1:val1,val2,val3 ...
			$sep_val = ','; // разделитель для параметров опции: val1,val2,val3 ...
			$out = '';
			
			if ($filter) {
			
				$matches = explode($sep_par, $filter);
				$values = array();
				$data = array();
				$options = array();
				
				$checkFliterKey = false;
				
				foreach ($matches as $option) {
					$data = explode($sep_opt, $option);
					if ($data[0] == $option_key) {
						$checkFliterKey = true;
						$values = explode($sep_val, $data[1]);
						if (!in_array($option_value, $values)) { 
							$values[] = $option_value;
						} 
						else {
							unset($values[array_search($option_value, $values)]);
						}
						$data[1] = implode($sep_val, $values);
					}
					if (!empty($data[1])){
						$filter_get[$data[0]] = $data[1];
					}
						
				}

			}
			// End filter
			
			// проверяем на обьязательные поля
			if(isset($this->request->get['filter'])){			
				foreach($filter_group_options_by_index as $v){
					
					$cur_key = mb_substr($v['type_index'], 0, 1);
					
					if($cur_key == 'q'){
						if($v['required'] AND empty($filter_get['min_quantity']) AND empty($filter_get['max_quantity'])){
							$this->data['filter_error'] = $this->language->get('error_required');
							break;
						}
					}else{
						if($v['required'] AND empty($filter_get[$cur_key])){
							$this->data['filter_error'] = $this->language->get('error_required');
							break;
						}
					}			
					
				}
			}
			
			$this->data['filter_get'] = $filter_get;
			
			// Передача данных фильтра в представление
			$this->data['filters'] = array_merge_recursive($filter_group_options_by_index, $filterItemNames);
			
			
			// Сортировка данных фильтра
			uasort($this->data['filters'], array('ControllerModuleFilter', 'sortFilters'));

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/filter.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/filter.tpl';
			} 
			else {
				$this->template = 'default/template/module/filter.tpl';
			}
				
			$this->render();
		}
	}

	// Сортировка фильтров по значению sort_order, указанному в системе
	static private function sortFilters($array_first, $array_second) {

	if ($array_first['sort_order'] == $array_second['sort_order']) {
		return 0; 
	}
	return ($array_first['sort_order'] < $array_second['sort_order']) ? -1 : 1; 

	}
	
	private function getUrl() {
		
		// Получение переменных GET запроса, для формирования ссылки на фильтр
		$url = '';

		if (isset($this->request->get['sort'])) {
		  $url .= '&sort=' . htmlspecialchars($this->request->get['sort']);
		}

		if (isset($this->request->get['order'])) {
		  $url .= '&order=' . htmlspecialchars($this->request->get['order']);
		}
		
		if (isset($this->request->get['limit'])) {
		  $url .= '&limit=' . (int)$this->request->get['limit'];
		}
		
		return $url;
	
	}

	// Получение имен всех фильтров для данной категории
	private function getFilterItemNames($filter_types, $categories_id) {
		
		$filterItemNames = array();
		$categories_id_to_string = implode(",", $categories_id);
		
		if (isset($filter_types['price'])) {
			$price_data = $this->getDataForFilter($filter_types['price'], $categories_id_to_string, 'price');
			
			$price_data['price']['filters'][0]['value'] = floor($this->currency->format($price_data['price']['filters'][0]['value'], '', '', false));
			$price_data['price']['filters'][1]['value'] = ceil($this->currency->format($price_data['price']['filters'][1]['value'], '', '', false));
			
			$filterItemNames += $price_data;
			
		}
		
		if (isset($filter_types['manufacter'])) {
			$filterItemNames += $this->getDataForFilter($filter_types['manufacter'], $categories_id_to_string, 'manufacter');
		}
		if (isset($filter_types['quantity'])) {
			$filterItemNames += $this->getDataForFilter($filter_types['quantity'], $categories_id_to_string, 'quantity');
		}
		
		if (isset($filter_types['name'])) {
			$filterItemNames += $this->getNameDataForFilter($filter_types['name'], $categories_id_to_string, 'name');
		}		
		
		
		if (isset($filter_types['option'])) {
			$filterItemNames += $this->getDataForFilter($filter_types['option'], $categories_id_to_string, 'option');
		}
		
		if (isset($filter_types['attribute'])) {
			$filterItemNames += $this->getDataForFilter($filter_types['attribute'], $categories_id_to_string, 'attribute');
		}
		
		return $filterItemNames; 

	}
	
	private function getNameDataForFilter($filter_types, $categories_id_to_string, $type) {
		
		$filterItemNames = array();
		$url = $this->getUrl();
		
		$item_names = array();
		
		if (!empty($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
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
		
		if (isset($this->request->get['path'])) {
			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}	

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);

			$category_id = (int)array_pop($parts);
			
		} else {
			$category_id = 0;
		}
		
		$data = array(
				'filter_category_id' => $category_id,
				'filter_filter'      => $filter, 
				'sort'               => $sort,
				'no_get_product'     => true,
				'order'              => $order
			);
			
		$item_names = $this->model_catalog_product->getProducts($data, $this->request->get['filter']);
		if(empty($item_names)){
			$item_names = array(1);
		}
		
		if (!empty($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
	   } else {
			$filter = '';
	   }
	   
	   $sep_par = ';'; // разделитель пар опций -> значений: opt1:val1,val2,val3;opt2:val1,val2,val3 ...
			$sep_opt = ':'; // разделитель внутри пары опция -> значения: opt1:val1,val2,val3 ...
			$sep_val = ','; // разделитель для параметров опции: val1,val2,val3 ...
			$out = '';
			
			if ($filter) {
			
				$matches = explode($sep_par, $filter);
				$values = array();
				$data = array();
				$options = array();
				
				$checkFliterKey = false;
				
				foreach ($matches as $option) {
					$data = explode($sep_opt, $option);
					if ($data[0] == $option_key) {
						$checkFliterKey = true;
						$values = explode($sep_val, $data[1]);
						if (!in_array($option_value, $values)) { 
							$values[] = $option_value;
						} 
						else {
							unset($values[array_search($option_value, $values)]);
						}
						$data[1] = implode($sep_val, $values);
					}
					if (!empty($data[1])){
						$filter_get[$data[0]] = $data[1];
					}
						
				}

			}
		
		//$item_names = $this->model_catalog_filter->$method_name($categories_id_to_string);
		
		if($filter_get['n']){
			$cur_search = trim(mb_strtolower($filter_get['n'],'UTF-8'));
		}
		
		foreach ($item_names as $item_name) {			
			
			if ($type == 'option' || $type == 'attribute') {
				$index = $type . '_' . $item_name['id'];
				$option_key = mb_substr($type, 0, 1) . '_' . $item_name['id'];
			} else {
				$index = $type;
				$option_key = mb_substr($type, 0, 1);
			}
			
			//$data = $this->getDataForFilterValue($url, $option_key, $item_name['name']);
			
			
			$item_name_str = trim(trim(mb_strtolower($item_name['name'],'UTF-8')),'+');
			
			if($cur_search == $item_name_str){
				$active = true;
			}else{
				$active = false;
			}
			
			$filterItemNames[$index]['filters'][$item_name['name']] = array(
				'name' => $item_name['name'],
				'value'		 => $item_name['name'],
				'key'		 => $option_key,
				'active'     => $active,
				'count'      => 1,
				'view_count' => '',
				'image'      => $image );
		}
		
		return $filterItemNames;
	
	}
	private function getDataForFilter($filter_types, $categories_id_to_string, $type) {
		
		$filterItemNames = array();
		$url = $this->getUrl();
		$no_image = $this->model_tool_image->resize('no_image.jpg', 20, 20);
		$method_name = 'get' . ucfirst($type) . 'ItemNames';
		
		$item_names = array();
		
		if ($type == 'option' || $type == 'attribute') {
			$filter_options_to_string = implode(",", $filter_types);
			$item_names = $this->model_catalog_filter->$method_name($filter_options_to_string, $categories_id_to_string);
		} else {	
			$item_names = $this->model_catalog_filter->$method_name($categories_id_to_string);
		}
		
		
		foreach ($item_names as $item_name) {
			
			if ($type == 'option' || $type == 'attribute') {
				$index = $type . '_' . $item_name['id'];
				$option_key = mb_substr($type, 0, 1) . '_' . $item_name['id'];
			} else {
				$index = $type;
				$option_key = mb_substr($type, 0, 1);
			}
			
			
			$data = $this->getDataForFilterValue($url, $option_key, $item_name['value']);
			
			if (isset($item_name['image']) && !empty($item_name['image'])) {
				$image = $this->model_tool_image->resize($item_name['image'], 20, 20);
			} else {
				$image = $no_image;
			}
			
				
			$filterItemNames[$index]['filters'][] = array('name' => $item_name['name'],
				'href'       => $data['href'],
				'value'		 => $item_name['value'],
				'key'		 => $option_key,
				'active'     => $data['active'],
				'count'      => $data['count'],
				'view_count' => ($data['count'] != '') ? '(' . $data['count'] . ')' : '',
				'image'      => $image );
		}
		
		return $filterItemNames;
	
	}
	
	private function getDataForFilterValue($url, $option_key, $option_value) {

		$href = '';
		if(isset($this->request->get['path']))
			$path = htmlspecialchars($this->request->get['path']);
		else
			$path = 0;
		$href .= 'path=' . $path;
		
		$get_filter = '';
		if (isset($this->request->get['filter']))
			$get_filter = htmlspecialchars($this->request->get['filter']);
		
		$filter = $this->getFilterURLParams($get_filter, $option_key, $option_value);
		if(!empty($filter))
			$href .= '&filter=' . $filter;
			
		$href .= $url;
		
		$data['href'] = $this->url->link('product/category', $href);
		// Проверка выбран ли фильтр
		$data['active'] = (strlen($get_filter) > strlen($filter)) ? true : false;
		
		$categories_id = explode("_", $path);
		
		$data_for_query['filter_category_id'] = end($categories_id);
		
		// Количество товаров в категории для фильтра
		$data['count'] = '';
		
		if ($this->config->get('count_enabled')) {
		
			if (!$data['active']) {
				$filter_count = preg_replace('/(' . $option_key . ':)([^;]+)/i', '${1}' . $option_value, $filter);				
				if($option_key != 'n'){
					$data['count'] = $this->model_catalog_product->getTotalProducts($data_for_query, $filter_count);
				}
				
			}
			else {
				
				$filter_count = preg_replace('/(' . $option_key . ':)([^;]+)/i', '${1}' . $option_value, $get_filter);
				$data['count'] = $this->model_catalog_product->getTotalProducts($data_for_query, $filter_count);
			}
		
		}
		
		return $data;

	}


	// Получение уже существующих параметров фильтра
	private function getFilterURLParams($filter = 0, $option_key, $option_value) {
		
		$sep_par = ';'; // разделитель пар опций -> значений: opt1:val1,val2,val3;opt2:val1,val2,val3 ...
		$sep_opt = ':'; // разделитель внутри пары опция -> значения: opt1:val1,val2,val3 ...
		$sep_val = ','; // разделитель для параметров опции: val1,val2,val3 ...
		$out = '';
		
		if ($filter) {
		
			$matches = explode($sep_par, $filter);
			$values = array();
			$data = array();
			$options = array();
			
			$checkFliterKey = false;
			
			foreach ($matches as $option) {
				$data = explode($sep_opt, $option);
				if ($data[0] == $option_key) {
					$checkFliterKey = true;
					$values = explode($sep_val, $data[1]);
					if (!in_array($option_value, $values)) { 
						$values[] = $option_value;
					} 
					else {
						unset($values[array_search($option_value, $values)]);
					}
					$data[1] = implode($sep_val, $values);
				}
				if (!empty($data[1]))
					$options[] = implode($sep_opt, $data);
			}
			if (!$checkFliterKey) {
				$options[] = $option_key . $sep_opt . $option_value;
			}
			$out = implode($sep_par, $options);

		}
		else {
			$out .= $option_key . $sep_opt . $option_value; 
		}

		return $out;
	}
}
?>