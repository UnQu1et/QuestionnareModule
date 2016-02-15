
var container = '';
var page = 1;
var wh = 0;
var load = false;
var ct = 0;
var pages = [];
var filter_ajax = false;
var tmp_data_container = '';
var container_first_div = '';
function getNextPage() {
    if (load) return;
    if (!filter_ajax) {
        if (page>pages.length) return;
        page++;
    } else {
        if ($('.pagination b').next().length==0) return;
    }
    load = true;
    if (filter_ajax) {
        h = parseFloat($(container).css('height'));
        $(container).css('height',h+'px');
        tmp_data_container = $(container).html();
        container_first_div = $(container).find('div').eq(0).html();
    } 
    w = parseFloat($(container).css('width'));
    $(container).append('<div id="ajaxblock" style="width:'+w+'px;height:30px;margin-top:20px;text-align:center;border:none !important;"><img src="/image/loader.gif" /></div>');
    
    
    if (!filter_ajax) {
        $.ajax({
        url:pages[page-2], 
        type:"GET", 
        data:'',
        success:function (data) {
            
            $data =$(data);
            $('#ajaxblock').remove();
            if ($data) {         
                if ($data.find('.product-list').length>0)    {
                    $(container).append($data.find('.product-list').html());
                } else {
                    $(container).append($data.find('.product-grid').html());
                }
            }
            if (typeof display=='function') {
                
                view = $.cookie('display');
             
                if (view) {
                    display(view);
                } else {
                    display('list');
                }
            }
            load = false;
        }});
    } else {
      $('.pagination b').next().click();
      setTimeout('checkData()',100);
      
    }

}
function checkData() {
    if (container_first_div == $(container).find('div').eq(0).html()) {
        setTimeout('checkData()',100);    
    } else {
       $(container).prepend(tmp_data_container);
       $('#ajaxblock').remove();
       $(container).css('height','auto');
       load = false; 
    }
}
function scroll_top_page() {
    $('html, body').animate({
                     scrollTop: 0
                 }, 400, function() {
                     $('.arrow-scroll-top').remove();
                 });
   
}

$(document).ready(function(){ 

    wh = $(window).height();
    if ($('.product-list').length>0) {
        container = '.product-list';
    } else {
        container = '.product-grid';
    }
    if ($(container).length>0) {
        ct = parseFloat($(container).offset().top);
        filter_ajax = (typeof doFiltergs == 'function') || (typeof doFilter == 'function');
        
                       
        $('.pagination a').each(function(){
            href = $(this).attr('href');
            if (jQuery.inArray(href,pages)==-1) {
              pages.push(href);
            }
        });
        $('.pagination').hide();
        $(window).scroll(function(){
            ch = $(container).height();
            scroll_t = $(this).scrollTop();
            if (scroll_t>100) {
                if ($('.arrow-scroll-top').length==0) {
                    $('body').append('<a class="arrow-scroll-top" onclick="scroll_top_page();"></a>')
                }
            } else {
                $('.arrow-scroll-top').remove();
            }
            if(ct+ch-wh<(scroll_t+50)){
                getNextPage();
            }
        });
    }    
    
});