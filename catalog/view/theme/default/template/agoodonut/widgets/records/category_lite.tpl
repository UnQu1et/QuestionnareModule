<?php
  // prepare categories
  foreach ($records as $num => $record) {   $cat[$record['blog_id']][$num]= $record;
  }
  foreach ($cat as $cat_id => $cat_records) {
	$blog_info = $this->model_catalog_blog->getBlog($cat_id);
    $cat[$cat_id]['sort'] = $blog_info['sort_order'];
    $cat[$cat_id]['blog_info'] = $blog_info;
  }
//sorting categories by order
usort($cat, 'compareblogs');
?>
<style>
        .nikita_columns {
            display: table;
            width: 100%;
            -moz-box-sizing: border-box;      /* фикс проблемы для Firefox       */
            -webkit-box-sizing: border-box;   /* фикс для старых Chrome и Safari */
            box-sizing: border-box;           /* не поддерживается в CSS2        */
            margin-left: 0;
            margin-right: 0;
            text-align: center;
        }

        .nikita_columns > div,
        .nikita_columns > noindex > div {
            display: inline-block;
            vertical-align: top;
            max-width: 170px;
            width: auto;
            vertical-align: top;
            text-align: left;
            margin-right: 10px;
            margin-bottom: 5px;
            position: relative;
            -moz-box-sizing: border-box;      /* фикс проблемы для Firefox       */
            -webkit-box-sizing: border-box;   /* фикс для старых Chrome и Safari */
            box-sizing: border-box;
            background-color: #FFF;
           /* border-right: 1px solid #DDD; */

        }
</style>


<div class="nikita_columns">

<?php

foreach ($cat as $cat_id => $cat_records) {

$blog_info = $cat[$cat_id]['blog_info'];

$this->load->model('tool/image');
if ($blog_info) {
	if ($blog_info['image']) {
		if (isset($settings_widget['avatar']['width']) && isset($settings_widget['avatar']['height']) && $settings_widget['avatar']['width'] != "" && $settings_widget['avatar']['height'] != "") {
			$thumb = $this->model_tool_image->resize($blog_info['image'], $settings_widget['avatar']['width'], $settings_widget['avatar']['height']);
		} else {
			$thumb = $this->model_tool_image->resize($blog_info['image'], 150, 150);
		}
	} else {
		$thumb = '';
	}
} else {
	$thumb = '';
}
$blog_href             = $this->model_catalog_blog->getPathByblog($cat_records['blog_info']['blog_id']);
$blog_link = $this->url->link('record/blog', 'blog_id=' .$blog_href['path']);
?>

<div>

	<div class="box">
  					      <?php
					      if ($thumb) { ?>
					      <div style="margin-bottom: 10px; text-align: center;">
					      <div class="box-heading">
					      <a href="<?php echo $blog_link; ?>"><?php echo $blog_info['name']; ?></a>
					      </div>
					      <a href="<?php echo $blog_link; ?>"><img src="<?php echo $thumb; ?>" title="<?php echo $blog_info['name']; ?>" alt="<?php echo $blog_info['name']; ?>" /></a>
					      </div>
                          <?php } else { ?>
                          <div class="box-heading">
                            <a href="<?php echo $this->url->link('record/blog', 'blog_id=' .$blog_href['path']); ?>"><?php echo $blog_info['name']; ?></a>
                           </div>
                          <?php } ?>

	</div>

	<div style=" text-align: left;">
	<?php

	if ($cat_records) {	// limiter
	$count_records = -1;
	?>


   				<?php foreach ($cat_records as $nm => $record) {   					if ($nm!='sort' && $nm!='blog_info') {
   					 $count_records++;
   					 if (isset ($record['settings']['reserved']) && $record['settings']['reserved']!='' && $count_records <  $record['settings']['reserved']) {
   					?>

					      <?php if ($record['thumb'] || count($record['images']) > 0) { ?>
					      <div class="image blog-image">
					      <?php if ($record['thumb'] && (isset ($record['settings']['avatar_status']) && $record['settings']['avatar_status'])) { ?>
					      <div>
					      <a href="<?php echo $record['href']; ?>"><img src="<?php echo $record['thumb']; ?>" title="<?php echo $record['name']; ?>" alt="<?php echo $record['name']; ?>" /></a>
					      </div>
					       <?php } ?>
					      <?php if (isset ($record['settings_blog']['images_view']) && $record['settings_blog']['images_view'] ) { ?>
					      <?php if (isset ($record['settings']['images_view']) && $record['settings']['images_view'] ) { ?>
					      <?php foreach ($record['images'] as $numi => $images) { ?>
					     <div class="image blog-image">
							<a class="imagebox" rel="imagebox" title="<?php echo $images['title']; ?>" href="<?php echo $images['popup']; ?>">
							<img alt="<?php echo $images['title']; ?>" title="<?php echo $images['title']; ?>" src="<?php echo $images['thumb']; ?>">
							</a>
						</div>

					      <?php } ?>
					      <?php } ?>
					      <?php } ?>
					      </div>
					      <?php } ?>

					<div>
					    <?php
					    //if (isset ($record['settings_blog']['category_status']) && $record['settings_blog']['category_status'] ) {
					    ?>
					    <?php if (isset ($record['settings']['category_status']) && $record['settings']['category_status'] ) { ?>
					    <a href="<?php echo $record['blog_href']; ?>" class="blog-title"><?php echo $record['blog_name']; ?></a><ins class="blog-arrow">&nbsp;&rarr;&nbsp;</ins>
					    <?php } ?>
					    <?php
					    //	}
					    ?>
					    <a href="<?php echo $record['href']; ?>" class="blog-title"><?php echo $record['name']; ?></a>
    				</div>
                    <?php if (isset ($record['settings']['description_status']) && $record['settings']['description_status'] ) { ?>
				   	<div class="blogdescription  margintop5"><?php echo $record['description']; ?>&nbsp;
				         	<a href="<?php echo $record['href']; ?>" class="blog_further"><?php if (isset($settings_general['further'][$this->config->get('config_language_id')])) echo html_entity_decode($settings_general['further'][$this->config->get('config_language_id')]); ?></a>
					</div>
					<?php } ?>

					<div class="name marginbottom5">
					 <div>
				    <?php if (isset ($record['settings_blog']['view_rating']) && $record['settings_blog']['view_rating'] ) { ?>
				    <?php if (isset ($record['settings']['view_rating']) && $record['settings']['view_rating'] ) { ?>
				      <?php if ($record['rating']) { ?>
				      <div class="rating">
					     <?php
					      $themeFile = $this->getThemeFile('image/blogstars-'.$record['rating'].'.png');
					      if ($themeFile) {
					      ?>
					      <img style="border: 0px;"  title="<?php echo $record['rating']; ?>" alt="<?php echo $record['rating']; ?>" src="catalog/view/theme/<?php echo $themeFile; ?>">
					     <?php } ?>
				      </div>
				      <?php } ?>
				    <?php } ?>
				    <?php } ?>

				    <?php if (isset ($record['settings_blog']['view_date']) && $record['settings_blog']['view_date'] ) { ?>
				    <?php if (isset ($record['settings']['view_date']) && $record['settings']['view_date'] ) { ?>
				      <?php if ($record['date_available']) { ?>
				        <div class="blog-date"><?php echo $record['date_available']; ?></div>
				      <?php } ?>
				    <?php } ?>
                    <?php } ?>
     				<div>

				       <?php if (isset ($record['settings_blog']['view_comments']) && $record['settings_blog']['view_comments'] ) { ?>
				       <?php if (isset ($record['settings']['view_comments']) && $record['settings']['view_comments'] ) { ?>
					      <?php if ($record['settings_comment']['status']) { ?>
					      <div class="blog-light-small-text"><?php echo $text_comments; ?> <?php echo $record['comments']; ?></div>
					      <?php } ?>
				       <?php } ?>
                       <?php } ?>

				        <?php if (isset ($record['settings_blog']['view_viewed']) && $record['settings_blog']['view_viewed'] ) { ?>
				        <?php if (isset ($record['settings']['view_viewed']) && $record['settings']['view_viewed'] ) { ?>
					      <div class="blog-light-small-text"><?php echo $text_viewed; ?> <?php echo $record['viewed']; ?></div>
				        <?php } ?>
                        <?php } ?>
				     </div>

      				<div class="overflowhidden lineheight1">&nbsp;</div>
	    		</div>
 	<?php
	 // for admin, edit from front
	 if ($userLogged)    {
	?>
	<div class="blog-edit_container">
	   <a class="zametki" target="_blank" href="<?php echo $admin_path; ?>index.php?route=catalog/record/update&token=<?php echo $this->session->data['token']; ?>&record_id=<?php echo $record['record_id']; ?>"><?php echo $this->language->get('text_edit');?></a>
	</div>

	<?php
	 }
	?>

  				<div class="blog-child_divider">&nbsp;</div>
    			</div>



         <?php
          }
    	 }
    	}
    	?>
</div>

             <div class="box-heading">
              <a href="<?php echo $this->url->link('record/blog', 'blog_id=' .$blog_href['path']); ?>"><?php echo $this->language->get('text_all_begin'); ?><?php echo utf8_strtolower($blog_info['name']); ?><?php echo $this->language->get('text_all_end'); ?></a>
             </div>



<?php
}

?>
</div>

<?php
}
?>
 </div>
<div class="overflowhidden width100">&nbsp;</div>

