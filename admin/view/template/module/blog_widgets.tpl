<?php echo $header; ?>

<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>

  <div style="border-bottom: 1px solid #DDD; background-color: #EFEFEF; height: 40px; min-width: 900px; overflow: hidden;">
    <div style="float:left; margin-top: 10px;" >
    <img src="view/image/blog-icon.png" style="height: 21px; margin-left: 10px; " >
    </div>

<div style="margin-left: 10px; float:left; font-size: 16px; margin-top: 10px;">
<ins style="color: green; padding-top: 17px; text-shadow: 0 2px 1px #FFFFFF; padding-left: 3px; font-size: 17px; font-weight: bold;  text-decoration: none; ">
<?php echo strip_tags($heading_title); ?>
</ins> ver.: <?php echo $blog_version; ?>
</div>

    <div style=" height: 40px; float:right; background:#aceead;">
   <div style="margin-top: 2px; line-height: 18px; margin-left: 9px; margin-right: 9px; font-size: 13px; overflow: hidden;"><?php echo $this->language->get('heading_dev'); ?></div>
    </div>


</div>

<div id="content" style="border: none;">

<!--
<div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo strip_tags($breadcrumb['text']); ?></a>
  <?php } ?>
</div>
-->
<div style="clear: both; line-height: 1px; font-size: 1px;"></div>


<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>

<?php if (isset($this->session->data['success'])) {unset($this->session->data['success']);
?>
<div class="success"><?php echo $this->language->get('text_success'); ?></div>
<?php } ?>


<div class="box1">

<div class="content">

<div style="margin-left: 0px;">
<a href="<?php echo $url_blog; ?>" class="markbutton"><div style="float: left;"><img src="view/image/blog-icon-m.png" style="" ></div><div style="float: left; margin-left: 7px; margin-top: 3px; "><?php echo $url_blog_text; ?></div></a>
<a href="<?php echo $url_record; ?>" class="markbutton"><div style="float: left;"><img src="view/image/blog-rec-m.png"  style="" ></div><div style="float: left; margin-left: 7px; margin-top: 3px; "><?php echo $url_record_text; ?></div></a>
<a href="<?php echo $url_comment; ?>" class="markbutton"><div style="float: left;"><img src="view/image/blog-com-m.png"  style="" ></div><div style="float: left; margin-left: 7px; margin-top: 3px; "><?php echo $url_comment_text; ?></div></a>
<a href="<?php echo $url_modules; ?>" class="markbutton"><div style="float: left;"><img src="view/image/blog-back-m.png"  style="" ></div><div style="float: left; margin-left: 7px; margin-top: 3px; "><?php echo $url_modules_text; ?></div></a>
</div>

<div style=" margin-left: 0px;">
<div style="margin-right:5px; margin-left: 0px; float:left;">
<a href="<?php echo $url_options; ?>" class="markbutton"><div style="float: left;"><img src="view/image/agoodonut-options-m.png"  style="" ></div>
<div style="float: left; margin-left: 7px; margin-top: 4px; "><?php echo $this->language->get('tab_options'); ?></div></a>
</div>


<div style="margin-right:5px; float:left;">
<a href="<?php echo $url_schemes; ?>" class="markbutton"><div style="float: left;"><img src="view/image/agoodonut-schemes-m.png"  style="" ></div>
<div style="float: left; margin-left: 7px; margin-top: 4px; "><?php echo $tab_general; ?></div></a>
</div>

<div style="margin-right:5px; float:left;">
<a href="<?php echo $url_widgets; ?>" class="markbutton-active"><div style="float: left;"><img src="view/image/agoodonut-widgets-m.png"  style="" ></div>
<div style="float: left; margin-left: 7px; margin-top: 4px; "><?php echo $tab_list; ?></div></a>
</div>
</div>

<div style="margin-right:5px; float:right;">
   <a href="#" class="mbutton blog_save"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="mbutton"><?php echo $button_cancel; ?></a>
</div>

<div style=" clear: both; line-height: 1px; font-size: 1px;"></div>

<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

<script type="text/javascript">
function delayer(){
    window.location = 'index.php?route=module/blog/widgets&token=<?php echo $token; ?>';
}
</script>
<?php  if (count($mylist)>0) { ?>
<div id="widgets_loading" style="width: 100%; height: 24px; line-height: 24px;  background-color: #EEE; margin-bottom: 5px;">&nbsp;</div>
<?php } ?>

<div id="tab-list">
	<div id="lists">
		<div id="mytabs" class="vtabs" style="margin-top: 3px; padding-top: 0px;"><a href="#mytabs_add" style="color: #FFF; background: green; "><img src="view/image/madd.png" style="height: 16px; margin-right: 7px;" ><?php echo $this->language->get('text_add'); ?></a></div>


<div id="mytabs_add" >
<div style="">
<div style="float: left;">
 <?php
	             echo $this->language->get('type_list');
	       ?>


         <select id="mylist-what"  name="mylist-what">
                <option value="blogs"><?php echo $this->language->get('text_widget_blogs'); ?></option>
                <option value="blogsall"><?php echo $this->language->get('text_widget_blogsall'); ?></option>
                <option value="latest"><?php echo $this->language->get('text_widget_latest'); ?></option>
                <option value="records"><?php echo $this->language->get('text_widget_records'); ?></option>
                <option value="html"><?php echo $this->language->get('text_widget_html'); ?></option>
                <option value="treecomments"><?php echo $this->language->get('text_widget_treecomments'); ?></option>
                <option value="reviews"><?php echo $this->language->get('text_widget_reviews'); ?></option>
                <option value="related"><?php echo $this->language->get('text_widget_related'); ?></option>


         </select>
         </div>
      <div class="buttons" style="margin-left: 10px; float: left;"><a onclick="
      mylist_num++;
      type_what = $('#mylist-what :selected').val();
      this_block_html = $('#mytabs_add').html();
 		$.ajax({
					url: 'index.php?route=module/blog/ajax_list&token=<?php echo $token; ?>',
					type: 'post',
					data: { type: type_what, num: mylist_num },
					dataType: 'html',
					beforeSend: function()
					{
                      $('#mytabs_add').html('<?php echo $this->language->get('text_loading'); ?>');
					},
					success: function(html) {					$('#mytabs_add').html(this_block_html);
						if (html) {
							$('#mytabs').append('<a href=\'#mytabs' + mylist_num + '\' id=\'amytabs'+mylist_num+'\'>List-' + mylist_num + '<\/a>');
							$('#lists').append('<div id=\'mytabs'+mylist_num+'\'>'+html+'<\/div>');
							$('#mytabs a').tabs();
							$('#amytabs' + mylist_num).click();
							template_auto();
							fields_auto();
						}
						$('.mbutton').removeClass('loader');


					}
				});


      return false; " class="mbutton"><?php echo $this->language->get('button_add_list'); ?></a>
      </div>

 </div>

  </div>

	</div>

	<script type="text/javascript">

	 form_submit = function() {

		$('#form').submit();
		return false;
	}

    </script>




	<?php

	if (count($mylist)>0)
	{
	reset($mylist);
	$first_key = key($mylist);

	$ki=0;
	foreach ($mylist as $num =>$list) {
	$ki++;
	$slist = serialize($list);

	if (isset($list['title_list_latest'][ $this->config->get('config_language_id')]) &&  $list['title_list_latest'][ $this->config->get('config_language_id')]!='')
	{
     $title=$list['title_list_latest'][ $this->config->get('config_language_id')];
	}
	else
	{	 $title="List-".$num;
	}


	?>
	<script type="text/javascript">

	var mylist_num=<?php echo $num; ?>;
		$('#mytabs').append('<a href=\"#mytabs<?php echo $num; ?>\" id=\"amytabs<?php echo $num; ?>\"><?php echo $title; ?><\/a>');
    var progress_num = 0;
    var allcount = <?php echo (count($mylist)); ?>;
		$.ajax({
					url: 'index.php?route=module/blog/ajax_list&token=<?php echo $token; ?>',
					type: 'post',
					async: true,
					data: { list: '<?php echo base64_encode($slist); ?>', num: '<?php echo $num; ?>' },
					dataType: 'html',
					beforeSend: function() {
					 $('a.mbutton').addClass('loader');
					 $('.blog_save').unbind('click');

					},
					success: function(html) {
						if (html) {							$('#lists').append('<div id=\"mytabs<?php echo $num; ?>\">'+html+'<\/div>');
							$('#mytabs a').tabs();
							$('#amytabs<?php echo $first_key; ?>').click();
							template_auto();
							fields_auto();
						}
						<?php if (count($mylist)<=$ki) {  ?>
						$('a.mbutton').removeClass('loader');
						$('.blog_save').bind('click', form_submit);
						$('#widgets_loading').hide();
						<?php } ?>

						<?php
						$loading_recent = round((100*$num)/count($mylist));
						?>
						progress_num++;
						loading_recent = Math.round((100*progress_num)/allcount);

                        $('#widgets_loading').html('<div style=\"height: 24px; line-height: 24px; text-align: center; width:'+loading_recent+'%; color: white;background-color: orange;\">'+loading_recent+'%<\/div>');

					}
				});

		</script>
	<?php

	 }



	}
	else
	{     ?>
     	<script type="text/javascript">
	var mylist_num=0;
        </script>
     <?php
	} ?>

</div>
</div>

    </form>
     <div style="clear: both; line-height: 1px; font-size: 1px;"></div>
      <div class="buttons right" style="margin-top: 20px;float: right;">
    <a href="#" class="mbutton blog_save"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="mbutton"><?php echo $button_cancel; ?></a>

      </div>

  </div>

  </div>
</div>

<script>
template_auto = function() {	$('.template').each(function() {

		var e = this;
		var path = $(e).next().attr('value');
		var list = $(e).next().next().attr('value');
		var iname = $(e).attr('name');
	     // alert(iname);

		$(e).autocomplete({
			delay: 0,
            autoFocus: true,
            minLength: 0,
			source: function(request, response) {

				$.ajax({
					url: 'index.php?route=module/blog/autocomplete_template&path='+path+'&token=<?php echo $token; ?>',
					dataType: 'json',
					success: function(json) {
						response($.map(json, function(item) {
							return {
								label: item.name + ' -> '+ path,
								value: item.name
							}
						}));
					}
				});

			},
			select: function(event, ui) {
			//alert(ui.item.value);

			$('input[name=\''+ iname +'\']').val(ui.item.value);
			return false;
			}
		});

	});
}
</script>



<script>
fields_auto = function() {
	$('.fields').each(function() {

		var e = this;
		var fname = $(e).attr('name');
        var fvalue = $(e).attr('value');


		$(e).autocomplete({
			delay: 0,
            autoFocus: true,
            minLength: 0,
			source: function(request, response) {

				$.ajax({
					url: 'index.php?route=catalog/fields/autocomplete&value='+fvalue+'&token=<?php echo $token; ?>',
					dataType: 'json',
					success: function(json) {
						response($.map(json, function(item) {
							return {
								value: item.name
							}
						}));
					}
				});

			},
			select: function(event, ui) {
			//alert(ui.item.value);

			$('input[name=\''+ fname +'\']').val(ui.item.value);
			return false;
			}
		});

	});
}
</script>




<script type="text/javascript">
$('input[name=\'record\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/record/autocomplete&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.record_id
					}
				}));
			}
		});
	},
	select: function(event, ui) {
		$('input[name=\'record\']').val(ui.item.label);
		$('input[name=\'record_id\']').val(ui.item.value);

		return false;
	}
});

    $('#mytabs a').tabs();
	$('.blog_save').bind('click', form_submit);


 </script>


</div>

<?php echo $footer; ?>