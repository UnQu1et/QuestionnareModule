<?php
foreach ($mylist as $list_num=>$list) {
/*
print_r("<PRE>");
print_r($mylist);
print_r("</PRE>");
 */
?>
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
    </tr>

	 <?php foreach ($languages as $language) { ?>
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
	<td>
			<?php echo $this->language->get('entry_template_comments'); ?>

		</td>

			<td>
				<input type="text" class="template" name="mylist[<?php echo $list_num; ?>][template]" value="<?php if (isset($list['template'])) echo $list['template']; ?>" size="60" />
				<input type="hidden" name="tpath" value="widgets/treecomments/treecomments">
			</td>

	</tr>


	<tr>
	<td>
			<?php echo $this->language->get('entry_template_comment'); ?>

		</td>

			<td>
				<input type="text" class="template" name="mylist[<?php echo $list_num; ?>][blog_template_comment]" value="<?php if (isset($list['blog_template_comment'])) echo $list['blog_template_comment']; ?>" size="60" />
				<input type="hidden" name="tpath" value="widgets/treecomments/treecomment">
			</td>

	</tr>



		    <tr>
		     <td class="left"><?php echo $this->language->get('entry_langfile'); ?></td>
		     <td class="left">
		      <input type="text" name="mylist[<?php echo $list_num; ?>][langfile]" value="<?php  if (isset($list['langfile'])) echo $list['langfile']; ?>" size="50" />
		     </td>
		    </tr>



		    <tr>
		     <td class="left"><?php echo $this->language->get('entry_anchor'); ?></td>
		     <td class="left">
		      <textarea style="width: 96%;" name="mylist[<?php echo $list_num; ?>][anchor]"><?php  if (isset($list['anchor'])) echo $list['anchor']; ?></textarea>
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
			<td>
			<?php echo $this->language->get('entry_blog_num_comments'); ?>

		</td>

			<td>
				<input type="text" name="mylist[<?php echo $list_num; ?>][number_comments]" value="<?php  if (isset( $list['number_comments'])) echo $list['number_comments']; ?>" size="3" />
			</td>

	</tr>


            <tr>
              <td><?php echo $this->language->get('entry_comment_status'); ?></td>
              <td><select name="mylist[<?php echo $list_num; ?>][status]">
                  <?php if (isset($list['status']) && $list['status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>




            <tr>
              <td><?php echo $this->language->get('entry_comment_status_reg'); ?></td>
              <td><select name="mylist[<?php echo $list_num; ?>][status_reg]">
                  <?php if (isset( $list['status_reg']) &&  $list['status_reg']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>

            <tr>
              <td><?php echo $this->language->get('entry_comment_status_now'); ?></td>
              <td><select name="mylist[<?php echo $list_num; ?>][status_now]">
                  <?php if (isset($list['status_now']) && $list['status_now']) { ?>
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
				<?php echo $this->language->get('entry_comments_email'); ?>

				</td>

				<td>
					<input type="text" name="mylist[<?php echo $list_num; ?>][comments_email]" value="<?php  if (isset( $list['comments_email'])) echo $list['comments_email']; ?>" style="width: 90%;" />
				</td>

			</tr>


            <tr>
              <td><?php echo $this->language->get('entry_karma'); ?></td>
              <td><select name="mylist[<?php echo $list_num; ?>][karma]">
                  <?php if (isset($list['karma']) && $list['karma']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>

            <tr>
              <td><?php echo $this->language->get('entry_karma_reg'); ?></td>
              <td><select name="mylist[<?php echo $list_num; ?>][karma_reg]">
                  <?php if (isset($list['karma_reg']) && $list['karma_reg']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>

		    <tr>
		     <td class="left"><?php echo $this->language->get('entry_comment_rating_num'); ?></td>
		     <td class="left">
		      <input type="text" name="mylist[<?php echo $list_num; ?>][rating_num]" value="<?php  if (isset($list['rating_num'])) echo $list['rating_num']; ?>" size="3" />
		     </td>
		    </tr>


          <tr>
              <td><?php echo $this->language->get('entry_comment_must'); ?></td>
              <td><select name="mylist[<?php echo $list_num; ?>][comment_must]">
                  <?php if (isset($list['comment_must']) && $list['comment_must'] || !isset($list['comment_must'])) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>





          <tr>
              <td><?php echo $this->language->get('entry_rating'); ?></td>
              <td><select name="mylist[<?php echo $list_num; ?>][rating]">
                  <?php if (isset($list['rating']) && $list['rating']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>

          <tr>
              <td><?php echo $this->language->get('entry_rating_must'); ?></td>
              <td><select name="mylist[<?php echo $list_num; ?>][rating_must]">
                  <?php if (isset($list['rating_must']) && $list['rating_must']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>


          <tr>
              <td><?php echo $this->language->get('entry_visual_rating'); ?></td>
              <td><select name="mylist[<?php echo $list_num; ?>][visual_rating]">
                  <?php if (isset($list['visual_rating']) && $list['visual_rating']) { ?>
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
			<?php echo $this->language->get('entry_order'); ?>
		</td>
		<td>
         <select id="mylist_<?php echo $list_num; ?>_order"  name="mylist[<?php echo $list_num; ?>][order]">
           <option value="sort"  <?php if (isset($list['order']) &&  $list['order']=='sort')  { echo 'selected="selected"'; } ?>><?php echo $this->language->get('text_what_sort'); ?></option>
           <option value="date"  <?php if (isset( $list['order']) &&  $list['order']=='date')  { echo 'selected="selected"'; } ?>><?php echo $this->language->get('text_what_date'); ?></option>
           <option value="rating" <?php if (isset( $list['order']) &&  $list['order']=='rating') { echo 'selected="selected"'; } ?>><?php echo $this->language->get('text_what_rating'); ?></option>
            <option value="rate" <?php if (isset( $list['order']) &&  $list['order']=='rate') { echo 'selected="selected"'; } ?>><?php echo $this->language->get('text_what_rate'); ?></option>
         </select>
		</td>
	</tr>




	<tr>
 		<td>
			<?php echo $this->language->get('entry_order_ad'); ?>
		</td>
		<td>
         <select id="mylist_<?php echo $list_num; ?>_order_ad"  name="mylist[<?php echo $list_num; ?>][order_ad]">
           <option value="desc"  <?php if (isset( $list['order_ad']) &&  $list['order_ad']=='desc') { echo 'selected="selected"'; } ?>><?php echo $this->language->get('text_what_desc'); ?></option>
           <option value="asc"   <?php if (isset( $list['order_ad']) &&  $list['order_ad']=='asc')  { echo 'selected="selected"'; } ?>><?php echo $this->language->get('text_what_asc'); ?></option>
        </select>
		</td>
      </tr>



            <tr>
              <td><?php echo $this->language->get('entry_fields_view'); ?></td>
              <td><select name="mylist[<?php echo $list_num; ?>][fields_view]">
                  <?php if (isset($list['fields_view']) && $list['fields_view']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>

            <tr>
              <td><?php echo $this->language->get('entry_view_captcha'); ?></td>
              <td><select name="mylist[<?php echo $list_num; ?>][view_captcha]">
                  <?php if (isset($list['view_captcha']) && $list['view_captcha']) { ?>
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
              <td><select name="mylist[<?php echo $list_num; ?>][signer]">
                  <?php if (isset($list['signer']) && $list['signer'] || !isset($list['signer'])) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>



          <tr>
              <td><?php echo $this->language->get('entry_visual_editor'); ?></td>
              <td><select name="mylist[<?php echo $list_num; ?>][visual_editor]">
                  <?php if (isset($list['visual_editor']) && $list['visual_editor']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>


		    <tr>
		     <td class="left"><?php echo $this->language->get('entry_bbwidth'); ?></td>
		     <td class="left">
		      <input type="text" name="mylist[<?php echo $list_num; ?>][bbwidth]" value="<?php if (isset($list['bbwidth'])) echo $list['bbwidth']; ?>" size="3" />
		      </td>
		    </tr>


		    <tr>
		     <td class="left"><?php echo $this->language->get('entry_record'); ?></td>
		     <td class="left">
			  <input type="text" name="mylist[<?php echo $list_num; ?>][record]" value="<?php if (isset($list['record'])) echo $list['record']; ?>" style="width: 80%;">
			  <input type="text" name="mylist[<?php echo $list_num; ?>][recordid]" value="<?php if (isset($list['recordid']) ) echo $list['recordid']; ?>" style="width: 10%;">
		      </td>
		    </tr>





       <tr>
       <td colspan="2">


   <table class="mytable" id="table_fields_<?php echo $list_num;?>" >
     <thead>
      <tr>
      <td class="left" style=""><div style="width: 100%"><?php echo $this->language->get('entry_name_field'); ?></div></td>
       <td class="left" style=""><div style="width: 100%"><?php echo $this->language->get('entry_title_list_latest'); ?></div></td>
       <td class="right" style=""><div style="width: 100%"><?php echo $this->language->get('text_sort_order'); ?></div></td>
       <td style="width: 200px;"><?php echo $this->language->get('text_action'); ?></td>
      </tr>

     </thead>
        <?php
          $fields_row = 0;
        ?>

      <?php



       if (isset($list['addfields']) && !empty($list['addfields'])) {
        foreach ($list['addfields'] as $num_field => $field) {

         while (!isset($list['addfields'][$fields_row])) {
          $fields_row++;
         }

        if (isset($field['name']) &&  $field['name']!='') {
        	$field['field_name'] = $field['name'];
        }

        if (isset($field['sort_order']) &&  $field['sort_order']!='') {
        	$field['field_order'] =$field['sort_order'];
        }

        if (isset($field['title']) &&  $field['title']!='') {
        	$field['field_description'] =$field['title'];
        }

        ?>
          <tr id="field-row-<?php echo $list_num;?>-<?php echo $num_field; ?>">

            <td class="left">
            <input type="text" class="fields" name="mylist[<?php echo $list_num; ?>][addfields][<?php echo $num_field; ?>][field_name]" value="<?php echo $field['field_name']; ?>" size="10" />
            </td>

            <td class="left" > <!-- <?php echo $num_field; ?>&nbsp; -->
	 		<?php foreach ($languages as $language) { ?>

                <div style=" overflow: hidden;">
				<div style="float: left; font-size: 11px; width: 77px; text-decoration: none;"><?php echo  ($language['name']); ?></div>
				<div style="float: left;">
					<input type="text" name="mylist[<?php echo $list_num; ?>][addfields][<?php echo $num_field; ?>][field_description][<?php echo $language['language_id']; ?>]" value="<?php if (isset($field['field_description'][$language['language_id']])) echo $field['field_description'][$language['language_id']]; ?>" size="20" /><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
				</div>
				</div>

			   	<?php } ?>
			</td>

            <td class="right">
            <input type="text" name="mylist[<?php echo $list_num; ?>][addfields][<?php echo $num_field; ?>][field_order]" value="<?php echo $field['field_order']; ?>" size="3" />
            </td>

            <td class="left">
            <div style="width: 100px;">
             <a onclick="$('#field-row-<?php echo $list_num;?>-<?php echo $num_field; ?>').remove();" class="mbuttonr"><?php echo $this->language->get('button_remove');?></a>
            </div>
           <div style="width: 100px;">
             <a href="<?php echo  $this->url->link('catalog/fields/update', 'token=' . $this->session->data['token'] . '&field_name='.$field['field_name'], 'SSL'); ?>" class="markbuttono" target="_blank" style="margin-left: 0px; margin-top: 3px;"><?php echo $this->language->get('button_update');?></a>
            </div>


           </td>

      </tr>



        <?php
        }
        } else  {
        ?>
       <td class="left"><div style="width: 100%">&nbsp;</div></td>
       <td class="left"><div style="width: 100%">&nbsp;</div></td>
       <td class="right"><div style="width: 100%">&nbsp;</div></td>
       <td style="width: 200px;"><div style="width: 100%">&nbsp;</div></td>
        <?php
        }

        ?>
        <tfoot>
          <tr>
            <td colspan="3"></td>
            <td class="left"><a id="add_f-<?php echo $list_num;?>"  class="markbutton"><?php echo $this->language->get('text_action_add_field'); ?></a></td>
          </tr>
        </tfoot>
      </table>

      </td>
       </tr>


</table>




</div>

<?php }   ?>


<script>
var afields_row_<?php echo $list_num;?> = Array();

<?php
if (isset($list['addfields'])) {
 foreach ($list['addfields'] as $indx => $module) {
?>
afields_row_<?php echo $list_num;?>.push(<?php echo $indx; ?>);
<?php
 }
}
?>
var num_field =<?php echo $fields_row; ?>;


function addfields() {var aindex = -1;
	for(i=0; i<afields_row_<?php echo $list_num;?>.length; i++) {
	 flg = jQuery.inArray(i, afields_row_<?php echo $list_num;?>);
	 if (flg == -1) {
	  aindex = i;
	 }
	}
	if (aindex == -1) {
	  aindex = afields_row_<?php echo $list_num;?>.length;
	}
	num_field = aindex;
	afields_row_<?php echo $list_num;?>.push(aindex);




addfields_<?php echo $list_num;?> = '<tr>';


addfields_<?php echo $list_num;?>+= '            <td class="right">';
addfields_<?php echo $list_num;?>+= '            <input type="text" class="fields" name="mylist[<?php echo $list_num; ?>][addfields]['+num_field +'][field_name]" value="" size="10" />';
addfields_<?php echo $list_num;?>+= '            </td>';

addfields_<?php echo $list_num;?>+= '<td class="left">';
//addfields_<?php echo $list_num;?>+= num_field + '&nbsp;';

	 		<?php foreach ($languages as $language) { ?>


addfields_<?php echo $list_num;?>+= '	               <div style="width: 100%">';
addfields_<?php echo $list_num;?>+= '					<div style="float: left; font-size: 11px; width: 77px; text-decoration: none;"><?php echo  ($language['name']); ?></div>';
addfields_<?php echo $list_num;?>+= '					<div style="">';

addfields_<?php echo $list_num;?>+= '<input type="text" name="mylist[<?php echo $list_num; ?>][addfields]['+num_field +'][field_description][<?php echo $language['language_id']; ?>]" value="" size="50" /><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" >';


addfields_<?php echo $list_num;?>+= '					</div>';
addfields_<?php echo $list_num;?>+= '					</div>';



			   	<?php } ?>
addfields_<?php echo $list_num;?>+= '			</td>';
addfields_<?php echo $list_num;?>+= '            <td class="right">';
addfields_<?php echo $list_num;?>+= '            <input type="text" name="mylist[<?php echo $list_num; ?>][addfields]['+num_field +'][field_order]" value="" size="3" />';
addfields_<?php echo $list_num;?>+= '            </td>';
addfields_<?php echo $list_num;?>+= '            <td class="left">';
addfields_<?php echo $list_num;?>+= '            <div style="float:left; width: 100px;">';
addfields_<?php echo $list_num;?>+= '             <a onclick="$(\'#field-row-<?php echo $list_num;?>-' + num_field + '\').remove();" class="mbuttonr"><?php echo $this->language->get('button_remove');?></a>';
addfields_<?php echo $list_num;?>+= '           </div>';
addfields_<?php echo $list_num;?>+= '    </td>';
addfields_<?php echo $list_num;?>+= ' </tr>';

html_<?php echo $list_num;?>  = '<tbody id="field-row-<?php echo $list_num;?>-' + num_field + '">' + addfields_<?php echo $list_num;?> + '</tbody>';



$('#table_fields_<?php echo $list_num;?> tfoot').before(html_<?php echo $list_num;?>);

num_field++;

fields_auto();

}

$('#add_f-<?php echo $list_num;?>').bind('click',{ }, addfields);
</script>

<script type="text/javascript">
$('input[name=\'mylist[<?php echo $list_num; ?>][record]\']').autocomplete({
	delay: 0,

	source: function(request, response) {		$.ajax({
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
		$('input[name=\'mylist[<?php echo $list_num; ?>][record]\']').val(ui.item.label);
		//if (ui.item.label=='') ui.item.value ='';
		$('input[name=\'mylist[<?php echo $list_num; ?>][recordid]\']').val(ui.item.value);

		return false;
	}
});

</script>

<script>
if ($.isFunction($.fn.on)) {
	$(document).on('blur','input[name=\'mylist[<?php echo $list_num; ?>][record]\']',  function() {

        if ($('input[name=\'mylist[<?php echo $list_num; ?>][record]\']').val()=='') {
         $('input[name=\'mylist[<?php echo $list_num; ?>][recordid]\']').val('');
         }

		return false;
	});

} else {

	$('input[name=\'mylist[<?php echo $list_num; ?>][record]\']').live('blur',  function() {
        if ($('input[name=\'mylist[<?php echo $list_num; ?>][record]\']').val()=='') {
         $('input[name=\'mylist[<?php echo $list_num; ?>][recordid]\']').val('');
         }

		return false;
	});

}
</script>









