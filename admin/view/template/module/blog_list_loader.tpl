<?php
foreach ($mylist as $list_num=>$list) { ?>
<div id="list<?php echo $list_num;?>"  style="padding-left: 200px;">

  <input type="hidden" name="mylist[<?php echo $list_num; ?>][type]" value="<?php if (isset($list['type'])) echo $list['type']; else echo 'blogs'; ?>">


<table >
    <tr>
    <td>
    </td>
    <td>
	 <div class="buttons"><a onclick="mylist_num--; $('#amytabs<?php echo $list_num;?>').remove(); $('#mytabs<?php echo $list_num;?>').remove(); $('#mytabs a').tabs(); return false; " class="mbuttonr"><?php echo $this->language->get('button_remove'); ?></a></div>
    </td>
    </tr>
	 <?php foreach ($languages as $language) { ?>
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
				<input type="hidden" name="tpath" value="widgets/loader">
			</td>

	</tr>



</table>
</div>


  <?php }  ?>


</div>
