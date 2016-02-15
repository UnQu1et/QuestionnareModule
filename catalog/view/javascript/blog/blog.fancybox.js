$(document).ready(function(){
$('.imagebox').fancybox({
	cyclic: false,
	autoDimensions: true,
	autoScale: false,
	'onComplete' : function(){
        $.fancybox.resize();
  }
});
});