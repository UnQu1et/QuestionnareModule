<div id="html_<?php echo $prefix;?>">
<?php echo($html); ?>
</div>

<script>
$(document).ready(function(){
	<?php if (isset($settings_widget['anchor']) && $settings_widget['anchor']!='') { ?>
	var html_<?php echo $prefix; ?> = $('#html_<?php echo $prefix;?>').html();
	<?php echo $settings_widget['anchor']; ?>(html_<?php echo $prefix; ?>);
	$('#html_<?php echo $prefix; ?>').remove();
   <?php  } ?>
});
</script>

