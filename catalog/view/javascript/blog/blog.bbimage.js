$(document).ready(function(){

var $img = $('.bbimage');

// ���������� �������� ��� ����
$img.each(function() {
    var src = $(this).attr('src');
    $(this).attr('src', '');
    $(this).attr('src', src);
});

// ���� �������� �������� ���������
$img.load(function(){
    // ������� �������� width � height

    var attrwidth = $(this).attr("width");


    $(this).removeAttr("width").removeAttr("height").css({ width: "", height: "" });
    // �������� �������� �����
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