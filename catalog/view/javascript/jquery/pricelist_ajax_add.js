$(document).ready(function () {
	$('.add_to_cart').removeAttr('onclick');

	$('.add_to_cart').click(function () {
        var this_form = $(this).parent();
		$.ajax({
			type: 'post',
			url: 'index.php?route=checkout/cart/update',
			dataType: 'json',
			data: this_form.find(':input'),
            success: function(json) {
    			$('.success, .warning, .attention, information, .error').remove();

    			if (json['error']) {
    				if (json['error']['warning']) {
    					$('#notification').html('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

    					$('.warning').fadeIn('slow');
    				}

    				for (i in json['error']) {
    					$('#option-' + i).after('<span class="error">' + json['error'][i] + '</span>');
    				}
    			}

    			if (json['success']) {
    				$('#notification').html('<div class="attention" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

    				$('.attention').fadeIn('slow');

    				$('#cart_total').html(json['total']);

    				$('html, body').animate({ scrollTop: 0 }, 'slow');
    			}
    		}
		});			
	});
});