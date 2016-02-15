<div id="blog-mts_<?php echo md5(serialize($myblogs)); ?>" style="display: none;">
<?php
if (count($myblogs) > 0) {
	foreach ($myblogs as $blogs) {
		for ($i = 0; $i < $blogs['flag_start']; $i++) {
?>
<li><a href="<?php if ($blogs['active'] == 'active') echo $blogs['href'] . "#"; else echo $blogs['href'];?>" class=" <?php if ($blogs['active'] == 'active') echo 'active'; if ($blogs['active'] == 'pass')	echo 'pass'; ?>"><?php echo $blogs['name'];
?></a>
<?php
			if ($i >= $blogs['flag_end']) {
?>
<div><ul>
<?php
			}
?>
<?php
			for ($m = 0; $m < $blogs['flag_end']; $m++) {
?>

		<?php
				if ($blogs['flag_start'] <= $m) {
?>
</ul></div>
<?php
				}
						?>
			</li>
		<?php



			}

		}
	}
}
?>
</div>
<script>
$(document).ready(function(){

	<?php if (isset($settings_widget['anchor']) && $settings_widget['anchor']!='') { ?>
	var blog_menu_<?php echo md5(serialize($myblogs)); ?> = $('#blog-mts_<?php echo md5(serialize($myblogs)); ?>').html();
	<?php echo $settings_widget['anchor']; ?>(blog_menu_<?php echo md5(serialize($myblogs)); ?>);
	//$('#blog-mts_<?php echo md5(serialize($myblogs)); ?>').hide('slow').remove();
   <?php  } ?>
});
</script>