<div id="social" style="width:185px;">
<?php

$vk = $code;
$facebook = $code2;

 ?>

<?php $color = 'FB5A29'; ?>
<?php if ($code3 == 'red') { $color = 'BB0800'; } ?>
<?php if ($code3 == 'blue') { $color = '3178B7'; } ?>
<?php if ($code3 == 'original') { $color = '5B7FA6'; } ?>

 <div class='box'> <div class='box-heading social' style="background:none!important;">
   <div id='tabs-s' class='htabs-s'>
	<?php if($code){ ?>  <a href='#vk'>Вконтакте</a>  <?php } ?> 
    <?php if($code2){ ?>  <a href='#facebook'>Facebook</a> <?php } ?> 
    </div>
  </div> 
	<?php if($code){ ?>
  <div id='vk' style='min-height:290px;float:left;'>
	
  <script type='text/javascript' src='//vk.com/js/api/openapi.js?105'></script>
	<!-- VK Widget -->
	<div id='vk_groups'></div>
	<script type='text/javascript'>
	VK.Widgets.Group('vk_groups', {mode: 0, width: '180', height: '290', color1: 'FFFFFF', color2: '<? echo $color; ?>', color3: '<? echo $color; ?>'}, <? echo $vk ?>);
	</script>
  
  </div>
	<?php } ?> 
	<?php if($code2){ ?>
  <div id='facebook' style='min-height:330px;float:left;'>
  <div style='background:#fff;' class='fb-like-box' data-href='<? echo $facebook ?>' data-width='180' data-height='272' data-show-faces='true' data-stream='false' data-header='false'></div> 
	</div>
	<?php } ?> 

<script type='text/javascript'><!--
$('#tabs-s a').tabs();
//--></script> 

<div style="clear:both;"></div>
</div> </div>


