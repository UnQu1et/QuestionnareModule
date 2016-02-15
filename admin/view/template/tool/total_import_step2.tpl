<?php
#####################################################################################
#  Module TOTAL IMPORT PRO for Opencart 1.5.x From HostJars opencart.hostjars.com 	#
#####################################################################################
?>
<?php echo $header; ?>
<script type="text/javascript">
function updateText(el, name) {
	var action = el.value;
	if (name == 'remote_images' & action == '1') {
		$("#image_subfolder").show();
	} else {
		$("#image_subfolder").hide();
	}
}
$(document).ready(function() {
	updateText(document.settings_form.remote_images, 'remote_images');
});
</script>
<style type="text/css">
.info_image{ 
	vertical-align: middle;
	padding-bottom: 3px;
}
</style>
<div id="content">
  <div class="breadcrumb">
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
	<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
	<?php } ?>
  </div>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="box">
  <div class="heading">
	<h1><img src='view/image/feed.png' /><?php echo $heading_title; ?>&nbsp;(<a title='<?php echo $text_documentation; ?>' href='<?php echo $help_link; ?>'><?php echo $text_documentation; ?></a>)</h1>
	<div class="buttons">
		<a onclick="$('#import_form').submit();" class="button"><span><?php echo $button_next; ?></span></a>
		<a onclick="javascript:saveSettings();return false;" class="button"><span><?php echo $button_save; ?></span></a>
		<a href="<?php echo $skip_url; ?>" class="button"><span><?php echo $button_skip; ?></span></a>
		<a href="<?php echo $cancel; ?>" class="button"><span><?php echo $button_cancel; ?></span></a>
	</div>
  </div>
  <div class="content">
	<div id="tabs" class="htabs"><!-- <a href="#tab_fetch">Step 1: Fetch Feed</a><a href="#tab_adjust"><?php echo $tab_adjust; ?></a> --><a href="#tab_global"><?php echo $tab_global; ?></a><!-- <a href="#tab_mapping"><?php echo $tab_mapping; ?></a><a href="#tab_import"><?php echo $tab_import; ?></a> --></div>
	  <form action="<?php echo $action; ?>" method="post" name="settings_form" enctype="multipart/form-data" id="import_form">
		<input type='hidden' value='import_step2' name='step'/>
		<div id="tab_global">
		<table class="form">
			<!-- Out of Stock Status -->
			<tr>
				<td><?php echo $entry_out_of_stock_status_insert; ?></td>
				<td>
					<select name="out_of_stock_status_insert">
					<?php foreach ($stock_status_selections as $status) { ?>
						<option value="<?php echo $status['stock_status_id']; ?>" <?php if (isset($out_of_stock_status_insert) && $out_of_stock_status_insert == $status['stock_status_id']) { echo "selected='true'"; } ?>><?php echo $status['name']; ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td><?php echo $entry_out_of_stock; ?></td>
				<td>
					<select name="out_of_stock_status">
					<?php foreach ($stock_status_selections as $status) { ?>
						<option value="<?php echo $status['stock_status_id']; ?>" <?php if (isset($out_of_stock_status) && $out_of_stock_status == $status['stock_status_id']) { echo "selected='true'"; } ?>><?php echo $status['name']; ?></option>
					<?php } ?>
					</select>
				</td>
			</tr> 
			<tr>
				<td><?php echo $entry_download_tovars; ?></td>
				<td>
					<select name="download_tovars">
						<option value="1">Да</option>
						<option value="0" <?php if (isset($download_tovars) && !$download_tovars) { echo 'selected="true"'; }?>>Нет</option>
					</select>
				</td>
			</tr> 
			<tr>
				<td><?php echo $entry_category_method; ?></td>
				<td>
					<select name="category_method">
						<option value="1">Назначать родительские категории</option>
						<option value="0" <?php if (isset($category_method) && !$category_method) { echo 'selected="true"'; }?>>Назначить только выбранную категорию</option>
					</select>
				</td>
			</tr>      	
			<!-- Subtract Stock -->
			<tr>
				<td><?php echo $entry_subtract_stock; ?></td>
				<td>
					<select name="subtract_stock">
						<option value="1">Да</option>
						<option value="0" <?php if (isset($subtract_stock) && !$subtract_stock) { echo 'selected="true"'; }?>>Нет</option>
					</select>
				</td>
			</tr> 
			<!-- Shipping Required -->
			<tr>
				<td><?php echo $entry_requires_shipping; ?></td>
				<td>
					<select name="requires_shipping">
						<option value="1">Да</option>
						<option value="0" <?php if (isset($requires_shipping) && !$requires_shipping) { echo 'selected="true"'; }?>>Нет</option>
					</select>
				</td>
			</tr> 
			<!-- Minimum Quantity -->
			<tr>
				<td><?php echo $entry_minimum_quantity; ?></td>
				<td><input type="text" name="minimum_quantity" value="<?php echo (isset($minimum_quantity)) ? $minimum_quantity : '1';?>"></td>
			</tr> 
			<tr>
				<td><?php echo $entry_default_quantity; ?></td>
				<td><input type="text" name="default_quantity" value="<?php echo (isset($default_quantity)) ? $default_quantity : '100';?>"></td>
			</tr> 
			<!-- Product Status -->
			<tr>
				<td><?php echo $entry_product_status; ?></td>
				<td>
					<select name="product_status">
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" <?php if (isset($product_status) && !$product_status) { echo 'selected="true"'; }?>><?php echo $text_disabled; ?></option>
					</select>
				</td>
			</tr> 
			<tr>
				<td><?php echo $entry_product_status_hide; ?></td>
				<td>
					<select name="product_status_hide">
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" <?php if (isset($product_status_hide) && !$product_status_hide) { echo 'selected="true"'; }?>><?php echo $text_disabled; ?></option>
					</select>
				</td>
			</tr> 
			<!-- Weight Class -->
			<tr>
				<td><?php echo $entry_weight_class; ?></td>
				<td>
					<select name="weight_class">
						<?php foreach ($weight_class_selections as $weight) { ?>
						<option value="<?php echo $weight['weight_class_id']; ?>" <?php if (isset($weight_class) && $weight_class == $weight['weight_class_id']) { echo 'selected="true"'; }?>><?php echo $weight['title']; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr> 
			<!-- Length Class -->
			<tr>
				<td><?php echo $entry_length_class; ?></td>
				<td>
					<select name="length_class">
						<?php foreach ($length_class_selections as $length) { ?>
						<option value="<?php echo $length['length_class_id']; ?>" <?php if (isset($length_class) && $length_class == $length['length_class_id']) { echo 'selected="true"'; }?>><?php echo $length['title']; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr> 
			<!-- Tax Class -->
			<tr>
				<td><?php echo $entry_tax_class; ?></td>
				<td>
					<select name="tax_class">
						<option value="0">--- None ---</option>
						<?php foreach ($tax_class_selections as $tax) { ?>
						<option value="<?php echo $tax['tax_class_id']; ?>" <?php if (isset($tax_class) && $tax_class == $tax['tax_class_id']) { echo 'selected="true"'; }?>><?php echo $tax['title']; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr> 	
			<!-- Customer Group -->
			<?php if (count($customer_group_selections) > 1) { ?>
			<tr>
				<td><?php echo $entry_customer_group; ?></td>
				<td>
					<select name="customer_group">
						<?php foreach ($customer_group_selections as $group) { ?>
						<option value="<?php echo $group['customer_group_id']; ?>" <?php if (isset($customer_group) && $customer_group == $group['customer_group_id']) { echo 'selected="true"'; }?>><?php echo $group['name']; ?></option>
						<?php } ?>
					</select>
					<?php foreach ($customer_group_selections as $group) { ?>
					<input type="hidden" name="customer_group_ids[<?php echo strtolower($group['name']); ?>]" value="<?php echo $group['customer_group_id']; ?>">
					<?php } ?>
				</td>
			</tr>
				<?php }	else { foreach ($customer_group_selections as $group) { ?>
					<input type="hidden" name="customer_group" value="<?php echo $group['customer_group_id']; ?>">
					<input type="hidden" name="customer_group_ids[<?php echo strtolower($group['name']); ?>]" value="<?php echo $group['customer_group_id']; ?>">
				<?php }} ?>
			<!-- Add categories to top bar -->
			
			<tr>
				<td><?php echo $entry_hide_category; ?></td>
				<td>
					<select name="hide_category_id" id="update_product">
						<option value="0"><?php echo $text_none; ?></option>
						<?php foreach ($categories as $categories_v) { ?>
							<? if($hide_category_id == $categories_v['category_id']){?>
								<option value="<?php echo $categories_v['category_id']; ?>" selected="selected" ><?php echo $categories_v['name']; ?></option>
							<?}else{?>
								<option value="<?php echo $categories_v['category_id']; ?>"><?php echo $categories_v['name']; ?></option>
							<?}?>
						<?}?>
					</select>
				</td>
			</tr> 
			<tr>
				<td><?php echo $entry_top_categories; ?></td>
				<td>
					<select name="top_categories">
						<option value="0"><?php echo $entry_no; ?></option>
						<option value="1" <?php if (isset($top_categories) && $top_categories) echo 'selected="true"'; ?>><?php echo $entry_yes; ?></option>
					</select>
				</td>
			</tr> 
			<!-- Add products only to bottom category -->
			<tr>
				<td><?php echo $entry_bottom_category_only; ?></td>
				<td>
					<select name="bottom_category_only">
						<option value="0" ><?php echo $entry_all_categories; ?></option>
						<option value="1" <?php if (isset($bottom_category_only) && $bottom_category_only) echo 'selected="true"'; ?>><?php echo $entry_bottom_category_only; ?></option>
					</select>
				</td>
			</tr> 
			<!-- Categories delimited -->
			<tr>
				<td><?php echo $entry_split_category; ?><a href="http://helpdesk.hostjars.com/entries/21816598-how-do-i-import-categories" target="_blank" alt="Importing Categories"><img class="info_image" src="view/image/information.png" title="Importing Categories"/></a></td>
				<td>
					<input type="text" name="split_category" style="width:30px;" value="<?php if (isset($split_category)) echo $split_category; ?>">
				</td>
			</tr> 
			<tr>
				<td><?php echo $entry_split_image; ?></td>
				<td>
					<input type="text" name="split_image" style="width:30px;" value="<?php if (isset($split_image)) echo $split_image; ?>">
				</td>
			</tr> 
			<tr>
				<td><?php echo $entry_tovar_type; ?></td>
				<td>			  
				  <select name="tovartype_id" id="update_product">
						<option value="0"><?php echo $text_none; ?></option>
						<?php foreach ($tovartype as $tovartype_v) { ?>
							<? if($tovartype_id == $tovartype_v['tovartype_id']){?>
								<option value="<?php echo $tovartype_v['tovartype_id']; ?>" selected="selected" ><?php echo $tovartype_v['name']; ?></option>
							<?}else{?>
								<option value="<?php echo $tovartype_v['tovartype_id']; ?>"><?php echo $tovartype_v['name']; ?></option>
							<?}?>
						<?}?>
					</select>
				  
				</td>
			</tr> 
			<tr>
				<td>Перезаписывать тип товаров в имеющихся товарах: <p class="help">Например по модели тек. прайса найден товар, но с другого прайса(по другому типу товара), если опция "Да" - тогда тип товара будет переписан к данному прайсу, если "Нет" - тогда импорт пропустит данный товар.</p></td>
				<td>			  
				  <select name="rewrite_tovartype_id" id="update_product">
						<option value="0" <?php if (isset($rewrite_tovartype_id) && $rewrite_tovartype_id == 0) echo 'selected="selected"'; ?>>Нет</option>
						<option value="1" <?php if (isset($rewrite_tovartype_id) && $rewrite_tovartype_id == 1) echo 'selected="selected"'; ?>>Да</option>
					</select>
				  
				</td>
			</tr> 
			<tr>
				<td><?php echo $entry_location; ?></td>
				<td>
					<input type="text" name="location" style="width:100px;" value="<?php if (isset($location)) echo $location; ?>">
				</td>
			</tr>			
			<!-- Download Remote Images -->
			<tr>
				<td><?php echo $entry_remote_images; ?></td>
				<td>
					<select name="remote_images" onchange="updateText(this, 'remote_image');">
						<option value="0"><?php echo $entry_no; ?></option>
						<option value="1" <?php if (isset($remote_images) && $remote_images) echo 'selected="true"'; ?>><?php echo $entry_yes; ?></option>
					</select>
				</td>
			</tr> 
			<!-- Download Image Subfolder -->
			<tr id="image_subfolder">
				<td><?php echo $entry_image_subfolder; ?></td>
				<td>
					<?php echo $image_folder_path ?><input type="text" name="image_subfolder" value="<?php if (isset($image_subfolder)) echo $image_subfolder; ?>">
				</td>
			</tr> 
			<!-- Stores -->
			<?php if (count($store_selections)) { ?>
			<tr>
				<td><?php echo $entry_store; ?></td>
					<td>
					<input type="checkbox" name="store[]" value="0" checked="true" id="store_selection"/><label for="store_selection">Default</label>
					<?php foreach ($store_selections as $sto) { ?>
					<input type="checkbox" name="store[]" value="<?php echo $sto['store_id']; ?>" id="store_selection_<?php echo $sto['store_id'] ?>"<?php if (isset($store) && in_array($sto['store_id'], $store)) { echo ' checked="true"'; } ?>><label for="store_selection_<?php echo $sto['store_id'] ?>"><?php echo $sto['name']; ?></label>
					<?php } ?>
				</td>
			</tr>
			<?php }	else { ?>
					<input type="hidden" name="store[]" value="0"/>
			<?php } ?> 

			<!-- Languages -->
			<?php if (count($language_selections) > 1) { ?>
			<tr>
				<td><?php echo $entry_language; ?></td>
				<td colspan="2">
				<?php foreach ($language_selections as $lang) { ?>
					<input type="checkbox" name="language[]" value="<?php echo $lang['language_id']; ?>"<?php if (isset($language) && in_array($lang['language_id'], $language)) { echo ' checked="true"'; } ?>><?php echo $lang['name']; ?>
				<?php } ?>
				</td>
			</tr>
			<?php }	else { foreach ($language_selections as $lang) { ?>
					<input type="hidden" name="language[]" value="<?php echo $lang['language_id']; ?>">
			<?php }} ?>
		</table>
	   </div>
	  </form>
  </div>
</div><script type="text/javascript"><!--
$('#tabs a').tabs();

function saveSettings() {
	var data = $('#import_form').serialize();
	var url = 'index.php?route=tool/total_import/saveSettings&token=<?php echo $token ?>';
	$.ajax({
		type: "POST",
		url: url,
		data: data,
		success: function(result) {
			addSave(result);
		}
	});
}

function addSave(result) {
	$('.success').remove();
	$('.warning').hide();
	$('.breadcrumb').append('<div class="success" style="margin-top:15px;">'+result+'</div>');
}
//--></script>
<?php echo $footer; ?>