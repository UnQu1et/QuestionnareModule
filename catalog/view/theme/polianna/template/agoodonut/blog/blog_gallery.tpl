<?php echo $header; ?>

<div class="breadcrumb">
<div id="wrapper">

    <?php $i=0; foreach ($breadcrumbs as $breadcrumb) { $i++; ?>
    <?php echo $breadcrumb['separator']; ?><?php if (count($breadcrumbs)!= $i) { ?><a href="<?php echo $breadcrumb['href']; ?>"><?php } ?><?php echo $breadcrumb['text']; ?><?php if (count($breadcrumbs)!=$i) { ?></a><?php } ?>
    <?php } ?>

  </div>
  </div>

<div id="wrapper">

<?php echo $column_left; ?><?php echo $column_right; ?>

<div id="content"><?php echo $content_top; ?>

  <div class="blog-heading_title">
	  <h1><?php echo $heading_title; ?></h1>
  </div>

  <?php if ($description)  {   ?>
  <div class="blog-info">
    <?php if ($thumb && $description) { ?>
    <div class="image blog-image imagebox"><img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" /></div>
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
  <div class="blog-list" >
    <?php if (count($categories) <= 2) { ?>
    <ul>
      <?php foreach ($categories as $blog) { ?>
      <li><a href="<?php echo $blog['href']; ?>"><?php echo $blog['name']; ?></a></li>
      <?php } ?>
    </ul>
    <?php } else { ?>
    <?php for ($i = 0; $i < count($categories);) { ?>
    <ul>
      <?php $j = $i + ceil(count($categories) / 3); ?>
      <?php for (; $i < $j; $i++) { ?>
      <?php if (isset($categories[$i])) { ?>
      <li><a href="<?php echo $categories[$i]['href']; ?>"><?php echo $categories[$i]['name']; ?></a></li>
      <?php } ?>
      <?php } ?>
    </ul>
    <?php } ?>
    <?php } ?>
  </div>
	<div class="blog-child_divider" style="margin-top: 5px;">&nbsp;</div>
  <?php } ?>


  <?php if ($records) { ?>

  <div class="blog-record-list-small">
    <?php foreach ($records as $record) { ?>

    <div style="width: 250px; height: 360px; float: left;">
     <div class="name" style="margin-bottom: 5px;">
    <?php if (isset ($record['settings']['category_status']) && $record['settings']['category_status'] ) { ?>
    <?php if (isset ($record['settings_blog']['category_status']) && $record['settings_blog']['category_status'] ) { ?>
    <a href="<?php echo $record['blog_href']; ?>" class="blog-title"><?php echo $record['blog_name']; ?></a><ins class="blog-arrow">&nbsp;&rarr;&nbsp;</ins>
    <?php } ?>
    <?php } ?>

    	<a href="<?php echo $record['href']; ?>" class="blog-title"><?php echo $record['name']; ?></a>
     </div>

      <?php if ($record['thumb']) { ?>
      <div style="width: 100%;">
      <div>
	      <a href="<?php echo $record['popup']; ?>" title="<?php echo $record['name']; ?>" class="imagebox" rel="imagebox">
	      <img src="<?php echo $record['thumb']; ?>"  title="<?php echo $record['name']; ?>" alt="<?php echo $record['name']; ?>" >
	      </a>
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

      <div class="lineheight1 overflowhidden" style="width: 100%; clear: both;">&nbsp;</div>
      </div>
      <?php } ?>




		<?php if ($record['description']!='') { ?>
          	<div class="description" style="font-size: 15px; line-height: 23px;"><?php echo $record['description']; ?>&nbsp;
          	<a href="<?php echo $record['href']; ?>" class="blog_further"><?php if (isset($settings_general['further'][$this->config->get('config_language_id')])) echo html_entity_decode($settings_general['further'][$this->config->get('config_language_id')]); ?></a>
          	</div>
		<?php } ?>

      <div class="" style="float:none;">


    <?php if (isset ($record['settings_blog']['view_date']) && $record['settings_blog']['view_date'] ) { ?>

      <?php if ($record['date_available']) { ?>
        <div class="blog-date"><?php echo $record['date_available']; ?></div>
      <?php } ?>

    <?php } ?>


    <?php if (isset ($record['settings_blog']['view_rating']) && $record['settings_blog']['view_rating'] ) { ?>

      <?php if ($record['rating']) { ?>
      <div class="">
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
  <div class="" style="float: left; width: 100%; height: 100%; overflow: hidden;"><!-- AddThis Button BEGIN -->

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

      <div style="">
       <?php if (isset ($record['settings_blog']['view_rating']) && $record['settings_blog']['view_comments'] ) { ?>
	      <?php if ($record['settings_comment']['status']) { ?>
	      <div class="blog-comments"><?php echo $text_comments; ?> <?php echo $record['comments']; ?></div>
	      <?php } ?>
       <?php } ?>


    	<?php if (isset ($record['settings_blog']['view_viewed']) && $record['settings_blog']['view_viewed'] ) { ?>

	      <div class="blog-viewed"><?php echo $text_viewed; ?> <?php echo $record['viewed']; ?></div>

	      <?php } ?>
      </div>




      <div style="overflow: hidden; line-height: 1px; ">&nbsp;</div>



      </div>
 	<?php
	 if ($userLogged)
	  {
	?>
	<div class="">
	   <a class="zametki" target="_blank" href="<?php echo $admin_path; ?>index.php?route=catalog/record/update&token=<?php echo $this->session->data['token']; ?>&record_id=<?php echo $record['record_id']; ?>"><?php echo $this->language->get('text_edit');?></a>
	 </div>
	<?php
	 }
	?>

  <div class="blog-child_divider">&nbsp;</div>

    </div>


    <?php } ?>
  </div>


    <div class="record-filter" style="width: 100%; overflow: hidden;">
  <div class="limit" style="float:left;"><b><?php echo $text_limit; ?></b>
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
    <div class="sort" style="float:left; margin-left: 10px;"><b><?php echo $text_sort; ?></b>
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

  <div class="pagination" style="margin-top:5px;" ><?php echo $pagination; ?></div>



  <?php } ?>
  <?php if (!$categories && !$records) { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><span><?php echo $button_continue; ?></span></a></div>
  </div>
  <?php } ?>
  <?php echo $content_bottom; ?></div>


<?php echo $footer; ?>