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
      <h1><img src="view/image/shipping.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">

      <div class="vtabs"><a href="#tab-general"><?php echo $tab_general; ?></a>
        <a href="#tab-geo-zones"><?php echo $tab_geo_zones; ?></a>
      </div>

      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general" class="vtabs-content">
          <table class="form">
            <tr>
              <td><?php echo $entry_tax_class; ?></td>
              <td><select name="pickpoint_tax_class_id">
                  <option value="0"><?php echo $text_none; ?></option>
                  <?php foreach ($tax_classes as $tax_class) { ?>
                  <?php if ($tax_class['tax_class_id'] == $pickpoint_tax_class_id) { ?>
                  <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="pickpoint_status">
                  <?php if ($pickpoint_status) { ?>
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
              <td><input type="text" name="pickpoint_sort_order" value="<?php echo $pickpoint_sort_order; ?>" size="1" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_shop_id; ?></td>
              <td><input type="text" name="pickpoint_shop_id" value="<?php echo $pickpoint_shop_id; ?>" size="10" /></td>
            </tr>

            <tr>
              <td><?php echo $entry_pickpoint_free_shipping; ?></td>
              <td><input type="text" name="pickpoint_free_shipping" value="<?php echo $pickpoint_free_shipping; ?>"  /></td>
            </tr>

		<?php if ($is_geo_zones_exist == false) { ?>
            <tr>
              <td></td>
              <td><div class="buttons"><a onclick="location = '<?php echo $create_geo_zones; ?>'" class="button"><?php echo $button_create; ?></a></div></td>
            </tr>
		<?php } ?>

          </table>
        </div>


        <div id="tab-geo-zones" class="vtabs-content">
          <table class="form" style="width:500px">
            <tr>
		<td><b><?php echo $entry_name; ?></b></td><td><b><?php echo $entry_rate; ?></b></td><td><b><?php echo $entry_status; ?></b></td>
		<tr/>


        <?php foreach ($geo_zones as $geo_zone) { ?>
            <tr>
			<td><?php echo $geo_zone['name']; ?></td>
                  <td><input type="text" name="pickpoint_<?php echo $geo_zone['geo_zone_id']; ?>_rate" value="<?php echo ${'pickpoint_' . $geo_zone['geo_zone_id'] . '_rate'}; ?>" </td>
	            <td>

<input type="checkbox" name="pickpoint_<?php echo $geo_zone['geo_zone_id']; ?>_status" value="1" 

<?php if (${'pickpoint_' . $geo_zone['geo_zone_id'] . '_status'}) { ?>

checked 
	
<?php } ?>
>
	
			</td>
            </tr>

        <?php } ?>

          </table>

        </div>

      </form>
    </div>
<br>
		<div style="text-align:center; color:#555555;">PickPoint v<?php echo $pickpoint_version; ?></div>

  </div>
</div>
<script type="text/javascript"><!--
$('.vtabs a').tabs(); 
//--></script> 
<?php echo $footer; ?> 