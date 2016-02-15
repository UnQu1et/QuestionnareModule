<?php
#####################################################################################
#  Module TOTAL IMPORT PRO for Opencart 1.5.x From HostJars opencart.hostjars.com 	#
#####################################################################################
?>
<?php echo $header; ?>
<script type="text/javascript"> 
function addSub(el) {
	sub = $(el).closest('.left').children('select').last().clone();;
	$(el).before(sub);
	return false;
}
</script>

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
	<div id="tabs" class="htabs"><!-- <a href="#tab_fetch">Step 1: Fetch Feed</a> --><a href="#tab_adjust"><?php echo $tab_adjust; ?></a><!-- <a href="#tab_global"><?php echo $tab_global; ?></a><a href="#tab_mapping"><?php echo $tab_mapping; ?></a><a href="#tab_import"><?php echo $tab_import; ?></a> --></div>
	  <form action="<?php echo $action; ?>" method="post" name="settings_form" enctype="multipart/form-data" id="import_form">
		<input type='hidden' value='import_step3' name='step'/>
		<div id="tab_adjust">
		<table class="form">
			<tr>
				<td colspan="2"><?php echo $text_adjust_help; ?></td>
			</tr>	    
			<!-- Sample Fields -->
			<tr><td colspan="2"><?php echo $text_sample; ?></td></tr>
			<tr>
				<td colspan="2">
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
				</td>
			</tr>
		</table>
		<table class="list" id="addOperation">
			<thead>
				<tr>
					<td class="center"><?php echo $text_operation_type; ?></td>
					<td class="left"><?php echo $text_operation_desc; ?></td>
					<td></td>
				</tr>
			</thead>
			<tbody id="operations">
		
			<?php if (isset($adjust)) { ?>
				<?php $adjust_count = 0; ?>
				<?php foreach ($adjust as $row => $previous_data) { ?>
					<tr id="adjustment_row_<?php echo $adjust_count; ?>">
						<td class="center"><?php echo $operations[$adjust[$row][0]]['name']; ?> <input type="hidden" name="adjust[<?php echo $adjust_count; ?>][]" value="<?php echo $adjust[$row][0]; ?>"/></td>
						<?php $i = 1; ?>
						<td class="left">
						<?php foreach ($operations[$adjust[$row][0]]['inputs'] as $field => $value) { ?>
							&nbsp;<?php echo $value['prepend']; ?>&nbsp;
							<?php if ($value['type'] == 'text') { ?>
								<input type="text" name="adjust[<?php echo $adjust_count; ?>][]" value="<?php echo $previous_data[$i] ?>" />
							<?php } elseif ($value['type'] == 'field') { ?>
								<select class="<?php echo $value['type'] ?>" name="adjust[<?php echo $adjust_count ?>][]">
									<option><?php echo $text_select; ?></option>
									<?php foreach ($fields as $field) { ?>
									<option value="<?php echo $field ?>" <?php if ($previous_data[$i] == $field) echo 'selected="selected"'; ?>><?php echo $field; ?></option>
									<?php } ?>
								</select>
							<?php } ?>
						<?php $i++;	} ?>
						<?php while(isset($previous_data[$i])) {?>
							<select class="<?php echo $value['type'] ?>" name="adjust[<?php echo $adjust_count ?>][]">
								<option><?php echo $text_select; ?></option>
								<?php foreach ($fields as $field) { ?>
								<option value="<?php echo $field ?>" <?php if ($previous_data[$i] == $field) echo 'selected="selected"'; ?>><?php echo $field; ?></option>
								<?php } ?>
							</select>
						<?php $i++; } ?> 
						<?php if(isset($value['option']) &&  $value['option'] == 'addMore') { ?>
								<a onclick="return addSub(this);" class="button"><span>More&nbsp;&rarr;&nbsp;</span></a>
						<?php } ?>
						</td><td class="left"><a onclick="$('#adjustment_row_<?php echo $adjust_count; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
				  <?php $adjust_count++; ?>
				  </tr>
			   <?php } ?>
			<?php } ?>
			</tbody>
			<tfoot>
				<td class="center" colspan="2"></td>
				<td class="left">
					<select id="operationToAdd">
						<option><?php echo $text_select_operation; ?></option>
						<?php foreach ($labels as $text) { ?>
							<optgroup label="<?php echo $text ?>">
								<?php foreach ($operations as $key => $value) { ?>
									 <?php if($value['label'] == $text) { ?>
										<option value="<?php echo $key; ?>"><?php echo $value['name']; ?></option>
									<?php } ?>
								<?php } ?>
							</optgroup>
						<?php } ?>
					</select>
					<a class="button" id="addOperation" onclick="addOperation();"><?php echo $button_add_operation; ?></a>
				</td>
			</tfoot>
		</table>
		</div>
	  </form>
  </div>
</div><script type="text/javascript"><!--
$('#tabs a').tabs();
//--></script>
<script type="text/javascript">
<!--
	var operation_row = <?php echo (isset($adjust)) ? count($adjust) : '0'; ?>;
	var operations = <?php echo json_encode($operations); ?>;
	
	function addOperation(){
		selected_op = $("#operationToAdd option:selected").val();
		if (operations[selected_op]) {
			ops = operations[selected_op];
			inputs = ops['inputs'];
			html = '<tr id="adjustment_row_' + operation_row + '">';
			html += '<td class="center">'+ops['name'];
			html += '<input type="hidden" name="adjust[' + operation_row + '][]" value="' + selected_op + '"/></td><td class="left">';
			for(i in inputs) {
				html += '&nbsp;' + inputs[i]["prepend"] + '&nbsp;';
				if (inputs[i]["type"] == 'text') {
					html += '<input type="text" name="adjust[' + operation_row + '][]" />';
				} else if (inputs[i]["type"] == 'field') {
					html += '<select name="adjust[' + operation_row + '][]"><option><?php echo $text_select; ?></option>';
					html += '<?php foreach ($fields as $field) { ?><option value="<?php echo $field; ?>"><?php echo $field; ?></option><?php } ?></select>';
				}
				if(inputs[i]["option"] == 'addMore') {
					html += '<a onclick="return addSub(this);" class="button"><span>More&nbsp;&rarr;&nbsp;</span></a>';
				}
			}
			html += '</td><td class="left"><a onclick="$(\'#adjustment_row_' + operation_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
			html += '</tr>';
			
			$('#operations').append(html);
			operation_row++;
		}
	}

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
	
-->
</script>
<?php echo $footer; ?>