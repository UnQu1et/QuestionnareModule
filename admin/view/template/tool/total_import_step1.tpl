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
	<div id="tabs" class="htabs"><a href="#tab_fetch">Step 1: Fetch Feed</a><!-- <a href="#tab_adjust"><?php echo $tab_adjust; ?></a><a href="#tab_global"><?php echo $tab_global; ?></a><a href="#tab_mapping"><?php echo $tab_mapping; ?></a><a href="#tab_import"><?php echo $tab_import; ?></a> --></div>
	  <form action="<?php echo $action; ?>" method="post" name="settings_form" enctype="multipart/form-data" id="import_form">
		<input type='hidden' value='import_step1' name='step'/>
		<div id="tab_fetch">
		<table class="form">
			<!-- Feed Source -->
			<tr>
				<td><?php echo $entry_feed_source; ?></td>
				<td colspan="2">
					<select name="source" id="source" onchange="updateText(this, 'source');">
						<option value="file" <?php if (isset($source) && $source == 'file') echo 'selected="true"';?>><?php echo $entry_file_upload; ?></option>
						<option value="url" <?php if (isset($source) && $source == 'url') echo 'selected="true"';?>>URL</option>
						<option value="ftp" <?php if (isset($source) && $source == 'ftp') echo 'selected="true"';?>>FTP</option>
						<option value="site_price" <?php if (isset($source) && $source == 'site_price') echo 'selected="true"';?>>Загрузить прайсы с сайта поставщика</option>
						<option value="filepath" <?php if (isset($source) && $source == 'filepath') echo 'selected="true"';?>><?php echo $entry_file_system; ?></option>
					</select>
				</td>
			</tr>
			<!-- File -->
			<tr id="file">
				<td><?php echo $entry_import_file; ?>&nbsp;<span class="required" title="<?php echo $entry_required; ?>">*</span></td>
				<td colspan="2"><input type="file" name="feed_file" id="feed_file"/><span class="help"><?php echo $entry_max_file_size; ?></span></td>
			</tr>
			<!-- ...or URL -->
			<tr id="url">
				<td><?php echo $entry_import_url; ?>&nbsp;<span class="required" title="<?php echo $entry_required; ?>">*</span></td>
				<td colspan="2"><input type="text" size="70" name="feed_url" id="feed_url" value="<?php if (isset($feed_url)) echo $feed_url; ?>" /></td>
			</tr>
			<!-- ...or site -->
			<tr class="site_price">
				<td>Ссылка на авторизацию&nbsp;<span class="required" title="<?php echo $entry_required; ?>">*</span></td>
				<td colspan="2"><input type="text" size="70" name="site_price_reg" id="feed_ftpserver" value="<?php if (isset($site_price_reg)) echo $site_price_reg; ?>" /></td>
			</tr>
			<tr class="site_price">
				<td><?php echo $entry_ftp_user; ?>&nbsp;<span class="required" title="<?php echo $entry_required; ?>">*</span></td>
				<td colspan="2"><input type="text" size="70" name="site_price_user" id="feed_ftpuser" value="<?php if (isset($site_price_user)) echo $site_price_user; ?>" /></td>
			</tr>
			<tr class="site_price">
				<td><?php echo $entry_ftp_pass; ?>&nbsp;<span class="required" title="<?php echo $entry_required; ?>">*</span></td>
				<td colspan="2"><input type="text" size="70" name="site_price_pass" id="feed_ftppass" value="<?php if (isset($site_price_pass)) echo $site_price_pass; ?>" /></td>
			</tr>
			<tr class="site_price">
				<td>Ссылка на прайс&nbsp;<span class="required" title="<?php echo $entry_required; ?>">*</span></td>
				<td colspan="2">
				<textarea cols="100" name="site_price_path" rows="10"><?php if (isset($site_price_path)) echo $site_price_path; ?></textarea>
			</tr>
			<!-- ...or FTP -->
			<tr class="ftp">
				<td><?php echo $entry_ftp_server; ?>&nbsp;<span class="required" title="<?php echo $entry_required; ?>">*</span></td>
				<td colspan="2"><input type="text" size="70" name="feed_ftpserver" id="feed_ftpserver" value="<?php if (isset($feed_ftpserver)) echo $feed_ftpserver; ?>" /></td>
			</tr>
			<tr class="ftp">
				<td><?php echo $entry_ftp_user; ?>&nbsp;<span class="required" title="<?php echo $entry_required; ?>">*</span></td>
				<td colspan="2"><input type="text" size="70" name="feed_ftpuser" id="feed_ftpuser" value="<?php if (isset($feed_ftpuser)) echo $feed_ftpuser; ?>" /></td>
			</tr>
			<tr class="ftp">
				<td><?php echo $entry_ftp_pass; ?>&nbsp;<span class="required" title="<?php echo $entry_required; ?>">*</span></td>
				<td colspan="2"><input type="text" size="70" name="feed_ftppass" id="feed_ftppass" value="<?php if (isset($feed_ftppass)) echo $feed_ftppass; ?>" /></td>
			</tr>
			<tr class="ftp">
				<td><?php echo $entry_ftp_path; ?>&nbsp;<span class="required" title="<?php echo $entry_required; ?>">*</span></td>
				<td colspan="2"><input type="text" size="70" name="feed_ftppath" value="<?php if (isset($feed_ftppath)) echo $feed_ftppath; ?>" /></td>
			</tr>
			<!-- ...or File Path -->
			<tr id="filepath">
				<td><?php echo $entry_import_filepath; ?>&nbsp;<span class="required" title="<?php echo $entry_required; ?>">*</span></td>
				<td colspan="2"><input type="text" name="feed_filepath" id="feed_filepath"/></td>
			</tr>
			<!-- Feed Format -->
			<tr>
				<td>Метод запроса "Ссылка на прайс"</td>
				<td colspan="2">
					<select name="method_site_price" onchange="updateText(this, 'format');" id="format">
						<option value="get">GET</option>
						<option value="post" <?php if (isset($method_site_price) && $method_site_price == 'post') echo 'selected="true"'; ?>>POST</option>
					</select>
				</td>
			</tr>
			<tr>
				<td><?php echo $entry_feed_format; ?></td>
				<td colspan="2">
					<select name="format" onchange="updateText(this, 'format');" id="format">
						<option value="csv">CSV</option>
						<option value="xml" <?php if (isset($format) && $format == 'xml') echo 'selected="true"'; ?>>XML</option>
					</select>
				</td>
			</tr>
			<!-- (XML Only) Product Tag -->
			<tr id="xml">
				<td><?php echo $entry_xml_product_tag; ?>&nbsp;<span class="required" title="<?php echo $entry_required; ?>">*</span></td>
				<td colspan="2"><input type="text" name="xml_product_tag" id="xml_product_tag" value="<?php if (isset($xml_product_tag)) echo $xml_product_tag; ?>"></td>
			</tr>
			<!-- (CSV Only) Delimiter -->
			<tr class="csv">
				<td><?php echo $entry_delimiter; ?></td>
				<td colspan="2">
					<select name="delimiter" id="delimiter">
						<option value="," <?php if (isset($delimiter) && $delimiter == ',') { echo 'selected="true"'; } ?>>,</option>
						<option value="\t" <?php if (isset($delimiter) && $delimiter == '\t') { echo 'selected="true"'; } ?>>Tab</option>
						<option value="|" <?php if (isset($delimiter) && $delimiter == '|') { echo 'selected="true"'; } ?>>|</option>
						<option value=";" <?php if (isset($delimiter) && $delimiter == ';') { echo 'selected="true"'; } ?>>;</option>
						<option value="^" <?php if (isset($delimiter) && $delimiter == '^') { echo 'selected="true"'; } ?>>^</option>
						<option value="~" <?php if (isset($delimiter) && $delimiter == '~') { echo 'selected="true"'; } ?>>~</option>
					</select>
				</td>
			</tr>
		</table>
		
		<div id="accordion">
			<h3><a href="#"><?php echo $entry_advanced; ?></a></h3>
			<div>
				<table class="form">
				<tr class="csv">
					<td><?php echo $entry_first_row_is_headings; ?></td>
					<td colspan="2"><input type="checkbox" name="has_headers" id="has_headers" <?php if (!isset($has_headers) || (isset($has_headers) && $has_headers == 'on')) echo 'checked="1"';?>/></td>
				</tr>
				<tr class="csv">	
					<!-- Use Safe Headers -->
					<td><?php echo $entry_use_safe_headings; ?><span class="help"><?php echo $entry_use_safe_headings_help; ?></span></td>
					<td colspan="2"><input type="checkbox" name="safe_headers" id="safe_headers" <?php if (isset($safe_headers)) echo 'checked="1" '; ?>/></td>
				</tr>
				<!-- Unzip feed -->
				<tr>
					<td>Gzip Content-type</td>
					<td colspan="2"><input type="checkbox" name="gzip_feed" id="unzip_feed" <?php if (isset($gzip_feed)) echo 'checked="1" '; ?>/></td>
				</tr>
				<tr>
					<td><?php echo $entry_unzip_feed; ?></td>
					<td colspan="2"><input type="checkbox" name="unzip_feed" id="unzip_feed" <?php if (isset($unzip_feed)) echo 'checked="1" '; ?>/></td>
				</tr>
				<!-- File Encoding -->
				<tr>
				<td><?php echo $entry_file_encoding; ?>
					<a href="http://helpdesk.hostjars.com/entries/21806583-file-encoding-for-imports" target="_blank"><img class="info_image" src="view/image/information.png" title="Information About Encoding"/></a>
					<span class="help"><?php echo $entry_file_encoding_help; ?></span>
				</td>
				<td colspan="2">
					<select name="file_encoding" id="file_encoding">
						<option value="UTF-8" <?php if (isset($file_encoding) && $file_encoding == 'UTF-8') { echo 'selected="true"'; } ?>>UTF-8</option>
						<option value="ISO-8859-1" <?php if (isset($file_encoding) && $file_encoding == 'ISO-8859-1') { echo 'selected="true"'; } ?>>ISO-8859-1</option>
						<option value="US-ASCII" <?php if (isset($file_encoding) && $file_encoding == 'US-ASCII') { echo 'selected="true"'; } ?>>ASCII</option>
						<option value="windows-1251" <?php if (isset($file_encoding) && $file_encoding == 'windows-1251') { echo 'selected="true"'; } ?>>windows-1251</option>
					</select>
				</td>
				</tr>
			</table>
			</div>
		</div>
	   </div>
	  </form>
  </div>
</div><script type="text/javascript"><!--
function updateText(el, name) {
	var action = el.value;
	if (name == 'source') {
		$("#file, #url, #filepath, .ftp, .site_price").hide();
	} else {
		$("#xml, .csv").hide();
	}
	$("#"+action+", ."+action).show();
}

$(document).ready(function() {
	updateText(document.settings_form.source, 'source');
	updateText(document.settings_form.format, 'format');
	
	$('#tabs a').tabs();
	$("#accordion").accordion({
		collapsible: true, active: false 
	});
});

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