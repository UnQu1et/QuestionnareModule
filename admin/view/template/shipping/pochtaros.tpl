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
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">

          <tr>
            <td><?php echo $entry_name; ?></td>
            <td><input type="text" name="pochtaros_name" value="<?php if (isset($pochtaros_name)) echo $pochtaros_name; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_cost; ?></td>
            <td><input type="text" name="pochtaros_cost" value="<?php if (isset($pochtaros_cost)) echo $pochtaros_cost; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_city; ?></td>
            <td><input type="text" name="pochtaros_city" value="<?php if (isset($pochtaros_city)) echo $pochtaros_city; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_zone; ?></td>
            <td><select name="pochtaros_zone">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($zones as $zone) { ?>
                <?php if ($zone['name'] == $pochtaros_zone) { ?>
                <option value="<?php echo $zone['name']; ?>" selected="selected"><?php echo $zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $zone['name']; ?>"><?php echo $zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="pochtaros_status">
                <?php if ($pochtaros_status) { ?>
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
            <td><input type="text" name="pochtaros_sort_order" value="<?php echo $pochtaros_sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>