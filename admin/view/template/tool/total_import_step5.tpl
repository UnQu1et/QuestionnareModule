<?php
#####################################################################################
#  Module TOTAL IMPORT PRO for Opencart 1.5.x From HostJars opencart.hostjars.com 	#
#####################################################################################
?>
<?php echo $header; ?>
<form action="<?php echo $action; ?>" method="post" name="settings_form" enctype="multipart/form-data" id="import_form">
<input type='hidden' value='import_step5' name='step'/>
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
<script type="text/javascript">
function updateText() {
	$(".non_reset").show();
	if (document.settings_form.reset.value == '1') {
		$(".non_reset").hide();
	}
}
function enableRange(check) {
	if(check) {
		$("#import_range_start").removeAttr("disabled");
		$("#import_range_end").removeAttr("disabled");
	} else {
		 $("#import_range_start").attr("disabled",true);
		 $("#import_range_end").attr("disabled",true);
	}
}
$(document).ready(function() {
	updateText();
	<?php if (isset($import_range) && $import_range == 'partial') echo 'enableRange(true);'; ?>
	<?php if (isset($import_range) && $import_range == 'all') echo 'enableRange(false);'; ?>
});
</script>


<div class="box">
  <div class="heading">
	<h1><img src='view/image/feed.png' /><?php echo $heading_title; ?>&nbsp;(<a title='<?php echo $text_documentation; ?>' href='<?php echo $help_link; ?>'><?php echo $text_documentation; ?></a>)</h1>
	<div class="buttons">
		<?php echo $text_save_profile; ?><input type="text" name="save_settings_name" value="">
		<a onclick="$('#import_form').submit();" class="button"><span><?php echo $button_import; ?></span></a>
		<a onclick="javascript:saveSettings();return false;" class="button"><span><?php echo $button_save; ?></span></a>
		<a href="<?php echo $cancel; ?>" class="button"><span><?php echo $button_cancel; ?></span></a>
	</div>
  </div>
  <div class="content">
	<div id="tabs" class="htabs"><!-- <a href="#tab_fetch">Step 1: Fetch Feed</a><a href="#tab_adjust"><?php echo $tab_adjust; ?></a><a href="#tab_global"><?php echo $tab_global; ?></a><a href="#tab_mapping"><?php echo $tab_mapping; ?></a> --><a href="#tab_import"><?php echo $tab_import; ?></a></div>
		<div id="tab_import">
		<table class="form">
			<!-- Reset Store -->
			<tr>
				<td><?php echo $entry_reset; ?></td>
				<td>
					<select name="reset" onchange="updateText();">
						<option value="0"><?php echo $entry_no; ?></option>
						<option value="1" <?php if (isset($reset) && $reset) echo 'selected="true"'; ?>><?php echo $entry_yes; ?></option>
					</select>
				</td>
			</tr>
			<!-- New Items -->
			<tr class="non_reset">
				<td><?php echo $entry_new_items; ?></td>
				<td>
					<select name="new_items">
						<option value="add"><?php echo $entry_add; ?></option>
						<option value="skip" <?php if (isset($new_items) && $new_items == 'skip') echo 'selected="true"'; ?>><?php echo $entry_skip; ?></option>
					</select>       
				</td>
			</tr>
			<!-- Existing Items -->
			<tr class="non_reset">
				<td><?php echo $entry_existing_items; ?></td>
				<td>
					<select name="existing_items">
						<option value="update"><?php echo $entry_update; ?></option>
						<option value="skip" <?php if (isset($existing_items) && $existing_items == 'skip') echo 'selected="true"'; ?>><?php echo $entry_skip; ?></option>
					</select>
				</td>
			</tr>
			<!-- Identify Existing Items -->
			<tr class="non_reset">
				<td><?php echo $text_identify_existing; ?></td>
				<td>       	
					<select name="update_field">
						<option value="model"><?php echo $text_field_model; ?></option>	
						<option value="sku" <?php if (isset($update_field) && $update_field == 'sku') echo 'selected="true"'; ?>><?php echo $text_field_sku; ?></option>
						<option value="upc" <?php if (isset($update_field) && $update_field == 'upc') echo 'selected="true"'; ?>><?php echo $text_field_upc; ?></option>
						<option value="ean" <?php if (isset($update_field) && $update_field == 'ean') echo 'selected="true"'; ?>><?php echo $text_field_ean; ?></option>
						<option value="jan" <?php if (isset($update_field) && $update_field == 'jan') echo 'selected="true"'; ?>><?php echo $text_field_jan; ?></option>
						<option value="isbn" <?php if (isset($update_field) && $update_field == 'isbn') echo 'selected="true"'; ?>><?php echo $text_field_isbn; ?></option>
						<option value="mpn" <?php if (isset($update_field) && $update_field == 'mpn') echo 'selected="true"'; ?>><?php echo $text_field_mpn; ?></option>
					</select>
				</td>
			</tr> 
			<!-- Items in store, not in feed -->
			<tr class="non_reset">
				<td><?php echo $entry_delete_diff; ?></td>
				<td>
					<select name="delete_diff" onchange="updateText();">
						<option value="ignore"><?php echo $entry_ignore; ?></option>
						<option value="delete" <?php if (isset($delete_diff) && $delete_diff == 'delete') echo 'selected="true"'; ?>><?php echo $entry_delete; ?></option>
						<option value="disable" <?php if (isset($delete_diff) && $delete_diff == 'disable') echo 'selected="true"'; ?>><?php echo $entry_disable; ?></option>
						<option value="zero_quantity" <?php if (isset($delete_diff) && $delete_diff == 'zero_quantity') echo 'selected="true"'; ?>><?php echo $entry_zero_quantity; ?></option>
						<option value="set_status_none" <?php if (isset($delete_diff) && $delete_diff == 'set_status_none') echo 'selected="true"'; ?>>Поставить статус: Нет в наличии</option>
					</select>
				</td>
			</tr>
			<!-- Product Range to Import -->
			<tr class="non_reset">
				<td>
					<?php echo $entry_import_range; ?>
					<span class="help"><?php echo $entry_import_range_help; ?></span>
				</td>
				<td>       	
					<input type="radio" id="import_range_all" class="option_radio" name="import_range" onclick='javascript:enableRange(false);' value="all" <?php if ((isset($import_range) && $import_range == 'all') || !isset($import_range)) echo 'checked="checked"'; ?>><label for='import_range_all'><?php echo $entry_all; ?></label>
					<input type="radio" id="import_range_partial" class="option_radio" onclick='javascript:enableRange(true);' name="import_range" value="partial" <?php if (isset($import_range) && $import_range == 'partial') echo 'checked="checked"'; ?>><label for='import_range_partial'><?php echo $entry_range; ?>&nbsp;-&nbsp;<span id="display_import_range"><?php echo $entry_from; ?> <input type="text" id="import_range_start" name="import_range_start" size="6" value="<?php if(!isset($import_range_start))  echo '1'; if(isset($import_range_start)) echo $import_range_start; ?>"> <?php echo $entry_to; ?> <input type="text" id="import_range_end" name="import_range_end" size="6" value="<?php if(!isset($import_range_end)) echo '1000'; if(isset($import_range_end)) echo $import_range_end; ?>"></span></label>
				</td>
			</tr>
		</table>
	   </div>
	 
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
</form>
  <? if($hj_import_category) {?>
	  <style type="text/css">
		.cat_table{
			width:600px;
			margin:auto;
		}
		.cat_table p{
			text-align:center;
			font-size:20px;
			font-weight:bold;
		}
		.cat_table thead td{
			text-align:center;
			font-size:16px;
			
		}
		.cat_table td p{
			margin:0px;
			
		}
		.cat_table td select,.cat_table td input{
			width:250px;
			margin:auto;
		}
		.cat_table .del_cat_field{
			cursor:pointer;
			font-weight:100;
			font-size:14px;
		}
		.del_cat_field:hover{
			text-decoration:underline;
			
		}
		.hidden_cat_table{
			display:none;
		}
	  </style>
	  <form action="#" class="hj_import_category_form">
	  <table class="cat_table">
		<thead>
			<tr>
				<td colspan="2"><p>Настройки Категорий</p></td>
			</tr>
			<tr>
				<td>Категория в магазине</td>
				<td>Категория в файле</td>
				<td>Действие</td>
			</tr>
		</thead>
		<tbody>
			<? $cat_html = ''; foreach($site_categories as $site_categories_v){ 
				$cat_html .= "<option value='{$site_categories_v['category_id']}'>{$site_categories_v['name']}</option>";
			} ?>
			<? foreach($hj_import_category_step5 as $category_name => $category_id){?>
				<tr>
					<td>
						<p align="center">						   
						<select name="hj_import_category[]">
							<option value="0" selected="selected">None</option>
							<?php $cat_html_new = str_replace('value=\''.$category_id.'\'','value=\''.$category_id.'\' selected="selected"',$cat_html);?>    
							<?=$cat_html_new;?>							
						</select>
						</p>
					</td>
					<td><p align="center"><input type="text" name="cat_name[]" value="<?php echo $category_name; ?>"></p></td>
					<td><p align="center" class="del_cat_field" title="Удалить">X</span></p>
				</tr>
			<?}?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2" align="right">
					<a class="button add_cat_field">
						<span>Добавить</span>
					</a>
					<a class="button save_cat_field">
						<span>Сохранить</span>
					</a>
				</td>
			</tr>
		</tfoot>
	  </table>
	  </form>
	  <script type="text/javascript">
		$(".add_cat_field").live("click",function(){
			var hidden_cat_table_val = $(".hidden_cat_table tbody").html();
			$(".cat_table tbody").append(hidden_cat_table_val);			
		});
		$(".del_cat_field").live("click",function(){
			$(this).parents("tr").remove();	
		});
		$(".save_cat_field").live("click",function(){
			$.ajax({
				url: 'index.php?route=tool/total_import/savecat&token=<?php echo $token; ?>',
				type: 'POST',
				data:$(".hj_import_category_form").serialize(),
				success: function() {		
					alert('Категории сохранены!');
				}
			});
		});
	  </script>
	  <table class="hidden_cat_table">
		<tr>
			<td>
				<p align="center">
				<select name="hj_import_category[]">
					<? foreach($site_categories as $site_categories_v){?>
						<option value="<?=$site_categories_v['category_id'];?>"><?=$site_categories_v['name'];?></option>
					<?}?>
				</select>
				</p>
			</td>
			<td><p align="center"><input type="text" name="cat_name[]" value=""></p></td>
			<td><p align="center" class="del_cat_field" title="Удалить">X</span></p>
		</tr>
	  </table>
	  <?}?>
<?php echo $footer; ?>