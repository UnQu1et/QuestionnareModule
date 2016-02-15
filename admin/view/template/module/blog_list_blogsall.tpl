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
          <td class="left"><?php echo $entry_avatar_dim; ?></td>
     <td class="left">
      <input type="text" name="mylist[<?php echo $list_num; ?>][avatar][width]" value="<?php  if (isset($list['avatar']['width'])) echo $list['avatar']['width']; ?>" size="3" />x
      <input type="text" name="mylist[<?php echo $list_num; ?>][avatar][height]" value="<?php if (isset($list['avatar']['height'])) echo $list['avatar']['height']; ?>" size="3" />
     </td>
    </tr>
	<tr>
			<td>
			<?php echo $this->language->get('entry_template'); ?>

		</td>

			<td>
				<input type="text" class="template"  name="mylist[<?php echo $list_num; ?>][template]" value="<?php if (isset($list['template'])) echo $list['template']; ?>" size="60" />
			<input type="hidden" name="tpath" value="widgets/blogs">
			</td>

	</tr>
		    <tr>
		     <td class="left"><?php echo $this->language->get('entry_anchor'); ?></td>
		     <td class="left">
		      <textarea style="width: 96%;"  name="mylist[<?php echo $list_num; ?>][anchor]"><?php  if (isset($list['anchor'])) echo $list['anchor']; ?></textarea>
		     </td>
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
          <tr>
              <td><?php echo $this->language->get('entry_widget_counting'); ?></td>
              <td><select name="mylist[<?php echo $list_num; ?>][counting]">
                  <?php if (isset($list['counting']) && $list['counting']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>




</table>
</div>
  <?php }

//echo $this->request->post['list']."<br>";
  ?>
