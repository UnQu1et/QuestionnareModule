<?php echo $header; ?>
<?php
$selectbox_javascript = '';
function selectbox_html($name, $value, $fields, $type='hidden', $help = '') {
    global $selectbox_javascript;
    global $text_select;

    $n = md5($name);
    $html = '<span class="help">use drag and drop to sort the items</span>';
    $html .= '<div id="msc_'.$n.'" class="sortable"></div>'.
            '<input type="'.$type.'" '.($type == 'text' ? 'size="100"' : '').' name="'.$name.'" id="input_'.$n.'" value="'.$value.'">';
    $html .= $help ? '<span class="help">'.$help.'</span>' : '';
    $html .= '<select style="margin-top:5px;" id="select_'.$n.'">'.
            '<option value="">'.$text_select.'</option>';
    foreach ($fields as $key => $text) {
        $style = '';
        if (strpos($key, 'header_') === 0) {
            $style = 'style="font-weight:bold;"';
        }
        $html .= '<option '.$style.' value="'.$key.'">'.$text.'</option>';
    }
    $html .= '</select>';

    $selectbox_javascript .= '$("#select_'.$n.'").multiSelect("#input_'.$n.'","#msc_'.$n.'");';

    return $html;
}
?>

<style>
#footer {margin-top:0px;}
.list .help {
    max-width: 300px;
}
.stores {
    margin-right: 5px;
    display: inline-block;
}
.htabs a {
    margin-top: 3px;
    margin-bottom: 0px;
    border-bottom: 1px solid #DDDDDD;
}
.htabs a.selected {
    margin-bottom: 0px;
    border-bottom: none;
}
</style>
<script type="text/javascript" src="view/javascript/jquery/ui/i18n/jquery.ui.datepicker-<?php echo $current_language ?>.js"></script>
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
    <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons">
        <?php if (count($stores) > 0) { ?>
        <div class="stores">
            Store:&nbsp;
            <select name="store_id" id="store_id" onchange="location='<?php echo $action_without_store; ?>'+'&store_id='+$(this).val()">
                <?php foreach ($stores as $key => $value) { ?>
                    <option value="<?php echo $value['store_id'] ?>" <?php echo $store_id == $value['store_id'] ? 'selected="selected"' : '' ?>><?php echo $value['store_id'] ?> - <?php echo $value['name'] ?></option>
                <?php } ?>
            </select>
        </div>
        <?php } ?>
        <a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <div id="tabs" class="htabs">
        <a href="#tab-checkout"><?php echo $tab_checkout; ?></a>
        <a href="#tab-registration"><?php echo $tab_registration; ?></a>
        <a href="#tab-pages"><?php echo $tab_account_pages; ?></a>
        <a href="#tab-customer-fields"><?php echo $tab_customer_fields; ?></a>
        <a href="#tab-headers"><?php echo $tab_headers; ?></a>
        <a href="#tab-methods"><?php echo $tab_methods; ?></a>
        <a href="#tab-googleapi">Google API</a>
        <a href="#tab-joomla">Joomla</a>
        <a href="#tab-template">Template Helper</a>
        <a href="#tab-backup"><?php echo $tab_backup ?></a>
    </div>
    <div style="width:100%;height:1px;clear:both;"></div>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
    
      <div id="tab-checkout">
        <h3><?php echo $text_simplecheckout ?></h3>
        <table class="form">
          <tr>
            <td>
                <span class="required">*</span> <?php echo $entry_template; ?>
                <span class="help"><?php echo $entry_template_description; ?></span>
            </td>
            <td><textarea name="simple_common_template" cols="150" rows="5"><?php echo $simple_common_template ?></textarea>
              <?php if (isset($error_simple_common_template)) { ?>
              <span class="error"><?php echo $error_simple_common_template ?></span>
              <?php } ?>
                <div class="help">Examples:</div>
                <br>
                <div class="help">Two columns:</div>
                <div class="help">{left_column}{cart}{customer}{/left_column}{right_column}{shipping}{payment}{help}{agreement}{/right_column}{payment_form}</div>
                <br>
                <div class="help">One column:</div>
                <div class="help">{help}{customer}{shipping}{payment}{cart}{agreement}{payment_form}</div>
                <br>
                <div class="help">Combined:</div>
                <div class="help">{help}{customer}{left_column}{shipping}{/left_column}{right_column}{payment}{/right_column}{cart}{agreement}{payment_form}</div>
                <br>
                <div class="help">Three columns:</div>
                <div class="help">{three_column}{customer}{/three_column}{three_column}{shipping}{payment}{/three_column}{three_column}{cart}{agreement}{/three_column}{payment_form}</div>
            </td>
          </tr>
          <tr>
            <td>
                <?php echo $entry_steps ?>:
            </td>
            <td>
                <label><input type="radio" name="simple_steps" value="1" <?php if ($simple_steps) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_steps" value="0" <?php if (!$simple_steps) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
            </td>
          </tr>
          <tr>
            <td>
                <?php echo $entry_steps_summary ?>:
            </td>
            <td>
                <label><input type="radio" name="simple_steps_summary" value="1" <?php if ($simple_steps_summary) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_steps_summary" value="0" <?php if (!$simple_steps_summary) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
            </td>
          </tr>
          <tr>
            <td>
                Debug mode:
            </td>
            <td>
                <label><input type="radio" name="simple_debug" value="1" <?php if ($simple_debug) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_debug" value="0" <?php if (!$simple_debug) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
            </td>
          </tr>
          <tr>
            <td>
                <?php echo $entry_guest_checkout ?>:
            </td>
            <td>
                <label><input type="radio" name="simple_disable_guest_checkout" value="1" <?php if ($simple_disable_guest_checkout) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_disable_guest_checkout" value="0" <?php if (!$simple_disable_guest_checkout) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
            </td>
          </tr>
          <tr>
            <td>
                <?php echo $entry_empty_email ?>:
            </td>
            <td>
                <input type="text" name="simple_empty_email" value="<?php echo $simple_empty_email ?>">
            </td>
          </tr>
          <tr>
            <td>
                <?php echo $entry_fields_for_reload; ?>
            </td>
            <td>
                <?php echo selectbox_html('simple_set_for_reload', $simple_set_for_reload, $simple_fields_for_checkout_customer) ?>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_use_cookies; ?>:
            </td>
            <td>
                <label><input type="radio" name="simple_use_cookies" value="1" <?php if ($simple_use_cookies) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_use_cookies" value="0" <?php if (!$simple_use_cookies) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
            </td>
          </tr>
          <tr>
            <td>
                Show button 'back':
            </td>
            <td>
                <label><input type="radio" name="simple_show_back" value="1" <?php if ($simple_show_back) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_show_back" value="0" <?php if (!$simple_show_back) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
            </td>
          </tr>
          <tr>
            <td>
                <?php echo $text_checkout_asap_not_logged; ?>:
            </td>
            <td>
                <label><input type="radio" name="simple_checkout_asap_for_not_logged" value="1" <?php if ($simple_checkout_asap_for_not_logged) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_checkout_asap_for_not_logged" value="0" <?php if (!$simple_checkout_asap_for_not_logged) { ?>checked="checked"<?php } ?>><?php echo $text_no_only_after_click ?></label>
            </td>
          </tr>
          <tr>
            <td>
                <?php echo $text_checkout_asap_logged; ?>:
            </td>
            <td>
                <label><input type="radio" name="simple_checkout_asap_for_logged" value="1" <?php if ($simple_checkout_asap_for_logged) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_checkout_asap_for_logged" value="0" <?php if (!$simple_checkout_asap_for_logged) { ?>checked="checked"<?php } ?>><?php echo $text_no_only_after_click ?></label>
            </td>
          </tr>
          <tr>
            <td>
                GeoIP Mode:
            </td>
            <td>
                <select name="simple_geoip_mode" id="simple_geoip_mode">
                    <option value="1" <?php echo $simple_geoip_mode == 1 ? 'selected="selected"' : '' ?>>own from geo_ip table</option>
                    <option value="2" <?php echo $simple_geoip_mode == 2 ? 'selected="selected"' : '' ?>>maxmind as extension</option>
                    <option value="3" <?php echo $simple_geoip_mode == 3 ? 'selected="selected"' : '' ?>>maxmind as table</option>
                </select>
            </td>
          </tr>
          <tr>
            <td>
                <?php echo $entry_customer_type_selection; ?>:
            </td>
            <td>
                <select name="simple_type_of_selection_of_group" id="simple_type_of_selection_of_group">
                    <option value="select" <?php echo $simple_type_of_selection_of_group == 'select' ? 'selected="selected"' : '' ?>>select</option>
                    <option value="radio" <?php echo $simple_type_of_selection_of_group == 'radio' ? 'selected="selected"' : '' ?>>radio</option>
                </select>
            </td>
          </tr>
          <tr>
            <td>
                <?php echo $entry_customer_group_after_reg; ?>:
            </td>
            <td>
                <select name="simple_customer_group_id_after_reg" id="simple_customer_group_id_after_reg">
                    <?php foreach ($groups as $group) { ?>
                        <option value="<?php echo $group['customer_group_id'] ?>" <?php echo $simple_customer_group_id_after_reg == $group['customer_group_id'] ? 'selected="selected"' : '' ?>><?php echo $group['name'] ?></option>
                    <?php } ?>
                </select>
            </td>
          </tr>
          <tr>
            <td>
                <?php echo $entry_comment_target; ?>:
            </td>
            <td>
                <select name="simple_comment_target" id="simple_comment_target">
                    <option value="" <?php echo !$simple_comment_target ? 'selected="selected"' : '' ?>>not move</option>
                    <option value="bottom" <?php echo $simple_comment_target == 'bottom' ? 'selected="selected"' : '' ?>>bottom</option>
                    <optgroup label="after">
                    <option value="customer" <?php echo $simple_comment_target == 'customer' ? 'selected="selected"' : '' ?>>customer</option>
                    <option value="cart" <?php echo $simple_comment_target == 'cart' ? 'selected="selected"' : '' ?>>cart</option>
                    <option value="shipping" <?php echo $simple_comment_target == 'shipping' ? 'selected="selected"' : '' ?>>shipping</option>
                    <option value="payment" <?php echo $simple_comment_target == 'payment' ? 'selected="selected"' : '' ?>>payment</option>
                    <option value="agreement" <?php echo $simple_comment_target == 'agreement' ? 'selected="selected"' : '' ?>>agreement</option>
                    <option value="help" <?php echo $simple_comment_target == 'help' ? 'selected="selected"' : '' ?>>help</option>
                    </optgroup>
                </select>
            </td>
          </tr>
          <tr>
            <td>
                <?php echo $entry_minify; ?>:
            </td>
            <td>
                <label><input type="radio" name="simple_minify" value="1" <?php if ($simple_minify) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_minify" value="0" <?php if (!$simple_minify) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
            </td>
          </tr>
        </table>
        <h3><?php echo $text_order_minmax ?></h3>
        <table class="form">
          <tr>
            <td>
                <?php echo $entry_use_total; ?>
            </td>
            <td>
                <label><input type="radio" name="simple_use_total" value="1" <?php if ($simple_use_total) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_use_total" value="0" <?php if (!$simple_use_total) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
            </td>
          </tr>
          <tr>
            <td>
                <?php echo $entry_show_weight; ?>
            </td>
            <td>
                <label><input type="radio" name="simple_show_weight" value="1" <?php if ($simple_show_weight) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_show_weight" value="0" <?php if (!$simple_show_weight) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
            </td>
          </tr>
          <tr>
            <td>
                <?php echo $entry_min_amount; ?>
            </td>
            <td>
                <input type="text" name="simple_min_amount" value="<?php echo $simple_min_amount ?>">
            </td>
          </tr>
          <tr>
            <td>
                <?php echo $entry_max_amount; ?>
            </td>
            <td>
                <input type="text" name="simple_max_amount" value="<?php echo $simple_max_amount ?>">
            </td>
          </tr>
          <tr>
            <td>
                <?php echo $entry_min_quantity; ?>
            </td>
            <td>
                <input type="text" name="simple_min_quantity" value="<?php echo $simple_min_quantity ?>">
            </td>
          </tr>
          <tr>
            <td>
                <?php echo $entry_max_quantity; ?>
            </td>
            <td>
                <input type="text" name="simple_max_quantity" value="<?php echo $simple_max_quantity ?>">
            </td>
          </tr>
          <tr>
            <td>
                <?php echo $entry_min_weight; ?>
            </td>
            <td>
                <input type="text" name="simple_min_weight" value="<?php echo $simple_min_weight ?>">
            </td>
          </tr>
          <tr>
            <td>
                <?php echo $entry_max_weight; ?>
            </td>
            <td>
                <input type="text" name="simple_max_weight" value="<?php echo $simple_max_weight ?>">
            </td>
          </tr>
        </table>
        <h3><?php echo $text_agreement_block ?></h3>
        <table class="form">
          <tr>
            <td>
                <?php echo $entry_agreement_id; ?>
            </td>
            <td>
                <select name="simple_common_view_agreement_id">
                    <option value="0"><?php echo $text_select ?></option>
                    <?php foreach ($informations as $information) { ?>
                    <option value="<?php echo $information['information_id'] ?>" <?php if ($information['information_id'] == $simple_common_view_agreement_id) { ?>selected="selected"<?php } ?>><?php echo $information['title'] ?></option>
                    <?php } ?>
                </select>
              <?php if (isset($error_simple_common_view_agreement_id)) { ?>
              <span class="error"><?php echo $error_simple_common_view_agreement_id ?></span>
              <?php } ?>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_agreement_text; ?>
            </td>
            <td>
                <label><input type="radio" name="simple_common_view_agreement_text" value="1" <?php if ($simple_common_view_agreement_text) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_common_view_agreement_text" value="0" <?php if (!$simple_common_view_agreement_text) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                <?php if (isset($error_simple_common_view_agreement_text)) { ?>
                <span class="error"><?php echo $error_simple_common_view_agreement_text ?></span>
                <?php } ?>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_agreement_checkbox; ?>
            </td>
            <td>
                <label><input type="radio" name="simple_common_view_agreement_checkbox" value="1" <?php if ($simple_common_view_agreement_checkbox) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_common_view_agreement_checkbox" value="0" <?php if (!$simple_common_view_agreement_checkbox) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                <?php if (isset($error_simple_common_view_agreement_checkbox)) { ?>
                <span class="error"><?php echo $error_simple_common_view_agreement_checkbox ?></span>
                <?php } ?>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_agreement_checkbox_init; ?>
            </td>
            <td>
                <label><input type="radio" name="simple_common_view_agreement_checkbox_init" value="1" <?php if ($simple_common_view_agreement_checkbox_init) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_common_view_agreement_checkbox_init" value="0" <?php if (!$simple_common_view_agreement_checkbox_init) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                <?php if (isset($error_simple_common_view_agreement_checkbox_init)) { ?>
                <span class="error"><?php echo $error_simple_common_view_agreement_checkbox_init ?></span>
                <?php } ?>
            </td>  
          </tr>
        </table>
        <h3><?php echo $text_help_block ?></h3>
        <table class="form">
          <tr>
            <td>
                <?php echo $entry_help_id; ?>
            </td>
            <td>
                <select name="simple_common_view_help_id">
                    <option value="0"><?php echo $text_select ?></option>
                    <?php foreach ($informations as $information) { ?>
                    <option value="<?php echo $information['information_id'] ?>" <?php if ($information['information_id'] == $simple_common_view_help_id) { ?>selected="selected"<?php } ?>><?php echo $information['title'] ?></option>
                    <?php } ?>
                </select>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_help_text; ?>
            </td>
            <td>
                <label><input type="radio" name="simple_common_view_help_text" value="1" <?php if ($simple_common_view_help_text) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_common_view_help_text" value="0" <?php if (!$simple_common_view_help_text) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
            </td>  
          </tr>
        </table>
        <h3><?php echo $text_shipping_block ?></h3>
        <table class="form">
          <tr>
            <td>
                <?php echo $entry_hide; ?>
            </td>
            <td>
                <label><input type="radio" name="simple_shipping_methods_hide" value="1" <?php if ($simple_shipping_methods_hide) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_shipping_methods_hide" value="0" <?php if (!$simple_shipping_methods_hide) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                <?php if (isset($error_simple_shipping_methods_hide)) { ?>
                <span class="error"><?php echo $error_simple_shipping_methods_hide ?></span>
                <?php } ?>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_shipping_title; ?>
            </td>
            <td>
                <label><input type="radio" name="simple_shipping_view_title" value="1" <?php if ($simple_shipping_view_title) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_shipping_view_title" value="0" <?php if (!$simple_shipping_view_title) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                <?php if (isset($error_simple_shipping_view_title)) { ?>
                <span class="error"><?php echo $error_simple_shipping_view_title ?></span>
                <?php } ?>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_shipping_address_empty; ?>
            </td>
            <td>
                <label><input type="radio" name="simple_shipping_view_address_empty" value="1" <?php if ($simple_shipping_view_address_empty) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_shipping_view_address_empty" value="0" <?php if (!$simple_shipping_view_address_empty) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                <?php if (isset($error_simple_shipping_view_address_empty)) { ?>
                <span class="error"><?php echo $error_simple_shipping_view_address_empty ?></span>
                <?php } ?>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_shipping_autoselect_first; ?>
            </td>
            <td>
                <label><input type="radio" name="simple_shipping_view_autoselect_first" value="1" <?php if ($simple_shipping_view_autoselect_first) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_shipping_view_autoselect_first" value="0" <?php if (!$simple_shipping_view_autoselect_first) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                <?php if (isset($error_simple_shipping_view_autoselect_first)) { ?>
                <span class="error"><?php echo $error_simple_shipping_view_autoselect_first ?></span>
                <?php } ?>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_shipping_address_full; ?>
                <span class="help"><?php echo $entry_shipping_address_full_description; ?></span>
            </td>
            <td>
                <table>
                    <?php foreach ($shipping_extensions as $shipping_code => $shipping_name) { ?>
                    <tr>
                        <td>
                        <?php echo $shipping_name ?>
                        </td>
                        <td>
                            <label><input type="radio" name="simple_shipping_view_address_full[<?php echo $shipping_code ?>]" value="1" <?php if (!empty($simple_shipping_view_address_full[$shipping_code])) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                            <label><input type="radio" name="simple_shipping_view_address_full[<?php echo $shipping_code ?>]" value="0" <?php if (empty($simple_shipping_view_address_full[$shipping_code])) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </td>  
          </tr>
        </table>
        <h3><?php echo $text_payment_block ?></h3>
        <table class="form">
          <tr>
            <td>
                <?php echo $entry_hide; ?>
            </td>
            <td>
                <label><input type="radio" name="simple_payment_methods_hide" value="1" <?php if ($simple_payment_methods_hide) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_payment_methods_hide" value="0" <?php if (!$simple_payment_methods_hide) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                <?php if (isset($error_simple_shipping_methods_hide)) { ?>
                <span class="error"><?php echo $error_simple_shipping_methods_hide ?></span>
                <?php } ?>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_payment_address_empty; ?>
            </td>
            <td>
                <label><input type="radio" name="simple_payment_view_address_empty" value="1" <?php if ($simple_payment_view_address_empty) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_payment_view_address_empty" value="0" <?php if (!$simple_payment_view_address_empty) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                <?php if (isset($error_simple_payment_view_address_empty)) { ?>
                <span class="error"><?php echo $error_simple_payment_view_address_empty ?></span>
                <?php } ?>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_payment_autoselect_first; ?>
            </td>
            <td>
                <label><input type="radio" name="simple_payment_view_autoselect_first" value="1" <?php if ($simple_payment_view_autoselect_first) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_payment_view_autoselect_first" value="0" <?php if (!$simple_payment_view_autoselect_first) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                <?php if (isset($error_simple_payment_view_autoselect_first)) { ?>
                <span class="error"><?php echo $error_simple_payment_view_autoselect_first ?></span>
                <?php } ?>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_payment_address_full; ?>
                <span class="help"><?php echo $entry_payment_address_full_description; ?></span>
            </td>
            <td>
                <table>
                    <?php foreach ($payment_extensions as $payment_code => $payment_name) { ?>
                    <tr>
                        <td>
                        <?php echo $payment_name ?>
                        </td>
                        <td>
                            <label><input type="radio" name="simple_payment_view_address_full[<?php echo $payment_code ?>]" value="1" <?php if (!empty($simple_payment_view_address_full[$payment_code])) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                            <label><input type="radio" name="simple_payment_view_address_full[<?php echo $payment_code ?>]" value="0" <?php if (empty($simple_payment_view_address_full[$payment_code])) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </td>  
          </tr>
        </table>
        <h3><?php echo $text_customer_block ?></h3>
        <table class="form">
          <tr>
            <td>
                <?php echo $entry_hide_if_logged; ?>
            </td>
            <td>
                <label><input type="radio" name="simple_customer_hide_if_logged" value="1" <?php if ($simple_customer_hide_if_logged) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_customer_hide_if_logged" value="0" <?php if (!$simple_customer_hide_if_logged) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                <?php if (isset($error_simple_customer_hide_if_logged)) { ?>
                <span class="error"><?php echo $error_simple_customer_hide_if_logged ?></span>
                <?php } ?>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_show_will_be_registerd; ?>:
            </td>
            <td>
                <label><input type="radio" name="simple_show_will_be_registered" value="1" <?php if ($simple_show_will_be_registered) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_show_will_be_registered" value="0" <?php if (!$simple_show_will_be_registered) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
            </td>
          </tr>
          <tr>
            <td>
                <?php echo $entry_customer_register; ?>
            </td>
            <td>
                <label><input type="radio" name="simple_customer_action_register" value="1" <?php if ($simple_customer_action_register == 1) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_customer_action_register" value="0" <?php if (!$simple_customer_action_register) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                <label><input type="radio" name="simple_customer_action_register" value="2" <?php if ($simple_customer_action_register == 2) { ?>checked="checked"<?php } ?>><?php echo $text_user_choice ?></label>
                <?php if (isset($error_simple_customer_action_register)) { ?>
                <span class="error"><?php echo $error_simple_customer_action_register ?></span>
                <?php } ?>
            </td>  
          </tr>
          <tr id="customer_view_email" <?php if ($simple_customer_action_register == 1) { ?>style="display:none"<?php } ?>>
            <td>
                <?php echo $entry_customer_email_field; ?>
            </td>
            <td>
                <label><input type="radio" name="simple_customer_view_email" value="0" <?php if ($simple_customer_view_email == 0) { ?>checked="checked"<?php } ?>><?php echo $text_hide ?></label>
                <label><input type="radio" name="simple_customer_view_email" value="1" <?php if ($simple_customer_view_email == 1) { ?>checked="checked"<?php } ?>><?php echo $text_show_not_required ?></label>
                <label><input type="radio" name="simple_customer_view_email" value="2" <?php if ($simple_customer_view_email == 2) { ?>checked="checked"<?php } ?>><?php echo $text_required ?></label>
                <?php if (isset($error_simple_customer_view_email)) { ?>
                <span class="error"><?php echo $error_simple_customer_view_email ?></span>
                <?php } ?>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_confirm_email; ?>:
            </td>
            <td>
                <label><input type="radio" name="simple_customer_view_email_confirm" value="1" <?php if ($simple_customer_view_email_confirm) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_customer_view_email_confirm" value="0" <?php if (!$simple_customer_view_email_confirm) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
            </td>
          </tr>
          <tr id="customer_register_init" <?php if ($simple_customer_action_register != 2) { ?>style="display:none"<?php } ?>>
            <td>
                <?php echo $entry_customer_register_init; ?>
            </td>
            <td>
                <label><input type="radio" name="simple_customer_view_customer_register_init" value="1" <?php if ($simple_customer_view_customer_register_init) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_customer_view_customer_register_init" value="0" <?php if (!$simple_customer_view_customer_register_init) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                <?php if (isset($error_simple_customer_view_customer_register_init)) { ?>
                <span class="error"><?php echo $error_simple_customer_view_customer_register_init ?></span>
                <?php } ?>
            </td>  
          </tr>
          <tr id="customer_password_generate" <?php if ($simple_customer_action_register == 0) { ?>style="display:none"<?php } ?>>
            <td>
                <?php echo $entry_generate_password; ?>
            </td>
            <td>
                <label><input type="radio" name="simple_customer_generate_password" value="1" <?php if ($simple_customer_generate_password) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_customer_generate_password" value="0" <?php if (!$simple_customer_generate_password) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                <?php if (isset($error_simple_customer_generate_password)) { ?>
                <span class="error"><?php echo $error_simple_customer_generate_password ?></span>
                <?php } ?>
            </td>  
          </tr>
          <tr id="customer_password_confirm" <?php if ($simple_customer_action_register == 0) { ?>style="display:none"<?php } ?>>
            <td>
                <?php echo $entry_customer_password_confirm; ?>
            </td>
            <td>
                <label><input type="radio" name="simple_customer_view_password_confirm" value="1" <?php if ($simple_customer_view_password_confirm) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_customer_view_password_confirm" value="0" <?php if (!$simple_customer_view_password_confirm) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                <?php if (isset($error_simple_customer_view_password_confirm)) { ?>
                <span class="error"><?php echo $error_simple_customer_view_password_confirm ?></span>
                <?php } ?>
            </td>  
          </tr>
          <tr id="customer_password_length" <?php if ($simple_customer_action_register == 0) { ?>style="display:none"<?php } ?>>
            <td>
                <?php echo $entry_password_length; ?>
            </td>
            <td>
                min <input type="text" size="3" name="simple_customer_view_password_length_min" value="<?php echo $simple_customer_view_password_length_min ?>" >
                max <input type="text" size="3" name="simple_customer_view_password_length_max" value="<?php echo $simple_customer_view_password_length_max ?>" >
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_customer_subscribe; ?>
            </td>
            <td>
                <label><input type="radio" name="simple_customer_action_subscribe" value="1" <?php if ($simple_customer_action_subscribe == 1) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_customer_action_subscribe" value="0" <?php if (!$simple_customer_action_subscribe) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                <label><input type="radio" name="simple_customer_action_subscribe" value="2" <?php if ($simple_customer_action_subscribe == 2) { ?>checked="checked"<?php } ?>><?php echo $text_user_choice ?></label>
                <?php if (isset($error_simple_customer_action_subscribe)) { ?>
                <span class="error"><?php echo $error_simple_customer_action_subscribe ?></span>
                <?php } ?>
            </td>  
          </tr>
          <tr id="customer_subscribe_init" <?php if ($simple_customer_action_subscribe != 2) { ?>style="display:none"<?php } ?>>
            <td>
                <?php echo $entry_customer_subscribe_init; ?>
            </td>
            <td>
                <label><input type="radio" name="simple_customer_view_customer_subscribe_init" value="1" <?php if ($simple_customer_view_customer_subscribe_init) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_customer_view_customer_subscribe_init" value="0" <?php if (!$simple_customer_view_customer_subscribe_init) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                <?php if (isset($error_simple_customer_view_customer_subscribe_init)) { ?>
                <span class="error"><?php echo $error_simple_customer_view_customer_subscribe_init ?></span>
                <?php } ?>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_customer_login; ?>
            </td>
            <td>
                <label><input type="radio" name="simple_customer_view_login" value="1" <?php if ($simple_customer_view_login) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_customer_view_login" value="0" <?php if (!$simple_customer_view_login) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                <?php if (isset($error_simple_customer_view_login)) { ?>
                <span class="error"><?php echo $error_simple_customer_view_login ?></span>
                <?php } ?>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_customer_address_select; ?>
            </td>
            <td>
                <label><input type="radio" name="simple_customer_view_address_select" value="1" <?php if ($simple_customer_view_address_select) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_customer_view_address_select" value="0" <?php if (!$simple_customer_view_address_select) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                <?php if (isset($error_simple_customer_view_address_select)) { ?>
                <span class="error"><?php echo $error_simple_customer_view_address_select ?></span>
                <?php } ?>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_customer_type; ?>
            </td>
            <td>
                <label><input type="radio" name="simple_customer_view_customer_type" value="1" <?php if ($simple_customer_view_customer_type) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_customer_view_customer_type" value="0" <?php if (!$simple_customer_view_customer_type) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                <?php if (isset($error_simple_customer_view_customer_type)) { ?>
                <span class="error"><?php echo $error_simple_customer_view_customer_type ?></span>
                <?php } ?>
            </td>  
          </tr>
          <?php foreach($groups as $group) { ?>
          <tr>
            <td>
                <?php echo $group['name']; ?>
            </td>
            <td>
                <?php echo selectbox_html('simple_set_checkout_customer[group]['.$group['customer_group_id'].']', isset($simple_set_checkout_customer['group'][$group['customer_group_id']]) ? $simple_set_checkout_customer['group'][$group['customer_group_id']] : '', $simple_fields_for_checkout_customer) ?>
            </td>  
          </tr>
          <?php } ?>
        </table>
        <h3><?php echo $text_shipping_address_block ?></h3>
        <table class="form">
          <tr>
            <td>
                <?php echo $entry_shipping_address_show; ?>
            </td>
            <td>
                <label><input type="radio" name="simple_show_shipping_address" value="1" <?php if ($simple_show_shipping_address) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_show_shipping_address" value="0" <?php if (!$simple_show_shipping_address) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                <?php if (isset($error_simple_show_shipping_address)) { ?>
                <span class="error"><?php echo $error_simple_show_shipping_address ?></span>
                <?php } ?>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_shipping_address_same_init; ?>
            </td>
            <td>
                <label><input type="radio" name="simple_show_shipping_address_same_init" value="1" <?php if ($simple_show_shipping_address_same_init) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_show_shipping_address_same_init" value="0" <?php if (!$simple_show_shipping_address_same_init) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                <?php if (isset($error_simple_show_shipping_address_same_init)) { ?>
                <span class="error"><?php echo $error_simple_show_shipping_address_same_init ?></span>
                <?php } ?>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_shipping_address_same_show; ?>
            </td>
            <td>
                <label><input type="radio" name="simple_show_shipping_address_same_show" value="1" <?php if ($simple_show_shipping_address_same_show) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_show_shipping_address_same_show" value="0" <?php if (!$simple_show_shipping_address_same_show) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                <?php if (isset($error_simple_show_shipping_address_same_show)) { ?>
                <span class="error"><?php echo $error_simple_show_shipping_address_same_show ?></span>
                <?php } ?>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_shipping_address_select; ?>
            </td>
            <td>
                <label><input type="radio" name="simple_shipping_view_address_select" value="1" <?php if ($simple_shipping_view_address_select) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_shipping_view_address_select" value="0" <?php if (!$simple_shipping_view_address_select) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                <?php if (isset($error_simple_shipping_view_address_select)) { ?>
                <span class="error"><?php echo $error_simple_shipping_view_address_select ?></span>
                <?php } ?>
            </td>  
          </tr>
          <?php foreach($groups as $group) { ?>
          <tr>
            <td>
                <?php echo $group['name']; ?>
            </td>
            <td>
                <?php echo selectbox_html('simple_set_checkout_address[group]['.$group['customer_group_id'].']', isset($simple_set_checkout_address['group'][$group['customer_group_id']]) ? $simple_set_checkout_address['group'][$group['customer_group_id']] : '', $simple_fields_for_checkout_shipping_address) ?>
            </td>
          </tr>
          <?php } ?>
        </table>
        <?php foreach($groups as $group) { ?>
        <table class="list">
            <thead>
              <tr>
                <td class="left"><?php echo $entry_shipping_module; ?></td>
                <td class="left"><?php echo $entry_customer_fields; ?> - <?php echo $group['name']; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($shipping_extensions as $shipping_code => $shipping_name) { ?>
              <tr>
                <td style="width:20%"><?php echo $shipping_name ?> [<?php echo $group['name']; ?>]<span class="help"><?php echo $shipping_code ?></span></td>
                <td style="padding:5px;">
                    <?php echo selectbox_html('simple_set_checkout_customer[shipping]['.$group['customer_group_id'].']['.$shipping_code.']', isset($simple_set_checkout_customer['shipping'][$group['customer_group_id']][$shipping_code]) ? $simple_set_checkout_customer['shipping'][$group['customer_group_id']][$shipping_code] : '', $simple_fields_for_checkout_customer) ?>
                </td>
              </tr>
              <?php } ?>
              <?php foreach ($shipping_extensions_for_customer as $code) { ?>
              <tr>
                <?php 
                    $text = '';
                    $parts = explode('.', $code); 
                    if (count($parts) == 2) {
                        $text = isset($shipping_extensions[$parts[0]]) ? $shipping_extensions[$parts[0]] : $code;
                    }
                ?>
                <td style="width:20%"><?php echo $text ?> [<?php echo $group['name']; ?>]<span class="help"><?php echo $code ?></span></td>
                <?php $shipping_code = str_replace('.','_101_', $code) ?>
                <td style="padding:5px;">
                    <?php echo selectbox_html('simple_set_checkout_customer[shipping]['.$group['customer_group_id'].']['.$shipping_code.']', isset($simple_set_checkout_customer['shipping'][$group['customer_group_id']][$code]) ? $simple_set_checkout_customer['shipping'][$group['customer_group_id']][$code] : '', $simple_fields_for_checkout_customer) ?>
                    <br><a style="margin-top:5px" onclick="$(this).parent().parent().remove()" class="button"><span><?php echo $button_delete; ?></span></a>
                </td>
              </tr>
              <?php } ?>
              <tr>
                <td style="padding:5px;">
                    <select>
                    <?php foreach ($shipping_extensions as $key => $value) { ?>
                    <option value="<?php echo $key ?>"><?php echo $key ?></option>
                    <?php } ?>
                    </select><input type="text" name="shipping_code_for_customer_<?php echo $group['customer_group_id'] ?>" id="shipping_code_for_customer_<?php echo $group['customer_group_id'] ?>" value="">
                    <span class="help">Example citylink.citylink1</span>
                </td>
                <td style="padding:5px;">
                    <a onclick="add_shipping_method_for_customer('<?php echo $group['customer_group_id'] ?>')" class="button"><span><?php echo $button_add; ?></span></a>
                </td>  
              </tr>
            </tbody>
          </table>
        <?php } ?>
        <?php foreach($groups as $group) { ?>
        <table class="list">
            <thead>
              <tr>
                <td class="left"><?php echo $entry_shipping_module; ?></td>
                <td class="left"><?php echo $entry_shipping_address_fields; ?> - <?php echo $group['name']; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($shipping_extensions as $shipping_code => $shipping_name) { ?>
              <tr>
                <td style="width:20%"><?php echo $shipping_name ?> [<?php echo $group['name']; ?>]<span class="help"><?php echo $shipping_code ?></span></td>
                <td style="padding:5px;">
                    <?php echo selectbox_html('simple_set_checkout_address[shipping]['.$group['customer_group_id'].']['.$shipping_code.']', isset($simple_set_checkout_address['shipping'][$group['customer_group_id']][$shipping_code]) ? $simple_set_checkout_address['shipping'][$group['customer_group_id']][$shipping_code] : '', $simple_fields_for_checkout_shipping_address) ?>
                </td>
              </tr>
              <?php } ?>
              <?php foreach ($shipping_extensions_for_shipping_address as $code) { ?>
              <tr>
                <?php 
                    $text = '';
                    $parts = explode('.', $code); 
                    if (count($parts) == 2) {
                        $text = isset($shipping_extensions[$parts[0]]) ? $shipping_extensions[$parts[0]] : $code;
                    }
                ?>
                <td style="width:20%"><?php echo $text ?> [<?php echo $group['name']; ?>]<span class="help"><?php echo $code ?></span></td>
                <?php $shipping_code = str_replace('.','_101_', $code) ?>
                <td style="padding:5px;">
                    <?php echo selectbox_html('simple_set_checkout_address[shipping]['.$group['customer_group_id'].']['.$shipping_code.']', isset($simple_set_checkout_address['shipping'][$group['customer_group_id']][$code]) ? $simple_set_checkout_address['shipping'][$group['customer_group_id']][$code] : '', $simple_fields_for_checkout_shipping_address) ?>
                    <br><a style="margin-top:5px" onclick="$(this).parent().parent().remove()" class="button"><span><?php echo $button_delete; ?></span></a>
                </td>
              </tr>
              <?php } ?>
              <tr>
                <td style="padding:5px;">
                    <select>
                    <?php foreach ($shipping_extensions as $key => $value) { ?>
                    <option value="<?php echo $key ?>"><?php echo $key ?></option>
                    <?php } ?>
                    </select><input type="text" name="shipping_code_for_shipping_<?php echo $group['customer_group_id'] ?>" id="shipping_code_for_shipping_<?php echo $group['customer_group_id'] ?>" value="">
                    <span class="help">Example citylink.citylink1</span>
                </td>
                <td style="padding:5px;">
                    <a onclick="add_shipping_method_for_shipping('<?php echo $group['customer_group_id'] ?>')" class="button"><span><?php echo $button_add; ?></span></a>
                </td>  
              </tr>
            </tbody>
          </table>
          <?php } ?>
          <!-- start of sets for payment methods -->
          <?php foreach($groups as $group) { ?>
          <table class="list">
            <thead>
              <tr>
                <td class="left"><?php echo $entry_payment_method; ?></td>
                <td class="left"><?php echo $entry_customer_fields; ?> - <?php echo $group['name']; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($payment_extensions as $payment_code => $payment_name) { ?>
              <tr>
                <td style="width:20%"><?php echo $payment_name ?> [<?php echo $group['name']; ?>]<span class="help"><?php echo $payment_code ?></span></td>
                <td style="padding:5px;">
                    <?php echo selectbox_html('simple_set_checkout_customer[payment]['.$group['customer_group_id'].']['.$payment_code.']', isset($simple_set_checkout_customer['payment'][$group['customer_group_id']][$payment_code]) ? $simple_set_checkout_customer['payment'][$group['customer_group_id']][$payment_code] : '', $simple_fields_for_checkout_customer) ?>
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
          <?php } ?>
        <?php foreach($groups as $group) { ?>
        <table class="list">
            <thead>
              <tr>
                <td class="left"><?php echo $entry_payment_method; ?></td>
                <td class="left"><?php echo $entry_shipping_address_fields; ?> - <?php echo $group['name']; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($payment_extensions as $payment_code => $payment_name) { ?>
              <tr>
                <td style="width:20%"><?php echo $payment_name ?> [<?php echo $group['name']; ?>]<span class="help"><?php echo $payment_code ?></span></td>
                <td style="padding:5px;">
                    <?php echo selectbox_html('simple_set_checkout_address[payment]['.$group['customer_group_id'].']['.$payment_code.']', isset($simple_set_checkout_address['payment'][$group['customer_group_id']][$payment_code]) ? $simple_set_checkout_address['payment'][$group['customer_group_id']][$payment_code] : '', $simple_fields_for_checkout_shipping_address) ?>
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
          <?php } ?>
          <!-- end of sets for payment methods -->
        <h3><?php echo $text_module_links ?></h3>
          <table class="list">
            <thead>
              <tr>
                <td class="left"><?php echo $entry_payment_module; ?></td>
                <td class="left"><?php echo $entry_shipping_modules; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($payment_extensions as $payment_code => $payment_name) { ?>
              <tr>
                <td style="width:20%"><?php echo $payment_name ?></td>
                <td style="padding:5px;">
                    <?php echo selectbox_html('simple_links['.$payment_code.']', isset($simple_links[$payment_code]) ? $simple_links[$payment_code] : "", $shipping_extensions, 'text', 'Example: citylink - for all methods for this module, citylink.citylink1 - only for submethod citylink1 of module citylink') ?>
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
          <h3><?php echo $text_payment_options_for_groups ?></h3>
            <table class="list">
            <tbody>
              <?php foreach($groups as $group) { ?>
              <tr>
                <td style="width:20%"><?php echo $group['name'] ?></td>
                <td style="padding:5px;">
                    <?php echo selectbox_html('simple_group_payment['.$group['customer_group_id'].']', isset($simple_group_payment[$group['customer_group_id']]) ? $simple_group_payment[$group['customer_group_id']] : "", $payment_extensions) ?>
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
          <h3><?php echo $text_shipping_options_for_groups ?></h3>
          <table class="list">
            <tbody>
              <?php foreach($groups as $group) { ?>
              <tr>
                <td style="width:20%"><?php echo $group['name'] ?></td>
                <td style="padding:5px;">
                    <?php echo selectbox_html('simple_group_shipping['.$group['customer_group_id'].']', isset($simple_group_shipping[$group['customer_group_id']]) ? $simple_group_shipping[$group['customer_group_id']] : "", $shipping_extensions) ?>
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
      </div>
      <div id="tab-customer-fields">
        <div class="fields-list">
        <?php foreach ($simple_fields_all as $field) { ?>
        <?php if (substr($field['id'], 0, 4) == 'main') { ?>
        <table class="form" style="margin-bottom:50px;">
          <tr>
            <td>
                <?php echo $entry_field_id; ?>
            </td>
            <td>
                <h3><?php echo !empty($field['label'][$current_language]) ? $field['id'].' ('.$field['label'][$current_language].')' : $field['id'] ?></h3>
                <input type="hidden" name="simple_fields_main[<?php echo $field['id'] ?>][id]" value="<?php echo $field['id'] ?>">
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_field_label; ?>
            </td>
            <td>
                <?php foreach ($languages as $language) { ?>
                <div><img src="view/image/flags/<?php echo $language['image']; ?>" />&nbsp;<input type="text" name="simple_fields_main[<?php echo $field['id'] ?>][label][<?php echo $language['code'] ?>]" value="<?php echo !empty($field['label'][$language['code']]) ? $field['label'][$language['code']] : '' ?>"></div>
                <?php } ?>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_field_type; ?>
            </td>
            <td>
                <select class="type" name="simple_fields_main[<?php echo $field['id'] ?>][type]">
                    <option value="text" <?php echo $field['type'] == 'text' ? 'selected="selected"' : '' ?>>text</option>
                    <option value="textarea" <?php echo $field['type'] == 'textarea' ? 'selected="selected"' : '' ?>>textarea</option>
                    <option value="radio" <?php echo $field['type'] == 'radio' ? 'selected="selected"' : '' ?>>radio</option>
                    <option value="checkbox" <?php echo $field['type'] == 'checkbox' ? 'selected="selected"' : '' ?>>checkbox</option>
                    <option value="select" <?php echo $field['type'] == 'select' ? 'selected="selected"' : '' ?>>select</option>
                    <option value="select_from_api" <?php echo $field['type'] == 'select_from_api' ? 'selected="selected"' : '' ?>>select_from_api</option>
                    <option value="radio_from_api" <?php echo $field['type'] == 'radio_from_api' ? 'selected="selected"' : '' ?>>radio_from_api</option>
                    <option value="checkbox_from_api" <?php echo $field['type'] == 'checkbox_from_api' ? 'selected="selected"' : '' ?>>checkbox_from_api</option>
                    <option value="date" <?php echo $field['type'] == 'date' ? 'selected="selected"' : '' ?>>jquery date</option>
                </select>
            </td>  
          </tr>
          <!--<tr>
            <td>
                <?php echo $entry_field_place; ?>
                <span class="help">only for simplecheckout page and only for moving to another block, don't forget to add field to the sets</span>
            </td>
            <td>
                <select name="simple_fields_main[<?php echo $field['id'] ?>][place]">
                    <option value="customer" <?php echo (isset($field['place']) && $field['place'] == 'customer') || empty($field['place']) ? 'selected="selected"' : '' ?>>customer (not move)</option>
                    <option value="shipping" <?php echo isset($field['place']) && $field['place'] == 'shipping' ? 'selected="selected"' : '' ?>>shipping</option>
                    <option value="payment" <?php echo isset($field['place']) && $field['place'] == 'payment' ? 'selected="selected"' : '' ?>>payment</option>
                </select>
            </td>  
          </tr>-->
          <tr class="row_values" <?php if ($field['type'] == 'text' || $field['type'] == 'textarea' || $field['type'] == 'select_from_api' || $field['type'] == 'radio_from_api' || $field['type'] == 'checkbox_from_api' || $field['type'] == 'date') { ?>style="display:none;"<?php } ?>>
            <td>
                <?php echo $entry_field_values; ?>
            </td>
            <td>
                <?php if ($field['id'] != 'main_country_id' && $field['id'] != 'main_zone_id') { ?>
                    <?php $values = '' ?>
                    <?php foreach ($languages as $language) { ?>
                    <img src="view/image/flags/<?php echo $language['image']; ?>" />&nbsp;<textarea name="simple_fields_main[<?php echo $field['id'] ?>][values][<?php echo $language['code'] ?>]" cols="150"><?php echo !empty($field['values'][$language['code']]) ? $field['values'][$language['code']] : '' ?></textarea><br />
                    <?php $values = (empty($values) && !empty($field['values'][$language['code']])) ? $field['values'][$language['code']] : $values ?>
                    <?php } ?>
                    <span class="help">
                    Example: value1=text1;value2=text2;value3=text3
                    </span>
                <?php } else { ?>
                    <?php echo $field['values'] ?>
                    <input type="hidden" name="simple_fields_main[<?php echo $field['id'] ?>][values]" value="<?php echo $field['values'] ?>">
                <?php } ?>
            </td>  
          </tr>
          <tr class="row_init">
            <td>
                <?php echo $entry_field_init; ?>
            </td>
            <td id="init">
                <?php if ($field['type'] == 'select' && $field['values'] == 'countries') { ?>
                    <select id="country_id" name="simple_fields_main[<?php echo $field['id'] ?>][init]" onchange="$('#zone_id').load('<?php echo $zone_action ?>&country_id=' + this.value);">
                        <option value="0"><?php echo $text_select ?></option>
                        <?php foreach ($countries as $country) { ?>
                        <option value="<?php echo $country['country_id'] ?>" <?php if ($country['country_id'] == $field['init']) { ?>selected="selected"<?php } ?>><?php echo $country['name'] ?></option>
                        <?php } ?>
                    </select>
                <?php } elseif ($field['type'] == 'select' && $field['values'] == 'zones') { ?>
                    <select id="zone_id" name="simple_fields_main[<?php echo $field['id'] ?>][init]">
                        <option value="0"><?php echo $text_select ?></option>
                        <?php foreach ($zones as $zone) { ?>
                        <option value="<?php echo $zone['zone_id'] ?>" <?php if ($zone['zone_id'] == $field['init']) { ?>selected="selected"<?php } ?>><?php echo $zone['name'] ?></option>
                        <?php } ?>
                    </select>
                <?php } elseif ($field['type'] == 'select' || $field['type'] == 'radio' || $field['type'] == 'checkbox') { ?>
                    <?php $values = explode(';', $values); ?>
                    <?php if (count($values) > 0) { ?>
                        <?php if ($field['type'] == 'select') { ?>
                            <select name="simple_fields_main[<?php echo $field['id'] ?>][init]">
                        <?php } ?>
                        <?php foreach ($values as $value) { ?>
                            <?php $r = explode('=', $value, 2); ?>
                            <?php if (count($r) == 2) { ?>
                                <?php if ($field['type'] == 'radio') { ?>
                                    <label><input name="simple_fields_main[<?php echo $field['id'] ?>][init]" type="radio" value="<?php echo $r[0] ?>" <?php echo isset($field['init']) && $r[0] == $field['init'] ? 'checked="checked"' : '' ?>><?php echo $r[1] ?></label><br>
                                <?php } else if ($field['type'] == 'checkbox') { ?>
                                    <label><input name="simple_fields_main[<?php echo $field['id'] ?>][init][]" type="checkbox" value="<?php echo $r[0] ?>" <?php echo isset($field['init']) && is_array($field['init']) && in_array($r[0], $field['init']) ? 'checked="checked"' : '' ?>><?php echo $r[1] ?></label><br>
                                <?php } elseif ($field['type'] == 'select') { ?>
                                    <option value="<?php echo $r[0] ?>" <?php echo $r[0] == $field['init'] ? 'selected="selected"' : '' ?>><?php echo $r[1] ?></option>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                        <?php if ($field['type'] == 'select') { ?>
                            </select>
                        <?php } ?>
                    <?php } ?>
                <?php } elseif ($field['type'] == 'textarea') { ?>                
                    <textarea name="simple_fields_main[<?php echo $field['id'] ?>][init]"><?php echo isset($field['init']) ? $field['init'] : ''; ?></textarea>
                <?php } elseif ($field['type'] == 'text') { ?>                
                    <input type="text" name="simple_fields_main[<?php echo $field['id'] ?>][init]" value="<?php echo isset($field['init']) ? $field['init'] : '' ?>">
                <?php } ?>

                <?php if ($field['id'] == 'main_country_id' || $field['id'] == 'main_zone_id' || $field['id'] == 'main_city' || $field['id'] == 'main_postcode') { ?>
                    <br><label><input type="checkbox" name="simple_fields_main[<?php echo $field['id'] ?>][init_geoip]" value="1" <?php echo !empty($field['init_geoip']) ? 'checked="checked"' : '' ?>><?php echo $entry_geoip_init ?></label>
                <?php } ?>

                <?php if ($field['id'] == 'main_city') { ?>
                    <br><label><input type="checkbox" name="simple_fields_main[<?php echo $field['id'] ?>][autocomplete]" value="1" <?php echo !empty($field['autocomplete']) ? 'checked="checked"' : '' ?>><?php echo $entry_city_autocomplete ?></label>
                <?php } ?>

                <br><span class="init_from_api"><label><input type="checkbox" name="simple_fields_main[<?php echo $field['id'] ?>][init_from_api]" value="1" <?php echo !empty($field['init_from_api']) ? 'checked="checked"' : '' ?>><?php echo $text_validation_function ?> init_<?php echo $field['id'] ?></label></span>
            </td>  
          </tr>
          <tr class="row_date" <?php if ($field['type'] != 'date') { ?>style="display:none;"<?php } ?>>
            <td>
                <?php echo $entry_field_init; ?>
            </td>
            <td id="date">
                min date: <input name="simple_fields_main[<?php echo $field['id'] ?>][date_min]" type="text" class="datepicker" value="<?php echo !empty($field['date_min']) ? $field['date_min'] : '' ?>" > or start date after current date: <input name="simple_fields_main[<?php echo $field['id'] ?>][date_start]" type="text" size="1" value="<?php echo isset($field['date_start']) ? $field['date_start'] : '' ?>" ><br>
                max date: <input name="simple_fields_main[<?php echo $field['id'] ?>][date_max]" type="text" class="datepicker" value="<?php echo !empty($field['date_max']) ? $field['date_max'] : '' ?>" > or end date after current date: <input name="simple_fields_main[<?php echo $field['id'] ?>][date_end]" type="text" size="1" value="<?php echo isset($field['date_end']) ? $field['date_end'] : '' ?>" ><br>
                only business days: <input name="simple_fields_main[<?php echo $field['id'] ?>][date_only_business]" type="checkbox" value="1" <?php echo !empty($field['date_only_business']) ? 'checked="checked"' : '' ?>>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_field_validation; ?>
            </td>
            <td>
                <?php if ($field['id'] != 'main_email') { ?>
                <div><input type="radio" name="simple_fields_main[<?php echo $field['id'] ?>][validation_type]" value="0" <?php if ($field['validation_type'] == 0) { ?>checked="checked"<?php } ?>>&nbsp;<?php echo $text_validation_none ?></div>
                <div><input type="radio" name="simple_fields_main[<?php echo $field['id'] ?>][validation_type]" value="1" <?php if ($field['validation_type'] == 1) { ?>checked="checked"<?php } ?>>&nbsp;<?php echo $text_validation_not_null ?></div>
                <div><input type="radio" name="simple_fields_main[<?php echo $field['id'] ?>][validation_type]" value="2" <?php if ($field['validation_type'] == 2) { ?>checked="checked"<?php } ?>>&nbsp;<?php echo $text_validation_length ?> min: <input type="text" name="simple_fields_main[<?php echo $field['id'] ?>][validation_min]" size="5" value="<?php echo $field['validation_min'] ?>"> max: <input type="text" name="simple_fields_main[<?php echo $field['id'] ?>][validation_max]" size="5" value="<?php echo $field['validation_max'] ?>"></div>
                <div><input type="radio" name="simple_fields_main[<?php echo $field['id'] ?>][validation_type]" value="3" <?php if ($field['validation_type'] == 3) { ?>checked="checked"<?php } ?>>&nbsp;<?php echo $text_validation_regexp ?> <input type="text" name="simple_fields_main[<?php echo $field['id'] ?>][validation_regexp]" size="40" value="<?php echo $field['validation_regexp'] ?>"></div>
                <div><input type="radio" name="simple_fields_main[<?php echo $field['id'] ?>][validation_type]" value="4" <?php if ($field['validation_type'] == 4) { ?>checked="checked"<?php } ?>>&nbsp;<?php echo $text_validation_values ?></div>
                <div><input type="radio" name="simple_fields_main[<?php echo $field['id'] ?>][validation_type]" value="5" <?php if ($field['validation_type'] == 5) { ?>checked="checked"<?php } ?>>&nbsp;<?php echo $text_validation_function ?> validate_<?php echo $field['id'] ?></div>
                <?php } else { ?>
                <div><input type="radio" name="simple_fields_main[<?php echo $field['id'] ?>][validation_type]" value="3" <?php if ($field['validation_type'] == 3) { ?>checked="checked"<?php } ?>>&nbsp;<?php echo $text_validation_regexp ?> <input type="text" name="simple_fields_main[<?php echo $field['id'] ?>][validation_regexp]" size="40" value="<?php echo $field['validation_regexp'] ?>"></div>
                <?php } ?>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_field_validation_error; ?>
            </td>
            <td>
                <?php foreach ($languages as $language) { ?>
                <div><img src="view/image/flags/<?php echo $language['image']; ?>" />&nbsp;<input type="text" name="simple_fields_main[<?php echo $field['id'] ?>][validation_error][<?php echo $language['code'] ?>]" size="80" value="<?php echo !empty($field['validation_error'][$language['code']]) ? $field['validation_error'][$language['code']] : '' ?>"></div>
                <?php } ?>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_field_save_to; ?>
            </td>
            <td>
                <?php echo $field['save_to'] ?>
                <input type="hidden" name="simple_fields_main[<?php echo $field['id'] ?>][save_to]" value="<?php echo $field['save_to'] ?>">
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_jquery_masked_input_mask; ?>
            </td>
            <td>
                <input type="text" name="simple_fields_main[<?php echo $field['id'] ?>][mask]" value="<?php echo !empty($field['mask']) ? $field['mask'] : '' ?>"><br>
                <span class="mask_from_api"><label><input type="checkbox" name="simple_fields_main[<?php echo $field['id'] ?>][mask_from_api]" value="1" <?php echo !empty($field['mask_from_api']) ? 'checked="checked"' : '' ?>><?php echo $text_validation_function ?> mask_<?php echo $field['id'] ?></label></span>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_placeholder; ?>
            </td>
            <td>
                <?php foreach ($languages as $language) { ?>
                <div><img src="view/image/flags/<?php echo $language['image']; ?>" />&nbsp;<input type="text" name="simple_fields_main[<?php echo $field['id'] ?>][placeholder][<?php echo $language['code'] ?>]" size="80" value="<?php echo !empty($field['placeholder'][$language['code']]) ? $field['placeholder'][$language['code']] : '' ?>"></div>
                <?php } ?>
            </td>  
          </tr>                    
        </table>
        <?php } elseif (substr($field['id'], 0, 6) == 'custom') { ?>
        <table id="<?php echo $field['id'] ?>" class="form" style="margin-bottom:50px;">
          <tr>
            <td>
                <?php echo $entry_field_id; ?>
            </td>
            <td>
                <h3><?php echo !empty($field['label'][$current_language]) ? $field['id'].' ('.$field['label'][$current_language].')' : $field['id'] ?></h3>
                <input type="hidden" name="simple_fields_custom[<?php echo $field['id'] ?>][id]" value="<?php echo $field['id'] ?>">
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_field_label; ?>
            </td>
            <td>
                <?php foreach ($languages as $language) { ?>
                <div><img src="view/image/flags/<?php echo $language['image']; ?>" />&nbsp;<input type="text" name="simple_fields_custom[<?php echo $field['id'] ?>][label][<?php echo $language['code'] ?>]" value="<?php echo !empty($field['label'][$language['code']]) ? $field['label'][$language['code']] : '' ?>"></div>
                <?php } ?>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_field_object; ?>
            </td>
            <td>
                <select name="simple_fields_custom[<?php echo $field['id'] ?>][object_type]">
                    <option value="order" <?php echo !empty($field['object_type']) && $field['object_type'] == 'order' ? 'selected="selected"' : '' ?>>order</option>
                    <option value="address" <?php echo !empty($field['object_type']) && $field['object_type'] == 'address' ? 'selected="selected"' : '' ?>>address</option>
                    <option value="customer" <?php echo !empty($field['object_type']) && $field['object_type'] == 'customer' ? 'selected="selected"' : '' ?>>customer</option>
                </select>
            </td>
          </tr>
          <tr>
            <td>
                <?php echo $entry_field_object_field; ?>
            </td>
            <td>
                <input type="text" name="simple_fields_custom[<?php echo $field['id'] ?>][object_field]" value="<?php echo !empty($field['object_field']) ? $field['object_field'] : '' ?>" >
            </td>
          </tr>
          <tr>
            <td>
                <?php echo $entry_field_type; ?>
            </td>
            <td>
                <select class="type" name="simple_fields_custom[<?php echo $field['id'] ?>][type]">
                    <option value="text" <?php echo $field['type'] == 'text' ? 'selected="selected"' : '' ?>>text</option>
                    <option value="textarea" <?php echo $field['type'] == 'textarea' ? 'selected="selected"' : '' ?>>textarea</option>
                    <option value="radio" <?php echo $field['type'] == 'radio' ? 'selected="selected"' : '' ?>>radio</option>
                    <option value="checkbox" <?php echo $field['type'] == 'checkbox' ? 'selected="selected"' : '' ?>>checkbox</option>
                    <option value="select" <?php echo $field['type'] == 'select' ? 'selected="selected"' : '' ?>>select</option>
                    <option value="select_from_api" <?php echo $field['type'] == 'select_from_api' ? 'selected="selected"' : '' ?>>select_from_api</option>
                    <option value="radio_from_api" <?php echo $field['type'] == 'radio_from_api' ? 'selected="selected"' : '' ?>>radio_from_api</option>
                    <option value="checkbox_from_api" <?php echo $field['type'] == 'checkbox_from_api' ? 'selected="selected"' : '' ?>>checkbox_from_api</option>
                    <option value="date" <?php echo $field['type'] == 'date' ? 'selected="selected"' : '' ?>>jquery date</option>
                </select>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_field_place; ?>
                <span class="help">only for simplecheckout page and only for moving to another block, don't forget to add field to the sets</span>
            </td>
            <td>
                <select name="simple_fields_custom[<?php echo $field['id'] ?>][place]">
                    <option value="customer" <?php echo (isset($field['place']) && $field['place'] == 'customer') || empty($field['place']) ? 'selected="selected"' : '' ?>>customer (not move)</option>
                    <option value="shipping" <?php echo isset($field['place']) && $field['place'] == 'shipping' ? 'selected="selected"' : '' ?>>shipping</option>
                    <option value="payment" <?php echo isset($field['place']) && $field['place'] == 'payment' ? 'selected="selected"' : '' ?>>payment</option>
                </select>
            </td>  
          </tr>
          <tr class="row_values" <?php if ($field['type'] == 'text' || $field['type'] == 'textarea' || $field['type'] == 'select_from_api' || $field['type'] == 'radio_from_api' || $field['type'] == 'checkbox_from_api' || $field['type'] == 'date') { ?>style="display:none;"<?php } ?>>
            <td>
                <?php echo $entry_field_values; ?>
            </td>
            <td>
                <?php $values = '' ?>
                <?php foreach ($languages as $language) { ?>
                <img src="view/image/flags/<?php echo $language['image']; ?>" />&nbsp;<textarea name="simple_fields_custom[<?php echo $field['id'] ?>][values][<?php echo $language['code'] ?>]" cols="150"><?php echo !empty($field['values'][$language['code']]) ? $field['values'][$language['code']] : '' ?></textarea><br />
                <?php $values = (empty($values) && !empty($field['values'][$language['code']])) ? $field['values'][$language['code']] : $values ?>
                <?php } ?>
                <span class="help">
                Example: value1=text1;value2=text2;value3=text3
                </span>
            </td>  
          </tr>
          <tr class="row_init">
            <td>
                <?php echo $entry_field_init; ?>
            </td>
            <td id="init">
                <?php if ($field['type'] == 'select' || $field['type'] == 'checkbox' || $field['type'] == 'radio') { ?>
                <?php $values = explode(';', $values); ?>
                <?php if (count($values) > 0) { ?>
                    <?php if ($field['type'] == 'select') { ?>
                        <select name="simple_fields_custom[<?php echo $field['id'] ?>][init]">
                    <?php } ?>
                    <?php foreach ($values as $value) { ?>
                        <?php $r = explode('=', $value, 2); ?>
                        <?php if (count($r) == 2) { ?>
                            <?php if ($field['type'] == 'radio') { ?>
                                <label><input name="simple_fields_custom[<?php echo $field['id'] ?>][init]" type="radio" value="<?php echo $r[0] ?>" <?php echo isset($field['init']) && $r[0] == $field['init'] ? 'checked="checked"' : '' ?>><?php echo $r[1] ?></label><br>
                            <?php } else if ($field['type'] == 'checkbox') { ?>
                                <label><input name="simple_fields_custom[<?php echo $field['id'] ?>][init][]" type="checkbox" value="<?php echo $r[0] ?>" <?php echo isset($field['init']) && is_array($field['init']) && in_array($r[0], $field['init']) ? 'checked="checked"' : '' ?>><?php echo $r[1] ?></label><br>
                            <?php } elseif ($field['type'] == 'select') { ?>
                                <option value="<?php echo $r[0] ?>" <?php echo $r[0] == $field['init'] ? 'selected="selected"' : '' ?>><?php echo $r[1] ?></option>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                    <?php if ($field['type'] == 'select') { ?>
                        </select>
                    <?php } ?>
                <?php } ?>
                <br>
                <?php } ?>
                <span class="init_from_api"><label><input type="checkbox" name="simple_fields_custom[<?php echo $field['id'] ?>][init_from_api]" value="1" <?php echo !empty($field['init_from_api']) ? 'checked="checked"' : '' ?>><?php echo $text_validation_function ?> init_<?php echo $field['id'] ?></label></span>
            </td>  
          </tr>
          <tr class="row_date" <?php if ($field['type'] != 'date') { ?>style="display:none;"<?php } ?>>
            <td>
                <?php echo $entry_field_init; ?>
            </td>
            <td id="date">
                min date: <input name="simple_fields_custom[<?php echo $field['id'] ?>][date_min]" type="text" class="datepicker" value="<?php echo !empty($field['date_min']) ? $field['date_min'] : '' ?>" > or start date after current date: <input name="simple_fields_custom[<?php echo $field['id'] ?>][date_start]" type="text" size="1" value="<?php echo isset($field['date_start']) ? $field['date_start'] : '' ?>" ><br>
                max date: <input name="simple_fields_custom[<?php echo $field['id'] ?>][date_max]" type="text" class="datepicker" value="<?php echo !empty($field['date_max']) ? $field['date_max'] : '' ?>" > or end date after current date: <input name="simple_fields_custom[<?php echo $field['id'] ?>][date_end]" type="text" size="1" value="<?php echo isset($field['date_end']) ? $field['date_end'] : '' ?>" ><br>
                only business days: <input name="simple_fields_custom[<?php echo $field['id'] ?>][date_only_business]" type="checkbox" value="1" <?php echo !empty($field['date_only_business']) ? 'checked="checked"' : '' ?>>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_field_validation; ?>
            </td>
            <td>
                <div><input type="radio" name="simple_fields_custom[<?php echo $field['id'] ?>][validation_type]" value="0" <?php if ($field['validation_type'] == 0) { ?>checked="checked"<?php } ?>>&nbsp;<?php echo $text_validation_none ?></div>
                <div><input type="radio" name="simple_fields_custom[<?php echo $field['id'] ?>][validation_type]" value="1" <?php if ($field['validation_type'] == 1) { ?>checked="checked"<?php } ?>>&nbsp;<?php echo $text_validation_not_null ?></div>
                <div><input type="radio" name="simple_fields_custom[<?php echo $field['id'] ?>][validation_type]" value="2" <?php if ($field['validation_type'] == 2) { ?>checked="checked"<?php } ?>>&nbsp;<?php echo $text_validation_length ?> min: <input type="text" name="simple_fields_custom[<?php echo $field['id'] ?>][validation_min]" size="5" value="<?php echo $field['validation_min'] ?>"> max: <input type="text" name="simple_fields_custom[<?php echo $field['id'] ?>][validation_max]" size="5" value="<?php echo $field['validation_max'] ?>"></div>
                <div><input type="radio" name="simple_fields_custom[<?php echo $field['id'] ?>][validation_type]" value="3" <?php if ($field['validation_type'] == 3) { ?>checked="checked"<?php } ?>>&nbsp;<?php echo $text_validation_regexp ?> <input type="text" name="simple_fields_custom[<?php echo $field['id'] ?>][validation_regexp]" size="40" value="<?php echo $field['validation_regexp'] ?>"></div>
                <div><input type="radio" name="simple_fields_custom[<?php echo $field['id'] ?>][validation_type]" value="4" <?php if ($field['validation_type'] == 4) { ?>checked="checked"<?php } ?>>&nbsp;<?php echo $text_validation_values ?></div>
                <div><input type="radio" name="simple_fields_custom[<?php echo $field['id'] ?>][validation_type]" value="5" <?php if ($field['validation_type'] == 5) { ?>checked="checked"<?php } ?>>&nbsp;<?php echo $text_validation_function ?> validate_<?php echo $field['id'] ?></div>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_field_validation_error; ?>
            </td>
            <td>
                <?php foreach ($languages as $language) { ?>
                <div><img src="view/image/flags/<?php echo $language['image']; ?>" />&nbsp;<input type="text" name="simple_fields_custom[<?php echo $field['id'] ?>][validation_error][<?php echo $language['code'] ?>]" size="80" value="<?php echo !empty($field['validation_error'][$language['code']]) ? $field['validation_error'][$language['code']] : '' ?>"></div>
                <?php } ?>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_field_save_to; ?>
            </td>
            <td>
                <select name="simple_fields_custom[<?php echo $field['id'] ?>][save_to]">
                    <option value="" <?php echo empty($field['save_to'])? 'selected="selected"' : '' ?>></option>
                    <!--<option value="firstname" <?php echo $field['save_to'] == 'firstname' ? 'selected="selected"' : '' ?>>firstname</option>
                    <option value="lastname" <?php echo $field['save_to'] == 'lastname' ? 'selected="selected"' : '' ?>>lastname</option>
                    <option value="telephone" <?php echo $field['save_to'] == 'telephone' ? 'selected="selected"' : '' ?>>telephone</option>
                    <option value="fax" <?php echo $field['save_to'] == 'fax' ? 'selected="selected"' : '' ?>>fax</option>
                    <option value="company" <?php echo $field['save_to'] == 'company' ? 'selected="selected"' : '' ?>>company</option>
                    <option value="company_id" <?php echo $field['save_to'] == 'company_id' ? 'selected="selected"' : '' ?>>company_id</option>
                    <option value="tax_id" <?php echo $field['save_to'] == 'tax_id' ? 'selected="selected"' : '' ?>>tax_id</option>
                    <option value="address_1" <?php echo $field['save_to'] == 'address_1' ? 'selected="selected"' : '' ?>>address_1</option>
                    <option value="address_2" <?php echo $field['save_to'] == 'address_2' ? 'selected="selected"' : '' ?>>address_2</option>
                    <option value="postcode" <?php echo $field['save_to'] == 'postcode' ? 'selected="selected"' : '' ?>>postcode</option>
                    <option value="city" <?php echo $field['save_to'] == 'city' ? 'selected="selected"' : '' ?>>city</option>-->
                    <option value="comment" <?php echo $field['save_to'] == 'comment' ? 'selected="selected"' : '' ?>>comment</option>
                </select>
                <br><label><input type="checkbox" name="simple_fields_custom[<?php echo $field['id'] ?>][save_label]" value="1" <?php echo !empty($field['save_label']) ? 'checked="checked"' : '' ?>><?php echo $entry_save_label ?></label>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_jquery_masked_input_mask; ?>
            </td>
            <td>
                <input type="text" name="simple_fields_custom[<?php echo $field['id'] ?>][mask]" value="<?php echo !empty($field['mask']) ? $field['mask'] : '' ?>"><br>
                <span class="mask_from_api"><label><input type="checkbox" name="simple_fields_custom[<?php echo $field['id'] ?>][mask_from_api]" value="1" <?php echo !empty($field['mask_from_api']) ? 'checked="checked"' : '' ?>><?php echo $text_validation_function ?> mask_<?php echo $field['id'] ?></label></span>
            </td>  
          </tr>     
          <tr>
            <td>
                <?php echo $entry_placeholder; ?>
            </td>
            <td>
                <?php foreach ($languages as $language) { ?>
                <div><img src="view/image/flags/<?php echo $language['image']; ?>" />&nbsp;<input type="text" name="simple_fields_custom[<?php echo $field['id'] ?>][placeholder][<?php echo $language['code'] ?>]" size="80" value="<?php echo !empty($field['placeholder'][$language['code']]) ? $field['placeholder'][$language['code']] : '' ?>"></div>
                <?php } ?>
            </td>  
          </tr>                 
          <tr>
            <td>
            </td>
            <td>
                <a onclick="$('#<?php echo $field['id'] ?>').remove()" class="button"><span><?php echo $button_delete; ?></span></a>
            </td>  
          </tr>
        </table>
        <?php } ?>
        <?php } ?>
        </div>
        <h3><?php echo $text_add_field ?></h3>
        <table id="custom_new_field" class="form" style="margin-bottom:50px;">
          <tr>
            <td>
                <?php echo $entry_field_id; ?>
            </td>
            <td>
                <span style="margin-left:-50px;">custom_</span>
                <input type="text" name="id" value="">
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_field_label; ?>
            </td>
            <td>
                <?php foreach ($languages as $language) { ?>
                <div><img src="view/image/flags/<?php echo $language['image']; ?>" />&nbsp;<input type="text" name="label_<?php echo $language['code'] ?>" value=""></div>
                <?php } ?>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_field_object; ?>
            </td>
            <td>
                <select name="object_type">
                    <option value="order">order</option>
                    <option value="address">address</option>
                    <option value="customer">customer</option>
                </select>
            </td>
          </tr>
          <tr>
            <td>
                <?php echo $entry_field_object_field; ?>
            </td>
            <td>
                <input type="text" name="object_field" value="" >
            </td>
          </tr>
          <tr>
            <td>
                <?php echo $entry_field_type; ?>
            </td>
            <td>
                <select class="type" name="type">
                    <option value="text">text</option>
                    <option value="textarea">textarea</option>
                    <option value="radio">radio</option>
                    <option value="checkbox">checkbox</option>
                    <option value="select">select</option>
                    <option value="select_from_api">select_from_api</option>
                    <option value="radio_from_api">radio_from_api</option>
                    <option value="checkbox_from_api">checkbox_from_api</option>
                    <option value="date">jquery date</option>
                </select>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_field_place; ?>
                <span class="help">only for simplecheckout page and only for moving to another block, don't forget to add field to the sets</span>
            </td>
            <td>
                <select name="place">
                    <option value="customer">customer (not move)</option>
                    <option value="shipping">shipping</option>
                    <option value="payment">payment</option>
                </select>
            </td>  
          </tr>
          <tr class="row_date" style="display:none;">
            <td>
                <?php echo $entry_field_init; ?>
            </td>
            <td id="date">
                min date: <input name="date_min" type="text" class="datepicker" value="" > or start date after current date: <input name="date_start" type="text" size="1" value="" ><br>
                max date: <input name="date_max" type="text" class="datepicker" value="" > or end date after current date: <input name="date_end" type="text" size="1" value="" ><br>
                only business days: <input name="date_only_business" type="checkbox" value="1" >
            </td>  
          </tr>
          <tr class="row_values" style="display:none;">
            <td>
                <?php echo $entry_field_values; ?>
            </td>
            <td>
                <?php foreach ($languages as $language) { ?>
                <img src="view/image/flags/<?php echo $language['image']; ?>" />&nbsp;<textarea name="values_<?php echo $language['code'] ?>" cols="150"></textarea><br />
                <?php } ?>
                <span class="help">
                Example: value1=text1;value2=text2;value3=text3
                </span>
            </td>  
          </tr>
          <tr class="row_init">
            <td>
                <?php echo $entry_field_init; ?>
            </td>
            <td id="init">
                <label><input type="checkbox" name="init_from_api" value="1"><?php echo $text_validation_function ?> init_custom_{id}</label>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_field_validation; ?>
            </td>
            <td>
                <div><input type="radio" name="validation_type" value="0" checked="checked">&nbsp;<?php echo $text_validation_none ?></div>
                <div><input type="radio" name="validation_type" value="1">&nbsp;<?php echo $text_validation_not_null ?></div>
                <div><input type="radio" name="validation_type" value="2">&nbsp;<?php echo $text_validation_length ?> min: <input type="text" name="validation_min" size="5" value=""> max: <input type="text" name="validation_max" size="5" value=""></div>
                <div><input type="radio" name="validation_type" value="3">&nbsp;<?php echo $text_validation_regexp ?> <input type="text" name="validation_regexp" size="40" value=""></div>
                <div><input type="radio" name="validation_type" value="4">&nbsp;<?php echo $text_validation_values ?></div>
                <div><input type="radio" name="validation_type" value="5">&nbsp;<?php echo $text_validation_function ?> validate_custom_{id}</div>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_field_validation_error; ?>
            </td>
            <td>
                <?php foreach ($languages as $language) { ?>
                <div><img src="view/image/flags/<?php echo $language['image']; ?>" />&nbsp;<input type="text" name="validation_error_<?php echo $language['code'] ?>" size="80" value=""></div>
                <?php } ?>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_field_save_to; ?>
            </td>
            <td>
                <select name="save_to">
                    <option value="" selected="selected"></option>
                    <!--<option value="firstname">firstname</option>
                    <option value="lastname">lastname</option>
                    <option value="telephone">telephone</option>
                    <option value="fax">fax</option>
                    <option value="company">company</option>
                    <option value="company_id">company_id</option>
                    <option value="tax_id">tax_id</option>
                    <option value="address_1">address_1</option>
                    <option value="address_2">address_2</option>
                    <option value="postcode">postcode</option>
                    <option value="city">city</option>-->
                    <option value="comment">comment</option>
                </select>
                <br><label><input type="checkbox" name="save_label" value="1"><?php echo $entry_save_label ?></label>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_jquery_masked_input_mask; ?>
            </td>
            <td>
                <input type="text" name="mask" value=""><br>
                <span class="mask_from_api"><label><input type="checkbox" name="mask_from_api" value="1" ><?php echo $text_validation_function ?> mask_custom_{id}</label></span>
            </td>  
          </tr>   
          <tr>
            <td>
                <?php echo $entry_placeholder; ?>
            </td>
            <td>
                <?php foreach ($languages as $language) { ?>
                <div><img src="view/image/flags/<?php echo $language['image']; ?>" />&nbsp;<input type="text" name="placeholder_<?php echo $language['code'] ?>" size="80" value=""></div>
                <?php } ?>
            </td>  
          </tr>                  
          <tr>
            <td>
            </td>
            <td>
                <a onclick="add_field('#custom_new_field','#tab-customer-fields div.fields-list', 'simple_fields_custom', 'custom_')" class="button"><span><?php echo $button_add; ?></span></a>
            </td>  
          </tr>
        </table>
      </div>
      <div id="tab-registration">
        <h3><?php echo $text_registration_page ?></h3>
            <table class="form">
                <tr>
                    <td>
                        <?php echo $entry_confirm_email; ?>:
                    </td>
                    <td>
                        <label><input type="radio" name="simple_registration_view_email_confirm" value="1" <?php if ($simple_registration_view_email_confirm) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                        <label><input type="radio" name="simple_registration_view_email_confirm" value="0" <?php if (!$simple_registration_view_email_confirm) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                    </td>
                  </tr>
                <tr>
                <td>
                    <?php echo $entry_generate_password; ?>
                </td>
                <td>
                    <label><input type="radio" name="simple_registration_generate_password" value="1" <?php if ($simple_registration_generate_password) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                    <label><input type="radio" name="simple_registration_generate_password" value="0" <?php if (!$simple_registration_generate_password) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                    <?php if (isset($error_simple_registration_generate_password)) { ?>
                    <span class="error"><?php echo $error_simple_registration_generate_password ?></span>
                    <?php } ?>
                </td>  
              </tr>
              <tr>
                <td>
                    <?php echo $entry_customer_password_confirm; ?>
                </td>
                <td>
                    <label><input type="radio" name="simple_registration_password_confirm" value="1" <?php if ($simple_registration_password_confirm) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                    <label><input type="radio" name="simple_registration_password_confirm" value="0" <?php if (!$simple_registration_password_confirm) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                    <?php if (isset($error_simple_registration_password_confirm)) { ?>
                    <span class="error"><?php echo $error_simple_registration_password_confirm ?></span>
                    <?php } ?>
                </td>  
              </tr>
              <tr>
                <td>
                    <?php echo $entry_password_length; ?>
                </td>
                <td>
                    min <input type="text" size="3" name="simple_registration_password_length_min" value="<?php echo $simple_registration_password_length_min ?>" >
                    max <input type="text" size="3" name="simple_registration_password_length_max" value="<?php echo $simple_registration_password_length_max ?>" >
                </td>  
              </tr>
              <tr>
                <td>
                    <?php echo $entry_registration_agreement_id; ?>
                </td>
                <td>
                    <select name="simple_registration_agreement_id">
                        <option value="0"><?php echo $text_select ?></option>
                        <?php foreach ($informations as $information) { ?>
                        <option value="<?php echo $information['information_id'] ?>" <?php if ($information['information_id'] == $simple_registration_agreement_id) { ?>selected="selected"<?php } ?>><?php echo $information['title'] ?></option>
                        <?php } ?>
                    </select>
                  <?php if (isset($error_simple_registration_agreement_id)) { ?>
                  <span class="error"><?php echo $error_simple_registration_agreement_id ?></span>
                  <?php } ?>
                </td>  
              </tr>
              <tr>
                <td>
                    <?php echo $entry_registration_agreement_checkbox; ?>
                </td>
                <td>
                    <label><input type="radio" name="simple_registration_agreement_checkbox" value="1" <?php if ($simple_registration_agreement_checkbox) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                    <label><input type="radio" name="simple_registration_agreement_checkbox" value="0" <?php if (!$simple_registration_agreement_checkbox) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                    <?php if (isset($error_simple_registration_agreement_checkbox)) { ?>
                    <span class="error"><?php echo $error_simple_registration_agreement_checkbox ?></span>
                    <?php } ?>
                </td>  
              </tr>
              <tr>
                <td>
                    <?php echo $entry_registration_agreement_checkbox_init; ?>
                </td>
                <td>
                    <label><input type="radio" name="simple_registration_agreement_checkbox_init" value="1" <?php if ($simple_registration_agreement_checkbox_init) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                    <label><input type="radio" name="simple_registration_agreement_checkbox_init" value="0" <?php if (!$simple_registration_agreement_checkbox_init) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                    <?php if (isset($error_simple_registration_agreement_checkbox_init)) { ?>
                    <span class="error"><?php echo $error_simple_registration_agreement_checkbox_init ?></span>
                    <?php } ?>
                </td>  
              </tr>
              <tr>
                <td>
                    <?php echo $entry_registration_captcha; ?>
                </td>
                <td>
                    <label><input type="radio" name="simple_registration_captcha" value="1" <?php if ($simple_registration_captcha) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                    <label><input type="radio" name="simple_registration_captcha" value="0" <?php if (!$simple_registration_captcha) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                    <?php if (isset($error_simple_registration_captcha)) { ?>
                    <span class="error"><?php echo $error_simple_registration_captcha ?></span>
                    <?php } ?>
                </td>  
              </tr>
              <tr>
                <td>
                    <?php echo $entry_customer_subscribe; ?>
                </td>
                <td>
                    <label><input type="radio" name="simple_registration_subscribe" value="1" <?php if ($simple_registration_subscribe == 1) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                    <label><input type="radio" name="simple_registration_subscribe" value="0" <?php if (!$simple_registration_subscribe) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                    <label><input type="radio" name="simple_registration_subscribe" value="2" <?php if ($simple_registration_subscribe == 2) { ?>checked="checked"<?php } ?>><?php echo $text_user_choice ?></label>
                    <?php if (isset($error_simple_registration_subscribe)) { ?>
                    <span class="error"><?php echo $error_simple_registration_subscribe ?></span>
                    <?php } ?>
                </td>  
              </tr>
              <tr id="registration_subscribe_init" <?php if ($simple_registration_subscribe != 2) { ?>style="display:none"<?php } ?>>
                <td>
                    <?php echo $entry_customer_subscribe_init; ?>
                </td>
                <td>
                    <label><input type="radio" name="simple_registration_subscribe_init" value="1" <?php if ($simple_registration_subscribe_init) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                    <label><input type="radio" name="simple_registration_subscribe_init" value="0" <?php if (!$simple_registration_subscribe_init) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                    <?php if (isset($error_simple_registration_subscribe_init)) { ?>
                    <span class="error"><?php echo $error_simple_registration_subscribe_init ?></span>
                    <?php } ?>
                </td>  
              </tr>
              <tr>
                <td>
                    <?php echo $entry_customer_type; ?>
                </td>
                <td>
                    <label><input type="radio" name="simple_registration_view_customer_type" value="1" <?php if ($simple_registration_view_customer_type) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                    <label><input type="radio" name="simple_registration_view_customer_type" value="0" <?php if (!$simple_registration_view_customer_type) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                    <?php if (isset($error_simple_registration_view_customer_type)) { ?>
                    <span class="error"><?php echo $error_simple_registration_view_customer_type ?></span>
                    <?php } ?>
                </td>  
              </tr>
              <?php foreach($groups as $group) { ?>
              <tr>
                <td>
                    <?php echo $group['name']; ?>
                </td>
                <td>
                    <?php echo selectbox_html('simple_set_registration[group]['.$group['customer_group_id'].']', isset($simple_set_registration['group'][$group['customer_group_id']]) ? $simple_set_registration['group'][$group['customer_group_id']] : '', $simple_fields_for_registration) ?>
                </td>
              </tr>
              <?php } ?>
            </table>
      </div>
      <div id="tab-pages">
        <h3><?php echo $text_account_info_page ?></h3>
            <table class="form">
              <tr>
                <td>
                    <?php echo $entry_customer_type; ?>
                </td>
                <td>
                    <label><input type="radio" name="simple_account_view_customer_type" value="1" <?php if ($simple_account_view_customer_type) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                    <label><input type="radio" name="simple_account_view_customer_type" value="0" <?php if (!$simple_account_view_customer_type) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
                    <?php if (isset($error_simple_registration_view_customer_type)) { ?>
                    <span class="error"><?php echo $error_simple_registration_view_customer_type ?></span>
                    <?php } ?>
                </td>  
              </tr>
              <?php foreach($groups as $group) { ?>
              <tr>
                <td>
                    <?php echo $group['name']; ?>
                </td>
                <td>
                    <?php echo selectbox_html('simple_set_account_info[group]['.$group['customer_group_id'].']', isset($simple_set_account_info['group'][$group['customer_group_id']]) ? $simple_set_account_info['group'][$group['customer_group_id']] : '', $simple_fields_for_account_info) ?>
                </td>
              </tr>
              <?php } ?>
            </table>
        <h3><?php echo $text_account_address_page ?></h3>
            <table class="form">
              <?php foreach($groups as $group) { ?>
              <tr>
                <td>
                    <?php echo $group['name']; ?>
                </td>
                <td>
                    <?php echo selectbox_html('simple_set_account_address[group]['.$group['customer_group_id'].']', isset($simple_set_account_address['group'][$group['customer_group_id']]) ? $simple_set_account_address['group'][$group['customer_group_id']] : '', $simple_fields_for_account_address) ?>
                </td>
              </tr>
              <?php } ?>
            </table>
      </div>
      <div id="tab-joomla">
        <h3>Joomla</h3>
        <table class="form">
          <tr>
            <td>
                Joomla Component Path:
            </td>
            <td>
                <input type="text" size="70" name="simple_joomla_path" value="<?php echo $simple_joomla_path ?>">
                <span class="help">/components/com_aceshop/opencart/</span>
                <span class="help">/components/com_mijoshop/opencart/</span>
            </td>
          </tr>
          <tr>
            <td>
                Joomla Component Route:
            </td>
            <td>
                <input type="text" size="70" name="simple_joomla_route" value="<?php echo $simple_joomla_route ?>">
                <span class="help">option=com_aceshop&tmpl=component&format=raw&</span>
                <span class="help">option=com_mijoshop&tmpl=component&format=raw&</span>
            </td>
          </tr>
        </table>
    </div>
    <div id="tab-headers">
        <div class="headers-list">
        <table class="form">
          <tr>
            <td>
                <b>HTML Tag of headers</b>
            </td>
            <td>
                <select name="simple_header_tag">
                    <option value="" <?php echo $simple_header_tag == '' ? 'selected="selected"' : '' ?>></option>
                    <option value="h1" <?php echo $simple_header_tag == 'h1' ? 'selected="selected"' : '' ?>>h1</option>
                    <option value="h2" <?php echo $simple_header_tag == 'h2' ? 'selected="selected"' : '' ?>>h2</option>
                    <option value="h3" <?php echo $simple_header_tag == 'h3' ? 'selected="selected"' : '' ?>>h3</option>
                    <option value="h4" <?php echo $simple_header_tag == 'h4' ? 'selected="selected"' : '' ?>>h4</option>
                    <option value="h5" <?php echo $simple_header_tag == 'h5' ? 'selected="selected"' : '' ?>>h5</option>
                </select>
            </td>  
          </tr>
        </table>
        <?php foreach ($simple_headers as $field) { ?>
        <table class="form" id="<?php echo $field['id'] ?>" style="margin-bottom:50px;">
          <tr>
            <td>
                <?php echo $entry_field_id; ?>
            </td>
            <td>
                <h3><?php echo !empty($field['label'][$current_language]) ? $field['id'].' ('.$field['label'][$current_language].')' : $field['id'] ?></h3>
                <input type="hidden" name="simple_headers[<?php echo $field['id'] ?>][id]" value="<?php echo $field['id'] ?>">
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_field_label; ?>
            </td>
            <td>
                <?php foreach ($languages as $language) { ?>
                <div><img src="view/image/flags/<?php echo $language['image']; ?>" />&nbsp;<input type="text" size="70" name="simple_headers[<?php echo $field['id'] ?>][label][<?php echo $language['code'] ?>]" value="<?php echo !empty($field['label'][$language['code']]) ? $field['label'][$language['code']] : '' ?>"></div>
                <?php } ?>
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_field_place; ?>
                <span class="help">only for simplecheckout page and only for moving to another block, don't forget to add header to the sets</span>
            </td>
            <td>
                <select name="simple_headers[<?php echo $field['id'] ?>][place]">
                    <option value="customer" <?php echo (isset($field['place']) && $field['place'] == 'customer') || empty($field['place']) ? 'selected="selected"' : '' ?>>customer (not move)</option>
                    <option value="shipping" <?php echo isset($field['place']) && $field['place'] == 'shipping' ? 'selected="selected"' : '' ?>>shipping</option>
                    <option value="payment" <?php echo isset($field['place']) && $field['place'] == 'payment' ? 'selected="selected"' : '' ?>>payment</option>
                </select>
            </td>  
          </tr>
          <tr>
            <td>
            </td>
            <td>
                <a onclick="$('#<?php echo $field['id'] ?>').remove()" class="button"><span><?php echo $button_delete; ?></span></a>
            </td>  
          </tr>
        </table>
        <?php } ?>
        </div>
        <h3><?php echo $text_add_field ?></h3>
        <table id="custom_new_header" class="form" style="margin-bottom:50px;">
          <tr>
            <td>
                <?php echo $entry_field_id; ?>
            </td>
            <td>
                <span style="margin-left:-50px;">header_</span>
                <input type="text" name="id" value="">
            </td>  
          </tr>
          <tr>
            <td>
                <?php echo $entry_field_label; ?>
            </td>
            <td>
                <?php foreach ($languages as $language) { ?>
                <div><img src="view/image/flags/<?php echo $language['image']; ?>" />&nbsp;<input type="text" size="70" name="label_<?php echo $language['code'] ?>" value=""></div>
                <?php } ?>
            </td>  
          </tr>
          <tr>
            <td>
            </td>
            <td>
                <a onclick="add_header('#custom_new_header','#tab-headers div.headers-list', 'simple_headers', 'header_')" class="button"><span><?php echo $button_add; ?></span></a>
            </td>  
          </tr>
        </table>
      </div>
      <div id="tab-backup">
        <h3><?php echo $tab_backup ?></h3>
        <table class="form">
          <tr>
            <td>
                <a class="button" target="_blank" href="<?php echo $backup_link ?>"><span><?php echo $button_save ?></span></a>
            </td>
          </tr>
        </table>
        <h3><?php echo $text_restore ?></h3>
        <table class="form">
          <tr>
            <td><input name="restore" type="hidden" value="0" id="restore" /><input type="file" name="import" />&nbsp;<a onclick="$('#restore').val(1);$('#form').submit();" class="button"><span><?php echo $button_restore; ?></span></a></td>
          </tr>
        </table>
    </div>
    <div id="tab-methods">
        <h3><?php echo $entry_payment_method ?></h3>
        <?php foreach ($payment_extensions as $payment_code => $payment_name) { ?>
        <table class="form" style="margin-bottom:10px;">
          <tr>
            <td>
                <?php echo $payment_name ?><span class="help"><?php echo $payment_code ?></span>
            </td>
            <td>
                <label style="display:block;margin-bottom:0px;"><input type="radio" name="simple_payment_titles[<?php echo $payment_code; ?>][show]" value="0" <?php echo empty($simple_payment_titles[$payment_code]['show']) ? 'checked="checked"' : '' ?>>&nbsp;Don't show when address is empty</label>
                <label style="display:block;margin-bottom:0px;"><input type="radio" name="simple_payment_titles[<?php echo $payment_code; ?>][show]" value="1" <?php echo !empty($simple_payment_titles[$payment_code]['show']) && $simple_payment_titles[$payment_code]['show'] == 1 ? 'checked="checked"' : '' ?>>&nbsp;Show always</label>
                <label style="display:block;margin-bottom:0px;"><input type="radio" name="simple_payment_titles[<?php echo $payment_code; ?>][show]" value="2" <?php echo !empty($simple_payment_titles[$payment_code]['show']) && $simple_payment_titles[$payment_code]['show'] == 2 ? 'checked="checked"' : '' ?>>&nbsp;Show only if address is empty</label>
                <label style="display:block;margin-bottom:5px;"><input type="checkbox" name="simple_payment_titles[<?php echo $payment_code; ?>][use_description]" value="1" <?php echo !empty($simple_payment_titles[$payment_code]['use_description']) ? 'checked="checked"' : '' ?>>&nbsp;Use this description always for stub and for option too</label>
                <?php foreach ($languages as $language) { ?>
                <div style="margin-bottom:5px;"><img src="view/image/flags/<?php echo $language['image']; ?>" />&nbsp;<input type="text" size="70" name="simple_payment_titles[<?php echo $payment_code; ?>][title][<?php echo $language['code'] ?>]" value="<?php echo !empty($simple_payment_titles[$payment_code]['title'][$language['code']]) ? $simple_payment_titles[$payment_code]['title'][$language['code']] : $payment_name ?>"></div>
                <div style="margin-bottom:10px;"><img src="view/image/flags/<?php echo $language['image']; ?>" />&nbsp;Description:<br><textarea cols="70" rows="2" name="simple_payment_titles[<?php echo $payment_code; ?>][description][<?php echo $language['code'] ?>]"><?php echo !empty($simple_payment_titles[$payment_code]['description'][$language['code']]) ? $simple_payment_titles[$payment_code]['description'][$language['code']] : '' ?></textarea></div>
                <?php } ?>
            </td>  
          </tr>
        </table>
        <?php } ?>
        <h3><?php echo $entry_shipping_module ?></h3>
        <?php foreach ($shipping_extensions as $shipping_code => $shipping_name) { ?>
        <table class="form" style="margin-bottom:10px;">
          <tr>
            <td>
                <?php echo $shipping_name ?><span class="help"><?php echo $shipping_code ?></span>
            </td>
            <td>
                <label style="display:block;margin-bottom:0px;"><input type="radio" name="simple_shipping_titles[<?php echo $shipping_code; ?>][show]" value="0" <?php echo empty($simple_shipping_titles[$shipping_code]['show']) ? 'checked="checked"' : '' ?>>&nbsp;Don't show when address is empty</label>
                <label style="display:block;margin-bottom:0px;"><input type="radio" name="simple_shipping_titles[<?php echo $shipping_code; ?>][show]" value="1" <?php echo !empty($simple_shipping_titles[$shipping_code]['show']) && $simple_shipping_titles[$shipping_code]['show'] == 1 ? 'checked="checked"' : '' ?>>&nbsp;Show always</label>
                <label style="display:block;margin-bottom:0px;"><input type="radio" name="simple_shipping_titles[<?php echo $shipping_code; ?>][show]" value="2" <?php echo !empty($simple_shipping_titles[$shipping_code]['show']) && $simple_shipping_titles[$shipping_code]['show'] == 2 ? 'checked="checked"' : '' ?>>&nbsp;Show only if address is empty</label>
                <label style="display:block;margin-bottom:5px;"><input type="checkbox" name="simple_shipping_titles[<?php echo $shipping_code; ?>][use_description]" value="1" <?php echo !empty($simple_shipping_titles[$shipping_code]['use_description']) ? 'checked="checked"' : '' ?>>&nbsp;Use this description always for stub and for option too</label>
                <?php foreach ($languages as $language) { ?>
                <div style="margin-bottom:5px;"><img src="view/image/flags/<?php echo $language['image']; ?>" />&nbsp;<input type="text" size="70" name="simple_shipping_titles[<?php echo $shipping_code; ?>][title][<?php echo $language['code'] ?>]" value="<?php echo !empty($simple_shipping_titles[$shipping_code]['title'][$language['code']]) ? $simple_shipping_titles[$shipping_code]['title'][$language['code']] : $shipping_name ?>"></div>
                <div style="margin-bottom:10px;"><img src="view/image/flags/<?php echo $language['image']; ?>" />&nbsp;Description:<br><textarea cols="70" rows="2" name="simple_shipping_titles[<?php echo $shipping_code; ?>][description][<?php echo $language['code'] ?>]"><?php echo !empty($simple_shipping_titles[$shipping_code]['description'][$language['code']]) ? $simple_shipping_titles[$shipping_code]['description'][$language['code']] : '' ?></textarea></div>
                <?php } ?>
            </td>  
          </tr>
        </table>
        <?php } ?>
      </div>
    <div id="tab-template">
        <table class="form">
          <tr>
            <td>
                <h4>1) Upload all subfolders from 'upload/catalog/view/theme/default/' to folder of your theme <?php echo $theme_folder ?></h4>
            </td>
          </tr>
          <tr>
            <td>
                <h4>2) Save this simple_header.tpl and upload it to <?php echo $common_header_path ?></h4>
                <textarea cols="120" rows="20"><?php echo $common_header_content ?></textarea><br><br>
                <a class="button" target="_blank" href="<?php echo $header_save_link ?>"><span><?php echo $button_save ?></span></a>
            </td>
          </tr>
          <tr>
            <td>
                <h4>3) Save this simple_footer.tpl and upload it to <?php echo $common_footer_path ?></h4>
                <textarea cols="120" rows="20"><?php echo $common_footer_content ?></textarea><br><br>
                <a class="button" target="_blank" href="<?php echo $footer_save_link ?>"><span><?php echo $button_save ?></span></a>
            </td>
          </tr>
          <tr>
            <td>
                <h4>4) All styles of the module are described in <?php echo $styles_path ?></h4>
            </td>
          </tr>
        </table>
    </div>
    <div id="tab-googleapi">
        <h3>Google API</h3>
        <table class="form">
            <tr>
              <td><?php echo $entry_googleapi; ?></td>
              <td>
                <label><input type="radio" name="simple_googleapi" value="1" <?php if ($simple_googleapi) { ?>checked="checked"<?php } ?>><?php echo $text_yes ?></label>
                <label><input type="radio" name="simple_googleapi" value="0" <?php if (!$simple_googleapi) { ?>checked="checked"<?php } ?>><?php echo $text_no ?></label>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_googleapi_key; ?></td>
              <td><input type="text" name="simple_googleapi_key" size="50" value="<?php echo $simple_googleapi_key; ?>" /></td>
            </tr>
          </table>
    </div>
    </form>
</div>    
</div> 
</div>
<script type="text/javascript">
function add_field(form_selector, msc_selector, fields_name, field_prefix) {
    var id = $(form_selector + ' input[name=\'id\']').val().trim();
    var re = new RegExp ('^[a-zA-Z0-9_]+$');
    if (id == '' || !re.test(id)) {
        $(form_selector + ' input[name=\'id\']').next().remove();
        $(form_selector + ' input[name=\'id\']').css('border','2px solid #AA0000');
        $(form_selector + ' input[name=\'id\']').after('&nbsp;<span class="helper">Only latin, digits and \'_\'!</span>');
        return;
    }
    id = field_prefix + id;
    if ($('#'+id).length) {
        return;
    }
    $(form_selector + ' input[name=\'id\']').next().remove();
    $(form_selector + ' input[name=\'id\']').css('border','1px solid #CCCCCC');
    <?php foreach ($languages as $language) { ?>
    var label_<?php echo $language["code"] ?> = $(form_selector + ' input[name=\'label_<?php echo $language["code"] ?>\']').val();
    var label = $(form_selector + ' input[name=\'label_<?php echo $language["code"] ?>\']').val();
    <?php } ?>
    var type = $(form_selector + ' select[name=\'type\']').val();
    var object_type = $(form_selector + ' select[name=\'object_type\']').val();
    var object_field = $(form_selector + ' input[name=\'object_field\']').val();
    var values_text = '';
    <?php foreach ($languages as $language) { ?>
    var values_<?php echo $language["code"] ?> = $(form_selector + ' textarea[name=\'values_<?php echo $language["code"] ?>\']').val();
    values_text = values_text ? values_text : $(form_selector + ' textarea[name=\'values_<?php echo $language["code"] ?>\']').val();
    <?php } ?>
    var init = $(form_selector + ' select[name=\'init\']').val();
    init = init ? init : $(form_selector + ' input[name=\'init\']:checked').val();
    init = init ? init : '';
    var validation_type = $(form_selector + ' input[name=\'validation_type\']:checked').val();
    if (typeof validation_type == 'undefined') {
        validation_type = 0;
    }
    var validation_min = $(form_selector + ' input[name=\'validation_min\']').val();
    var validation_max = $(form_selector + ' input[name=\'validation_max\']').val();
    var validation_regexp = $(form_selector + ' input[name=\'validation_regexp\']').val();
    <?php foreach ($languages as $language) { ?>
    var validation_error_<?php echo $language["code"] ?> = $(form_selector + ' input[name=\'validation_error_<?php echo $language["code"] ?>\']').val();
    <?php } ?>
    var save_to = $(form_selector + ' select[name=\'save_to\']').val();
    var save_label = $(form_selector + ' input[name=\'save_label\']:checked').val();
    if (typeof save_label == 'undefined') {
        save_label = 0;
    }
    var mask = $(form_selector + ' input[name=\'mask\']').val();
    var mask_from_api = $(form_selector + ' input[name=\'mask_from_api\']:checked').val();
    <?php foreach ($languages as $language) { ?>
    var placeholder_<?php echo $language["code"] ?> = $(form_selector + ' input[name=\'placeholder_<?php echo $language["code"] ?>\']').val();
    <?php } ?>
    var init_html = '';
    if (type == 'select') {
        init_html += '<select name="'+fields_name+'['+id+'][init]"><option value=""></option>';
    }
    var values = values_text.toString().split(';');
    
    var selected = new Array();
    if (type == 'radio') {
        selected[0] = $(form_selector + ' input[name=\'init\']:checked').val();
    } else if (type == 'checkbox') {
        $(form_selector + ' input[name=\'init\']:checked').each(function() {
            selected[selected.length] = $(this).val();
        });
    } else if (type == 'select') {
        selected[0] = $(form_selector + ' select[name=\'init\']').val();
    }

    for (var i=0;i<values.length;i++) {
        var one = values[i].toString().split('=',2);
        if (one.length == 2 && one[0] != '' && one[1] != '') {
            if (type == 'radio') {
                init_html += '<label><input type="radio" name="'+fields_name+'['+id+'][init]" value="'+one[0]+'"' + ($.inArray(one[0],selected) != -1 ? ' checked="checked"' : '') + '>'+one[1]+'</label><br>';
            } else if (type == 'checkbox') {
                init_html += '<label><input type="checkbox" name="'+fields_name+'['+id+'][init][]" value="'+one[0]+'"' + ($.inArray(one[0],selected) != -1 ? ' checked="checked"' : '') + '>'+one[1]+'</label><br>';
            } else if (type == 'select') {
                init_html += '<option value="'+one[0]+'"' + ($.inArray(one[0],selected) != -1 ? ' selected="selected"' : '') + '>'+one[1]+'</option>';
            }
        }
    }
    var date_min = $(form_selector + ' input[name=\'date_min\']').val();
    var date_max = $(form_selector + ' input[name=\'date_max\']').val();
    var date_start = $(form_selector + ' input[name=\'date_start\']').val();
    var date_end = $(form_selector + ' input[name=\'date_end\']').val();
    var date_only_business = $(form_selector + ' input[name=\'date_only_business\']:checked').val();
    if (type == 'select') {
        init_html += '</select>';
    }
    var init_from_api = $(form_selector + ' input[name=\'init_from_api\']:checked').val();
    init_html += '<br><label><input type="checkbox" '+(init_from_api ? ' chechked="checked"' : '')+'name="init_from_api" value="1"><?php echo $text_validation_function ?> init_'+id+'</label>';
    $(msc_selector).append('<table id="'+id+'" class="form" style="margin-bottom:50px;">' +
          '<tr>' +
            '<td>' +
                '<?php echo $entry_field_id; ?>' +
            '</td>' +
            '<td>' +
                '<h3>'+id+(label != '' ? ' ('+label+')' : '')+'</h3>' +
                '<input type="hidden" name="'+fields_name+'['+id+'][id]" value="'+id+'">' +
            '</td>  ' +
          '</tr>' +
          '<tr>' +
            '<td>' +
                '<?php echo $entry_field_label; ?>' +
            '</td>' +
            '<td>' +
                '<?php foreach ($languages as $language) { ?>' +
                '<div><img src="view/image/flags/<?php echo $language["image"]; ?>" />&nbsp;<input type="text" name="'+fields_name+'['+id+'][label][<?php echo $language["code"] ?>]" value="' + label_<?php echo $language["code"] ?> + '"></div>' +
                '<?php } ?>' +
            '</td>  ' +
          '</tr>' +
          '<tr>' +
            '<td>' +
                '<?php echo $entry_field_object; ?>' +
            '</td>' +
            '<td>' +
                '<select name="'+fields_name+'['+id+'][object_type]">' +
                '<option value="order" ' + (object_type == 'order' ? 'selected="selected"' : '') + '>order</option>' +
                '<option value="address" ' + (object_type == 'address' ? 'selected="selected"' : '') + '>address</option>' +
                '<option value="customer" ' + (object_type == 'customer' ? 'selected="selected"' : '') + '>customer</option>' +
                '</select>' +
            '</td>  ' +
          '</tr>' +
          '<tr>' +
            '<td>' +
                '<?php echo $entry_field_object_field; ?>' +
            '</td>' +
            '<td>' +
                '<input name="'+fields_name+'['+id+'][object_field]" value="' + object_field + '">' +
            '</td>  ' +
          '</tr>' +
          '<tr>' +
            '<td>' +
                '<?php echo $entry_field_type; ?>' +
            '</td>' +
            '<td>' +
                '<select class="type" name="'+fields_name+'['+id+'][type]">' +
                '<option value="text" ' + (type == 'text' ? 'selected="selected"' : '') + '>text</option>' +
                '<option value="textarea" ' + (type == 'textarea' ? 'selected="selected"' : '') + '>textarea</option>' +
                '<option value="radio" ' + (type == 'radio' ? 'selected="selected"' : '') + '>radio</option>' +
                '<option value="checkbox" ' + (type == 'checkbox' ? 'selected="selected"' : '') + '>checkbox</option>' +
                '<option value="select" ' + (type == 'select' ? 'selected="selected"' : '') + '>select</option>' +
                '<option value="select_from_api" ' + (type == 'select_from_api' ? 'selected="selected"' : '') + '>select_from_api</option>' +
                '<option value="radio_from_api" ' + (type == 'radio_from_api' ? 'selected="selected"' : '') + '>radio_from_api</option>' +
                '<option value="checkbox_from_api" ' + (type == 'checkbox_from_api' ? 'selected="selected"' : '') + '>checkbox_from_api</option>' +
                '<option value="date" ' + (type == 'date' ? 'selected="selected"' : '') + '>jquery date</option>' +
                '</select>' +
            '</td>  ' +
          '</tr>' +
          '<tr class="row_date" '+((type != 'date') ? 'style="display:none;"' : '')+'>' +
            '<td>' +
                '<?php echo $entry_field_init; ?>' +
            '</td>' +
            '<td id="date">' +
                'min date: <input name="'+fields_name+'['+id+'][date_min]" type="text" class="datepicker" value="'+date_min+'" > or start date after current date: <input name="'+fields_name+'['+id+'][date_start]" type="text" size="1" value="'+date_start+'" ><br>' +
                'max date: <input name="'+fields_name+'['+id+'][date_max]" type="text" class="datepicker" value="'+date_max+'" > or end date after current date: <input name="'+fields_name+'['+id+'][date_end]" type="text" size="1" value="'+date_end+'" ><br>' +
                'only business days: <input name="'+fields_name+'['+id+'][date_only_business]" type="checkbox" value="1" ' + (date_only_business ? 'checked="checked"' : '') + '>' +
            '</td>  ' +
          '</tr>' +
          '<tr class="row_values" '+((type == 'text' || type == 'textarea' || type == 'select_from_api' || type == 'radio_from_api' || type == 'checkbox_from_api' || type == 'date') ? 'style="display:none;"' : '')+'>' +
            '<td>' +
                '<?php echo $entry_field_values; ?>' +
            '</td>' +
            '<td>' +
                '<?php foreach ($languages as $language) { ?>' +
                '<img src="view/image/flags/<?php echo $language["image"]; ?>" />&nbsp;<textarea cols="150" name="'+fields_name+'['+id+'][values][<?php echo $language["code"] ?>]">' + values_<?php echo $language["code"] ?> + '</textarea><br>' +
                '<?php } ?>' +
            '</td>  ' +
          '</tr>' +
          '<tr class="row_init">' +
            '<td>' +
                '<?php echo $entry_field_init; ?>' +
            '</td>' +
            '<td class="init">' + init_html +
            '</td>  ' +
          '</tr>' +
          '<tr>' +
            '<td>' +
                '<?php echo $entry_field_validation; ?>' +
            '</td>' +
            '<td>' +
                '<div><input type="radio" name="'+fields_name+'['+id+'][validation_type]" value="0" ' + (validation_type == 0 ? 'checked="checked"' : '') + '>&nbsp;<?php echo $text_validation_none ?></div>' +
                '<div><input type="radio" name="'+fields_name+'['+id+'][validation_type]" value="1" ' + (validation_type == 1 ? 'checked="checked"' : '') + '>&nbsp;<?php echo $text_validation_not_null ?></div>' +
                '<div><input type="radio" name="'+fields_name+'['+id+'][validation_type]" value="2" ' + (validation_type == 2 ? 'checked="checked"' : '') + '>&nbsp;<?php echo $text_validation_length ?> min: <input type="text" name="'+fields_name+'['+id+'][validation_min]" size="5" value="' + validation_min + '"> max: <input type="text" name="'+fields_name+'['+id+'][validation_max]" size="5" value="' + validation_max + '"></div>' +
                '<div><input type="radio" name="'+fields_name+'['+id+'][validation_type]" value="3" ' + (validation_type == 3 ? 'checked="checked"' : '') + '>&nbsp;<?php echo $text_validation_regexp ?> <input type="text" name="'+fields_name+'['+id+'][validation_regexp]" size="40" value="' + validation_regexp + '"></div>' +
                '<div><input type="radio" name="'+fields_name+'['+id+'][validation_type]" value="4" ' + (validation_type == 4 ? 'checked="checked"' : '') + '>&nbsp;<?php echo $text_validation_values ?></div>' +
                '<div><input type="radio" name="'+fields_name+'['+id+'][validation_type]" value="5" ' + (validation_type == 5 ? 'checked="checked"' : '') + '>&nbsp;<?php echo $text_validation_function ?> validate_' +id+ '</div>' +
            '</td>  ' +
          '</tr>' +
          '<tr>' +
            '<td>' +
                '<?php echo $entry_field_validation_error; ?>' +
            '</td>' +
            '<td>' +
                '<?php foreach ($languages as $language) { ?>' +
                '<div><img src="view/image/flags/<?php echo $language["image"]; ?>" />&nbsp;<input type="text" name="'+fields_name+'['+id+'][validation_error][<?php echo $language["code"] ?>]" size="80" value="' + validation_error_<?php echo $language["code"] ?> + '"></div>' +
                '<?php } ?>' +
            '</td>  ' +
          '</tr>' +
          '<tr>' +
            '<td>' +
                '<?php echo $entry_field_save_to; ?>' +
            '</td>' +
            '<td>' +
                '<select name="'+fields_name+'['+id+'][save_to]">' +
                '<option value="" ' + (save_to == '' ? 'selected="selected"' : '') + '></option>' +
                '<!--<option value="firstname" ' + (save_to == 'firstname' ? 'selected="selected"' : '') + '>firstname</option>' +
                '<option value="lastname" ' + (save_to == 'lastname' ? 'selected="selected"' : '') + '>lastname</option>' +
                '<option value="telephone" ' + (save_to == 'telephone' ? 'selected="selected"' : '') + '>telephone</option>' +
                '<option value="fax" ' + (save_to == 'fax' ? 'selected="selected"' : '') + '>fax</option>' +
                '<option value="company" ' + (save_to == 'company' ? 'selected="selected"' : '') + '>company</option>' +
                '<option value="company_id" ' + (save_to == 'company_id' ? 'selected="selected"' : '') + '>company_id</option>' +
                '<option value="tax_id" ' + (save_to == 'tax_id' ? 'selected="selected"' : '') + '>tax_id</option>' +
                '<option value="fax" ' + (save_to == 'fax' ? 'selected="selected"' : '') + '>fax</option>' +
                '<option value="address_1" ' + (save_to == 'address_1' ? 'selected="selected"' : '') + '>address_1</option>' +
                '<option value="address_2" ' + (save_to == 'address_2' ? 'selected="selected"' : '') + '>address_2</option>' +
                '<option value="postcode" ' + (save_to == 'postcode' ? 'selected="selected"' : '') + '>postcode</option>' +
                '<option value="city" ' + (save_to == 'city' ? 'selected="selected"' : '') + '>city</option>-->' +
                '<option value="comment" ' + (save_to == 'comment' ? 'selected="selected"' : '') + '>comment</option>' +
                '</select>' +
                '<br><span class="init_from_api"><label><input type="checkbox" name="'+fields_name+'['+id+'][save_label]" value="1" ' + (save_label ? 'checked="checked"' : '') + '><?php echo $entry_save_label ?></label></span>' +
            '</td>  ' +
          '</tr>' +
          '<tr>' +
            '<td>' +
                '<?php echo $entry_jquery_masked_input_mask; ?>' +
            '</td>' +
            '<td>' +
                '<input type="text" name="'+fields_name+'['+id+'][mask]" value="' + mask + '"><br>' +
                '<span class="mask_from_api"><label><input type="checkbox" name="'+fields_name+'['+id+'][mask_from_api]" ' + (mask_from_api ? 'checked="checked"' : '') + ' value="1" ><?php echo $text_validation_function ?> mask_custom_' + id + '</label></span>' +
            '</td>' +  
          '</tr>' +  
          '<tr>' +
            '<td>' +
                '<?php echo $entry_placeholder; ?>' +
            '</td>' +
            '<td>' +
                '<?php foreach ($languages as $language) { ?>' +
                '<div><img src="view/image/flags/<?php echo $language["image"]; ?>" />&nbsp;<input type="text" name="'+fields_name+'['+id+'][placeholder][<?php echo $language["code"] ?>]" size="80" value="' + placeholder_<?php echo $language["code"] ?> + '"></div>' +
                '<?php } ?>' +
            '</td>' +  
          '</tr>' +                    
          '<tr>' +
            '<td>' +
            '</td>' +
            '<td>' +
                '<a onclick="$(\'#' + id + '\').remove()" class="button"><span><?php echo $button_delete; ?></span></a>' +
            '</td>  ' +
          '</tr>' +
        '</table>');
    $('.datepicker').datepicker();
}
function add_header(form_selector, msc_selector, fields_name, field_prefix) {
    var id = $(form_selector + ' input[name=\'id\']').val().trim();
    var re = new RegExp ('^[a-zA-Z0-9_]+$');
    if (id == '' || !re.test(id)) {
        $(form_selector + ' input[name=\'id\']').next().remove();
        $(form_selector + ' input[name=\'id\']').css('border','2px solid #AA0000');
        $(form_selector + ' input[name=\'id\']').after('&nbsp;<span class="helper">Only latin, digits and \'_\'!</span>');
        return;
    }
    id = field_prefix + id;
    if ($('#'+id).length) {
        return;
    }

    <?php foreach ($languages as $language) { ?>
    var label_<?php echo $language["code"] ?> = $(form_selector + ' input[name=\'label_<?php echo $language["code"] ?>\']').val();
    var label = $(form_selector + ' input[name=\'label_<?php echo $language["code"] ?>\']').val();
    <?php } ?>
    
    $(msc_selector).append('<table id="'+id+'" class="form" style="margin-bottom:50px;">' +
          '<tr>' +
            '<td>' +
                '<?php echo $entry_field_id; ?>' +
            '</td>' +
            '<td>' +
                '<h3>'+id+(label != '' ? ' ('+label+')' : '')+'</h3>' +
                '<input type="hidden" name="'+fields_name+'['+id+'][id]" value="'+id+'">' +
            '</td>  ' +
          '</tr>' +
          '<tr>' +
            '<td>' +
                '<?php echo $entry_field_label; ?>' +
            '</td>' +
            '<td>' +
                '<?php foreach ($languages as $language) { ?>' +
                '<div><img src="view/image/flags/<?php echo $language["image"]; ?>" />&nbsp;<input type="text" name="'+fields_name+'['+id+'][label][<?php echo $language["code"] ?>]" value="' + label_<?php echo $language["code"] ?> + '"></div>' +
                '<?php } ?>' +
            '</td>  ' +
          '</tr>' +
        '</table>');
}
function init_values(selector) {
    var object = $(selector);
    var text = object.val();
    if (text == '') {
        return;
    }
    var values = text.toString().split(';');
    var html = '';
    var selected = object.parents('tr').nextAll('.row_init:first').find('td').eq(1).find('select').val();
    if (typeof selected == 'undefined') {
        selected = object.parents('tr').nextAll('.row_init:first').find('td').eq(1).find('input[type=radio]:checked').val();
    }
    var init_from_api = object.parents('tr').nextAll('.row_init:first').find('span.init_from_api').html();

    object.parents('tr').nextAll('.row_init:first').find('td').eq(1).empty();
    var type = object.parents('tr').parent().find('select.type').val();
    var name_type = object.parents('tr').parent().find('select.type').attr('name');
    var name = name_type && name_type.replace('[type]','[init]');
    if (name_type && name_type == name) {
        name = name_type.replace('type','init');
    }
    if (type == 'select') {
        html += '<select name="' + name + '"><option value=""></option>';
    }
    for (var i=0;i<values.length;i++) {
        var one = values[i].toString().split('=',2);
        if (one.length == 2 && one[0] != '' && one[1] != '') {
            if (type == 'radio') {
                html += '<label><input type="radio" name="' + name + '" value="'+one[0]+'"' + (selected == one[0] ? ' checked="checked"' : '') + '>'+one[1]+'</label><br>';
            } else if (type == 'checkbox') {
                html += '<label><input type="checkbox" name="' + name + '" value="'+one[0]+'"' + (selected == one[0] ? ' checked="checked"' : '') + '>'+one[1]+'</label><br>';
            } else if (type == 'select') {
                html += '<option value="'+one[0]+'"' + (selected == one[0] ? ' selected="selected"' : '') + '>'+one[1]+'</option>';
            }
        }
    }
    if (type == 'select') {
        html += '</select>';
    }
    html += '<br><span class="init_from_api">' + init_from_api + '</span>';
    object.parents('tr').nextAll('.row_init:first').find('td').eq(1).html(html);
}
function add_shipping_method_for_customer(customer_group_id) {
    var part1 = $('#shipping_code_for_customer_'+customer_group_id).prev().val();
    var part2 = $('#shipping_code_for_customer_'+customer_group_id).val();
    part2 = part2.replace(/[\.\s]/gi,'');
    var text = part1 + '.' + part2;
    var code = part1 + '_101_' + part2;
    if (part2) {
        var k = customer_group_id + '_' + code;
        var html = '<tr>' +
            '<td style="width:20%">' + text + '</td>' +
            '<td style="padding:5px;">' +
            '<div id="msc_simple_customer_fields_set_' + k + '"></div>' +
            '<input type="hidden" name="simple_set_checkout_customer[shipping][' + customer_group_id + '][' + code + ']" id="simple_customer_fields_set_' + k + '" value="" />' +
            '<select style="margin-top:5px;" id="customer_fields_' + k + '">' +
            '<option value=""><?php echo $text_select ?></option>' +
            <?php foreach ($simple_fields_for_checkout_customer as $key => $text) { ?>
            '<option value="<?php echo $key ?>"><?php echo $text ?></option>' +
            <?php } ?>
            '</select>' +
            '<br><a style="margin-top:5px" onclick="$(this).parent().parent().remove()" class="button"><span><?php echo $button_delete; ?></span></a>' +
            '</td>' +
            '</tr>';
        $('#shipping_code_for_customer_'+customer_group_id).parent().parent().before(html);
        $('#shipping_code_for_customer_'+customer_group_id).val('');
        $("#customer_fields_" + k).multiSelect("#simple_customer_fields_set_" + k,"#msc_simple_customer_fields_set_" + k);
    }
}

function add_shipping_method_for_shipping(customer_group_id) {
    var part1 = $('#shipping_code_for_shipping_'+customer_group_id).prev().val();
    var part2 = $('#shipping_code_for_shipping_'+customer_group_id).val();
    part2 = part2.replace(/[\.\s]/gi,'');
    var text = part1 + '.' + part2;
    var code = part1 + '_101_' + part2;
    if (part2) {
        var k = customer_group_id + '_' + code;
        var html = '<tr>' +
            '<td style="width:20%">' + text + '</td>' +
            '<td style="padding:5px;">' +
            '<div id="msc_simple_shipping_address_fields_set_' + k + '"></div>' +
            '<input type="hidden" name="simple_set_checkout_address[shipping][' + customer_group_id + '][' + code + ']" id="simple_shipping_address_fields_set_' + k + '" value="" />' +
            '<select style="margin-top:5px;" id="shipping_address_fields_' + k + '">' +
            '<option value=""><?php echo $text_select ?></option>' +
            <?php foreach ($simple_fields_for_checkout_shipping_address as $key => $text) { ?>
            '<option value="<?php echo $key ?>"><?php echo $text ?></option>' +
            <?php } ?>
            '</select>' +
            '<br><a style="margin-top:5px" onclick="$(this).parent().parent().remove()" class="button"><span><?php echo $button_delete; ?></span></a>' +
            '</td>' +
            '</tr>';
        $('#shipping_code_for_shipping_'+customer_group_id).parent().parent().before(html);
        $('#shipping_code_for_shipping_'+customer_group_id).val('');
        $("#shipping_address_fields_" + k).multiSelect("#simple_shipping_address_fields_set_" + k,"#msc_simple_shipping_address_fields_set_" + k);
    }
}
$(function() {
    $('#tabs a').tabs(); 
    $('#vtab-customer-fields a').tabs();
    $('#vtab-company-fields a').tabs();
    setTimeout(function() {
        $('.success').hide('slow');
    }, 2000);
    $('input[name=simple_customer_action_register]').live('change',function(){
        if ($(this).val() == 2) {
            $('#customer_register_init').show();
            $('#customer_password_generate').show();
            $('#customer_password_confirm').show();
            $('#customer_password_length').show();
            $('#customer_view_email').show();
        } else if ($(this).val() == 1) {
            $('#customer_register_init').hide();
            $('#customer_password_generate').show();
            $('#customer_password_confirm').show();
            $('#customer_password_length').show();
            $('#customer_view_email').hide();
        } else if ($(this).val() == 0) {
            $('#customer_register_init').hide();
            $('#customer_password_generate').hide();
            $('#customer_password_confirm').hide();
            $('#customer_password_length').hide();
            $('#customer_view_email').show();
        }
    });
    $('input[name=simple_customer_action_subscribe]').live('change',function(){
        if ($(this).val() == 2) {
            $('#customer_subscribe_init').show();
        } else {
            $('#customer_subscribe_init').hide();
        }
    });
    $('input[name=simple_registration_subscribe]').live('change',function(){
        if ($(this).val() == 2) {
            $('#registration_subscribe_init').show();
        } else {
            $('#registration_subscribe_init').hide();
        }
    });
    $('select.type').live('change', function() {
        var ftype = $(this).val();
        if (ftype == 'select' || ftype == 'radio' || ftype == 'checkbox') {
            $(this).parents('tr').nextAll('.row_values:first').show();
            init_values($(this).parents('tr').nextAll('.row_values:first').find('textarea'));
        } else {
            $(this).parents('tr').nextAll('.row_init:first').find('td').eq(1).children().not('span.init_from_api').remove();
            $(this).parents('tr').nextAll('.row_values:first').hide();
        }
        if (ftype != 'date') {
            $(this).parents('tr').nextAll('.row_date:first').hide();
        } else {
            $(this).parents('tr').nextAll('.row_date:first').show();
        }
    });
    $('tr.row_values textarea').live('keyup', function() {
        init_values($(this));
    });
    $('tr.row_values textarea').each(function() {
        //init_values(this);
    });
    
    $.fn.extend({
        multiSelect: function(destination,container) {
            var _select = this;
            //var _container = $(this).parent().find(container);
            var _container = $(container);
            var removeItems = function() {
                $("div", _container).remove();
            }
            var addItem = function(id,text) {
                var bold = '';
                if (id.indexOf('header_') === 0) {
                    var bold = 'font-weight:bold;'
                }
                _container
                    .append(
                        $("<div/>")
                            .attr("id", "multiSelectItem_" + id)
                            .data("id", id)
                            .data("destination", destination)
                            .attr("style", bold + "cursor:pointer;overflow:hidden;width:300px;border-bottom:1px dotted #DDDDDD;padding:3px;margin-bottom:3px;")
                            .append(
                                $("<span/>")
                                    .attr("style","margin-right:5px;")
                                    .text(text)
                            )
                            .append(
                                $("<img/>")
                                    .attr("src", "view/image/delete.png")
                                    .attr("style", "cursor:pointer;float:right;")
                                    .click(function() {
                                        $("div#multiSelectItem_" + id, _container).remove();
                                        serialize();
                                    })
                            )
                    );
            }
            var unserialize = function(data) {
                removeItems();
                data = data.split(",");
                for (var i=0;i<data.length;i++) {
                    var s = data[i].split('.');
                    var id = s.length > 0 ? s[0] : data;
                    addItem(id,$("option[value=\""+id+"\"]",_select).text());
                }
            }
            var serialize = function() {
                var list = $("div",_container);
                var serialized = "";
                for (var i=0;i<list.length;i++) {
                    serialized += (i == 0 ? "" : ",") + $(list[i]).data("id");
                }
                $(destination).val(serialized);
            }
            if ($(destination).val() != "") {
                unserialize($(destination).val());
            }
            $(this).change(function(){
                var id = $(this).val();
                var text = $(":selected",this).text();
                if (id == 0 || $("div#multiSelectItem_" + id, _container).length != 0) {
                    $(this).val("");
                    return;
                }
                addItem(id,text);
                serialize();
                $(this).val("");
            });
            $(this).focus(function(){
                if ($(destination).val() != "") {
                    unserialize($(destination).val());
                }
            });
            return this;
        }
    });
});
$(function() {
    <?php global $selectbox_javascript; ?>
    <?php echo $selectbox_javascript; ?>
    $('.datepicker').datepicker();
    $('.sortable').sortable({
        revert: true,
        stop: function(event, ui) {
            var list = ui.item.parent().find('div');
            var serialized = "";
            for (var i=0;i<list.length;i++) {
                serialized += (i == 0 ? "" : ",") + $(list[i]).data("id");
            }
            $(ui.item.data("destination")).val(serialized);
        }
    });
});
</script>
<?php echo $footer; ?>