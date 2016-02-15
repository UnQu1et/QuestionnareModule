/*
@author	Dmitriy Kubarev
@link	http://www.simpleopencart.com
@link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/ 

function simpleedit_init() {
    set_masks();
    set_placeholders();
    set_datepickers();
}

jQuery(function() {
    simpleedit_init();
});