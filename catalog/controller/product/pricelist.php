<?php 
class ControllerProductPricelist extends Controller { 	
	public function index() {
        if(!$this->config->get('pricelist_status') || ($this->config->get('pricelist_customer_group') != 0 && $this->customer->getCustomerGroupId() != $this->config->get('pricelist_customer_group'))) {
            $this->session->data['redirect'] = HTTPS_SERVER . 'index.php?route=common/home';
	        $this->redirect(HTTPS_SERVER . 'index.php?route=common/home');
        }
     
    	$this->language->load('product/pricelist');
	    $this->load->model('catalog/pricelist');
    	$this->document->setTitle($this->language->get('heading_title'));

		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
       		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_pricelist'),
			'href'      => $this->url->link('product/pricelist'),
       		'separator' => $this->language->get('text_separator')
   		);

	    $url = '';
		$urls = array(
            'sort' => '',
            'order' => '',
            'page' => '',
            'limit' => '',
            'catid' => '',
        );
		
		if (isset($this->request->get['sort'])) {
			$urls['sort'] = '&sort=' . $this->request->get['sort'];
			$url .= $urls['sort'];
		}	

		if (isset($this->request->get['order'])) {
            $urls['order'] = '&order=' . $this->request->get['order'];
            $url .= $urls['order'];
		}
				
		if (isset($this->request->get['page'])) {
		    $urls['page'] = '&page=' . $this->request->get['page'];
			$url .= $urls['page'];
		}

		if (isset($this->request->get['limit'])) {
			$urls['limit'] = '&limit=' . $this->request->get['limit'];
			$url .= $urls['limit'];
		}

		if (isset($this->request->get['catid'])) {
		    $urls['catid'] = '&catid=' . $this->request->get['catid'];
			$url .= $urls['catid'];
		}
			
   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->link('product/pricelist'.$url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => $this->language->get('text_separator')
   		);
		
    	$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_catagory'] = $this->language->get('text_catagory');
		$this->data['text_sort'] = $this->language->get('text_sort');
		$this->data['text_limit'] = $this->language->get('text_limit');
		$this->data['text_qty'] = $this->language->get('text_qty');
		$this->data['text_addcart'] = $this->language->get('text_addcart');
		$this->data['text_print'] = $this->language->get('text_print');
		$this->data['text_notfound'] = $this->language->get('text_notfound');
			 
  		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pd.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = '100';
		}
		
		if (isset($this->request->get['catid'])) {
			$catid = $this->request->get['catid'];
		} else {
			$catid = 0;
		}


		$this->load->model('catalog/product');

      	$this->data['action'] = $this->url->link('checkout/cart/update');

        if($catid > 0) {
            $product_total = $this->model_catalog_product->getTotalProducts(array('filter_category_id' => $catid));
        } else {
            $product_total = $this->model_catalog_pricelist->getTotalProducts();
        }

            $this->load->model('catalog/review');
			$this->load->model('tool/image');
				
       		$this->data['products'] = array();
			if($catid > 0) {
                $data = array(
					'filter_category_id' => $catid,
					'sort' => $sort,
					'order' => $order,
					'start' => ($page - 1) * $limit,
					'limit' => $limit
				);
				$results = $this->model_catalog_product->getProducts($data);
            } else {
                $results = $this->model_catalog_pricelist->getPricelistProducts($sort, $order, ($page - 1) * $limit, $limit);
            }

			foreach ($results as $result) {
				if ($result['image']) {
					$image = $result['image'];
				} else {
					$image = 'no_image.jpg';
				}						
					
				$rating = $this->model_catalog_review->getAverageRating($result['product_id']);	
				
				$special = $this->model_catalog_product->getProductSpecials($result['product_id']);
			
				if ($special) {
					$special = $this->currency->format($this->tax->calculate($special, $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = FALSE;
				}
						
				$this->data['products'][] = array(
           			'id'    => $result['product_id'],
           			'name'    => $result['name'],
					'model'   => $result['model'],
					'sku'   => $result['sku'],
					'rating'  => $rating,
					'stars'   => sprintf($this->language->get('text_stars'), $rating),
					'thumb'   => $this->model_tool_image->resize($image, $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height')),
           			'price'   => $this->currency->format($this->tax->calculate($result['price'] * $result['minimum'], $result['tax_class_id'], $this->config->get('config_tax'))),
					'special' => $special,
					'href'    => $this->url->link('product/product', 'product_id=' . $result['product_id'], 'SSL')
       			);
        	}
        	
        	$url = $urls['limit'] . $urls['page'] . $urls['catid'];
				
			$this->data['sorts'] = array();
				
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name',
				'href'  => $this->url->link('product/pricelist' . $url . '&sort=pd.name')
			);

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->url->link('product/pricelist' . $url . '&sort=pd.name&order=DESC')
			);  

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'p.price-ASC',
				'href'  => $this->url->link('product/pricelist' . $url . '&sort=p.price&order=ASC')
			); 

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'p.price-DESC',
				'href'  => $this->url->link('product/pricelist' . $url . '&sort=p.price&order=DESC')
			); 
				
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_rating_desc'),
				'value' => 'rating-DESC',
				'href'  => $this->url->link('product/pricelist' . $url . '&sort=rating&order=DESC')
			); 
				
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_rating_asc'),
				'value' => 'rating-ASC',
				'href'  => $this->url->link('product/pricelist' . $url . '&sort=rating&order=ASC')
			);
			
			$url = $urls['sort'] . $urls['order'] . $urls['page'] . $urls['catid'];
			
			$this->data['limits'] = array();
				
			$this->data['limits'][] = array(
				'text'  => '10',
				'value' => '10',
				'href'  => $this->url->link('product/pricelist' . $url . '&limit=10')
			);
			
			$this->data['limits'][] = array(
				'text'  => '25',
				'value' => '25',
				'href'  => $this->url->link('product/pricelist' . $url . '&limit=25')
			);
			
			$this->data['limits'][] = array(
				'text'  => '50',
				'value' => '50',
				'href'  => $this->url->link('product/pricelist' . $url . '&limit=50')
			);
			
			$this->data['limits'][] = array(
				'text'  => '100',
				'value' => '100',
				'href'  => $this->url->link('product/pricelist' . $url . '&limit=100')
			);
			
			$url = $urls['sort'] . $urls['order'] . $urls['page'] . $urls['limit'];
            $categories = $this->model_catalog_pricelist->getCategories(0);
            $this->data['categories'][] = array(
                'href' => $this->url->link('product/pricelist' . $url),
                'value'=> 0,
                'text' => 'All',
            );
            foreach ($categories as $category) {
                $this->data['categories'][] = array(
                    'href' => $this->url->link('product/pricelist' . $url . '&catid=' . $category['category_id']),
                    'value'=> $category['category_id'],
                    'text' => $category['name'],
                );
                $this->data['categories'] = array_merge($this->data['categories'], $this->model_catalog_pricelist->getCategories($category['category_id']));
            }

				
			$url = '';

			if (isset($this->request->get['catid'])) {
				$url .= '&catid=' . $this->request->get['catid'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
			
			if (isset($this->request->get['page']) && $product_total < $limit) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = ($product_total < (isset($this->request->get['limit'])) ? '1' : $page);
			$pagination->limit = (isset($this->request->get['limit'])) ? $this->request->get['limit'] : 100; 
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('product/pricelist' . $url . '&page={page}');
				
			$this->data['pagination'] = $pagination->render();
				
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			$this->data['limit'] = $limit;
			$this->data['catid'] = $catid;
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->data['print'] = $this->url->link('product/pricelist/printlist' . $url);

        $this->id       = 'content';

		//Q: pre-1.3.3 Backwards compatibility check
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/pricelist.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/pricelist.tpl';
		} elseif (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . 'product/pricelist.tpl')) { //v1.3.2
			$this->template = $this->config->get('config_template') . 'product/pricelist.tpl';
		} else {
			$this->template = 'default/template/product/pricelist.tpl';
		}

		if ($this->config->get('config_guest_checkout') != null) {
            $this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);
			$this->response->setOutput($this->render());
		} else {
			$this->layout   = 'common/layout';
			$this->render();
		}
  	}
  	
  	public function printlist()
  	{
        if (!$this->config->get('pricelist_status') || ($this->config->get('pricelist_customer_group') != 0 && $this->customer->getCustomerGroupId() != $this->config->get('pricelist_customer_group'))) {
            $this->session->data['redirect'] = $this->url->link('common/home');
	  		$this->redirect(HTTPS_SERVER . 'index.php?route=common/home');
        }


		$this->language->load('common/header');
		
        $this->data['charset'] = $this->language->get('charset');
		$this->data['lang'] = $this->language->get('code');
		$this->data['direction'] = $this->language->get('direction');
		$this->data['template'] = $this->config->get('config_template');
		$this->data['store'] = $this->config->get('config_name');
		$this->data['url'] = HTTPS_SERVER;
		$this->data['address'] = $this->config->get('config_address');
		$this->data['email'] = $this->config->get('config_email');
		$this->data['telephone'] = $this->config->get('config_telephone');
		
        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = HTTPS_IMAGE;
		} else {
			$server = HTTP_IMAGE;
		}
        if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
			$this->data['logo'] = $server . $this->config->get('config_logo');
		} else {
			$this->data['logo'] = '';
		}
		if ($this->config->get('config_icon') && file_exists(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->data['icon'] = $server . $this->config->get('config_icon');
		} else {
			$this->data['icon'] = '';
		}

    	$this->language->load('product/pricelist');
		$this->load->model('catalog/pricelist');
		
		$this->data['text_notfound'] = $this->language->get('text_notfound');

        $this->document->setTitle($this->language->get('heading_title'));

		$this->data['title'] = $this->document->getTitle();
		$this->data['links'] = $this->document->getLinks();
		$this->data['styles'] = $this->document->getStyles();

		foreach(get_object_vars($this->document) as $key => $value) {
			$this->data[$key] = $value;
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pd.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = '100';
		}

		if (isset($this->request->get['catid'])) {
			$catid = $this->request->get['catid'];
		} else {
			$catid = 0;
		}
		
		$this->load->model('catalog/product');
		
		if ($catid > 0) {
            $product_total = $this->model_catalog_product->getTotalProducts(array('filter_category_id' => $catid));
        } else {
            $product_total = $this->model_catalog_pricelist->getTotalProducts();
        }

		if ($product_total) {
            $this->load->model('catalog/review');

			$this->load->model('tool/image');

       		$this->data['products'] = array();
			if($catid > 0) {
                $data = array(
					'filter_category_id' => $catid,
					'sort' => $sort,
					'order' => $order,
					'start' => ($page - 1) * $limit,
					'limit' => $limit
				);
				$results = $this->model_catalog_product->getProducts($data);
            } else {
                $results = $this->model_catalog_pricelist->getPricelistProducts($sort, $order, ($page - 1) * $limit, $limit);
            }

			foreach ($results as $result) {
				if ($result['image']) {
					$image = $result['image'];
				} else {
					$image = 'no_image.jpg';
				}

				$rating = $this->model_catalog_review->getAverageRating($result['product_id']);

				$special = $this->model_catalog_product->getProductSpecials($result['product_id']);

				if ($special) {
					$special = $this->currency->format($this->tax->calculate($special, $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = FALSE;
				}

				$this->data['products'][] = array(
           			'id'      => $result['product_id'],
           			'name'    => $result['name'],
					'model'   => $result['model'],
					'description'  => html_entity_decode(substr($result['description'], 0, 180), ENT_QUOTES, 'UTF-8') . '...',
					'sku'     => $result['sku'],
					'rating'  => $rating,
					'stars'   => sprintf($this->language->get('text_stars'), $rating),
					'thumb'   => $this->model_tool_image->resize($image, $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height')),
           			'price'   => $this->currency->format($this->tax->calculate($result['price'] * $result['minimum'], $result['tax_class_id'], $this->config->get('config_tax'))),
					'special' => $special,
					'href'    => $this->url->link('product/product', 'product_id=' . $result['product_id'])
       			);
        	}
        }

        $this->id = 'content';
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/pricelist_print.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/pricelist_print.tpl';
		} elseif (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . 'product/pricelist_print.tpl')) { //v1.3.2
			$this->template = $this->config->get('config_template') . 'product/pricelist_print.tpl';
		} else {
			$this->template = 'default/template/product/pricelist_print.tpl';
		}

		if ($this->config->get('config_guest_checkout') != null) {
            $this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);
			$this->response->setOutput($this->render());
		} else {
			$this->layout   = 'common/layout';
			$this->render();
		}

    }
}
?>