<?php
foreach ($mylist as $list_num=>$list) { ?>
<div id="list<?php echo $list_num;?>"  style="padding-left: 200px;">

  <input type="hidden" name="mylist[<?php echo $list_num; ?>][type]" value="<?php if (isset($list['type'])) echo $list['type']; else echo 'blogs'; ?>">


<table style="width: 90%;">
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
			<div style="float: left;">
				<input type="text" name="mylist[<?php echo $list_num; ?>][title_list_latest][<?php echo $language['language_id']; ?>]" value="<?php if (isset($list['title_list_latest'][$language['language_id']])) echo $list['title_list_latest'][$language['language_id']]; ?>" size="60" />
				</div>
				<div style="float: left; margin-left: 3px;">
				<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
				</div>
			</td>

	</tr>
   <?php } ?>


	<tr>
			<td>
			<?php echo $this->language->get('entry_template'); ?>

		</td>

			<td>
				<input type="text" class="template" name="mylist[<?php echo $list_num; ?>][template]" value="<?php if (isset($list['template'])) echo $list['template']; ?>" size="60" />
				<input type="hidden" name="tpath" value="widgets/html">
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

	 <?php foreach ($languages as $language) { ?>
	<tr>
			<td>
			<?php echo $this->language->get('entry_html'); ?>

		</td>

			<td>
				<div style="float: left; width: 90%;">
				<textarea id="html_<?php echo $list_num; ?>_<?php echo $language['language_id']; ?>" name="mylist[<?php echo $list_num; ?>][html][<?php echo $language['language_id']; ?>]" rows="16" style="width: 100%;" ><?php if (isset($list['html'][$language['language_id']])) echo $list['html'][$language['language_id']]; ?></textarea>
				<br>
			(<a href="" onclick="load_editor('html_<?php echo $list_num; ?>_<?php echo $language['language_id']; ?>', '100'); return false;"><?php echo $this->language->get('entry_editor'); ?></a>)

				</div>
				<div style="float: left; margin-left: 3px;">
				<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" ><br>
               </div>
			</td>

	</tr>
   <?php } ?>

</table>
</div>


  <?php }  ?>

<script language="javascript">
var myEditor = new Array();

function load_editor(idName, idHeight) {
	if (!myEditor[idName]) {
	    CKEDITOR.remove(idName);
		var html = $('#'+idName).html();
		var config = {
						filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
						filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
						filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
						filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
						filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
						filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
						enterMode 	: CKEDITOR.ENTER_BR,
						entities 	: false,
						htmlEncodeOutput : false


					};


		myEditor[idName] = CKEDITOR.replace(idName, config, html );

		CKEDITOR.remove(myEditor[idName]);
	} 	else {
		$('#'+idName).html(myEditor[idName].getData());
		// Destroy editor
		myEditor[idName].destroy();
		myEditor[idName] = null;
	}
}
</script>

</div>
