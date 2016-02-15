$(document).ready(function() {


	$('#vignette .button a').click(function(){

		$('#vignette .button a').hide();
		$('.article-reveal').slideDown();

		$('#vignette h2').animate({

			}, {
				duration: 400
		});

		$('.close-article').fadeIn();

	});

	 $('.close-article').click(function(){

		$('.article-reveal').slideUp();
		$('#vignette .button a').fadeIn(2000);

		$('#vignette h2').animate({

			}, {
				duration: 400
		});

		$('.close-article').hide();

	});


	 if (jQuery.browser.msie && parseFloat(jQuery.browser.version) < 8) {

		  $('.flexslider').flexslider({
		  animation: "slide",
		  controlNav: false,
		  slideshowSpeed: 10500,
		  animationDuration: 1300,
		  pauseOnAction: true,
		  pauseOnHover: true,

		  after: function(slider) {

			$('.article-reveal').hide();
			$('#vignette .button a').show();
		  }

	});

	} else {

	$('.flexslider').flexslider({
		  animation: "slide",
		  controlNav: true,
		  slideshowSpeed: 10500,
		  animationDuration: 1300,
		  pauseOnAction: true,
		  start: function(slider) {
			$('.slides li .vignette-details').animate({
				opacity: 1.0,
				'margin-left': '0'
			}, 500 );

            if (slider.count < 2) {
            	slider.pause();
            }
			$('.vignette-details .button a').click(function() {
                slider.pause();
            });

            $('.close-article').click( function() {
                if (slider.count > 1) {
                slider.resume();
                }
            });

		  },

		  before: function(slider) {
			$('.slides li .vignette-details').animate({
				opacity: 0.0,
				'margin-left': '0'
			}, 500 );



		  },

		  after: function(slider) {

			$('.article-reveal').hide();
			$('#vignette .button a').show();
			$('.slides li .vignette-details').css('opacity', '0');


			$('.slides li .vignette-details').animate({
				opacity: 1.0,
				'margin-left': '0'
			}, 500 );


		  }
	 });
	 }


});