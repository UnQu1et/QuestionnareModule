<div id="blog-mr_<?php echo md5(serialize($records)); ?>" style="display: none;">
<?php
if (count($records) > 0) {
	foreach ($records as $record) {
?>
<li><a href="<?php echo $record['href'];?>" class="blog-menu-title"><?php echo $record['name'];?></a></li>
<?php
	}
}
?>
</div>
<script>
$(document).ready(function(){

	<?php if (isset($settings_widget['anchor']) && $settings_widget['anchor']!='') { ?>
	var data = $('#blog-mr_<?php echo md5(serialize($records)); ?>').html();
	<?php echo $settings_widget['anchor']; ?>;
	 delete data;
	$('#blog-mr_<?php echo md5(serialize($records)); ?>').hide('slow').remove();
   <?php  } ?>


});
</script>