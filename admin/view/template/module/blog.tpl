<?php echo $header; ?>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>

  <div style="border-bottom: 1px solid #DDD; background-color: #EFEFEF; height: 40px; min-width: 900px; overflow: hidden;">
    <div style="float:left; margin-top: 10px;" >
    <img src="view/image/blog-icon.png" style="height: 21px; margin-left: 10px; " >
    </div>

<div style="margin-left: 10px; float:left; font-size: 16px; margin-top: 10px;">
<ins style="color: green; padding-top: 17px; text-shadow: 0 2px 1px #FFFFFF; padding-left: 3px; font-size: 17px; font-weight: bold;  text-decoration: none; ">
<?php echo strip_tags($heading_title); ?>
</ins> ver.: <?php echo $blog_version; ?>
</div>

    <div style=" height: 40px; float:right; background:#aceead;">
   <div style="margin-top: 2px; line-height: 18px; margin-left: 9px; margin-right: 9px; font-size: 13px; overflow: hidden;"><?php echo $this->language->get('heading_dev'); ?></div>
    </div>


</div>

<div id="content" style="border: none;">

<!--
<div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo strip_tags($breadcrumb['text']); ?></a>
  <?php } ?>
</div>
-->
<div style="clear: both; line-height: 1px; font-size: 1px;"></div>


<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>

<?php if (isset($this->session->data['success'])) {unset($this->session->data['success']);
?>
<div class="success"><?php echo $this->language->get('text_success'); ?></div>
<?php } ?>


<div class="box1">

<div class="content">

<div style="margin-left:0px;">
<a href="<?php echo $url_blog; ?>" class="markbutton"><div style="float: left;"><img src="view/image/blog-icon-m.png" style="" ></div><div style="float: left; margin-left: 7px; margin-top: 3px; "><?php echo $url_blog_text; ?></div></a>
<a href="<?php echo $url_record; ?>" class="markbutton"><div style="float: left;"><img src="view/image/blog-rec-m.png"  style="" ></div><div style="float: left; margin-left: 7px; margin-top: 3px; "><?php echo $url_record_text; ?></div></a>
<a href="<?php echo $url_comment; ?>" class="markbutton"><div style="float: left;"><img src="view/image/blog-com-m.png"  style="" ></div><div style="float: left; margin-left: 7px; margin-top: 3px; "><?php echo $url_comment_text; ?></div></a>
<a href="<?php echo $url_modules; ?>" class="markbutton"><div style="float: left;"><img src="view/image/blog-back-m.png"  style="" ></div><div style="float: left; margin-left: 7px; margin-top: 3px; "><?php echo $url_modules_text; ?></div></a>
</div>

<div style="margin-left:0px;">
<div style="margin-right:5px;  float:left;">
<a href="<?php echo $url_options; ?>" class="markbutton-active"><div style="float: left;"><img src="view/image/agoodonut-options-m.png"  style="" ></div>
<div style="float: left; margin-left: 7px; margin-top: 4px; "><?php echo $this->language->get('tab_options'); ?></div></a>
</div>


<div style="margin-right:5px; float:left;">
<a href="<?php echo $url_schemes; ?>" class="markbutton"><div style="float: left;"><img src="view/image/agoodonut-schemes-m.png"  style="" ></div>
<div style="float: left; margin-left: 7px; margin-top: 4px; "><?php echo $tab_general; ?></div></a>
</div>

<div style="margin-right:5px;  float:left;">
<a href="<?php echo $url_widgets; ?>" class="markbutton"><div style="float: left;"><img src="view/image/agoodonut-widgets-m.png"  style="" ></div>
<div style="float: left; margin-left: 7px; margin-top: 4px; "><?php echo $tab_list; ?></div></a>
</div>
</div>


<div style="margin:5px; float:right;">
   <a href="#" class="mbutton blog_save"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="mbutton"><?php echo $button_cancel; ?></a>
</div>

<div style="clear: both; line-height: 1px; font-size: 1px;"></div>

<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

<script type="text/javascript">
function delayer(){
    window.location = 'index.php?route=module/blog&token=<?php echo $token; ?>';
}
</script>

 <div id="tabs" class="htabs"><a href="#tab-options"><?php echo $this->language->get('tab_options'); ?></a><a href="#tab-install"><?php echo $this->language->get('entry_install_update'); ?></a><a href="#tab-fields"><?php echo $this->language->get('entry_service'); ?></a></div>

<div id="tab-fields">
<a href="<?php echo $url_fields; ?>" class="markbutton"><div style="float: left;"><img src="view/image/blog-rec-m.png"  style="" ></div><div style="float: left; margin-left: 7px; margin-top: 3px; "><?php echo $this->language->get('entry_fields'); ?></div></a>
</div>


  <div id="tab-options">

   <table class="mynotable" style="margin-bottom:20px; background: white; vertical-align: center;">

    <tr>
     <td class="left"><?php echo $entry_small_dim; ?></td>
     <td class="left">
      <input type="text" name="blog_small[width]" value="<?php if (isset($blog_small['width'])) echo $blog_small['width']; ?>" size="3" />x
      <input type="text" name="blog_small[height]" value="<?php if (isset($blog_small['height'])) echo $blog_small['height']; ?>" size="3" />
     </td>
    </tr>

    <tr>
     <td class="left"><?php echo $entry_big_dim; ?></td>
     <td class="left">
      <input type="text" name="blog_big[width]" value="<?php  if (isset($blog_big['width'])) echo $blog_big['width']; ?>" size="3" />x
      <input type="text" name="blog_big[height]" value="<?php if (isset($blog_big['height'])) echo $blog_big['height']; ?>" size="3" />
     </td>
    </tr>

    <tr>
     <td class="left"><?php echo $entry_blog_num_records; ?></td>
     <td class="left">
      <input type="text" name="blog_num_records" value="<?php  if (isset($blog_num_records)) echo $blog_num_records; ?>" size="3" />
     </td>
    </tr>

    <tr>
     <td class="left"><?php echo $entry_blog_num_comments; ?></td>
     <td class="left">
      <input type="text" name="blog_num_comments" value="<?php  if (isset($blog_num_comments)) echo $blog_num_comments; ?>" size="3" />
     </td>
    </tr>

    <tr>
     <td class="left"><?php echo $entry_blog_num_desc; ?></td>
     <td class="left">
      <input type="text" name="blog_num_desc" value="<?php  if (isset($blog_num_comments)) echo $blog_num_desc; ?>" size="3" />
     </td>
    </tr>

    <tr>
     <td class="left"><?php echo $entry_blog_num_desc_words; ?></td>
     <td class="left">
      <input type="text" name="blog_num_desc_words" value="<?php  if (isset($blog_num_desc_words)) echo $blog_num_desc_words; ?>" size="3" />
     </td>
    </tr>

    <tr>
     <td class="left"><?php echo $entry_blog_num_desc_pred; ?></td>
     <td class="left">
      <input type="text" name="blog_num_desc_pred" value="<?php  if (isset($blog_num_desc_pred)) echo $blog_num_desc_pred; ?>" size="3" />
     </td>
    </tr>



 	<!--
 	<tr>
 		<td>
			<?php echo $this->language->get('entry_order_records'); ?>
		</td>
		<td>
         <select id="generallist_order"  name="generallist[order]">
           <option value="sort"  <?php if (isset($generallist['order']) &&  $generallist['order']=='sort')  { echo 'selected="selected"'; } ?>><?php echo $this->language->get('text_what_sort'); ?></option>
           <option value="latest"  <?php if (isset( $generallist['order']) &&  $generallist['order']=='latest')  { echo 'selected="selected"'; } ?>><?php echo $this->language->get('text_what_latest'); ?></option>
           <option value="popular" <?php if (isset( $generallist['order']) &&  $generallist['order']=='popular') { echo 'selected="selected"'; } ?>><?php echo $this->language->get('text_what_popular'); ?></option>
           <option value="rating" <?php if (isset( $generallist['order']) &&  $generallist['order']=='rating') { echo 'selected="selected"'; } ?>><?php echo $this->language->get('text_what_rating'); ?></option>
           <option value="comments" <?php if (isset( $generallist['order']) &&  $generallist['order']=='comments') { echo 'selected="selected"'; } ?>><?php echo $this->language->get('text_what_comments'); ?></option>
           <option value="reviews" <?php if (isset( $generallist['order']) &&  $generallist['order']=='reviews') { echo 'selected="selected"'; } ?>><?php echo $this->language->get('text_what_reviews'); ?></option>

         </select>
		</td>
	</tr>

 	<tr>
 		<td>
			<?php echo $this->language->get('entry_order_ad'); ?>
		</td>
		<td>
         <select id="generallist_order_ad"  name="generallist[order_ad]">
           <option value="desc"  <?php if (isset( $generallist['order_ad']) &&  $generallist['order_ad']=='desc') { echo 'selected="selected"'; } ?>><?php echo $this->language->get('text_what_desc'); ?></option>
           <option value="asc"   <?php if (isset( $generallist['order_ad']) &&  $generallist['order_ad']=='asc')  { echo 'selected="selected"'; } ?>><?php echo $this->language->get('text_what_asc'); ?></option>
        </select>
		</td>
	</tr>
         -->
          <tr>
              <td><?php echo $this->language->get('entry_category_status'); ?></td>
              <td><select name="generallist[category_status]">
                  <?php if ( isset($generallist['category_status']) && $generallist['category_status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>

          <tr>
              <td><?php echo $this->language->get('entry_cache_widgets'); ?></td>
              <td><select name="generallist[cache_widgets]">
                  <?php if (isset($generallist['cache_widgets']) && $generallist['cache_widgets']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
          <tr>
              <td><?php echo $this->language->get('entry_review_visual'); ?></td>
              <td><select name="generallist[review_visual]">
                  <?php if (isset($generallist['review_visual']) && $generallist['review_visual']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>

          <tr>
              <td><?php echo $this->language->get('entry_resize'); ?></td>
              <td><select name="blog_resize">
                  <?php if (isset($blog_resize) && $blog_resize) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>


    <tr>
     <td class="left"><?php echo $this->language->get('entry_end_url_record'); ?></td>
     <td class="left">
      <input type="text" class="template" name="generallist[end_url_record]" value="<?php  if (isset($generallist['end_url_record'])) echo $generallist['end_url_record']; ?>" size="20" />
     </td>
    </tr>


    <tr>
     <td class="left"><?php echo $this->language->get('entry_get_pagination');  ?></td>
     <td class="left">
      <input type="text" class="template" name="generallist[get_pagination]" value="<?php  if (isset($generallist['get_pagination'])) echo $generallist['get_pagination']; ?>" size="20" />
     </td>
    </tr>




            <tr>
            <td><?php echo $this->language->get('entry_colorbox_theme'); ?></td>
              <td>
               <select name="generallist[colorbox_theme]">
           	<?php
				foreach ($colorbox_theme as $num =>$list) {
		    ?>
                <?php if (isset($generallist['colorbox_theme']) && $generallist['colorbox_theme']==$list) { ?>
                <option value="<?php echo $list; ?>" selected="selected"><?php echo $list; ?></option>
                <?php } else { ?>
                <option value="<?php echo $list; ?>"><?php echo $list; ?></option>
                <?php } ?>

              <?php
              }
              ?>
              </select>
              </td>
              </tr>


	 <?php foreach ($languages as $language) { ?>
	<tr>
		<td class="left">
			<?php echo $this->language->get('entry_title_further'); ?> (<?php echo $language['name']; ?>)
		</td>
			<td>
				<div style="float: left;">
				<textarea name="generallist[further][<?php echo $language['language_id']; ?>]" rows="3" cols="50" ><?php if (isset($generallist['further'][$language['language_id']])) { echo $generallist['further'][$language['language_id']]; } else { echo '&rarr;'; } ?></textarea>
				</div>
				<div style="float: left; margin-left: 3px;">
				<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" ><br>
               </div>
			</td>

	</tr>
   <?php } ?>



    <tr>
     <td></td>
     <td></td>
    </tr>
   </table>
  </div>



<div id="tab-install">
 <div id="create_tables" style="color: green; font-weight: bold;"></div>
    <a href="#" onclick="
		$.ajax({
			url: '<?php echo $url_create; ?>',
			dataType: 'html',
			success: function(json) {
				$('#create_tables').html(json);
				setTimeout('delayer()', 2000);
			},
			error: function(json) {
			$('#create_tables').html('error');
			}
		}); return false;" class="markbuttono" style=""><?php echo $url_create_text; ?></a>

    <a href="#" onclick="
		$.ajax({
			url: '<?php echo $url_delete; ?>',
			dataType: 'html',
			success: function(json) {
				$('#create_tables').html(json);
				setTimeout('delayer()', 2000);
			},
			error: function(json) {
			$('#create_tables').html('error');
			}
		}); return false;" class="markbuttono" style=""><?php echo $url_delete_text; ?></a>


<?php if (isset($text_update) && $text_update!='' ) { ?>
<div style="font-size: 18px; color: red;"><?php echo $text_update; ?></div>
<?php } ?>

</div>


 </div>

 </form>
</div>
	<script type="text/javascript">

	 form_submit = function() {
		$('#form').submit();
		return false;
	}
	$('.blog_save').bind('click', form_submit);
	</script>

	<script type="text/javascript">
$('#tabs a').tabs();
</script>

</div>

<?php echo $footer; ?>