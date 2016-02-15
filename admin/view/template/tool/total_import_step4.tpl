<?php
#####################################################################################
#  Module TOTAL IMPORT PRO for Opencart 1.5.x From HostJars opencart.hostjars.com 	#
#####################################################################################
?>
<?php echo $header; ?>
<style type="text/css">
.info_image{ 
	vertical-align: middle;
	padding-bottom: 3px;
}
.mapping_field {
	width:200px;
}
.source_field {
	width:800px;;
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
<script type="text/javascript">
	function addSub(el) {
		sub = $(el).closest('.hori').children('td').children('select').first().clone();
		$(el).before(sub);
		return false;
	}
	function addVert(el, multi) {
		newEl = '<tr class="vert';
		if (multi) {
			newEl += ' hori';
		}
		newEl += '">' + $(el).closest('.vert').html() + '</tr>';
		if (multi == true) {
			matches = newEl.match(/\]\[(\d+)\]\[\]/);
			count = parseInt(matches[1]);
			count = count + 1;
			newEl = newEl.replace(']['+(count-1).toString()+'][]', ']['+count.toString()+'][]');
		}
		$(el).hide();
		$(el).closest('.vert').after(newEl);
		return false;
	}
</script>
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
	<div id="tabs" class="htabs">
			<!-- <a href="#tab_fetch">Step 1: Fetch Feed</a>
			<a href="#tab_adjust"><?php echo $tab_adjust; ?></a>
			<a href="#tab_global"><?php echo $tab_global; ?></a> 
			-->
		<a href="#tab_mapping"><?php echo $tab_mapping; ?></a>
		<!-- <a href="#tab_import"><?php echo $tab_import; ?></a> -->
	</div>
	<form action="<?php echo $action; ?>" method="post" name="settings_form" enctype="multipart/form-data" id="import_form">
		<input type='hidden' value='import_step4' name='step'/>
		<div id="tab_mapping">
		<table class="form">
			<tr class="instructions">
				<td colspan="3"><?php echo $text_mapping_description; ?></td>
			</tr>
			<tr><td><?php echo $text_feed_sample; ?></td></tr>
			<tr>
				<td>
					<div id="sample_data">
						<table class="list">
							<thead>
							<tr>
							<?php foreach ($fields as $field) { ?>
								<td class="center"><?php echo $field; ?></td>
							<?php } ?>
							<tr>
							</thead>
							<tbody><tr>
							<?php foreach ($feed_sample as $key=>$value) { ?>
								<td class="center"><?php $value = strip_tags($value); echo (strlen($value) > 90) ? substr($value, 0, 90) . '...' : $value; ?></td>
							<?php } ?>
							</tr></tbody>
						</table>   
					</div>     	
				</td>
			</tr>
			
			<!-- mapping fields to names -->
			<tr>
				<td colspan="3">
					<div id="mapping_tabs" class="htabs">
						<?php foreach($tab_field as $tab => $value) { ?>
							<a href="#tab_<?php echo $tab; ?>"><?php echo $tab; ?></a>
						<?php } ?>
							
					</div>
				<?php foreach($tab_field as $tab => $value) { ?>
					<div id="tab_<?php echo $tab; ?>">
					<table>
						<tr><td class="mapping_field"><h2><?php echo $text_field_oc_title; ?></h2></td><td><h2><?php echo $text_field_feed_title; ?></h2></td>
						<?php foreach ($field_map as $input_name => $pretty_name) { ?>
							<?php if (in_array($input_name, $value)) { ?>
							<?php if (!is_array($pretty_name)) { ?>
							<?php if (in_array($input_name, $multi_language_fields)) { ?>
								<?php foreach ($languages as $lang) { ?>
								<!-- Normal Field (Multi Language) -->
								<tr>
									<td class="mapping_field"><?php echo $pretty_name; ?>&nbsp;<img src="view/image/flags/<?php echo $lang['image']; ?>" title="<?php echo $lang['name']; ?>" /></td>
									<td class="source_field"><select name="field_names[<?php echo $input_name?>][<?php echo $lang['language_id']; ?>]">
										<option value=''><?php echo $entry_none; ?></option>
										<?php foreach ($fields as $field) { ?>
										<option value="<?php echo $field; ?>" <?php if (isset($field_names) && isset($field_names[$input_name]) && isset($field_names[$input_name][$lang['language_id']]) && $field_names[$input_name][$lang['language_id']] == $field) echo 'selected="true"'; ?>><?php echo $field; ?></option>
										<?php } ?>
									</select></td>
								</tr>
								<!-- END Normal Field (Multi Language) -->
								<?php } ?>
							<?php } else { ?>
							<!-- Normal Field -->
							<tr>
								<td class="mapping_field"><?php echo $pretty_name; ?></td>
								<td class="source_field"><select name="field_names[<?php echo $input_name?>]">
									<option value=''><?php echo $entry_none; ?></option>
									<?php foreach ($fields as $field) { ?>
									<option value="<?php echo $field; ?>" <?php if (isset($field_names) && isset($field_names[$input_name]) && $field_names[$input_name] == $field) echo 'selected="true"'; ?>><?php echo $field; ?></option>
									<?php } ?>
								</select></td>
							</tr>
							
							<? if($pretty_name == 'Subtract Stock'){?>
							<tr>
								<td class="mapping_field">Out Of Stock Status</td>
								<td class="source_field"><select name="field_names[out_of_stock_status]">
									<option value=''><?php echo $entry_none; ?></option>
									<?php foreach ($fields as $field) { ?>
									<option value="<?php echo $field; ?>" <?php if (isset($field_names) && isset($field_names['out_of_stock_status']) && $field_names['out_of_stock_status'] == $field) echo 'selected="true"'; ?>><?php echo $field; ?></option>
									<?php } ?>
								</select></td>
							</tr>
							<tr>
								<td class="mapping_field">Shipping Cost</td>
								<td class="source_field"><select name="field_names[shipping_cost]">
									<option value=''><?php echo $entry_none; ?></option>
									<?php foreach ($fields as $field) { ?>
									<option value="<?php echo $field; ?>" <?php if (isset($field_names) && isset($field_names['shipping_cost']) && $field_names['shipping_cost'] == $field) echo 'selected="true"'; ?>><?php echo $field; ?></option>
									<?php } ?>
								</select></td>
							</tr>
							<?}?>
							
							<!-- END Normal Field -->
							<?php } ?>
							<?php } elseif ($pretty_name[1] == 'both') { ?>
							<!-- Multi downwards/sideways Field -->
							
								<?php for ($i=0; (isset($field_names[$input_name]) && $i<count($field_names[$input_name]) || !$i && !count($field_names[$input_name])); $i++) { ?>
								<tr class="hori vert">
									<td class="mapping_field"><?php echo $pretty_name[0]; ?>
									<?php if($pretty_name[0] == 'Category') { ?>
										<a href="http://helpdesk.hostjars.com/entries/21816598-how-do-i-import-categories" target="_blank" alt="Importing Categories"><img class="info_image" src="view/image/information.png" title="Importing Categories"/></a>
									<?php } ?>
									<?php if (!isset($field_names[$input_name]) || !count($field_names[$input_name]) || ($i+1) == count($field_names[$input_name])) { ?><a style="float:right;" onclick="return addVert(this, true);" class="button"><span>More&nbsp;&darr;&nbsp;</span></a><?php } ?></td>
									<td class="source_field">
									<?php for ($j=0; $j<count($field_names[$input_name][$i]) || ($j==0 && !count($field_names[$input_name][$i])); $j++) { ?>
										<select name="field_names[<?php echo $input_name; ?>][<?php echo $i; ?>][]">
											<option value=''><?php echo $entry_none; ?></option>
											<?php foreach ($fields as $field) { ?>
											<option value="<?php echo $field; ?>" <?php if (isset($field_names) && $field_names[$input_name][$i][$j] == $field) echo 'selected="true"'; ?>><?php echo $field; ?></option>
											<?php } ?>
										</select>
										<?php if (($j+1) == count($field_names[$input_name][$i])) { ?>
										&nbsp;<a onclick="return addSub(this);" class="button"><span>More&nbsp;&rarr;&nbsp;</span></a>
										<?php } ?>
									<?php } ?>
									</td>
								</tr>
								<!-- END Multi downwards/sideways Field -->
								<?php } ?>
							<?php } else { ?>
							<!-- Multi downwards Field -->
							
								<?php for ($i=0; (isset($field_names[$input_name]) && $i<count($field_names[$input_name])) || (!$i && !count($field_names[$input_name])); $i++) { ?>
								<?php if ($i == 0 || (isset($field_names[$input_name]) && $field_names[$input_name][$i])) { ?> 
								<tr class="vert">
									<td class="mapping_field"><?php echo $pretty_name[0]; ?>
									<?php if($pretty_name[0] == 'Options') { ?>
										<a href="http://helpdesk.hostjars.com/entries/21766242-how-do-i-import-options" target="_blank" alt="Importing Options"><img class="info_image" src="view/image/information.png" title="Importing Options"/></a>
									<?php } ?>
									<?php if($pretty_name[0] == 'Discount Price') { ?>
										<a href="http://helpdesk.hostjars.com/entries/21782977-can-i-import-discounts" target="_blank" alt="Importing Discount Prices"><img class="info_image" src="view/image/information.png" title="Importing Discount Prices"/></a>
									<?php } ?>
									<?php if($pretty_name[0] == 'Download') { ?>
										<a href="http://helpdesk.hostjars.com/entries/22194853-can-i-import-downloads" target="_blank" alt="Importing Downloadable Products"><img class="info_image" src="view/image/information.png" title="Importing Downloadable Products"/></a>
									<?php } ?>
									<?php if (!isset($field_names[$input_name]) || !count($field_names[$input_name]) || ($i+1) == count($field_names[$input_name])) { ?><a style="float:right;" onclick="return addVert(this, false);" class="button"><span>More&nbsp;&darr;&nbsp;</span></a><?php } ?></td>
									<td><select name="field_names[<?php echo $input_name; ?>][]">
										<option value=''><?php echo $entry_none; ?></option>
										<?php foreach ($fields as $field) { ?>
										<option value="<?php echo $field; ?>" <?php if (isset($field_names) && $field_names[$input_name][$i] == $field) echo 'selected="true"'; ?>><?php echo $field; ?></option>
										<?php } ?>
									</select></td>
								</tr>
								<?php } ?>
								<?php } ?>
							<!-- END Multi downwards Field -->
							<?php } ?>
						<?php } ?>
						<?php } ?>
					</table>
				</div>
				<?php } ?>
			</td>
			</tr>
		</table>
	   </div>
	  </form>
  </div>
</div><script type="text/javascript"><!--
$('#tabs a').tabs();
$('#mapping_tabs a').tabs();

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