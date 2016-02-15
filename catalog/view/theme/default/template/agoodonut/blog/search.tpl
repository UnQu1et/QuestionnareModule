<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>

<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="blog-heading_title">
  <h1><?php echo $heading_title; ?></h1>
  </div>

  <b><?php echo $text_critea; ?></b>
  <div class="content">
    <p><?php echo $entry_search; ?>
      <?php if ($filter_name) { ?>
      <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" />
      <?php } else { ?>
      <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" onclick="this.value = '';" onkeydown="this.style.color = '000000'" style="color: #999;" />
      <?php } ?>
      <select name="filter_blog_id">
        <option value="0"><?php echo $text_blog; ?></option>


        <?php
        if (!empty($categories)) {
        foreach ($categories as $blog_1) {
        ?>
        <?php if ($blog_1['blog_id'] == $filter_blog_id) { ?>
        <option value="<?php echo $blog_1['blog_id']; ?>" selected="selected"><?php echo $blog_1['name']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $blog_1['blog_id']; ?>"><?php echo $blog_1['name']; ?></option>
        <?php } ?>
        <?php foreach ($blog_1['children'] as $blog_2) { ?>
        <?php if ($blog_2['blog_id'] == $filter_blog_id) { ?>
        <option value="<?php echo $blog_2['blog_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $blog_2['name']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $blog_2['blog_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $blog_2['name']; ?></option>
        <?php } ?>
        <?php foreach ($blog_2['children'] as $blog_3) { ?>
        <?php if ($blog_3['blog_id'] == $filter_blog_id) { ?>
        <option value="<?php echo $blog_3['blog_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $blog_3['name']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $blog_3['blog_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $blog_3['name']; ?></option>
        <?php } ?>
        <?php } ?>
        <?php } ?>
        <?php } ?>
        <?php } ?>
      </select>
      <?php if ($filter_sub_blog) { ?>
      <input type="checkbox" name="filter_sub_blog" value="1" id="sub_blog" checked="checked" />
      <?php } else { ?>
      <input type="checkbox" name="filter_sub_blog" value="1" id="sub_blog" />
      <?php } ?>
      <label for="sub_blog"><?php echo $text_sub_blog; ?></label>
    </p>
    <?php if ($filter_description) { ?>
    <input type="checkbox" name="filter_description" value="1" id="description" checked="checked" />
    <?php } else { ?>
    <input type="checkbox" name="filter_description" value="1" id="description" />
    <?php } ?>
    <label for="description"><?php echo $entry_description; ?></label>
  </div>
  <div class="buttons">
    <div class="right"><a id="button-search" class="button"><span><?php echo $button_search; ?></span></a></div>
  </div>
  <h2><?php echo $text_search; ?></h2>

<?php
if ($this->browser() !=  'ieO') {
?>
<script type="text/javascript">
if ($('head').html().indexOf('blog.css') <0) {
	$('head').append('<link href="catalog/view/theme/<?php echo $theme."/stylesheet/blog.css"; ?>" type="text/css" rel="stylesheet" />');
 }
</script>
<?php }
else
{
?>
<style>
<?php
 include (DIR_TEMPLATE.$theme.'/stylesheet/blog.css');
?>
</style>
<?php } ?>

 <?php if ($records) { ?>

  <div class="blog-record-list">
    <?php foreach ($records as $record) { ?>
    <div>
     <div class="name" style="margin-bottom: 5px;">
    <a href="<?php echo $record['blog_href']; ?>" class="blog-title"><?php echo $record['blog_name']; ?></a><ins class="blog-arrow">&nbsp;&rarr;&nbsp;</ins>
    <a href="<?php echo $record['href']; ?>" class="blog-title"><?php echo $record['name']; ?></a>
     </div>

      <?php if ($record['thumb']) { ?>
      <div class="image blog-image"><a href="<?php echo $record['href']; ?>"><img src="<?php echo $record['thumb']; ?>" title="<?php echo $record['name']; ?>" alt="<?php echo $record['name']; ?>" /></a></div>
      <?php } ?>

          	<div class="description" style="font-size: 15px; line-height: 23px;"><?php echo $record['description']; ?>&nbsp;
          	<a href="<?php echo $record['href']; ?>" class="blog_further"><?php if (isset($settings_general['further'][$this->config->get('config_language_id')])) echo html_entity_decode($settings_general['further'][$this->config->get('config_language_id')]); ?></a></div>


      <div class="blog-date_container">
      <?php if ($record['date_available']) { ?>
        <div class="blog-date"><?php echo $record['date_available']; ?></div>
      <?php } ?>

      <?php if ($record['rating']) { ?>
      <div class="rating blog-rate_container">
      <img style="border: 0px;"  title="<?php echo $record['rating']; ?>" alt="<?php echo $record['rating']; ?>" src="catalog/view/theme/<?php


			$template = '/image/blogstars-'.$record['rating'].'.png';
			if (file_exists(DIR_TEMPLATE . $theme . $template)) {
				$starpath = $theme . $template;
			} else {
				$starpath = 'default' . $template;
			}

			echo $starpath;

?>">

      </div>
      <?php } ?>

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




     <div class="blog-comment_container">
	      <div class="blog-comments"><?php echo $text_comments; ?> <?php echo $record['comments']; ?></div>
	      <div class="blog-viewed"><?php echo $text_viewed; ?> <?php echo $record['viewed']; ?></div>
      </div>




      <div style="overflow: hidden; line-height: 1px; ">&nbsp;</div>



      </div>


  <div class="blog-child_divider">&nbsp;</div>

    </div>
    <?php } ?>



  </div>





  <div class="record-filter" style="clear: both;">
    <div class="limit" style="float:left;"><?php echo $text_limit; ?>
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
    <div class="sort" style="margin-left: 5px; float:left;"><?php echo $text_sort; ?>
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


  <div class="pagination"><?php echo $pagination; ?></div>
  <?php } else { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <?php }?>
  <?php echo $content_bottom; ?></div>
<script type="text/javascript">
$('#content input[name=\'filter_name\']').keydown(function(e) {
	if (e.keyCode == 13) {
		$('#button-search').trigger('click');
	}
});

$('#button-search').bind('click', function() {
	url = 'index.php?route=record/search';

	var filter_name = $('#content input[name=\'filter_name\']').attr('value');

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

	var filter_category_id = $('#content select[name=\'filter_category_id\']').attr('value');

	if (filter_category_id > 0) {
		url += '&filter_category_id=' + encodeURIComponent(filter_category_id);
	}

	var filter_sub_category = $('#content input[name=\'filter_sub_category\']:checked').attr('value');

	if (filter_sub_category) {
		url += '&filter_sub_category=true';
	}

	var filter_description = $('#content input[name=\'filter_description\']:checked').attr('value');

	if (filter_description) {
		url += '&filter_description=true';
	}

	location = url;
});

</script>
<?php echo $footer; ?>