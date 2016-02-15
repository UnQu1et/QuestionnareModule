 <?php
 if ($records) {
 ?>
<div id="cmswidget-<?php echo $cmswidget; ?>">
  <div class="blog-heading_title"><h1><?php echo $heading_title; ?></h1></div>
	<div class="box-content">

  <div class="blog-record-list-small" id="cmswidget-<?php echo $cmswidget; ?>">
    <?php foreach ($records as $record) { ?>
    <div>


				    <?php if (isset ($record['settings']['view_date']) && $record['settings']['view_date'] ) { ?>
				      <?php if ($record['date_available']) { ?>
				        <div class="blog-date"><?php echo $record['date_available']; ?></div>
				      <?php } ?>
				    <?php } ?>



     <div class="name marginbottom5">

					    <?php if (isset ($record['settings']['category_status']) && $record['settings']['category_status'] ) { ?>
					    <a href="<?php echo $record['blog_href']; ?>" class="blog-title"><?php echo $record['blog_name']; ?></a><ins class="blog-arrow">&nbsp;&rarr;&nbsp;</ins>
					    <?php } ?>
					    <a href="<?php echo $record['href']; ?>" class="blog-title"><?php echo $record['name']; ?></a>

     </div>

      <?php if ($record['thumb'] || count($record['images']) > 0) { ?>
      <div class="image blog-image">
      <?php if ($record['thumb']) { ?>
      <div>
      <a href="<?php echo $record['href']; ?>"><img src="<?php echo $record['thumb']; ?>" title="<?php echo $record['name']; ?>" alt="<?php echo $record['name']; ?>" /></a>
      </div>
       <?php } ?>

      <?php if (isset ($record['settings']['images_view']) && $record['settings']['images_view'] ) { ?>
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




          	<div class="description"><?php echo $record['description']; ?>&nbsp;
          	<a href="<?php echo $record['href']; ?>" class="blog_further"><?php if (isset($settings_general['further'][$this->config->get('config_language_id')])) echo html_entity_decode($settings_general['further'][$this->config->get('config_language_id')]); ?></a></div>

     <div class="overflowhidden width100 lineheight1">&nbsp;</div>
      <div>



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


     <div>

					      <?php if ($record['settings_comment']['status']) { ?>
					      <?php if (isset ($record['settings']['view_comments']) && $record['settings']['view_comments'] ) { ?>
					      <div class="blog-light-small-text"><?php echo $text_comments; ?> <?php echo $record['comments']; ?></div>
					      <?php } ?>
				       <?php } ?>



				        <?php if (isset ($record['settings']['view_viewed']) && $record['settings']['view_viewed'] ) { ?>
					      <div class="blog-light-small-text"><?php echo $text_viewed; ?> <?php echo $record['viewed']; ?></div>
				        <?php } ?>

      </div>

      <div class="overflowhidden lineheight1">&nbsp;</div>
      </div>
 	<?php
	 if ($userLogged)    {
	?>
	<div class="blog-edit_container">
	   <a class="zametki" target="_blank" href="<?php echo $admin_path; ?>index.php?route=catalog/record/update&token=<?php echo $this->session->data['token']; ?>&record_id=<?php echo $record['record_id']; ?>"><?php echo $this->language->get('text_edit');?></a>
	 </div>
	<?php
	 }
	?>

  <div class="blog-child_divider">&nbsp;</div>
 <div class="overflowhidden lineheight1 width100">&nbsp;</div>
    </div>
    <?php } ?>
  </div>

    <div class="record-filter">


 <?php if (isset ($settings_widget['limit']) && $settings_widget['limit'] ) { ?>
  <div class="limit floatleft"><b><?php echo $this->language->get('text_limit');?></b>
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

    <?php } ?>

  <?php if (isset ($settings_widget['sort']) && $settings_widget['sort'] ) { ?>
  <div class="sort floatleft marginleft10"><b><?php echo $this->language->get('text_sort');?></b>
   <select onchange="location = this.value;">
       <!--   <select onchange="alert( this.value);"> -->
        <?php foreach ($sorts as $sorts) { ?>
        <?php if (strtolower($sorts['value']) == strtolower($sort . '-' . $order)) { ?>
        <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
  </div>
   <?php } ?>



 		<?php if (isset ($settings_widget['pagination']) && $settings_widget['pagination'] ) { ?>
		<div class="pagination margintop5"><?php echo $pagination; ?></div>
		<?php } ?>
 </div>
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