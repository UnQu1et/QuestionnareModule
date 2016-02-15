<div id="blog_sm_<?php echo md5(serialize($myblogs)); ?>" style="display: none;">
<?php
if (count($myblogs) > 0) {
	foreach ($myblogs as $blogs) {
		for ($i = 0; $i < $blogs['flag_start']; $i++) {
?>
<li><a href="<?php echo $blogs['href'];?>"><?php echo $blogs['name'];?></a>
<?php
			if ($i >= $blogs['flag_end']) {
?>
<ul>
<?php
			}
?>
<?php
			for ($m = 0; $m < $blogs['flag_end']; $m++) {
?>

		<?php
				if ($blogs['flag_start'] <= $m) {
?>
</ul>
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
var blog_sitemap_<?php echo md5(serialize($myblogs)); ?> = $('#blog_sm_<?php echo md5(serialize($myblogs)); ?>').html();
$('.sitemap-info .right ul:first').append(blog_sitemap_<?php echo md5(serialize($myblogs)); ?>);

});
</script>