<?php
class ControllerRecordGoogleSitemapBlog extends Controller
{
	public function index()
	{
		if ($this->config->get('google_sitemap_status')) {

			$cache = new sitemapCache();

			$output = '<?xml version="1.0" encoding="UTF-8"?>';
			$output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
			$this->load->model('catalog/product');
			$output .= '<url>';
			$output .= '<loc>' . $this->config->get('config_url') . '</loc>';
			$output .= '<changefreq>always</changefreq>';
			$output .= '<priority>1.0</priority>';
			$output .= '</url>';
			$cache_file = 'blog.sitemap.products.' . (int) $this->config->get('config_store_id') . '.' . (int) $this->config->get('config_language_id');
			$product_cache = $cache->get($cache_file);
			if (!isset($product_cache)) {
				$product_output = '';
				$products       = $this->model_catalog_product->getProducts();
				foreach ($products as $product) {
					$product_output .= '<url>';
					$product_output .= '<loc>' . str_replace('&', '&amp;', str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $product['product_id']))) . '</loc>';
					$product_output .= '<lastmod>' . substr(max($product['date_added'], $product['date_modified']), 0, 10) . '</lastmod>';
					$product_output .= '<changefreq>weekly</changefreq>';
					$product_output .= '<priority>1.0</priority>';
					$product_output .= '</url>';
				} //$products as $product
				$cache->set($cache_file, $product_output);
				$output .= $product_output;
			} //!isset($product_cache)
			else {
				$output .= $product_cache;
			}
			$this->load->model('catalog/category');
			$cache_file = 'blog.sitemap.categories.' . (int) $this->config->get('config_store_id') . '.' . (int) $this->config->get('config_language_id');

			$categories_cache = $cache->get($cache_file);
			if (!isset($categories_cache)) {
				$categories_output = $this->getCategories(0);
				$cache->set($cache_file, $categories_output);
				$output .= $categories_output;
			} //!isset($categories_cache)
			else {
				$output .= $categories_cache;
			}
			$this->load->model('catalog/manufacturer');
			$cache_file = 'blog.sitemap.manufacturer.' . (int) $this->config->get('config_store_id') . '.' . (int) $this->config->get('config_language_id');

			$manufacturer_cache = $cache->get($cache_file);
			if (!isset($manufacturer_cache)) {
				$manufacturers_output = '';
				$manufacturers        = $this->model_catalog_manufacturer->getManufacturers();
				foreach ($manufacturers as $manufacturer) {
					$manufacturers_output .= '<url>';
					$manufacturers_output .= '<loc>' . str_replace('&', '&amp;', str_replace('&amp;', '&', $this->url->link('product/manufacturer/product', 'manufacturer_id=' . $manufacturer['manufacturer_id']))) . '</loc>';
					$manufacturers_output .= '<changefreq>weekly</changefreq>';
					$manufacturers_output .= '<priority>0.7</priority>';
					$manufacturers_output .= '</url>';
				} //$manufacturers as $manufacturer
				$cache->set($cache_file, $manufacturers_output);
				$output .= $manufacturers_output;
			} //!isset($manufacturer_cache)
			else {
				$output .= $manufacturer_cache;
			}
			$this->load->model('catalog/information');
			$cache_file = 'blog.sitemap.information.' . (int) $this->config->get('config_store_id') . '.' . (int) $this->config->get('config_language_id');

			$information_cache = $cache->get($cache_file);
			if (!isset($information_cache)) {
				$information_output = '';
				$informations       = $this->model_catalog_information->getInformations();
				foreach ($informations as $information) {
					$information_output .= '<url>';
					$information_output .= '<loc>' . str_replace('&', '&amp;', str_replace('&amp;', '&', $this->url->link('information/information', 'information_id=' . $information['information_id']))) . '</loc>';
					$information_output .= '<changefreq>weekly</changefreq>';
					$information_output .= '<priority>0.5</priority>';
					$information_output .= '</url>';
				} //$informations as $information
				$cache->set($cache_file, $information_output);
				$output .= $information_output;
			} //!isset($information_cache)
			else {
				$output .= $information_cache;
			}
			$this->load->model('catalog/record');
			$cache_file = 'blog.sitemap.records.' . (int) $this->config->get('config_store_id') . '.' . (int) $this->config->get('config_language_id');
			$records_cache = $cache->get($cache_file);
			if (!isset($records_cache)) {
				$records_output = '';
				$this->getChild('common/seoblog');
				$records = $this->model_catalog_record->getRecords();
				if ($records) {
					foreach ($records as $record) {
						$records_output .= '<url>';
						$records_output .= '<loc>' . str_replace('&', '&amp;', str_replace('&amp;', '&', $this->url->link('record/record', 'record_id=' . $record['record_id']))) . '</loc>';
						$records_output .= '<lastmod>' . substr(max($record['date_available'], $record['date_modified']), 0, 10) . '</lastmod>';
						$records_output .= '<changefreq>weekly</changefreq>';
						$records_output .= '<priority>1.0</priority>';
						$records_output .= '</url>';
					} //$records as $record
				} //$records
				$cache->set($cache_file, $records_output);
				$output .= $records_output;
			} //!isset($records_cache)
			else {
				$output .= $records_cache;
			}
			$this->load->model('catalog/blog');
			$cache_file = 'blog.sitemap.blogies.' . (int) $this->config->get('config_store_id') . '.' . (int) $this->config->get('config_language_id');

			$blogies_cache = $cache->get($cache_file);
			if (!isset($blogies_cache)) {
				$blogies_output = $this->getBlogies(0);
				$cache->set($cache_file, $blogies_output);
				$output .= $blogies_output;
			} //!isset($blogies_cache)
			else {
				$output .= $blogies_cache;
			}
			$output .= '</urlset>';
			$this->response->addHeader('Content-Type: application/xml');
			$this->response->setOutput($output);
		} //$this->config->get('google_sitemap_status')
	}
	protected function getCategories($parent_id, $current_path = '')
	{
		$output  = '';
		$results = $this->model_catalog_category->getCategories($parent_id);
		if ($results) {
			foreach ($results as $result) {
				if (!$current_path) {
					$new_path = $result['category_id'];
				} //!$current_path
				else {
					$new_path = $current_path . '_' . $result['category_id'];
				}
				$output .= '<url>';
				$output .= '<loc>' . str_replace('&', '&amp;', str_replace('&amp;', '&', $this->url->link('product/category', 'path=' . $new_path))) . '</loc>';
				$output .= '<lastmod>' . substr(max($result['date_added'], $result['date_modified']), 0, 10) . '</lastmod>';
				$output .= '<changefreq>weekly</changefreq>';
				$output .= '<priority>0.7</priority>';
				$output .= '</url>';
				$output .= $this->getCategories($result['category_id'], $new_path);
			} //$results as $result
		} //$results
		return $output;
	}
	protected function getBlogies($parent_id, $current_path = '')
	{
		$output  = '';
		$results = $this->model_catalog_blog->getBlogies($parent_id);
		foreach ($results as $result) {
			if (!$current_path) {
				$new_path = $result['blog_id'];
			} //!$current_path
			else {
				$new_path = $current_path . '_' . $result['blog_id'];
			}
			$output .= '<url>';
			$output .= '<loc>' . str_replace('&', '&amp;', str_replace('&amp;', '&', $this->url->link('record/blog', 'blog_id=' . $new_path))) . '</loc>';
			$output .= '<lastmod>' . substr(max($result['date_added'], $result['date_modified']), 0, 10) . '</lastmod>';
			$output .= '<changefreq>weekly</changefreq>';
			$output .= '<priority>0.7</priority>';
			$output .= '</url>';
			$output .= $this->getBlogies($result['blog_id'], $new_path);
		} //$results as $result
		return $output;
	}
}

class sitemapCache {
	private $expire = 360000;

  	public function __construct() {
		$files = glob(DIR_CACHE . 'cache.*');

		if ($files) {
			foreach ($files as $file) {
				$time = substr(strrchr($file, '.'), 1);

      			if ($time < time()) {
					if (file_exists($file)) {
						unlink($file);
					}
      			}
    		}
		}
  	}

	public function get($key) {
		$files = glob(DIR_CACHE . 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');

		if ($files) {
			$cache = file_get_contents($files[0]);

			return unserialize($cache);
		}
	}

  	public function set($key, $value) {
    	$this->delete($key);

		$file = DIR_CACHE . 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.' . (time() + $this->expire);

		$handle = fopen($file, 'w');

    	fwrite($handle, serialize($value));

    	fclose($handle);
  	}

  	public function delete($key) {
		$files = glob(DIR_CACHE . 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');

		if ($files) {
    		foreach ($files as $file) {
      			if (file_exists($file)) {
					unlink($file);
				}
    		}
		}
  	}
}

?>