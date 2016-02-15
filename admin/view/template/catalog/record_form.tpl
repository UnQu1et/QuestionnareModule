<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>

<div style="margin:5px;">
<a href="<?php echo $url_blog; ?>" class="markbutton"><div style="float: left;"><img src="view/image/blog-icon-m.png" style="" ></div><div style="float: left; margin-left: 7px; margin-top: 3px; "><?php echo $url_blog_text; ?></div></a>
<a href="<?php echo $url_record; ?>" class="markbutton-active"><div style="float: left;"><img src="view/image/blog-rec-m.png"  style="" ></div><div style="float: left; margin-left: 7px; margin-top: 3px; "><?php echo $url_record_text; ?></div></a>
<a href="<?php echo $url_comment; ?>" class="markbutton"><div style="float: left;"><img src="view/image/blog-com-m.png"  style="" ></div><div style="float: left; margin-left: 7px; margin-top: 3px; "><?php echo $url_comment_text; ?></div></a>
<a href="<?php echo $url_back; ?>" class="markbutton"><div style="float: left;"><img src="view/image/blog-back-m.png"  style="" ></div><div style="float: left; margin-left: 7px; margin-top: 3px; "><?php echo $url_back_text; ?></div></a>
</div>



      <div class="buttons" style="float:right; clear: both;"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a>
      <a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a>
      </div>
      <div style="width: 100%; overflow: hidden; clear: both; height: 1px; line-height: 1px;">&nbsp;</div>


  <div class="box">
   <div class="content">
      <div id="tabs" class="htabs"><a href="#tab-general"><?php echo $tab_general; ?></a><a href="#tab-data"><?php echo $tab_data; ?></a><a href="#tab-links"><?php echo $tab_links; ?></a><a href="#tab-comments"><?php echo $url_comment_text; ?></a><a href="#tab-attribute"><?php echo $tab_attribute; ?></a><a href="#tab-image"><?php echo $tab_image; ?></a><a href="#tab-design"><?php echo $tab_design; ?></a></div>

      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general">
          <div id="languages" class="htabs">
            <?php foreach ($languages as $language) { ?>
            <a href="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
            <?php } ?>
          </div>
          <?php foreach ($languages as $language) { ?>
          <div id="language<?php echo $language['language_id']; ?>">
            <table class="form">
              <tr>
                <td><span class="required">*</span> <?php echo $entry_name; ?></td>
                <td><input type="text" class="record_name" name="record_description[<?php echo $language['language_id']; ?>][name]" size="100" value="<?php echo isset($record_description[$language['language_id']]) ? $record_description[$language['language_id']]['name'] : ''; ?>" />
                  <?php if (isset($error_name[$language['language_id']])) { ?>
                  <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
                  <?php } ?></td>
              </tr>

              <tr>
                <td><?php echo $this->language->get('entry_meta_title'); ?></td>
                <td><input type="text" name="record_description[<?php echo $language['language_id']; ?>][meta_title]" size="100" value="<?php echo isset($record_description[$language['language_id']]['meta_title']) ? $record_description[$language['language_id']]['meta_title'] : ''; ?>" /></td>
              </tr>

              <tr>
                <td><?php echo $this->language->get('entry_meta_h1'); ?></td>
                <td><input type="text" name="record_description[<?php echo $language['language_id']; ?>][meta_h1]" size="100" value="<?php echo isset($record_description[$language['language_id']]['meta_h1']) ? $record_description[$language['language_id']]['meta_h1'] : ''; ?>" /></td>
              </tr>


              <tr>
                <td><?php echo $entry_meta_description; ?></td>
                <td><textarea name="record_description[<?php echo $language['language_id']; ?>][meta_description]" cols="40" rows="5"><?php echo isset($record_description[$language['language_id']]) ? $record_description[$language['language_id']]['meta_description'] : ''; ?></textarea></td>
              </tr>
              <tr>
                <td><?php echo $entry_meta_keyword; ?></td>
                <td><textarea name="record_description[<?php echo $language['language_id']; ?>][meta_keyword]" cols="40" rows="5"><?php echo isset($record_description[$language['language_id']]) ? $record_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea></td>
              </tr>
              <tr>
                <td><?php echo $entry_sdescription; ?></td>
                <td><textarea name="record_description[<?php echo $language['language_id']; ?>][sdescription]" id="sdescription<?php echo $language['language_id']; ?>"><?php echo isset($record_description[$language['language_id']]) ? $record_description[$language['language_id']]['sdescription'] : ''; ?></textarea></td>
              </tr>
              <tr>
                <td><?php echo $entry_description; ?></td>
                <td><textarea name="record_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($record_description[$language['language_id']]) ? $record_description[$language['language_id']]['description'] : ''; ?></textarea></td>
              </tr>



              <tr>
                <td><?php echo $entry_tag; ?></td>
                <td><input type="text" name="record_tag[<?php echo $language['language_id']; ?>]" value="<?php echo isset($record_tag[$language['language_id']]) ? $record_tag[$language['language_id']] : ''; ?>" size="80" /></td>
              </tr>
            </table>
          </div>
          <?php } ?>
        </div>
        <div id="tab-data">
          <table class="form">
            <tr>
              <td><?php echo $entry_keyword; ?></td>
              <td><input type="text" id="record_seo_url" name="keyword" value="<?php echo $keyword; ?>" size="50"/></td>
            </tr>
            <tr>
              <td><?php echo $entry_image; ?></td>
              <td><div class="image"><img src="<?php echo $thumb; ?>" alt="" id="thumb" /><br />
                  <input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />
                  <a onclick="image_upload('image', 'thumb');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');"><?php echo $text_clear; ?></a></div></td>
            </tr>
            <tr>
              <td><?php echo $entry_date_available; ?></td>
              <td><input type="text" name="date_available" value="<?php echo $date_available; ?>" size="20" class="datetime" /></td>
            </tr>

            <tr>
              <td><?php echo $entry_date_end; ?></td>
              <td><input type="text" name="date_end" value="<?php echo $date_end; ?>" size="20" class="datetime" /></td>
            </tr>

            <tr>
                <td><?php echo $entry_customer_group_v; ?></td>
                <td>
                  <select name="customer_group_id">
                    <?php foreach ($customer_groups as $customer_group) { ?>
                    <?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </td>
             </tr>

              <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="status">
                  <?php if ($status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_sort_order; ?></td>
              <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="2" /></td>
            </tr>
          </table>
        </div>





        <div id="tab-comments">

          <table class="form">
          <tr>
              <td><?php echo $entry_comment_status; ?></td>
              <td><select name="comment[status]">
                  <?php if ($comment['status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>


            <tr>
              <td><?php echo $entry_comment_status_reg; ?></td>
              <td><select name="comment[status_reg]">
                  <?php if ($comment['status_reg']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>

            <tr>
              <td><?php echo $entry_comment_status_now; ?></td>
              <td><select name="comment[status_now]">
                  <?php if ($comment['status_now']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>

            <tr>
              <td><?php echo $this->language->get('entry_comment_rating'); ?></td>
              <td><select name="comment[rating]">
                  <?php if ($comment['rating']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>

            <tr>
              <td><?php echo $this->language->get('entry_comment_signer'); ?></td>
              <td><select name="comment[signer]">
                  <?php if ($comment['signer']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>



 	<tr>
 		<td>
			<?php echo $this->language->get('entry_order_comments'); ?>
		</td>
		<td>
         <select id="comment_order"  name="comment[order]">
           <option value="sort"  <?php if (isset($comment['order']) &&  $comment['order']=='sort')  { echo 'selected="selected"'; } ?>><?php echo $this->language->get('text_what_sort'); ?></option>
           <option value="date"  <?php if (isset( $comment['order']) &&  $comment['order']=='date')  { echo 'selected="selected"'; } ?>><?php echo $this->language->get('text_what_date'); ?></option>
           <option value="rating" <?php if (isset( $comment['order']) &&  $comment['order']=='rating') { echo 'selected="selected"'; } ?>><?php echo $this->language->get('text_what_rating'); ?></option>
            <option value="rate" <?php if (isset( $comment['order']) &&  $comment['order']=='rate') { echo 'selected="selected"'; } ?>><?php echo $this->language->get('text_what_rate'); ?></option>
         </select>
		</td>
	</tr>




 	<tr>
 		<td>
			<?php echo $this->language->get('entry_order_ad'); ?>
		</td>
		<td>
         <select id="comment_order_ad"  name="comment[order_ad]">
           <option value="desc"  <?php if (isset( $comment['order_ad']) &&  $comment['order_ad']=='desc') { echo 'selected="selected"'; } ?>><?php echo $this->language->get('text_what_desc'); ?></option>
           <option value="asc"   <?php if (isset( $comment['order_ad']) &&  $comment['order_ad']=='asc')  { echo 'selected="selected"'; } ?>><?php echo $this->language->get('text_what_asc'); ?></option>
        </select>
		</td>
	</tr>



		    <tr>
		     <td class="left"><?php echo $this->language->get('entry_comment_rating_num'); ?></td>
		     <td class="left">
		      <input type="text" name="comment[rating_num]" value="<?php  if (isset($comment['rating_num'])) echo $comment['rating_num']; ?>" size="3" />
		     </td>
		    </tr>

       <?php foreach ($languages as $language) {

              if (isset($record_description[$language['language_id']]['name']) && $record_description[$language['language_id']]['name']!='') {
            ?>

            <tr>
              <td>
              <?php echo  $language['name']; ?>&nbsp;&nbsp;<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
              </td>

              <td>
                           <a href="<?php echo $this->url->link('catalog/comment', 'token=' . $this->session->data['token'].'&filter_name='.$record_description[$language['language_id']]['name'], 'SSL');; ?>" style="position:relative" class="markbutton">

                           <div style="float: left; margin-left: 40px; margin-top: 3px; padding-bottom: 7px; "><?php echo $this->language->get('entry_comment_record'); ?>&nbsp;<ins style="text-decoration: none; font-size: 11px; color: #ACEEAD;">(<?php echo  $language['name']; ?>)</ins></div>
                             <div style="float: left; position: absolute; "><img src="view/image/blog-com-m.png"  style="" ></div>
                           </a>


              </td>

            </tr>
             <?php
               }
               }
             ?>





          </table>
         </div>




        <div id="tab-links">
          <table class="form">


            <tr>
              <td><?php echo $entry_blog; ?></td>
              <td><div class="scrollbox">
                  <?php $class = 'odd'; $i=0; ?>
                  <?php foreach ($categories as $blog) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">

					<?php if (in_array($blog['blog_id'], $record_blog)) {
						$endes ="";
						$check =' checked="checked" ';
					 } else {					    $endes =' disabled ';
					    $check ="";
					 } ?>

                    <?php if ($blog['blog_id']==$blog_main) { ?>
                     <input type="radio" id="blog_main_<?php echo $blog['blog_id']; ?>" name="blog_main" value="<?php echo $blog['blog_id']; ?>" checked="checked" />
                    <?php } else { ?>
                     <input type="radio" id="blog_main_<?php echo $blog['blog_id']; ?>" name="blog_main" value="<?php echo $blog['blog_id']; ?>" <?php echo $endes; ?> />
                    <?php } ?>

                    <input type="checkbox" class="record_blog" name="record_blog[<?php echo $i; ?>]" value="<?php echo $blog['blog_id']; ?>" <?php echo $check; ?> />


                    <?php echo $blog['name']; ?>





                  </div>
                  <?php
                   $i++;
                  } ?>
                </div>
                <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a></td>
            </tr>




            <tr>
              <td><?php echo $entry_store; ?></td>
              <td>

              <div class="scrollbox">
                  <?php $class = 'even'; ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array(0, $record_store)) { ?>
                    <input type="checkbox" name="record_store[]" value="0" checked="checked" />
                    <?php echo $text_default; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="record_store[]" value="0" />
                    <?php echo $text_default; ?>
                    <?php } ?>
                  </div>


                  <?php foreach ($stores as $store) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($store['store_id'], $record_store)) { ?>
                    <input type="checkbox" name="record_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                    <?php echo $store['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="record_store[]" value="<?php echo $store['store_id']; ?>" />
                    <?php echo $store['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div></td>
            </tr>


      	 <!--
      	 <tr>
              <td><?php echo $entry_download; ?></td>
              <td><div class="scrollbox">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($downloads as $download) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($download['download_id'], $record_download)) { ?>
                    <input type="checkbox" name="record_download[]" value="<?php echo $download['download_id']; ?>" checked="checked" />
                    <?php echo $download['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="record_download[]" value="<?php echo $download['download_id']; ?>" />
                    <?php echo $download['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div></td>
            </tr>
             -->
           <tr>
              <td><?php echo $this->language->get('entry_related_product'); ?></td>
              <td>
              <input type="text" name="prelated" id="product_related" class="related" value="" />
              <input type="hidden" name="phrelated" value="product">
              </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><div class="scrollbox" id="product-related">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($product_related as $product_related) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div id="product-related<?php echo $product_related['product_id']; ?>" class="<?php echo $class; ?>"> <?php echo $product_related['name']; ?><img src="view/image/delete.png" />
                    <input type="hidden" name="product_related[]" value="<?php echo $product_related['product_id']; ?>" />
                  </div>
                  <?php } ?>
                </div></td>
            </tr>


           <tr>
              <td><?php echo $this->language->get('entry_related_record'); ?></td>
              <td>
              <input type="text" name="related" id="record_related" class="related" value="" />
              <input type="hidden" name="rhrelated" value="record">
              </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><div class="scrollbox" id="record-related">
                  <?php $class = 'odd'; ?>
                  <?php if (isset($record_related)) { foreach ($record_related as $record_related) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div id="record-related<?php echo $record_related['record_id']; ?>" class="<?php echo $class; ?>"> <?php echo $record_related['name']; ?><img src="view/image/delete.png" />
                    <input type="hidden" name="record_related[]" value="<?php echo $record_related['record_id']; ?>" />
                  </div>
                  <?php } } ?>
                </div></td>
            </tr>







           <tr>
              <td><?php echo $this->language->get('entry_related_category'); ?></td>
              <td>
              <input type="text" name="crelated" id="blog_related" class="related" value="" />
              <input type="hidden" name="chrelated" value="category">
              </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><div class="scrollbox" id="category-related">
                  <?php $class = 'odd'; ?>
                  <?php if (isset($category_related)) { foreach ($category_related as $category_related) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div id="category-related<?php echo $category_related['category_id']; ?>" class="<?php echo $class; ?>"> <?php echo $category_related['name']; ?><img src="view/image/delete.png" />
                    <input type="hidden" name="category_related[]" value="<?php echo $category_related['category_id']; ?>" />
                  </div>
                  <?php } } ?>
                </div></td>
            </tr>




           <tr>
              <td><?php echo $this->language->get('entry_related_blog'); ?></td>
              <td>
              <input type="text" name="brelated" id="blog_related" class="related" value="" />
              <input type="hidden" name="bhrelated" value="blog">
              </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><div class="scrollbox" id="blog-related">
                  <?php $class = 'odd'; ?>
                  <?php if (isset($blog_related)) { foreach ($blog_related as $blog_related) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div id="blog-related<?php echo $blog_related['blog_id']; ?>" class="<?php echo $class; ?>"> <?php echo $blog_related['name']; ?><img src="view/image/delete.png" />
                    <input type="hidden" name="blog_related[]" value="<?php echo $blog_related['blog_id']; ?>" />
                  </div>
                  <?php } } ?>
                </div></td>
            </tr>






          </table>
        </div>
        <div id="tab-attribute">
          <table id="attribute" class="list">
            <thead>
              <tr>
                <td class="left"><?php echo $entry_attribute; ?></td>
                <td class="left"><?php echo $entry_text; ?></td>
                <td></td>
              </tr>
            </thead>
            <?php $attribute_row = 0; ?>
            <?php foreach ($record_attributes as $record_attribute) { ?>
            <tbody id="attribute-row<?php echo $attribute_row; ?>">
              <tr>
                <td class="left"><input type="text" name="record_attribute[<?php echo $attribute_row; ?>][name]" value="<?php echo $record_attribute['name']; ?>" />
                  <input type="hidden" name="record_attribute[<?php echo $attribute_row; ?>][attribute_id]" value="<?php echo $record_attribute['attribute_id']; ?>" /></td>
                <td class="left"><?php foreach ($languages as $language) { ?>
                  <textarea name="record_attribute[<?php echo $attribute_row; ?>][record_attribute_description][<?php echo $language['language_id']; ?>][text]" cols="40" rows="5"><?php echo isset($record_attribute['record_attribute_description'][$language['language_id']]) ? $record_attribute['record_attribute_description'][$language['language_id']]['text'] : ''; ?></textarea>
                  <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
                  <?php } ?></td>
                <td class="left"><a onclick="$('#attribute-row<?php echo $attribute_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
              </tr>
            </tbody>
            <?php $attribute_row++; ?>
            <?php } ?>
            <tfoot>
              <tr>
                <td colspan="2"></td>
                <td class="left"><a onclick="addAttribute();" class="button"><?php echo $button_add_attribute; ?></a></td>
              </tr>
            </tfoot>
          </table>
        </div>


       <div id="tab-image">
          <table id="images" class="list">
            <thead>
              <tr>
                <td class="left"><?php echo $entry_image; ?></td>
                <td><?php echo $this->language->get('entry_title'); ?></td>
                <td class="right"><?php echo $entry_sort_order; ?></td>
                <td></td>
              </tr>
            </thead>
            <?php $image_row = 0; ?>
            <?php foreach ($record_images as $record_image) { ?>
            <tbody id="image-row<?php echo $image_row; ?>">
              <tr>
                <td class="left"><div class="image"><img src="<?php echo $record_image['thumb']; ?>" alt="" id="thumb<?php echo $image_row; ?>" />
                    <input type="hidden" name="record_image[<?php echo $image_row; ?>][image]" value="<?php echo $record_image['image']; ?>" id="image<?php echo $image_row; ?>" />
                    <br />
                    <a onclick="image_upload('image<?php echo $image_row; ?>', 'thumb<?php echo $image_row; ?>');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb<?php echo $image_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $image_row; ?>').attr('value', '');"><?php echo $text_clear; ?></a></div></td>

					<td class="right">
					 <?php foreach ($languages as $language) { ?>

					<div style="margin-bottom: 3px;">
					<?php echo $this->language->get('entry_title'); ?>&nbsp;<input type="text" name="record_image[<?php echo $image_row; ?>][options][title][<?php echo $language['language_id']; ?>]" value="<?php if (isset($record_image['options']['title'][$language['language_id']])) echo $record_image['options']['title'][$language['language_id']]; ?>" style="width: 300px;"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" >
					</div>

					<div style="margin-bottom: 10px;">
					<?php echo $this->language->get('entry_description'); ?>&nbsp;<textarea name="record_image[<?php echo $image_row; ?>][options][description][<?php echo $language['language_id']; ?>]" style="width: 300px;"><?php if (isset($record_image['options']['description'][$language['language_id']])) echo $record_image['options']['description'][$language['language_id']]; ?></textarea><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" >
					</div>
                    <div style="border-bottom: 1px solid #DDD; height: 1px; line-height: 1px; width: 100%; margin-bottom: 5px;">&nbsp;</div>
					 <?php } ?>

      				<div style="margin-bottom: 3px;">
					<?php echo $this->language->get('entry_url'); ?>&nbsp;<input type="text" name="record_image[<?php echo $image_row; ?>][options][url]" value="<?php if (isset($record_image['options']['url'])) echo $record_image['options']['url']; ?>" style="width: 300px;">
					</div>
                     <div style="border-bottom: 1px solid #DDD; height: 1px; line-height: 1px; width: 100%; margin-bottom: 5px;">&nbsp;</div>
      				<div style="margin-bottom: 3px;">
					<?php echo $this->language->get('entry_reserved'); ?>&nbsp;<textarea name="record_image[<?php echo $image_row; ?>][options][reserved]" style="width: 300px;"><?php if (isset($record_image['options']['reserved'])) echo $record_image['options']['reserved']; ?></textarea>
					</div>

					</td>






                <td class="right"><input type="text" name="record_image[<?php echo $image_row; ?>][sort_order]" value="<?php echo $record_image['sort_order']; ?>" size="2" /></td>
                <td class="left"><a onclick="$('#image-row<?php echo $image_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
              </tr>
            </tbody>
            <?php $image_row++; ?>
            <?php } ?>
            <tfoot>
              <tr>
                <td colspan="3"></td>
                <td class="left"><a onclick="addImage();" class="button"><?php echo $button_add_image; ?></a></td>
              </tr>
            </tfoot>
          </table>
        </div>
       <div id="tab-design">
          <table class="list">
            <thead>
              <tr>
                <td class="left"><?php echo $entry_store; ?></td>
                <td class="left"><?php echo $entry_layout; ?></td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="left"><?php echo $text_default; ?></td>
                <td class="left"><select name="record_layout[0][layout_id]">
                    <option value=""></option>
                    <?php foreach ($layouts as $layout) { ?>
                    <?php if (isset($record_layout[0]) && $record_layout[0] == $layout['layout_id']) { ?>
                    <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
            </tbody>
            <?php foreach ($stores as $store) { ?>
            <tbody>
              <tr>
                <td class="left"><?php echo $store['name']; ?></td>
                <td class="left"><select name="record_layout[<?php echo $store['store_id']; ?>][layout_id]">
                    <option value=""></option>
                    <?php foreach ($layouts as $layout) { ?>
                    <?php if (isset($record_layout[$store['store_id']]) && $record_layout[$store['store_id']] == $layout['layout_id']) { ?>
                    <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
            </tbody>
            <?php } ?>
          </table>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
<?php foreach ($languages as $language) { ?>

CKEDITOR.replace('sdescription<?php echo $language['language_id']; ?>', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});

CKEDITOR.replace('description<?php echo $language['language_id']; ?>', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
<?php } ?>
</script>
<script>



$('.related').each(function() {
	var e = this;
	var this_pointer_name = $(e).next().attr('value');
	var this_pointer = this_pointer_name + '_id';

	$(e).autocomplete({
		delay: 0,

		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/record/autocomplete&pointer='+this_pointer+'&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
						label: item.name,
						value: eval('item.'+this_pointer)

						}
					}));
				}
			});

		},
		select: function(event, ui) {
			$('#'+this_pointer_name+'-related' + ui.item.value).remove();

			$('#'+this_pointer_name+'-related').append('<div id="'+this_pointer_name+'-related' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" /><input type="hidden" name="'+this_pointer_name+'_related[]" value="' + ui.item.value + '" /></div>');

			$('#'+this_pointer_name+'-related div:odd').attr('class', 'odd');
			$('#'+this_pointer_name+'-related div:even').attr('class', 'even');

			return false;
		}
	});

});

//*************************************************
$('input[name=\'prelated\']').autocomplete({
	delay: 0,
	source: function(request, response) {		//alert(request.id);
		$.ajax({
			url: 'index.php?route=catalog/record/pautocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.product_id
					}
				}));
			}
		});

	},
	select: function(event, ui) {
		$('#product-related' + ui.item.value).remove();

		$('#product-related').append('<div id="product-related' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" /><input type="hidden" name="product_related[]" value="' + ui.item.value + '" /></div>');

		$('#product-related div:odd').attr('class', 'odd');
		$('#product-related div:even').attr('class', 'even');

		return false;
	}
});
</script>

<script>
if ($.isFunction($.fn.on)) {

$(document).on('click', '#record-related div img', function() {
	$(this).parent().remove();

	$('#record-related div:odd').attr('class', 'odd');
	$('#record-related div:even').attr('class', 'even');
});

} else {
$('#record-related div img').live('click',  function() {
	$(this).parent().remove();

	$('#record-related div:odd').attr('class', 'odd');
	$('#record-related div:even').attr('class', 'even');
});
}
</script>

<script>
if ($.isFunction($.fn.on)) {

$(document).on('click', '#blog-related div img', function() {
	$(this).parent().remove();

	$('#blog-related div:odd').attr('class', 'odd');
	$('#blog-related div:even').attr('class', 'even');
});

} else {

$('#blog-related div img').live('click',  function() {
	$(this).parent().remove();

	$('#blog-related div:odd').attr('class', 'odd');
	$('#blog-related div:even').attr('class', 'even');
});
}
</script>


<script>
if ($.isFunction($.fn.on)) {

$(document).on('click', '#category-related div img', function() {
	$(this).parent().remove();

	$('#category-related div:odd').attr('class', 'odd');
	$('#category-related div:even').attr('class', 'even');
});

} else {

$('#category-related div img').live('click',  function() {
	$(this).parent().remove();

	$('#category-related div:odd').attr('class', 'odd');
	$('#category-related div:even').attr('class', 'even');
});
}
</script>



<script type="text/javascript">

if ($.isFunction($.fn.on)) {
$(document).on('click', '#product-related div img', function() {
	$(this).parent().remove();

	$('#product-related div:odd').attr('class', 'odd');
	$('#product-related div:even').attr('class', 'even');
});
} else {$('#product-related div img').live('click',  function() {
	$(this).parent().remove();

	$('#product-related div:odd').attr('class', 'odd');
	$('#product-related div:even').attr('class', 'even');
});

}
</script>


<script type="text/javascript">
var attribute_row = <?php echo $attribute_row; ?>;

function addAttribute() {
	html  = '<tbody id="attribute-row' + attribute_row + '">';
    html += '  <tr>';
	html += '    <td class="left"><input type="text" name="record_attribute[' + attribute_row + '][name]" value="" /><input type="hidden" name="record_attribute[' + attribute_row + '][attribute_id]" value="" /></td>';
	html += '    <td class="left">';
	<?php foreach ($languages as $language) { ?>
	html += '<textarea name="record_attribute[' + attribute_row + '][record_attribute_description][<?php echo $language['language_id']; ?>][text]" cols="40" rows="5"></textarea><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
    <?php } ?>
	html += '    </td>';
	html += '    <td class="left"><a onclick="$(\'#attribute-row' + attribute_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
    html += '  </tr>';
    html += '</tbody>';

	$('#attribute tfoot').before(html);

	attributeautocomplete(attribute_row);

	attribute_row++;
}

$.widget('custom.catcomplete', $.ui.autocomplete, {
	_renderMenu: function(ul, items) {
		var self = this, currentCategory = '';

		$.each(items, function(index, item) {
			if (item.blog != currentCategory) {
				ul.append('<li class="ui-autocomplete-blog">' + item.blog + '</li>');

				currentCategory = item.blog;
			}

			self._renderItem(ul, item);
		});
	}
});

function attributeautocomplete(attribute_row) {
	$('input[name=\'record_attribute[' + attribute_row + '][name]\']').catcomplete({
		delay: 0,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/attribute/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							blog: item.attribute_group,
							label: item.name,
							value: item.attribute_id
						}
					}));
				}
			});
		},
		select: function(event, ui) {
			$('input[name=\'record_attribute[' + attribute_row + '][name]\']').attr('value', ui.item.label);
			$('input[name=\'record_attribute[' + attribute_row + '][attribute_id]\']').attr('value', ui.item.value);

			return false;
		}
	});
}

$('#attribute tbody').each(function(index, element) {
	attributeautocomplete(index);
});
</script>
<script type="text/javascript">
var option_row =0;

$('input[name=\'option\']').catcomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/option/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						blog: item.blog,
						label: item.name,
						value: item.option_id,
						type: item.type
					}
				}));
			}
		});
	},
	select: function(event, ui) {
		html  = '<div id="tab-option-' + option_row + '" class="vtabs-content">';
		html += '	<input type="hidden" name="record_option[' + option_row + '][record_option_id]" value="" />';
		html += '	<input type="hidden" name="record_option[' + option_row + '][name]" value="' + ui.item.label + '" />';
		html += '	<input type="hidden" name="record_option[' + option_row + '][option_id]" value="' + ui.item.value + '" />';
		html += '	<input type="hidden" name="record_option[' + option_row + '][type]" value="' + ui.item.type + '" />';
		html += '	<table class="form">';
		html += '	  <tr>';
		html += '		<td><?php echo $entry_required; ?></td>';
		html += '       <td><select name="record_option[' + option_row + '][required]">';
		html += '	      <option value="1"><?php echo $text_yes; ?></option>';
		html += '	      <option value="0"><?php echo $text_no; ?></option>';
		html += '	    </select></td>';
		html += '     </tr>';

		if (ui.item.type == 'text') {
			html += '     <tr>';
			html += '       <td><?php echo $entry_option_value; ?></td>';
			html += '       <td><input type="text" name="record_option[' + option_row + '][option_value]" value="" /></td>';
			html += '     </tr>';
		}

		if (ui.item.type == 'textarea') {
			html += '     <tr>';
			html += '       <td><?php echo $entry_option_value; ?></td>';
			html += '       <td><textarea name="record_option[' + option_row + '][option_value]" cols="40" rows="5"></textarea></td>';
			html += '     </tr>';
		}

		if (ui.item.type == 'file') {
			html += '     <tr style="display: none;">';
			html += '       <td><?php echo $entry_option_value; ?></td>';
			html += '       <td><input type="text" name="record_option[' + option_row + '][option_value]" value="" /></td>';
			html += '     </tr>';
		}

		if (ui.item.type == 'date') {
			html += '     <tr>';
			html += '       <td><?php echo $entry_option_value; ?></td>';
			html += '       <td><input type="text" name="record_option[' + option_row + '][option_value]" value="" class="date" /></td>';
			html += '     </tr>';
		}

		if (ui.item.type == 'datetime') {
			html += '     <tr>';
			html += '       <td><?php echo $entry_option_value; ?></td>';
			html += '       <td><input type="text" name="record_option[' + option_row + '][option_value]" value="" class="datetime" /></td>';
			html += '     </tr>';
		}

		if (ui.item.type == 'time') {
			html += '     <tr>';
			html += '       <td><?php echo $entry_option_value; ?></td>';
			html += '       <td><input type="text" name="record_option[' + option_row + '][option_value]" value="" class="time" /></td>';
			html += '     </tr>';
		}

		html += '  </table>';

		if (ui.item.type == 'select' || ui.item.type == 'radio' || ui.item.type == 'checkbox' || ui.item.type == 'image') {
			html += '  <table id="option-value' + option_row + '" class="list">';
			html += '  	 <thead>';
			html += '      <tr>';
			html += '        <td class="left"><?php echo $entry_option_value; ?></td>';
			html += '        <td class="left"><?php echo $entry_subtract; ?></td>';
			html += '        <td class="right"><?php echo $entry_option_points; ?></td>';
			html += '        <td class="right"><?php echo $entry_weight; ?></td>';
			html += '        <td></td>';
			html += '      </tr>';
			html += '  	 </thead>';
			html += '    <tfoot>';
			html += '      <tr>';
			html += '        <td colspan="6"></td>';
			html += '        <td class="left"><a onclick="addOptionValue(' + option_row + ');" class="button"><?php echo $button_add_option_value; ?></a></td>';
			html += '      </tr>';
			html += '    </tfoot>';
			html += '  </table>';
			html += '</div>';
		}

		$('#tab-option').append(html);

		$('#option-add').before('<a href="#tab-option-' + option_row + '" id="option-' + option_row + '">' + ui.item.label + '&nbsp;<img src="view/image/delete.png" alt="" onclick="$(\'#vtab-option a:first\').trigger(\'click\'); $(\'#option-' + option_row + '\').remove(); $(\'#tab-option-' + option_row + '\').remove(); return false;" /></a>');

		$('#vtab-option a').tabs();

		$('#option-' + option_row).trigger('click');

		$('.date').datepicker({dateFormat: 'yy-mm-dd'});
		$('.datetime').datetimepicker({
			dateFormat: 'yy-mm-dd',
			timeFormat: 'h:m'
		});

		$('.time').timepicker({timeFormat: 'h:m'});

		option_row++;

		return false;
	}
});
</script>
<script type="text/javascript">
var option_value_row = 0;

function addOptionValue(option_row) {
	html  = '<tbody id="option-value-row' + option_value_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><select name="record_option[' + option_row + '][record_option_value][' + option_value_row + '][option_value_id]"></select><input type="hidden" name="record_option[' + option_row + '][record_option_value][' + option_value_row + '][record_option_value_id]" value="" /></td>';
	html += '    <td class="right"><input type="text" name="record_option[' + option_row + '][record_option_value][' + option_value_row + '][quantity]" value="" size="3" /></td>';
	html += '    <td class="left"><select name="record_option[' + option_row + '][record_option_value][' + option_value_row + '][subtract]">';
	html += '      <option value="1"><?php echo $text_yes; ?></option>';
	html += '      <option value="0"><?php echo $text_no; ?></option>';
	html += '    </select></td>';
	html += '    <td class="right"><select name="record_option[' + option_row + '][record_option_value][' + option_value_row + '][price_prefix]">';
	html += '      <option value="+">+</option>';
	html += '      <option value="-">-</option>';
	html += '    </select>';
	html += '    <input type="text" name="record_option[' + option_row + '][record_option_value][' + option_value_row + '][price]" value="" size="5" /></td>';
	html += '    <td class="right"><select name="record_option[' + option_row + '][record_option_value][' + option_value_row + '][points_prefix]">';
	html += '      <option value="+">+</option>';
	html += '      <option value="-">-</option>';
	html += '    </select>';
	html += '    <input type="text" name="record_option[' + option_row + '][record_option_value][' + option_value_row + '][points]" value="" size="5" /></td>';
	html += '    <td class="right"><select name="record_option[' + option_row + '][record_option_value][' + option_value_row + '][weight_prefix]">';
	html += '      <option value="+">+</option>';
	html += '      <option value="-">-</option>';
	html += '    </select>';
	html += '    <input type="text" name="record_option[' + option_row + '][record_option_value][' + option_value_row + '][weight]" value="" size="5" /></td>';
	html += '    <td class="left"><a onclick="$(\'#option-value-row' + option_value_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';

	$('#option-value' + option_row + ' tfoot').before(html);

	$('select[name=\'record_option[' + option_row + '][record_option_value][' + option_value_row + '][option_value_id]\']').load('index.php?route=catalog/record/option&token=<?php echo $token; ?>&option_id=' + $('input[name=\'record_option[' + option_row + '][option_id]\']').attr('value'));

	option_value_row++;
}
</script>


<script type="text/javascript">
function image_upload(field, thumb) {
	$('#dialog').remove();

	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
					dataType: 'text',
					success: function(text) {
						$('#' + thumb).replaceWith('<img src="' + text + '" alt="" id="' + thumb + '" />');
					}
				});
			}
		},
		bgiframe: false,
		width: 800,
		height: 400,
		resizable: false,
		modal: false
	});
};
</script>
<script type="text/javascript">
var image_row = <?php echo $image_row; ?>;

function addImage() {
    html  = '<tbody id="image-row' + image_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><div class="image"><img src="<?php echo $no_image; ?>" alt="" id="thumb' + image_row + '" /><input type="hidden" name="record_image[' + image_row + '][image]" value="" id="image' + image_row + '" /><br /><a onclick="image_upload(\'image' + image_row + '\', \'thumb' + image_row + '\');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#thumb' + image_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + image_row + '\').attr(\'value\', \'\');"><?php echo $text_clear; ?></a></div></td>';
	html += '				<td class="right">';
						 <?php foreach ($languages as $language) { ?>

	html += '				<div style="margin-bottom: 3px;">';
	html += '				<?php echo $this->language->get('entry_title'); ?>&nbsp;<input type="text" name="record_image[' + image_row  + '][options][title][<?php echo $language['language_id']; ?>]" value="" style="width: 300px;"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" >';
	html += '				</div>';

	html += '				<div style="margin-bottom: 10px;">';
	html += '				<?php echo $this->language->get('entry_description'); ?>&nbsp;<textarea name="record_image[' + image_row  + '][options][description][<?php echo $language['language_id']; ?>]" style="width: 300px;"></textarea><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" >';
	html += '				</div>';
    html += '                    <div style="border-bottom: 1px solid #DDD; height: 1px; line-height: 1px; width: 100%; margin-bottom: 5px;">&nbsp;</div>';

					   <?php } ?>
    html += '  				<div style="margin-bottom: 3px;">';
	html += '				<?php echo $this->language->get('entry_url'); ?>&nbsp;<input type="text" name="record_image[' + image_row  + '][options][url]" value="" style="width: 300px;">';
	html += '				</div>';
	html += '					 <div style="border-bottom: 1px solid #DDD; height: 1px; line-height: 1px; width: 100%; margin-bottom: 5px;">&nbsp;</div>';

    html += '                <div style="margin-bottom: 3px;">';
	html += '				<?php echo $this->language->get('entry_reserved'); ?>&nbsp;<textarea name="record_image[' + image_row  + '][options][reserved]" style="width: 300px;"></textarea>';
	html += '				</div>';


	html += '				</td>';
	html += '    <td class="right"><input type="text" name="record_image[' + image_row + '][sort_order]" value="" /></td>';
	html += '    <td class="left"><a onclick="$(\'#image-row' + image_row  + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';

	$('#images tfoot').before(html);

	image_row++;
}
</script>

<script type="text/javascript">
$('#tabs a').tabs();
$('#languages a').tabs();
$('#vtab-option a').tabs();
</script>

<script>
$('.record_blog').click(function() {

var c = this.checked;
var v = $(this).val();

if (c) {    $('#blog_main_'+v).removeAttr("disabled");
} else {	$('#blog_main_'+v).attr("disabled", "disabled");

	$('#blog_main_'+v).prop('checked',false);
}



});

</script>

<script type="text/javascript" src="view/javascript/blog/timepicker/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="view/javascript/blog/timepicker/localization/jquery-ui-timepicker-<?php echo $config_language; ?>.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('.datetime').datetimepicker({
		dateFormat: 'yy-mm-dd',
		timeFormat: 'HH:mm:ss'
	});
});
</script>

<script type="text/javascript" src="view/javascript/blog/synctranslit/jquery.synctranslit.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	if ($('#record_seo_url').val()=='') {
			$(".record_name").syncTranslit({destination: "record_seo_url"});
		}
});
</script>


<?php echo $footer; ?>