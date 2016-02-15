<?php
foreach ($mylist as $list_num=>$list) { ?>
<div id="list<?php echo $list_num;?>" style="padding-left: 200px;">



  <input type="hidden" name="mylist[<?php echo $list_num; ?>][type]" value="<?php if (isset($list['type'])) echo $list['type']; else echo 'blogs'; ?>">


<table>
    <tr>

    <td colspan="2">
	 <div class="buttons">
	 <a onclick="mylist_num--; $('#amytabs<?php echo $list_num;?>').remove(); $('#mytabs<?php echo $list_num;?>').remove(); $('#mytabs a').tabs(); return false; " class="mbuttonr"><?php echo $this->language->get('button_remove'); ?></a>
<a onclick="
      mylist_num++;
      type_what = '<?php echo $list['type']; ?>';
 		$.ajax({
					url: 'index.php?route=module/blog/ajax_list&token=<?php echo $token; ?>',
					type: 'post',
					data: { type: type_what, list: '<?php echo base64_encode($slist); ?>', num: mylist_num },
					dataType: 'html',
					beforeSend: function()
					{

					},
					success: function(html) {
						if (html) {
							$('#mytabs').append('<a href=\'#mytabs' + mylist_num + '\' id=\'amytabs'+mylist_num+'\'>List-' + mylist_num + '<\/a>');
							$('#lists').append('<div id=\'mytabs'+mylist_num+'\'>'+html+'<\/div>');
							$('#mytabs a').tabs();
							$('#amytabs' + mylist_num).click();
							template_auto();
						}
						$('.mbutton').removeClass('loader');


					}
				});


      return false; " class="mbutton"><?php echo $this->language->get('button_clone_widget'); ?>: <?php echo $this->language->get('text_widget_'.$list['type']); ?></a>

	 </div>

    </td>
    </tr>	 <?php foreach ($languages as $language) { ?>
	<tr>
			<td>
			<?php echo $this->language->get('entry_title_list_latest'); ?> (<?php echo  ($language['name']); ?>)

		</td>

			<td>

				<input type="text" name="mylist[<?php echo $list_num; ?>][title_list_latest][<?php echo $language['language_id']; ?>]" value="<?php if (isset($list['title_list_latest'][$language['language_id']])) echo $list['title_list_latest'][$language['language_id']]; ?>" size="60" /><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
			</td>

	</tr>
   <?php } ?>


		    <tr>
		     <td class="left"><?php echo $this->language->get('entry_anchor'); ?></td>
		     <td class="left">
		      <textarea style="width: 96%;" name="mylist[<?php echo $list_num; ?>][anchor]"><?php  if (isset($list['anchor'])) echo $list['anchor']; ?></textarea>
		     </td>
		    </tr>


    <tr>
     <td class="left"><?php echo $entry_avatar_dim; ?></td>
     <td class="left">
      <input type="text" name="mylist[<?php echo $list_num; ?>][avatar][width]" value="<?php  if (isset($list['avatar']['width'])) echo $list['avatar']['width']; ?>" size="3" />x
      <input type="text" name="mylist[<?php echo $list_num; ?>][avatar][height]" value="<?php if (isset($list['avatar']['height'])) echo $list['avatar']['height']; ?>" size="3" />
     </td>
    </tr>
	<tr>

    <tr>
     <td class="left"><?php echo $this->language->get('entry_images_dim'); ?></td>
     <td class="left">
      <input type="text" name="mylist[<?php echo $list_num; ?>][images][width]" value="<?php  if (isset($list['images']['width'])) echo $list['images']['width']; ?>" size="3" />x
      <input type="text" name="mylist[<?php echo $list_num; ?>][images][height]" value="<?php if (isset($list['images']['height'])) echo $list['images']['height']; ?>" size="3" />
     </td>
    </tr>
	<tr>

          <tr>
              <td><?php echo $this->language->get('entry_images_view'); ?></td>
              <td><select name="mylist[<?php echo $list_num; ?>][images_view]">
                  <?php if (isset( $list['images_view']) && $list['images_view']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>


          <tr>
              <td><?php echo $this->language->get('entry_widget_cached'); ?></td>
              <td><select name="mylist[<?php echo $list_num; ?>][cached]">
                  <?php if (isset($list['cached']) && $list['cached']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>




			<td>
			<?php echo $this->language->get('entry_template'); ?>

		</td>

			<td>
				<input type="text" class="template" name="mylist[<?php echo $list_num; ?>][template]" value="<?php if (isset($list['template'])) echo $list['template']; ?>" size="60" />
				<input type="hidden" name="tpath" value="widgets/records">
			</td>

	</tr>


    <tr>
     <td class="left"><?php echo $this->language->get('entry_blog_num_desc'); ?></td>
     <td class="left">
      <input type="text" name="mylist[<?php echo $list_num; ?>][desc_symbols]" value="<?php  if (isset( $list['desc_symbols'])) echo $list['desc_symbols']; ?>" size="3" />
     </td>
    </tr>

    <tr>
     <td class="left"><?php echo $this->language->get('entry_blog_num_desc_words'); ?></td>
     <td class="left">
      <input type="text" name="mylist[<?php echo $list_num; ?>][desc_words]" value="<?php  if (isset( $list['desc_words'])) echo $list['desc_words']; ?>" size="3" />
     </td>
    </tr>

    <tr>
     <td class="left"><?php echo $this->language->get('entry_blog_num_desc_pred'); ?></td>
     <td class="left">
      <input type="text" name="mylist[<?php echo $list_num; ?>][desc_pred]" value="<?php  if (isset( $list['desc_pred'])) echo $list['desc_pred']; ?>" size="3" />
     </td>
    </tr>

          <tr>
              <td><?php echo $this->language->get('entry_avatar_status'); ?></td>
              <td><select name="mylist[<?php echo $list_num; ?>][avatar_status]">
                  <?php if (isset($list['avatar_status']) && $list['avatar_status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>

          <tr>
              <td><?php echo $this->language->get('entry_description_status'); ?></td>
              <td><select name="mylist[<?php echo $list_num; ?>][description_status]">
                  <?php if (isset($list['description_status']) && $list['description_status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>


          <tr>
              <td><?php echo $this->language->get('entry_category_status'); ?></td>
              <td><select name="mylist[<?php echo $list_num; ?>][category_status]">
                  <?php if (isset($list['category_status']) && $list['category_status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>


           <tr>
              <td><div><?php echo $this->language->get('entry_records'); ?></div></td>
              <td><input type="text" name="mylist[<?php echo $list_num; ?>][related]" value="" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><div class="scrollbox" id="record-related_<?php echo $list_num; ?>">
                  <?php $class = 'odd'; ?>
                   <?php
                  foreach ($related as $nm=>$record_related) {
                  ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div id="record-related_<?php echo $list_num; ?>_<?php echo $record_related['record_id']; ?>" class="<?php echo $class; ?>"> <?php echo $record_related['name']; ?><img src="view/image/delete.png" />
                    <input type="hidden" name="mylist[<?php echo $list_num; ?>][related][]" value="<?php echo $record_related['record_id']; ?>" />
                  </div>
                  <?php } ?>
                </div></td>
            </tr>

            		    <tr>
		     <td class="left"><?php echo $this->language->get('entry_reserved'); ?></td>
		     <td class="left">
		      <textarea style="width: 96%;" name="mylist[<?php echo $list_num; ?>][reserved]"><?php  if (isset($list['reserved'])) echo $list['reserved']; ?></textarea>
		     </td>
		    </tr>




</table>
</div>
  <?php } ?>
</div>

<script type="text/javascript">
$(document).ready(function () {

$('input[name=\'mylist[<?php echo $list_num; ?>][related]\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/record/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {

					return {
						label: item.name,
						value: item.record_id
					}
				}));
			}
		});

	},
	select: function(event, ui) {

		$('#record-related_<?php echo $list_num; ?>_' + ui.item.value).remove();

		$('#record-related_<?php echo $list_num; ?>').append('<div id=\"record-related_<?php echo $list_num; ?>_' + ui.item.value + '\">' + ui.item.label + '<img src=\"view\/image\/delete.png\" ><input type=\"hidden\" name=\"mylist[<?php echo $list_num; ?>][related][]\" value=\"' + ui.item.value + '\" ><\/div>');

		$('#record-related_<?php echo $list_num; ?> div:odd').attr('class', 'odd');
		$('#record-related_<?php echo $list_num; ?> div:even').attr('class', 'even');

		return false;
	}
});


if ($.isFunction($.fn.on)) {
$(document).on('click', '#record-related_<?php echo $list_num; ?> div img', function() {
	$(this).parent().remove();

	$('#record-related_<?php echo $list_num; ?> div:odd').attr('class', 'odd');
	$('#record-related_<?php echo $list_num; ?> div:even').attr('class', 'even');
});
} else {
$('#record-related_<?php echo $list_num; ?> div img').live('click',  function() {
	$(this).parent().remove();

	$('#record-related_<?php echo $list_num; ?> div:odd').attr('class', 'odd');
	$('#record-related_<?php echo $list_num; ?> div:even').attr('class', 'even');
});

}




});
</script>
