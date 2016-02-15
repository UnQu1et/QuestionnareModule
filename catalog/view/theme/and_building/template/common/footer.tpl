<?php 
$store_andtemp = isset($GLOBALS["store_andtemp"]) ? $GLOBALS["store_andtemp"] : NULL;
?>

<?php if($store_andtemp['info_footer'] == 1) {?>
<div style="clear:both;"></div>
<script>
	function setEqualHeight(columns)
	{
	var tallestcolumn = 0;
	columns.each(
	function()
	{
	currentHeight = $(this).height();
	if(currentHeight > tallestcolumn)
	{
	tallestcolumn = currentHeight;
	}
	}
	);
	columns.height(tallestcolumn);
	}
	$(document).ready(function() {
	setEqualHeight($(".mod .modwrap"));
	});
</script>

<div class="mod about">
<h3><span><?php if(isset($store_andtemp['about_name']) && $store_andtemp['about_name']!=''){?>
<?php echo $store_andtemp['about_name'];?> 
<?php } ?>
</span></h3>
<div class="modwrap">
<?php if(isset($store_andtemp['about_text']) && $store_andtemp['about_text']!=''){?>
<?php 
	$about_text = htmlspecialchars_decode($store_andtemp['about_text']);
	echo $about_text;?> 
<?php } ?>
</div>
</div>

<div class="mod contacts">
<h3><span><?php if(isset($store_andtemp['contacts_name']) && $store_andtemp['contacts_name']!=''){?>
<?php echo $store_andtemp['contacts_name'];?> 
<?php } ?>
</span></h3>
<div class="modwrap" style="">
<?php if(isset($store_andtemp['phone']) && $store_andtemp['phone']!=''){?>
<div class="contwidget ph">
<?php echo $store_andtemp['phone'];?> 
</div>
<?php } ?>

<?php if(isset($store_andtemp['phone2']) && $store_andtemp['phone2']!=''){?>
<div class="contwidget ph2">
<?php echo $store_andtemp['phone2'];?> 
</div>
<?php } ?>


<?php if(isset($store_andtemp['email']) && $store_andtemp['email']!=''){?>
<div class="contwidget mail">
<?php echo $store_andtemp['email'];?> 
</div>
<?php } ?>

<?php if(isset($store_andtemp['skype']) && $store_andtemp['skype']!=''){?>
<div class="contwidget sk">
<?php echo $store_andtemp['skype'];?> 
</div>
<?php } ?>

<?php if(isset($store_andtemp['adress']) && $store_andtemp['adress']!=''){?>
<div class="contwidget ad">
<?php echo $store_andtemp['adress'];?> 
</div>
<?php } ?>

<?php if($store_andtemp['feedback'] == 1) {?>
<table align="center">
<tr>
<td>
<div class="feedback"><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></div>
</td>
</tr>
</table>
<?php } ?>

</div>
</div>

<div class="mod anyhtml">
<h3><span><?php if(isset($store_andtemp['anyhtml_name']) && $store_andtemp['anyhtml_name']!=''){?>
<?php echo $store_andtemp['anyhtml_name'];?> 
<?php } ?>
</span></h3>
<div class="modwrap" >
<?php if(isset($store_andtemp['anyhtml_text']) && $store_andtemp['anyhtml_text']!=''){?>
<?php 
	$anyhtml_text = htmlspecialchars_decode($store_andtemp['anyhtml_text']);
	echo $anyhtml_text;?> 
<?php } ?>
</div>
</div>

<?php } ?>

<div style="clear:both;"></div>
</div> </div>

<div id="footer"> <div class="footercont">

  <?php if ($informations) { ?>
  <div class="column">
    <h3><?php echo $text_information; ?></h3>
    <ul>
      <?php foreach ($informations as $information) { ?>
      <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
      <?php } ?>
    </ul>
  </div>
  <?php } ?>
  <div class="column">
    <h3><?php echo $text_service; ?></h3>
    <ul>
      <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
      <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
      <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
    </ul>
  </div>
  <div class="column">
    <h3><?php echo $text_extra; ?></h3>
    <ul>
      <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
      <li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
      <li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
      <li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>
    </ul>
  </div>
  <div class="column">
    <h3><?php echo $text_account; ?></h3>
    <ul>
      <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
      <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
      <li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
      <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
    </ul>
  </div>
<div class="powsoc">
<script type="text/javascript">(function() {
  if (window.pluso)if (typeof window.pluso.start == "function") return;
  if (window.ifpluso==undefined) { window.ifpluso = 1;
    var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
    s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
    s.src = ('https:' == window.location.protocol ? 'https' : 'http')  + '://share.pluso.ru/pluso-like.js';
    var h=d[g]('body')[0];
    h.appendChild(s);
  }})();</script>
<div class="pluso" data-background="transparent" data-options="small,round,line,horizontal,nocounter,theme=04" data-services="vkontakte,odnoklassniki,facebook,twitter,google"></div>
</div>
<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//-->
<div id="powered"><?php echo $powered; ?></div>
<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//-->
</div></div>

</div>
  <script src="catalog/view/theme/and_building/js/chosen.jquery.js" type="text/javascript"></script>

  <script type="text/javascript">
    var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
  </script>

<script>
 $(".box .box-heading").wrapInner("<span class='mod-head'></span>");
</script>
<script type="text/javascript">
$('.wishlist a').each(function() {
    $(this).attr( 'title', $(this).text() ).empty();
});
$('.compare a').each(function() {
    $(this).attr( 'title', $(this).text() ).empty();
});
</script>

<script>
    $(".wishlist a").addClass("masterTooltip");
    $(".compare a").addClass("masterTooltip");
</script>
</body></html>