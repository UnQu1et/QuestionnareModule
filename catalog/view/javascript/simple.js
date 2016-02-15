function set_autocomplete() {
    if (typeof(jQuery('input[autocomplete]').autocomplete) !== 'undefined') {
        jQuery('input[autocomplete]').autocomplete({
            source: function( request, response ) {
                jQuery.ajax({
                    url: "index.php?"+simple_route+"route=account/simpleregister/geo",
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function( data ) {
                        response( jQuery.map( data, function( item ) {
                            return {
                                id: item.id,
                                label: item.full,
                                value: item.city,
                                postcode: item.postcode,
                                zone_id: item.zone_id,
                                country_id: item.country_id,
                                city: item.city
                            }
                        }));
                    }
                });
            },
            minLength: 2,
            delay: 300,
            select: function( event, ui ) {
                var name = jQuery(this).attr('name');
                var from = name.substr(0, name.indexOf('['));
                if (ui.item) {
                    var country = jQuery('#'+from+'_main_country_id').val();
                    if (country != ui.item.country_id) {
                        jQuery('#'+from+'_main_zone_id').load('index.php?'+simple_route+'route=account/simpleregister/zone&country_id=' + ui.item.country_id, function() {
                            ui.item.country_id && jQuery('#'+from+'_main_country_id').val(ui.item.country_id);
                            ui.item.zone_id && jQuery('#'+from+'_main_zone_id').val(ui.item.zone_id);
                            ui.item.city && jQuery('#'+from+'_main_city').val(ui.item.city);
                            ui.item.postcode && jQuery('#'+from+'_main_postcode').val(ui.item.postcode);
                            if (typeof simplecheckout_reload === 'function') {
                                simplecheckout_reload('autocomplete_changed');
                            }
                        });
                    } else {
                        ui.item.country_id && jQuery('#'+from+'_main_country_id').val(ui.item.country_id);
                        ui.item.zone_id && jQuery('#'+from+'_main_zone_id').val(ui.item.zone_id);
                        ui.item.city && jQuery('#'+from+'_main_city').val(ui.item.city);
                        ui.item.postcode && jQuery('#'+from+'_main_postcode').val(ui.item.postcode);
                        if (typeof simplecheckout_reload === 'function') {
                            simplecheckout_reload('autocomplete_changed');
                        }
                    }
                }
            }
        });
    }
}

function set_popups() {
    if (typeof(jQuery.fancybox) == 'function') {
        jQuery('.fancybox').fancybox({
        	width: 560,
        	height: 560,
        	autoDimensions: false
        });
    }

    if (typeof(jQuery.colorbox) == 'function') {
        jQuery('.colorbox').colorbox({
            width: 560,
            height: 560
        });
    }

    if (typeof(jQuery.prettyPhoto) !== 'undefined') {
        jQuery("a[rel^='prettyPhoto']").prettyPhoto({
    	   theme: 'light_square',
    	   opacity: 0.5,
    	   social_tools: "",
    	   deeplinking: false
        });
    }
}

function set_masks() {
    var masked = [];
    jQuery('input[mask]').each(function(indx) {
        var mask = jQuery(this).attr('mask');
        var id = jQuery(this).attr('id');
        if (mask && id) {
            masked[masked.length] = [id,mask];
        }
    });
    for (var i=0;i<masked.length;i++) {
        jQuery('input[id=' + masked[i][0] + ']').mask(masked[i][1]);
    }
}

function set_placeholders() {
    jQuery('input[placeholder]').placeholder();
}

function no_weekends_or_holidays(date) {
    var noWeekend = jQuery.datepicker.noWeekends(date);
    if (noWeekend[0]) {
        return national_days(date);
    } else {
        return noWeekend;
    }
}

function national_days(date) {
    var nat_days = [
      [1, 1, 'ru'],
      [1, 7, 'ru'],
      [5, 9, 'ru']
    ];

    for (i = 0; i < nat_days.length; i++) {
        if (date.getMonth() == nat_days[i][0] - 1 && date.getDate() == nat_days[i][1]) {
            return [false, nat_days[i][2] + '_day'];
        }
    }
    return [true, ''];
}

function add_days(add, only_business) {
    var result = add | 0;

    if (only_business) {
        var i = 1;
        while (i <= result) {
            var date_test = new Date();
            date_test.setDate(date_test.getDate() + i);
            var test = no_weekends_or_holidays(date_test);
            if (!test[0]) {
                result++;
            }
            i++;
        }
    }
    
    return result;
}

function set_datepickers() {

    //if (jQuery.browser.msie && jQuery.browser.version <= 7) {
    //   jQuery('.datepicker').bgIframe();
    //}

    jQuery('input[jdate]').each(function() {
        if (typeof(jQuery(this).datepicker) !== 'undefined') {
            var only_business = jQuery(this).attr('date_only_business');

            if (jQuery(this).attr('date_min')) {
                var date_min = jQuery(this).attr('date_min');
            } else if (jQuery(this).attr('date_start') !== undefined) {
                var date_min = new Date();
                date_min.setDate(date_min.getDate() + add_days(jQuery(this).attr('date_start'), only_business));
            }

            if (jQuery(this).attr('date_max')) {
                var date_max = jQuery(this).attr('date_max');
            } else if (jQuery(this).attr('date_end') !== undefined) {
                var date_max = new Date();
                date_max.setDate(date_max.getDate() + add_days(jQuery(this).attr('date_end'), only_business));
            }

            jQuery(this).datepicker({
                firstDay: 1,
                beforeShowDay: only_business ? no_weekends_or_holidays : null,
                minDate: date_min ? date_min : null,
                maxDate: date_max ? date_max : null,
                onSelect: function(dateText, inst) {
                    if (typeof customer_field_changed === 'function') {
                        customer_field_changed(this);
                    }
                }
            });
        }
    });
}

function set_googleapi() {
    if (simple_googleapi) {
        $('input[googleapi]').change(function() {
            var from = $(this).attr('googleapi');
            var geocoder = new google.maps.Geocoder();
            var address = $('#'+from+'_main_postcode').val() + ',' + $('#'+from+'_main_country_id option:selected').text();
            var type_short;
            var anything_changed = false;

            if (geocoder) {
                geocoder.geocode({ 'address': address, 'language': $('#'+from+'_main_country_id option:selected').text() }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        for (result in results) {
                            for (component in results[result].address_components) {
                                for (type in results[result].address_components[component].types) {
                                    type_short = results[result].address_components[component].types[type];
                                    if(type_short == 'administrative_area_level_1') {
                                        $('#'+from+'_main_zone_id option').filter(function() {
                                            return $(this).text().replace(/\W/g,'') == results[result].address_components[component].long_name.replace(/\W/g,''); 
                                        }).attr('selected', 'selected');
                                        anything_changed = true;
                                    }
                                    if(type_short == 'locality') {
                                        $('#'+from+'_main_city').val(results[result].address_components[component].long_name);
                                        anything_changed = true;
                                    }
                                }
                            }
                        }
                        if (anything_changed && typeof simplecheckout_reload === 'function') {
                            simplecheckout_reload('googleapi_completed');
                        }
                    } else {
                        console.log("Geocoding failed: " + status);
                        if (typeof simplecheckout_reload === 'function') {
                            //simplecheckout_reload('googleapi_completed');
                        }
                    }
                });
            }
        });
    }
};