<?php if ($records) { ?>
<div class="box" id="cmswidget-<?php echo $cmswidget; ?>">
  <div class="box-heading"><?php echo $heading_title; ?></div>
	<div class="box-content">
  <div class="blog-record-list">
    <?php foreach ($records as $record) { ?>
    <div>
     <div class="name marginbottom5">

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

      <?php if ($record['thumb'] || count($record['images']) > 0) { ?>
      <div class="image blog-image">
      <?php if ($record['thumb']) { ?>
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

          	<div class="description record_description"><?php echo $record['description']; ?>&nbsp;
          	<a href="<?php echo $record['href']; ?>" class="blog_further"><?php if (isset($settings_general['further'][$this->config->get('config_language_id')])) echo html_entity_decode($settings_general['further'][$this->config->get('config_language_id')]); ?></a></div>


      <div class="blog-date_container">
      <?php if ($record['date_available']) { ?>
        <div class="blog-date"><?php echo $record['date_available']; ?></div>
      <?php } ?>

      <?php if ($record['rating']) { ?>
      <div class="rating blog-rate_container">
     <?php
      $themeFile = $this->getThemeFile('image/blogstars-'.$record['rating'].'.png');
      if ($themeFile) {
      ?>
      <img title="<?php echo $record['rating']; ?>" alt="<?php echo $record['rating']; ?>" src="catalog/view/theme/<?php echo $themeFile; ?>">
     <?php } ?>
      </div>
      <?php } ?>

  <div class="share blog-share_container"><!-- AddThis Button BEGIN -->

  <div class="addthis_toolbox addthis_default_style "
        addthis:url="<?php echo $record['href']; ?>"
        addthis:title="<?php echo $record['name']; ?>"
        addthis:description="<?php echo $record['description']; ?>">




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

      <div class="blog-comment_container">
      <?php if ($record['settings_comment']['status']) { ?>
	      <div class="blog-comments"><?php echo $text_comments; ?> <?php echo $record['comments']; ?></div>
	     <?php } ?>
	      <div class="blog-viewed"><?php echo $text_viewed; ?> <?php echo $record['viewed']; ?></div>
      </div>




       <div class="overflowhidden lineheight1 bordernone">&nbsp;</div>



      </div>
 	<?php
	 if ($userLogged)
	  {
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

  <div class="pagination margintop5"  ><?php echo $pagination; ?></div>

 </div>

	<?php if (isset ($settings_widget['pagination']) && $settings_widget['pagination'] ) { ?>
		<div class="pagination margintop5"><?php echo $pagination; ?></div>
		<?php } ?>

</div>
<?php if (isset($settings_widget['anchor']) && $settings_widget['anchor']!='') { ?>
<script>
$(document).ready(function(){

	var data = $('#cmswidget-<?php echo $cmswidget; ?>').html();
	<?php echo $settings_widget['anchor']; ?>;
	 delete data;
	$('#cmswidget-<?php echo $cmswidget; ?>').hide('slow').remove();

});
</script>
 <?php  } ?>
  <?php } ?>
