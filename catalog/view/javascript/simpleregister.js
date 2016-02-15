/*
@author	Dmitriy Kubarev
@link	http://www.simpleopencart.com
@link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/ 

function simpleregister_init() {
    set_masks();
    set_placeholders();
    set_datepickers();
    set_autocomplete();
    set_popups();
    set_googleapi();
}

jQuery(function() {
    simpleregister_init();

    jQuery('#email_confirm').live('change',function(){
        var confirm = $(this).val().trim();
        var email = jQuery('#registration_main_email').val().trim();
        if (confirm != email) {
            $('#email_confirm_error').show();
        } else {
            $('#email_confirm_error').hide();
        }
    });

    if (jQuery('#registration_main_city').is('select')) {
        jQuery('#registration_main_zone_id').live('change',function(){
            jQuery('#simpleregister').submit();
        });
    }
});