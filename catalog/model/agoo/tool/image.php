<?php
class agooModelToolImage extends Controller
{
	protected  $Layout;

   public function __call($name, array $params)
   {
        $object = 'ModelToolImage';
		$this->Layout =  new $object($this->registry);

        if ($this->config->get('blog_resize') && $name == 'resize' && ($this->registry->get("fseoblog") == 1 ||  $this->registry->get("blog_work"))) {
        if (isset($params[3])) {
        		$data = $this->resizeme($params[0], $params[1], $params[2], $params[3]);
        	} else {
        	    $data = $this->resizeme($params[0], $params[1], $params[2]);
        	}


        } else {
        	$data = call_user_func_array(array($this->Layout , $name), $params);

        }
        return $data;

   }


	public function resizeme($filename, $width, $height, $type = "")
	{
		if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
			return;
		} //!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)
		$info      = pathinfo($filename);
		$extension = $info['extension'];
		$old_image = $filename;
		$new_image = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . $width . 'x' . $height . $type . '.' . $extension;
		if (!file_exists(DIR_IMAGE . $new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_IMAGE . $new_image))) {
			$path        = '';
			$directories = explode('/', dirname(str_replace('../', '', $new_image)));
			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;
				if (!file_exists(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				} //!file_exists(DIR_IMAGE . $path)
			} //$directories as $directory
			list($width_orig, $height_orig) = getimagesize(DIR_IMAGE . $old_image);
			if ($width_orig != $width || $height_orig != $height) {
				if ($type == 1 || $type == '')
					$type = 'i';
				$image = new agooImage(DIR_IMAGE . $old_image);
				$image->resize($width, $height, $type);
				$image->save(DIR_IMAGE . $new_image);
			} //$width_orig != $width || $height_orig != $height
			else {
				copy(DIR_IMAGE . $old_image, DIR_IMAGE . $new_image);
			}
		} //!file_exists(DIR_IMAGE . $new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_IMAGE . $new_image))


		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			return $this->config->get('config_ssl') . 'image/' . $new_image;
		} //isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))
		else {
			return $this->config->get('config_url') . 'image/' . $new_image;
		}
	}


}
?>