<div class="simplecheckout-block-heading"><?php echo $text_checkout_shipping_method ?></div>
<?php if ($simple_show_errors && $error_warning) { ?>
    <div class="simplecheckout-warning-block"><?php echo $error_warning ?></div>
<?php } ?> 
<div class="simplecheckout-block-content">
    <?php if (!empty($disabled_methods)) { ?>
        <table class="simplecheckout-methods-table" style="margin-bottom:0px;">
            <?php foreach ($disabled_methods as $key => $value) { ?>
                <tr>
                    <td class="code">
                        <input type="radio" name="disabled_shipping_method" disabled="disabled" value="<?php echo $key; ?>" id="<?php echo $key; ?>" />
                    </td>
                    <td class="title" valign="middle">
                        <label for="<?php echo $key; ?>">
                            <?php echo $value['title']; ?>
                        </label>
                    </td>
                    <td class="quote">
                    </td>
                </tr>
                <?php if (!empty($value['description'])) { ?>
                    <tr>
                        <td class="code">
                        </td>
                        <td class="title">
                            <label for="<?php echo $key; ?>"><?php echo $value['description']; ?></label>
                        </td>
                        <td class="quote">
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </table>
    <?php } ?>
    <?php if (!empty($shipping_methods)) { ?>
        <table class="simplecheckout-methods-table">
            <?php foreach ($shipping_methods as $shipping_method) { ?>
                <?php if ($simple_shipping_view_title) { ?>
                <tr>
                    <td colspan="3"><b><?php echo $shipping_method['title']; ?></b></td>
                </tr>
                <?php } ?>
                <?php if (!empty($shipping_method['warning'])) { ?>
                    <tr>
                        <td colspan="3"><div class="simplecheckout-error-text"><?php echo $shipping_method['warning']; ?></div></td>
                    </tr>
                <?php } ?>
                <?php if (empty($shipping_method['error'])) { ?>
                    <?php foreach ($shipping_method['quote'] as $quote) { ?>
                        <tr>
                            <td class="code">
                                <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" <?php if ($quote['code'] == $code) { ?>checked="checked"<?php } ?> onchange="simplecheckout_reload('shipping_changed');" />
                            </td>
                            <td class="title" valign="middle">
                                <label for="<?php echo $quote['code']; ?>">
                                    <?php echo $quote['title']; ?>
                                </label>
                                <?php if (!empty($quote['img'])) { ?>
                                <label for="<?php echo $quote['code']; ?>">
                                    <img src="<?php echo $quote['img']; ?>" width="60" height="32" border="0" style="display:block;margin:3px;">
                                </label>
                                <?php } ?>
                            </td>
                            <td class="quote">
                                <label for="<?php echo $quote['code']; ?>"><?php echo $quote['text']; ?></label>
                            </td>
                        </tr>
                        <?php if (!empty($quote['description'])) { ?>
                            <tr>
                                <td class="code">
                                </td>
                                <td class="title">
                                    <label for="<?php echo $quote['code']; ?>"><?php echo $quote['description']; ?></label>
                                </td>
                                <td class="quote">
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="3"><div class="simplecheckout-error-text"><?php echo $shipping_method['error']; ?></div></td>
                    </tr>
                <?php } ?>
                <?php if (!empty($shipping_method['description'])) { ?>
                <tr>
                    <tr>
                        <td class="code">
                        </td>
                        <td class="title">
                            <?php echo $shipping_method['description']; ?>
                        </td>
                        <td class="quote">
                        </td>
                    </tr>
                </tr>
                <?php } ?>
                <?php } ?>
        </table>
        <input type="hidden" name="shipping_method_current" value="<?php echo $code ?>" />
        <input type="hidden" name="shipping_method_checked" value="<?php echo $checked_code ?>" />
    <?php } ?>
    <?php if (empty($shipping_methods) && $address_empty && $simple_shipping_view_address_empty) { ?>
        <div class="simplecheckout-warning-text"><?php echo $text_shipping_address; ?></div>
    <?php } ?>
    <?php if (empty($shipping_methods) && !$address_empty) { ?>
        <div class="simplecheckout-warning-text"><?php echo $error_no_shipping; ?></div>
    <?php } ?>
</div>
<?php if ($simple_debug) print_r($address); ?>