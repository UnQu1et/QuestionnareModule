<?php 
$simple_page = 'simpleaddress';
include $simple->tpl_header();
include $simple->tpl_static();
?>
<div class="simple-content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="simpleaddress">
        <div class="simpleregister">
            <?php
                $first_field = reset($address_fields); 
                $first_field_header = !empty($first_field) && $first_field['type'] == 'header'; 
                $i = 0;
            ?>
            <?php if ($first_field_header) { ?>
                <?php echo $first_field['tag_open'] ?><?php echo $first_field['label'] ?><?php echo $first_field['tag_close'] ?>
            <?php } ?>
                <div class="simpleregister-block-content">
                <table class="simplecheckout-customer">
                    <?php foreach ($address_fields as $field) { ?>
                        <?php if ($field['type'] == 'hidden') { continue; } ?>
                        <?php $i++ ?>
                        <?php if ($field['type'] == 'header') { ?>
                        <?php if ($i == 1) { ?>
                            <?php continue; ?>
                        <?php } else { ?>
                        </table>
                        </div>
                        <?php echo $field['tag_open'] ?><?php echo $field['label'] ?><?php echo $field['tag_close'] ?>
                        <div class="simpleregister-block-content">
                        <table class="simplecheckout-customer">
                        <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td class="simplecheckout-customer-left">
                                    <?php if ($field['required']) { ?>
                                        <span class="simplecheckout-required">*</span>
                                    <?php } ?>
                                    <?php echo $field['label'] ?>
                                </td>
                                <td class="simplecheckout-customer-right">
                                    <?php echo $simple->html_field($field) ?>
                                    <?php if (!empty($field['error']) && $simple_edit_address) { ?>
                                        <span class="simplecheckout-error-text"><?php echo $field['error']; ?></span>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>                    
                </table>        
                <?php foreach ($address_fields as $field) { ?>
                    <?php if ($field['type'] != 'hidden') { continue; } ?>
                    <?php echo $simple->html_field($field) ?>
                <?php } ?>
                <input type="hidden" name="simple_edit_address" id="simple_edit_address" value="1">
            </div>
        </div>
        <div class="simpleregister-button-block buttons">
            <div class="simpleregister-button-left"><a href="<?php echo $back; ?>" class="button btn"><span><?php echo $button_back; ?></span></a></div>
            <div class="simpleregister-button-right">
                <a onclick="$('#simpleaddress').submit();" class="button btn"><span><?php echo $button_continue; ?></span></a>
            </div>
        </div>
    </form>
</div>
<?php include $simple->tpl_footer() ?>