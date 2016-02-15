<div class="simplecheckout-block-heading">
    <?php echo $text_checkout_customer ?>
    <?php if ($simple_customer_view_login) { ?>
    <span class="simplecheckout-block-heading-button">
        <a href="<?php echo $default_login_link ?>" <?php if (!$is_mobile) { ?>onclick="simple_login_open();return false;"<?php } ?> id="simplecheckout_customer_login"><?php echo $text_checkout_customer_login ?></a>
    </span>
    <?php } ?>
</div>  
<div class="simplecheckout-block-content">
    <?php if ($simple_customer_registered) { ?>
        <div class="success" id="customer_registered" style="text-align:left;"><?php echo $simple_customer_registered ?></div>
    <?php } ?>
    <?php if ($text_you_will_be_registered) { ?>
        <div class="you-will-be-registered"><?php echo $text_you_will_be_registered ?></div>
    <?php } ?>
    <?php if ($simple_customer_view_address_select && !empty($addresses)) { ?>
        <div class="simplecheckout-customer-address">
        <span><?php echo $text_select_address ?>:</span>&nbsp;
        <select name='customer_address_id' id="customer_address_id" reload='address_changed'>
            <option value="0" <?php echo $customer_address_id == 0 ? 'selected="selected"' : '' ?>><?php echo $text_add_new ?></option>
            <?php foreach($addresses as $address) { ?>
                <option value="<?php echo $address['address_id'] ?>" <?php echo $customer_address_id == $address['address_id'] ? 'selected="selected"' : '' ?>><?php echo $address['firstname']; ?> <?php echo !empty($address['lastname']) ? ' '.$address['lastname'] : ''; ?><?php echo !empty($address['address_1']) ? ', '.$address['address_1'] : ''; ?><?php echo !empty($address['city']) ? ', '.$address['city'] : ''; ?></option>
            <?php } ?>
        </select>
        </div>
    <?php } ?>
    <input type="hidden" name="<?php echo Simple::SET_CHECKOUT_CUSTOMER ?>[address_id]" id="customer_address_id" value="<?php echo $customer_address_id ?>" />
    <?php $split_previous = false; ?>
    <?php $user_choice = false; ?>
    <div class="simplecheckout-customer-block">
    <table class="<?php echo $simple_customer_two_column ? 'simplecheckout-customer-two-column-left' : 'simplecheckout-customer-one-column' ?>">
        <?php $email_field_exists = false; ?>
        <?php $i = 0; ?>
        <?php foreach ($checkout_customer_fields as $field) { ?>
            <?php if ($i == 0 && !$customer_logged && $simple_customer_action_register == Simple::REGISTER_USER_CHOICE) { ?>
                <tr>
                    <td class="simplecheckout-customer-left">
                       <?php echo $entry_register; ?>
                    </td>
                    <td class="simplecheckout-customer-right">
                      <label><input type="radio" name="register" value="1" <?php echo $register == 1 ? 'checked="checked"' : ''; ?> reload="customer_register" /><?php echo $text_yes ?></label>&nbsp;
                      <label><input type="radio" name="register" value="0" <?php echo $register == 0 ? 'checked="checked"' : ''; ?> reload="customer_not_register" /><?php echo $text_no ?></label>
                    </td>
                </tr>
                <?php $user_choice = true; ?>
            <?php $i++ ?>
            <?php } ?>
            <?php if ($field['type'] == 'hidden') { ?>
                <?php continue; ?>
            <?php } elseif ($field['type'] == 'header') { ?>
            <tr class="simple_table_row" <?php echo !empty($field['place']) ? 'place="'.$field['place'].'"' : '' ?>>
                <td colspan="2" <?php echo $user_choice && $split_previous ? 'class="simple-header-right"' : ''; ?>>
                    <?php echo $field['tag_open'] ?><?php echo $field['label'] ?><?php echo $field['tag_close'] ?>
                </td>
            </tr>
            <?php } elseif ($field['type'] == 'split') { ?>
                </table>
                <table class="<?php echo $simple_customer_two_column ? 'simplecheckout-customer-two-column-right' : 'simplecheckout-customer-one-column' ?>">
                <?php $split_previous = true; ?>
            <?php } else { ?>
                <?php if ((($user_choice && $i == 1) || (!$user_choice && $i == 0)) && $simple_customer_view_customer_type) { ?>
                    <tr>
                        <td class="simplecheckout-customer-left">
                            <span class="simplecheckout-required">*</span>
                            <?php echo $entry_customer_type ?>
                        </td>
                        <td class="simplecheckout-customer-right">
                            <?php if ($simple_type_of_selection_of_group == 'select') { ?>
                            <select name="customer_group_id" reload="group_changed">
                                <?php foreach ($customer_groups as $id => $name) { ?>
                                <option value="<?php echo $id ?>" <?php echo $id == $customer_group_id ? 'selected="selected"' : '' ?>><?php echo $name ?></option>
                                <?php } ?>
                            </select>
                            <?php } else { ?>
                                <?php foreach ($customer_groups as $id => $name) { ?>
                                <label><input type="radio" name="customer_group_id" reload="group_changed" value="<?php echo $id ?>" <?php echo $id == $customer_group_id ? 'checked="checked"' : '' ?>><?php echo $name ?></label><br>
                                <?php } ?>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php $i++ ?>
                    <?php $split_previous = false; ?>
                <?php } ?>
                <?php if ($field['id'] == 'main_email') { ?>
                    <?php if (!$customer_logged) { ?>
                        <?php if (!$simple_customer_action_register &&  !$simple_customer_view_email && !$simple_customer_view_customer_type) { continue; } ?>
                        <?php $split_previous = false; ?>
                        <?php if (!($simple_customer_view_email == Simple::EMAIL_NOT_SHOW && ($simple_customer_action_register == Simple::REGISTER_NO || ($simple_customer_action_register == Simple::REGISTER_USER_CHOICE && !$register)))) { ?>
                        <?php $email_field_exists = true; ?>
                        <tr>
                            <td class="simplecheckout-customer-left">
                                <?php if ($field['required']) { ?>
                                    <span class="simplecheckout-required" <?php echo ($simple_customer_view_email == Simple::EMAIL_SHOW_AND_NOT_REQUIRED && ($simple_customer_action_register == Simple::REGISTER_NO || ($simple_customer_action_register == Simple::REGISTER_USER_CHOICE && !$register))) ? ' style="display:none"' : '' ?>>*</span>
                                <?php } ?>
                                <?php echo $field['label'] ?>
                            </td>
                            <td class="simplecheckout-customer-right">
                                <?php echo $simple->html_field($field) ?>
                                <?php if (!empty($field['error']) && $simple_show_errors) { ?>
                                    <span class="simplecheckout-error-text"><?php echo $field['error']; ?></span>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php if ($simple_customer_view_email_confirm) { ?>
                        <tr>
                            <td class="simplecheckout-customer-left">
                                <?php if ($field['required']) { ?>
                                    <span class="simplecheckout-required" <?php echo ($simple_customer_view_email == Simple::EMAIL_SHOW_AND_NOT_REQUIRED && ($simple_customer_action_register == Simple::REGISTER_NO || ($simple_customer_action_register == Simple::REGISTER_USER_CHOICE && !$register))) ? ' style="display:none"' : '' ?>>*</span>
                                <?php } ?>
                                <?php echo $entry_email_confirm ?>
                            </td>
                            <td class="simplecheckout-customer-right">
                                <input name="email_confirm" id="email_confirm" type="text" value="<?php echo $email_confirm ?>">
                                <span class="simplecheckout-error-text" id="email_confirm_error" <?php if (!($email_confirm_error && $simple_show_errors)) { ?>style="display:none;"<?php } ?>><?php echo $error_email_confirm; ?></span>
                            </td>
                        </tr>
                        <?php } ?>
                        <?php if ($simple_customer_action_register == Simple::REGISTER_YES || ($simple_customer_action_register == Simple::REGISTER_USER_CHOICE && $register)) { ?>
                            <tr id="password_row" <?php echo $simple_customer_generate_password ? ' style="display:none;"' : '' ?> <?php echo $simple_customer_generate_password ? 'autogenerate="1"' : '' ?>>
                                <td class="simplecheckout-customer-left">
                                    <span class="simplecheckout-required">*</span>
                                    <?php echo $entry_password ?>
                                </td>
                                <td class="simplecheckout-customer-right">
                                    <input <?php echo !empty($error_password) ? 'class="simplecheckout-red-border"' : '' ?> type="password" name="password" value="<?php echo $password ?>">
                                    <?php if (!empty($error_password) && $simple_show_errors) { ?>
                                        <span class="simplecheckout-error-text"><?php echo $error_password; ?></span>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php if ($simple_customer_view_password_confirm) { ?>
                            <tr id="confirm_password_row" <?php echo $simple_customer_generate_password ? ' style="display:none;"' : '' ?> <?php echo $simple_customer_generate_password ? 'autogenerate="1"' : '' ?>>
                                <td class="simplecheckout-customer-left">
                                    <span class="simplecheckout-required">*</span>
                                    <?php echo $entry_password_confirm ?>
                                </td>
                                <td class="simplecheckout-customer-right">
                                    <input <?php echo !empty($error_password_confirm) ? 'class="simplecheckout-red-border"' : '' ?> type="password" name="password_confirm" value="<?php echo $password_confirm ?>">
                                    <?php if (!empty($error_password_confirm) && $simple_show_errors) { ?>
                                        <span class="simplecheckout-error-text"><?php echo $error_password_confirm; ?></span>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } ?>
                        <?php } ?>
                        <?php } ?>
                    <?php } ?>
                    <?php if ($customer_logged) { continue; } ?>
                <?php } else { ?>
                    <tr class="simple_table_row" <?php echo !empty($field['place']) ? 'place="'.$field['place'].'"' : '' ?>>
                        <td class="simplecheckout-customer-left">
                            <?php if ($field['required']) { ?>
                                <span class="simplecheckout-required">*</span>
                            <?php } ?>
                            <?php echo $field['label'] ?>
                        </td>
                        <td class="simplecheckout-customer-right">
                            <?php echo $simple->html_field($field) ?>
                            <?php if (!empty($field['error']) && $simple_show_errors) { ?>
                                <span class="simplecheckout-error-text"><?php echo $field['error']; ?></span>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php $split_previous = false; ?>
                <?php } ?>
                <?php $i++; ?>
            <?php } ?>
        <?php } ?>
        <?php if ($simple_customer_action_subscribe == Simple::SUBSCRIBE_USER_CHOICE && $email_field_exists) { ?>
            <tr id="subscribe_row"<?php echo $simple_customer_action_register == Simple::REGISTER_USER_CHOICE && !$register && !$simple_customer_view_email ? ' style="display:none;"' : '' ?>>
                <td class="simplecheckout-customer-left">
                   <?php echo $entry_newsletter; ?>
                </td>
                <td class="simplecheckout-customer-right">
                  <label><input type="radio" name="subscribe" value="1" <?php echo $subscribe == 1 ? 'checked="checked"' : ''; ?> /><?php echo $text_yes ?></label>&nbsp;
                  <label><input type="radio" name="subscribe" value="0" <?php echo $subscribe == 0 ? 'checked="checked"' : ''; ?> /><?php echo $text_no ?></label>
                </td>
            </tr>
        <?php } ?>
    </table>
    <?php foreach ($checkout_customer_fields as $field) { ?>
        <?php if ($field['type'] == 'hidden') { ?>
        <?php echo $simple->html_field($field) ?>
        <?php } ?>
    <?php } ?>
    </div>
</div>

<?php if ($simple_show_shipping_address) { ?>
<div class="simplecheckout-customer-same-address">
<?php if ($simple_show_shipping_address_same_show) { ?>
    <label><input type="checkbox" name="shipping_address_same" id="shipping_address_same" value="1" <?php if ($shipping_address_same) { ?>checked="checked"<?php } ?> reload="address_same">&nbsp;<?php echo $entry_address_same ?></label>
<?php } ?>
</div>
<?php if (!$shipping_address_same) { ?>
<div class="simplecheckout-block-heading simplecheckout-shipping-address">
    <?php echo $text_checkout_shipping_address ?>
</div>  
<div class="simplecheckout-block-content simplecheckout-shipping-address">
    <?php if ($simple_shipping_view_address_select && !empty($addresses)) { ?>
        <div class="simplecheckout-customer-address">
        <span><?php echo $text_select_address ?>:</span>&nbsp;
        <select name='shipping_address_id' id="shipping_address_id" reload='address_changed'>
            <option value="0" <?php echo $shipping_address_id == 0 ? 'selected="selected"' : '' ?>><?php echo $text_add_new ?></option>
            <?php foreach($addresses as $address) { ?>
                <option value="<?php echo $address['address_id'] ?>" <?php echo $shipping_address_id == $address['address_id'] ? 'selected="selected"' : '' ?>><?php echo $address['firstname']; ?> <?php echo !empty($address['lastname']) ? ' '.$address['lastname'] : ''; ?><?php echo !empty($address['address_1']) ? ', '.$address['address_1'] : ''; ?><?php echo !empty($address['city']) ? ', '.$address['city'] : ''; ?></option>
            <?php } ?>
        </select>
        </div>
    <?php } ?>
    <input type="hidden" name="<?php echo Simple::SET_CHECKOUT_ADDRESS ?>[address_id]" id="shipping_address_id" value="<?php echo $shipping_address_id ?>" />
    <div class="simplecheckout-customer-block">
    <table class="<?php echo $simple_customer_two_column ? 'simplecheckout-customer-two-column-left' : 'simplecheckout-customer-one-column' ?>">
        <?php foreach ($checkout_address_fields as $field) { ?>
            <?php if ($field['type'] == 'hidden') { ?>
                <?php continue; ?>
            <?php } elseif ($field['type'] == 'header') { ?>
            <tr class="simple_table_row" <?php echo !empty($field['place']) ? 'place="'.$field['place'].'"' : '' ?>>
                <td colspan="2">
                    <?php echo $field['tag_open'] ?><?php echo $field['label'] ?><?php echo $field['tag_close'] ?>
                </td>
            </tr>
            <?php } elseif ($field['type'] == 'split') { ?>
                </table>
                <table class="<?php echo $simple_customer_two_column ? 'simplecheckout-customer-two-column-right' : 'simplecheckout-customer-one-column' ?>">
            <?php } else { ?>
            <tr class="simple_table_row" <?php echo !empty($field['place']) ? 'place="'.$field['place'].'"' : '' ?>>
                <td class="simplecheckout-customer-left">
                    <?php if ($field['required']) { ?>
                        <span class="simplecheckout-required">*</span>
                    <?php } ?>
                    <?php echo $field['label'] ?>
                </td>
                <td class="simplecheckout-customer-right">
                    <?php echo $simple->html_field($field) ?>
                    <?php if (!empty($field['error']) && $simple_show_errors) { ?>
                        <span class="simplecheckout-error-text"><?php echo $field['error']; ?></span>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        <?php } ?>
    </table>
    <?php foreach ($checkout_address_fields as $field) { ?>
        <?php if ($field['type'] == 'hidden') { ?>
        <?php echo $simple->html_field($field) ?>
        <?php } ?>
    <?php } ?>
    </div>
</div>
<?php } ?>
<?php } ?>
<?php if ($simple_debug) print_r($customer); ?>
<?php if ($simple_debug) print_r($comment); ?>