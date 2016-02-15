<?php echo $header; ?>
<div class="breadcrumb">
<div class="wrapper-center">
    <?php $i=0; foreach ($breadcrumbs as $breadcrumb) { $i++; ?>
    <?php echo $breadcrumb['separator']; ?><?php if (count($breadcrumbs)!= $i) { ?><a href="<?php echo $breadcrumb['href']; ?>"><?php } ?><?php echo $breadcrumb['text']; ?><?php if (count($breadcrumbs)!=$i) { ?></a><?php } ?>
    <?php } ?>
</div>
</div>
<div id="container-center">

<?php echo $column_left; ?><?php echo $column_right; ?>

<div id="content"><?php echo $content_top; ?>


  <div class="blog-heading_title">
	  <h1><?php echo $heading_title; ?></h1>
  </div>

    <?php if (isset ($settings_blog['view_rss']) && $settings_blog['view_rss'] ) { ?>
    <a href="<?php echo $url_rss; ?>" class="floatright"><img style="border: 0px;"  title="RSS" alt="RSS" src="catalog/view/theme/<?php
			$template = '/image/rss24.png';
			if (file_exists(DIR_TEMPLATE . $theme . $template)) {
				$rsspath = $theme . $template;
			} else {
				$rsspath = 'default' . $template;
			}
			echo $rsspath;
?>"></a>
    <?php } ?>

  <?php if ($description) {
  ?>
  <div class="category-info">
    <?php if ($thumb && $description) { ?>
    <div class="image blog-image"><img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" /></div>
    <?php } ?>
    <?php if ($description) { ?>
    <div class="blog-description">
    <?php echo $description; ?>
    </div>
    <?php } ?>
  </div>
  <?php } ?>

 <div class="blog-divider"></div>

  <?php if ($categories) { ?>
  <div class="blog-child_divider">&nbsp;</div>
  <h2 class="blog-refine_title"><?php echo $text_refine; ?>:</h2>
  <div class="category-list" >
    <?php if (count($categories) <= 2) { ?>
    <ul>
      <?php foreach ($categories as $blog) { ?>
      <li>
      <?php if (isset($blog['thumb']) && $blog['thumb']!='') { ?>
      <img src="<?php echo $blog['thumb']; ?>">
	  <?php  } ?>
      <a href="<?php echo $blog['href']; ?>"><?php echo $blog['name']; ?></a>
      </li>
      <?php } ?>
    </ul>
    <?php } else { ?>
    <?php for ($i = 0; $i < count($categories);) { ?>
    <ul>
      <?php $j = $i + ceil(count($categories) / 3); ?>
      <?php for (; $i < $j; $i++) { ?>
      <?php if (isset($categories[$i])) { ?>
      <li>

      <?php if (isset($categories[$i]['thumb']) && $categories[$i]['thumb']!='') { ?>
      <img src="<?php echo $categories[$i]['thumb']; ?>">
	  <?php  } ?>

      <a href="<?php echo $categories[$i]['href']; ?>"><?php echo $categories[$i]['name']; ?>&nbsp;(<?php echo $categories[$i]['total']; ?>)</a></li>
      <?php } ?>
      <?php } ?>
    </ul>
    <?php } ?>
    <?php } ?>
  </div>



	<div class="blog-child_divider">&nbsp;</div>
  <?php } ?>





  <?php if ($records) { ?>
  <div>
    <?php foreach ($records as $record) {

    ?>
    <div class="content" >
     <div class="name marginbottom5">
    <?php if (isset ($record['settings']['category_status']) && $record['settings']['category_status'] ) { ?>
    <?php if (isset ($record['settings_blog']['category_status']) && $record['settings_blog']['category_status'] ) { ?>
    <a href="<?php echo $record['blog_href']; ?>" class="blog-title blog-record-list"><?php echo $record['blog_name']; ?></a><ins class="blog-arrow">&nbsp;&rarr;&nbsp;</ins>
    <?php } ?>
    <?php } ?>

    <h2><a href="<?php echo $record['href']; ?>" class="blog-title blog-record-list"><?php echo $record['name']; ?></a></h2>
     </div>

      <?php if ($record['thumb']) { ?>
      <div class="image blog-image">
      <div>
      <a href="<?php echo $record['href']; ?>"><img src="<?php echo $record['thumb']; ?>" title="<?php echo $record['name']; ?>" alt="<?php echo $record['name']; ?>" /></a>
      </div>

      <?php if (isset ($record['settings_blog']['images_view']) && $record['settings_blog']['images_view'] ) { ?>
      <?php foreach ($record['images'] as $numi => $images) { ?>
     <div class="image blog-image">
	<a class="imagebox" rel="imagebox" title="<?php echo $images['title']; ?>" href="<?php echo $images['popup']; ?>">
	<img alt="<?php echo $images['title']; ?>" title="<?php echo $images['title']; ?>" src="<?php echo $images['thumb']; ?>">
	</a>
	</div>

      <?php } ?>
      <?php } ?>


      </div>
      <?php } ?>

          	<div class="description record_description"><?php echo $record['description']; ?>&nbsp;<a href="<?php echo $record['href']; ?>" class="blog_further"><?php if (isset($settings_general['further'][$this->config->get('config_language_id')])) echo html_entity_decode($settings_general['further'][$this->config->get('config_language_id')]); ?></a></div>


     <div class="overflowhidden width100 lineheight1 bordernone">&nbsp;</div>
       <div class="blog-child_divider width100 bordernone">&nbsp;</div>
      <div class="blog-date_container">

    <?php if (isset ($record['settings_blog']['view_date']) && $record['settings_blog']['view_date'] ) { ?>
      <?php if ($record['date_available']) { ?>
        <div class="blog-date"><?php echo $record['date_available']; ?></div>
      <?php } ?>
    <?php } ?>

    <?php if (isset ($record['settings_blog']['view_rating']) && $record['settings_blog']['view_rating'] ) { ?>
      <?php if ($record['rating']) { ?>
      <div class="rating blog-rate_container">

     <?php
      $themeFile = $this->getThemeFile('image/blogstars-'.$record['rating'].'.png');
      if ($themeFile) {
      ?>
      <img style="border: 0px;"  title="<?php echo $record['rating']; ?>" alt="<?php echo $record['rating']; ?>" src="catalog/view/theme/<?php echo $themeFile; ?>">
     <?php } ?>



      </div>
      <?php } ?>
    <?php } ?>

    <?php if (isset ($record['settings_blog']['view_share']) && $record['settings_blog']['view_share'] ) { ?>
	  <div class="share blog-share_container"><!-- AddThis Button BEGIN -->

	  <div class="addthis_toolbox addthis_default_style "
        addthis:url="<?php echo $record['href']; ?>"
        addthis:title="<?php echo $record['name']; ?>"
        addthis:description="<?php echo strip_tags($record['description']); ?>">
          <a class="addthis_button_facebook"></a>
          <a class="addthis_button_vk"></a>
          <a class="addthis_button_odnoklassniki_ru"></a>
          <a class="addthis_button_twitter"></a>
          <a class="addthis_button_email"></a>
          <a class="addthis_button_compact"></a>
          </div>

          <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js"></script>
          <!-- AddThis Button END -->
        </div>
    <?php } ?>

      <div class="blog-comment_container">
       <?php if (isset ($record['settings_blog']['view_comments']) && $record['settings_blog']['view_comments'] ) { ?>
	      <?php if ($record['settings_comment']['status']) { ?>
	      <div class="blog-comments"><?php echo $text_comments; ?> <?php echo $record['comments']; ?></div>
	      <?php } ?>
       <?php } ?>


    	<?php if (isset ($record['settings_blog']['view_viewed']) && $record['settings_blog']['view_viewed'] ) { ?>
	      <div class="blog-viewed"><?php echo $text_viewed; ?> <?php echo $record['viewed']; ?></div>
        <?php } ?>

      </div>
      <div class="lineheight1 overflowhidden">&nbsp;</div>
      </div>
 	<?php
	 if ($userLogged)  {
	?>
	<div class="blog-edit_container">
	   <a class="zametki" target="_blank" href="<?php echo $admin_path; ?>index.php?route=catalog/record/update&token=<?php echo $this->session->data['token']; ?>&record_id=<?php echo $record['record_id']; ?>"><?php echo $this->language->get('text_edit');?></a>
	 </div>
	<?php
	 }
	?>

  <div class="blog-child_divider">&nbsp;</div>
    </div>
    <?php } ?>
  </div>

    <div class="record-filter">
  <div class="limit floatleft"><b><?php echo $text_limit; ?></b>
      <select onchange="location = this.value;">
        <?php foreach ($limits as $limits) { ?>
        <?php if ($limits['value'] == $limit) { ?>
        <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
    <div class="sort floatleft marginleft10"><b><?php echo $text_sort; ?></b>
      <select onchange="location = this.value;">
        <?php foreach ($sorts as $sorts) { ?>
        <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
        <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
  </div>

  <div class="pagination margintop5"><?php echo $pagination; ?></div>
    <?php } ?>
  <?php if (!$categories && !$records) { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><span><?php echo $button_continue; ?></span></a></div>
  </div>
  <?php } ?>
  <?php echo $content_bottom; ?>

  </div>
<?php echo $footer; ?>