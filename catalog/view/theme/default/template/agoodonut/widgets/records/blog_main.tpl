<?php if ($records) { ?>
<div class="box" id="cmswidget-<?php echo $cmswidget; ?>">
  <div class="box-heading"><?php echo $heading_title; ?></div>
	<div class="box-content">


<div class="fich">
    <div class="gallery_fich">
     <?php foreach ($records as $record) { ?>
         <div class="section margintop10">

                <a class="width_img" href="<?php echo $record['href']; ?>" title="">
                <img src="<?php echo $record['thumb']; ?>" alt="" title=""/>

                <div class="hid hid_small"><ins class="margintop10"><?php echo $record['name']; ?></ins></div>
                </a>

                    <span class="comments-count small_g">
                        <span class="com-text"><?php echo $text_comments; ?></span>
                            <span class="bubble">
                            <a href="<?php echo $record['href']; ?>#tab-comment"><?php echo $record['comments']; ?></a>
                            </span>
                            <span class="mbubble">
                            </span>
            		</span>

        </div>

<?php } ?>

                    </div>
</div>
</div>
</div>
<div class="overflowhidden width100 lineheight1 bordernone">&nbsp;</div>
<?php if (isset($settings_widget['anchor']) && $settings_widget['anchor']!='') { ?>
<script>
$(document).ready(function(){

	var data = $('#cmswidget-<?php echo $cmswidget; ?>').html();
	<?php echo $settings_widget['anchor']; ?>;
	 delete data;
	$('#cmswidget-<?php echo $cmswidget; ?>').hide('slow').remove();

});
</script>
 <?php  } ?>
<?php } ?>

