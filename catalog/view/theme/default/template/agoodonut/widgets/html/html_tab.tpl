<div id="html_<?php echo $prefix;?>">
<div id="tab-html-<?php echo $prefix; ?>" class="tab-content">
<div class="box" style="display: block">
	<div class="box-content bordernone">

<?php echo($html); ?>

 </div>
</div>
</div>
</div>
<script>

<?php if (isset($settings_widget['anchor']) && $settings_widget['anchor']!='') { ?>
<?php
$parts_anchor  = explode(';',$settings_widget['anchor']);
?>

$(document).ready(function(){
	<?php echo $parts_anchor[0]; ?>('<a href="#tab-html-<?php echo $prefix; ?>"><?php echo $heading_title; ?></a>');

	var html<?php echo $prefix; ?> = $('#html_<?php echo $prefix;?>').html();
	<?php echo $parts_anchor[1]; ?>(html<?php echo $prefix; ?> );
	$('#html_<?php echo $prefix;?>').hide('slow').remove();

	$('#tabs a').tabs();

});
<?php  } ?>
</script>

