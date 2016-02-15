<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/and_building/stylesheet/stylesheet.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/and_building/stylesheet/building.css" />
<link rel="stylesheet" href="catalog/view/theme/and_building/stylesheet/chosen.css">
<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-1.8.16.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css" />
<script type="text/javascript" src="catalog/view/javascript/common.js"></script>
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
<!--[if IE 7]> 
<link rel="stylesheet" type="text/css" href="catalog/view/theme/and_building/stylesheet/ie7.css" />
<![endif]-->
<!--[if lt IE 7]>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/and_building/stylesheet/ie6.css" />
<script type="text/javascript" src="catalog/view/javascript/DD_belatedPNG_0.0.8a-min.js"></script>
<script type="text/javascript">
DD_belatedPNG.fix('#logo img');
</script>
<![endif]-->
<?php if ($stores) { ?>
<script type="text/javascript"><!--
$(document).ready(function() {
<?php foreach ($stores as $store) { ?>
$('body').prepend('<iframe src="<?php echo $store; ?>" style="display: none;"></iframe>');
<?php } ?>
});
//--></script>
<?php } ?>
<?php echo $google_analytics; ?>
<script>
$(document).ready(function(){
	
	$('#menu ul li').hover(
		function() {
			$(this).addClass("active");
			$(this).find('div').stop(false, true).slideDown();
		},
		function() {
			$(this).removeClass("active");        
			$(this).find('div').stop(false, true).slideUp('fast');
		}
	);
	});
</script>
<div id="fb-root"></div>
<script type="text/javascript">(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/ru_RU/all.js#xfbml=1&appId=598635853499914";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<script type="text/javascript" src="catalog/view/javascript/jquery/tabs.js"></script>
		<link rel="stylesheet" type="text/css" href="catalog/view/theme/and_building/stylesheet/jcarousel.responsive.css">
		<script type="text/javascript" src="catalog/view/theme/and_building/js/jquery.jcarousel.min.js"></script>
		<script type="text/javascript" src="catalog/view/theme/and_building/js/jcarousel.responsive.js"></script>
<script type="text/javascript">
$(document).ready(function() {
		// Tooltip only Text
		$('.masterTooltip').hover(function(){
				// Hover over code
				var title = $(this).attr('title');
				$(this).data('tipText', title).removeAttr('title');
				$('<p class="tooltip"></p>')
				.text(title)
				.appendTo('body')
				.fadeIn('slow');
		}, function() {
				// Hover out code
				$(this).attr('title', $(this).data('tipText'));
				$('.tooltip').remove();
		}).mousemove(function(e) {
				var mousex = e.pageX + 5; //Get X coordinates
				var mousey = e.pageY + 5; //Get Y coordinates
				$('.tooltip')
				.css({ top: mousey, left: mousex })
		});
});
</script>
<script type="text/javascript">$(document).ready(function(){
$(window).scroll(function () {if ($(this).scrollTop() > 0) {$('#scroller').fadeIn();} else {$('#scroller').fadeOut();}});
$('#scroller').click(function () {$('body,html').animate({scrollTop: 0}, 400); return false;});
});</script>
<?php 
$store_andtemp = isset($GLOBALS["store_andtemp"]) ? $GLOBALS["store_andtemp"] : NULL;
?>
<?php $color = isset($store_andtemp['color_style']) && ($store_andtemp['color_style']!='') ? $store_andtemp['color_style'] : '' ;
	 ?>

<link rel="stylesheet" type="text/css" href="catalog/view/theme/and_building/stylesheet/orange.css" />

<?php if ($color == 'blue') { ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/and_building/stylesheet/blue.css" />
 <?php } ?>
<?php if ($color == 'red') { ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/and_building/stylesheet/red.css" />
 <?php } ?>
</head>
<body>

<div id="scroller" class="b-top" style="display: none;"><span class="b-top-but"></span></div>
<div id="mainwrap">
<?php if ($logged) { ?>
<div id="system-top">
  <div id="welcome">
	<?php if (!$logged) { ?>
	<?php echo $text_welcome; ?>
	<?php } else { ?>
	<?php echo $text_logged; ?>
	<?php } ?>
  </div>
  <div class="links"><a href="<?php echo $home; ?>"><?php echo $text_home; ?></a><a href="<?php echo $wishlist; ?>" id="wishlist-total"><?php echo $text_wishlist; ?></a><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a><a href="<?php echo $checkout; ?>"><?php echo $text_checkout; ?></a></div>

</div>
<?php } ?>
<div id="container">
<div id="header">
  <?php if ($logo) { ?>
  <div id="logo"><a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" /></a></div>
  <?php } ?>
  <?php if ($logged) { ?>
  <?php echo $language; ?>
  <?php echo $currency; ?>
  <?php echo $cart; ?>
  <div id="search">
	<div class="button-search"><?php echo $text_search; ?></div>
	<input type="text" name="search"  value="<?php echo $search; ?>" placeholder="Введите название товара"  />
	<input type="text" name="quantity"  value="<?php echo $search_quantity; ?>" placeholder="Желаемое количество" />
  </div>
  <?}?>

</div>

<div id="menu">
<?php if ($categories AND $logged) { ?>
  <ul>
	<?php foreach ($categories as $category) { ?>
	<li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
	  <?php if ($category['children']) { ?>
	  <div>
		<?php for ($i = 0; $i < count($category['children']);) { ?>
		<ul>
		  <?php $j = $i + ceil(count($category['children']) / $category['column']); ?>
		  <?php for (; $i < $j; $i++) { ?>
		  <?php if (isset($category['children'][$i])) { ?>
		  <li><a href="<?php echo $category['children'][$i]['href']; ?>"><?php echo $category['children'][$i]['name']; ?></a></li>
		  <?php } ?>
		  <?php } ?>
		</ul>
		<?php } ?>
	  </div>
	  <?php } ?>
	</li>
	<?php } ?>
	<?php foreach ($informations as $information) { ?>
	  <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
	  <?php } ?>
	  <li><a href="/news/">Новости</a></li>
	  <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
	  <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
  </ul>
  <?php } ?>
</div>

<?php if ($error) { ?>
	
	<div class="warning"><?php echo $error ?><img src="catalog/view/theme/and_building/image/close.png" alt="" class="close" /></div>
	
<?php } ?>
<div id="notification"></div>
