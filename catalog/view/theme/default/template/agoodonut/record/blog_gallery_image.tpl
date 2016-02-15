<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
<div>
<?php echo $content_top; ?>

  <div class="breadcrumb">
    <?php $i=0; foreach ($breadcrumbs as $breadcrumb) { $i++; ?>
    <?php echo $breadcrumb['separator']; ?><?php if (count($breadcrumbs)!= $i) { ?><a href="<?php echo $breadcrumb['href']; ?>"><?php } ?><?php echo $breadcrumb['text']; ?><?php if (count($breadcrumbs)!=$i) { ?></a><?php } ?>
    <?php } ?>
  </div>

  <h1 class="marginbottom5"><?php echo $heading_title; ?></h1>
  <div class="record-info" style="padding: 0; margin: 0;">

	<div class="blog-small-record">
	<ul>
	     <?php if (isset ($settings_blog['view_date']) && $settings_blog['view_date'] ) { ?>
			<li class="blog-data-record"> <?php echo $date_added; ?></li>
	     <?php } ?>


	 <?php if (isset ($settings_blog['view_comments']) && $settings_blog['view_comments'] ) { ?>
	<li class="blog-comments-record"> <?php echo $tab_comment; ?>:<ins style="text-decoration:none;" class="comment_count"><?php echo $comment_count; ?>
	 <!--<?php echo $text_comments; ?>--></ins></li>
	<?php } ?>
	 <?php if (isset ($settings_blog['view_viewed']) && $settings_blog['view_viewed'] ) { ?>
	<li class="blog-viewed-record"><?php echo $text_viewed; ?> <?php echo $viewed; ?></li>
	 <?php } ?>

	 <li class="floatright" style="float: right; ">
	<span typeof="v:Review-aggregate" xmlns:v="http://rdf.data-vocabulary.org/#">
	<?php if (isset ($settings_blog['view_rating']) && $settings_blog['view_rating'] ) { ?>
     <?php
      $themeFile = $this->getThemeFile('image/blogstars-'.$rating.'.png');
      if ($themeFile) {
      ?>
      <img style="border: 0px;"  title="<?php echo $rating; ?>" alt="<?php echo $rating; ?>" src="catalog/view/theme/<?php echo $themeFile; ?>">
     <?php } ?>
	<?php } ?>


	    <span property="v:itemreviewed"></span> <span rel="v:rating"><span typeof="v:Rating"><span property="v:average" content="<?php echo $rating; ?>"></span><span property="v:best" content="5"></span></span></span><span property="v:votes" content="<?php echo $comment_count; ?>"></span>
	    <span property="v:count" content="<?php echo $comment_count; ?>"></span>

	</span>

	 </li>

	</ul>

 </div>


<div class="overflowhidden" style="width: 100%;">&nbsp;</div>


      <?php if ($thumb) { ?>
      <div class="image blog-image">
      <a href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>" class="imagebox" rel="imagebox">
      <img src="<?php echo $thumb; ?>"  title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>>" >
      </a>
      </div>
      <?php } ?>

  <div class="blog-record-description">
  <?php echo $description; ?>
  </div>
<div class="overflowhidden" style="width: 100%;">&nbsp;</div>
<div class="blog-next-prev">
<?php if($record_previous['name']!='') {?>
<a href="<?php echo $record_previous['url']; ?>">&larr;&nbsp;<?php echo $record_previous['name']; ?></a>&nbsp;&nbsp;|&nbsp;
<?php } ?>

<?php if($record_next['name']!='') {?>
<a href="<?php echo $record_next['url']; ?>"><?php echo $record_next['name']; ?>&nbsp;&rarr;</a>
<?php } ?>
</div>

    <div>
      <div class="description">

     <?php
      if ($comment_status) {
      $h=end($breadcrumbs);
      $href=$h['href'];
      ?>
      <div class="comment">

        <div>
        <br>
        <a onclick="$('a[href=\'#tab-review\']').trigger('click');"><?php echo $tab_comment; ?>:<ins style="text-decoration:none;" class="comment_count"><?php echo $comment_count; ?><!--<?php echo $text_comments; ?>--></ins></a>&nbsp;&nbsp;

        |&nbsp;&nbsp;<a onclick="$('a[href=\'#tab-review\']').trigger('click');" href="<?php echo $href; ?>#comment-title"><?php echo $text_write; ?></a></div>

        <div class="overflowhidden">&nbsp;</div>

      </div>
      <?php } ?>
    </div>
  </div>

  </div>



  <div id="tabs" class="htabs">
    <?php
    if ($comment_status) {
    ?>
    <a href="#tab-review"><?php echo $tab_comment; ?><ins style="text-decoration: none;" class="comment_count">(<?php echo $comment_count; ?>)</ins></a>
    <?php } ?>

    <?php if ($images) { ?>
     <a href="#tab-images"><?php echo $tab_images; ?></a>
    <?php } ?>

    <?php if ($attribute_groups) { ?>
    <a href="#tab-attribute"><?php echo $tab_attribute; ?></a>
    <?php } ?>

    <?php if ($records) { ?>
    <a href="#tab-related"><?php echo $tab_related; ?> (<?php echo count($records); ?>)</a>
    <?php } ?>

    <?php if ($products) { ?>
    <a href="#tab-product-related"><?php echo $tab_product_related; ?> (<?php echo count($products); ?>)</a>
    <?php } ?>


</div>


  <?php
  if ($comment_status) { ?>
  <div id="tab-review" class="tab-content">
  </div>
  <?php } ?>


  <?php if ($products) { ?>
  <div id="tab-product-related" class="tab-content">
    <div class="box-product">
      <?php foreach ($products as $product) { ?>
      <div>
        <?php if ($product['thumb']) { ?>
        <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
        <?php } ?>
        <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
        <?php if ($product['price']) { ?>
        <div class="price">
          <?php if (!$product['special']) { ?>
          <?php echo $product['price']; ?>
          <?php } else { ?>
          <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
          <?php } ?>
        </div>
        <?php } ?>
        <?php if ($product['rating']) { ?>
        <div class="rating"><img src="catalog/view/theme/<?php echo $theme; ?>/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>
        <?php } ?>
        <a onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button"><span><?php echo $button_cart; ?></span></a></div>
      <?php } ?>
    </div>
  </div>
  <?php } ?>


  <?php if ($records) { ?>
  <div id="tab-related" class="tab-content">
    <div class="box-product">
      <?php foreach ($records as $record) { ?>
      <div>
        <?php if ($record['thumb']) { ?>
        <div class="image"><a href="<?php echo $record['href']; ?>"><img src="<?php echo $record['thumb']; ?>" alt="<?php echo $record['name']; ?>" /></a></div>
        <?php } ?>
        <div class="name"><a href="<?php echo $record['href']; ?>"><?php echo $record['name']; ?></a></div>

        <?php if ($record['rating']) { ?>
        <div class="rating"><img src="catalog/view/theme/<?php echo $theme; ?>/image/blogstars-<?php echo $record['rating']; ?>.png" alt="<?php echo $record['comments']; ?>" /></div>
        <?php } ?>
        </div>
      <?php } ?>
    </div>
  </div>
  <?php } ?>


  <?php if ($images) { ?>

  <div id="tab-images" class="tab-content">
    <div class="left">
      <?php if ($images) { ?>
      <div class="image-additional">
        <?php foreach ($images as $image) { ?>
        <a href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>" class="imagebox" rel="imagebox"><img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
        <?php } ?>
      </div>
      <?php } ?>
    </div>
  </div>
  <?php } ?>


  <?php if ($attribute_groups) { ?>
  <div id="tab-attribute" class="tab-content">
    <table class="attribute">
      <?php foreach ($attribute_groups as $attribute_group) { ?>
      <thead>
        <tr>
          <td colspan="2"><?php echo $attribute_group['name']; ?></td>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
        <tr>
          <td><?php echo $attribute['name']; ?></td>
          <td><?php echo $attribute['text']; ?></td>
        </tr>
        <?php } ?>
      </tbody>
      <?php } ?>
    </table>
  </div>
  <?php } ?>




 <?php if (isset ($settings_blog['view_share']) && $settings_blog['view_share'] ) { ?>
<div class="share floatleft"><!-- AddThis Button BEGIN -->

  <div  class="addthis_toolbox addthis_default_style ">
          <a class="addthis_button_facebook"></a>
          <a class="addthis_button_vk"></a>
          <a class="addthis_button_odnoklassniki_ru"></a>
          <a class="addthis_button_youtube"></a>
          <a class="addthis_button_twitter"></a>
          <a class="addthis_button_email"></a>
          <a class="addthis_button_compact"></a>
          </div>

          <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js"></script>
          <!-- AddThis Button END -->
        </div>

  <div class="powered_blog_icon"><h3 class="blog-icon">Powered by module Blog | News | Reviews | Gallery ver.: <?php echo $blog_version; ?> (opencartadmin.com)</h3></div>
   <div style="overflow: hidden;">&nbsp;</div>
 <?php } ?>

   <?php if ($tags) {
   ?>
  <div class="tags"><b><?php echo $text_tags; ?></b>
    <?php for ($i = 0; $i < count($tags); $i++) { ?>
    <?php if ($i < (count($tags) - 1)) { ?>
    <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>,
    <?php } else { ?>
    <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>
    <?php } ?>
    <?php } ?>
  </div>
  <?php } ?>


  </div>

<?php echo $content_bottom; ?>
</div>



<script type="text/javascript">
$('#tabs a').tabs();
</script>


<script>
$(document).ready(function(){$('.powered_blog').hide();
});
</script>

<style>
h3.blog-icon {
  background-image: url("catalog/view/theme/<?php
	$template = '/image/ib.png';
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . $template)) {
					$ibpath = $this->config->get('config_template') . $template;
				} else {
					$ibpath = 'default' . $template;
				}

	echo $ibpath;
	?>");
  height: 16px;
  width:  16px;
  text-indent: 100%;
  white-space: nowrap;
  overflow: hidden;
  font-size:12px;
  font-weight: normal;
}
</style>





<?php echo $footer; ?>