$(document).ready(function(){

    $('.imagebox').colorbox({
        overlayClose: true,
        opacity: 0.5
    });

    var acolor = $('a.blog-title').css('color');

    if (typeof acolor!='undefined') {

    var rgb = acolor.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);

    if (rgb!=null) {
     var rgba= 'rgba(' + rgb[1] + ',' + rgb[2] +',' + rgb[3] + ',0.5)';

    $('a.blog-title').hover(
      function () {
        $(this).css("border-color", rgba);
      },
      function () {
        $(this).css("border-color", acolor);
      }
    );
    }
    }

});