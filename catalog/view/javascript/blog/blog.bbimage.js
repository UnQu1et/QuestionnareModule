$(document).ready(function(){

var $img = $('.bbimage');

// сбрасываем атрибуты для кеша
$img.each(function() {
    var src = $(this).attr('src');
    $(this).attr('src', '');
    $(this).attr('src', src);
});

// ждем загрузки картинки браузером
$img.load(function(){
    // удаляем атрибуты width и height

    var attrwidth = $(this).attr("width");


    $(this).removeAttr("width").removeAttr("height").css({ width: "", height: "" });
    // получаем заветные цифры
    var width  = $(this).width();
    var height = $(this).height();
   //  alert(width + 'x' + height);
 if (width > attrwidth) {
	$(this).attr('width', attrwidth+'px');
 } else {
    $(this).attr('width', width+'px');
}
});


});