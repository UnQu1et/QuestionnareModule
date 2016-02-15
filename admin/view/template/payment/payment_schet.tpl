<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_bank; ?></td>
          <td><input name="payment_schet_bank" type="text" size="100" value="<?php echo $payment_schet_bank; ?>" />
            <?php if ($error_bank) { ?>
            <span class="error"><?php echo $error_bank; ?></span>
            <?php } ?></td>
        </tr>
		<tr>
          <td><span class="required">*</span> <?php echo $entry_uradres; ?></td>
          <td><input name="payment_schet_uradres" type="text" size="100" value="<?php echo $payment_schet_uradres; ?>" />
            <?php if ($error_uradres) { ?>
            <span class="error"><?php echo $error_uradres; ?></span>
            <?php } ?></td>
        </tr>
		<tr>
          <td><span class="required">*</span> <?php echo $entry_faktadres; ?></td>
          <td><input name="payment_schet_faktadres" type="text" size="100" value="<?php echo $payment_schet_faktadres; ?>" /> 
            <?php if ($error_faktadres) { ?>
            <span class="error"><?php echo $error_faktadres; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_inn; ?></td>
          <td><input name="payment_schet_inn" type="text" size="20" value="<?php echo $payment_schet_inn; ?>" /> <?php echo $entry_kpp; ?> <input name="payment_schet_kpp" type="text" size="20" value="<?php echo $payment_schet_kpp; ?>" />
            <?php if ($error_inn) { ?>
            <span class="error"><?php echo $error_inn; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_rs; ?></td>
          <td><input name="payment_schet_rs" type="text" size="54" value="<?php echo $payment_schet_rs; ?>" /> 
            <?php if ($error_rs) { ?>
            <span class="error"><?php echo $error_rs; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_bankuser; ?></td>
          <td><input name="payment_schet_bankuser" type="text" size="100" value="<?php echo $payment_schet_bankuser; ?>" />
            <?php if ($error_bankuser) { ?>
            <span class="error"><?php echo $error_bankuser; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_bik; ?></td>
          <td><input name="payment_schet_bik" type="text" size="20" value="<?php echo $payment_schet_bik; ?>" />
            <?php if ($error_bik) { ?>
            <span class="error"><?php echo $error_bik; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_ks; ?></td>
          <td><input name="payment_schet_ks" type="text" size="54" value="<?php echo $payment_schet_ks; ?>" />
            <?php if ($error_ks) { ?>
            <span class="error"><?php echo $error_ks; ?></span>
            <?php } ?></td>
        </tr>
		<tr>
          <td><?php echo $entry_tel; ?></td>
          <td><input name="payment_schet_tel" type="text" size="20" value="<?php echo $payment_schet_tel; ?>" /></td>
        </tr>
		<tr>
          <td><?php echo $entry_mobtel; ?></td>
          <td><input name="payment_schet_mobtel" type="text" size="20" value="<?php echo $payment_schet_mobtel; ?>" /></td>
        </tr>
		<tr>
          <td><?php echo $entry_punkton; ?></td>
            <td><?php if ($config_punkton) { ?>
              <input type="radio" name="config_punkton" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="config_punkton" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="config_punkton" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="config_punkton" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?>
		  </td>
        </tr>
		<tr>
          <td><?php echo $entry_punkt; ?></td>
          <td><textarea name="payment_schet_punkt" cols="100" rows="10"><?php echo $payment_schet_punkt; ?></textarea></td>
        </tr>
		<tr>
          <td><?php echo $entry_image; ?></td>
          <td valign="middle">
		  <input name="payment_schet_image" type="text" size="27" value="<?php echo $payment_schet_image; ?>" id="image" />&nbsp;&nbsp;&nbsp;&nbsp;<a class="button" onclick="image_upload('image', 'thumb');"><?php echo $text_browse; ?></a></td>
        </tr>
		<tr>
          <td><?php echo $entry_podpis; ?></td>
          <td valign="middle">
		  <input name="payment_schet_podpis" type="text" size="27" value="<?php echo $payment_schet_podpis; ?>" id="images" />&nbsp;&nbsp;&nbsp;&nbsp;<a class="button" onclick="image_upload('images', 'thumb');"><?php echo $text_browse; ?></a></td>
        </tr>
        <tr>
          <td><?php echo $entry_order_status; ?></td>
          <td><select name="payment_schet_order_status_id">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $payment_schet_order_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_geo_zone; ?></td>
          <td><select name="payment_schet_geo_zone_id">
              <option value="0"><?php echo $text_all_zones; ?></option>
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <?php if ($geo_zone['geo_zone_id'] == $payment_schet_geo_zone_id) { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="payment_schet_status">
              <?php if ($payment_schet_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_sort_order; ?></td>
          <td><input type="text" name="payment_schet_sort_order" value="<?php echo $payment_schet_sort_order; ?>" size="1" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('description<?php echo $language['language_id']; ?>', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
<?php } ?>
//--></script> 
<script type="text/javascript"><!--
function image_upload(field, thumb) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).val()),
					dataType: 'text',
					success: function(data) {
						$('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 800,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script> 
<?php echo $footer; ?>