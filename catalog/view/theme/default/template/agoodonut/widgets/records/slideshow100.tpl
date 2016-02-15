<?php
$this->document->addStyle('catalog/view/javascript/blog/flexslider/css/flexslider.css');
$this->document->addScript('catalog/view/javascript/blog/flexslider/js/flexslider.js');
$this->document->addScript('catalog/view/javascript/blog/flexslider/js/jquery.flexslider.js');

	foreach ($records as $record) {

		if (isset ($record['settings']['images']['width']) && $record['settings']['images']['width'] ) {	      $width = $record['settings']['images']['width'];
		}
		if (isset ($record['settings']['images']['height']) && $record['settings']['images']['height'] ) {
	      $height = $record['settings']['images']['height'];
		}

	}
	reset($records);
?>
<!-- flexslider -->
<div class="slideshow" style="height: <?php echo $height; ?>px; width:100%; margin-top: -15px;position:absolute;  right: 0;  ">
	<div style="margin-left: 0px; z-index: 1000; height: <?php echo $height; ?>px; width: 100%; ">
		<div id="scroll-wrap"></div>
			<div class="scrollblock">
				<div id="vignette" class="slide">
					<div style="overflow: hidden;" class="flexslider clearfix">
						<ul style=" " class="slides clearfix">
							<?php foreach ($records as $record) {
								foreach ($record['images'] as $banner) { ?>
        						<li class="clone" style="  float: left;">
									<div class="viewport">
										<div style="opacity:0; margin-left: 0px;  z-index: 1; top:40px;" class="vignette-details">
										<?php if ($banner['url']) { ?>
											<a href="<?php echo $banner['url']; ?>" style="text-decoration: none;">
										<?php } ?>
											<h2 style="margin-bottom: 20px; text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.5); text-decoration: none; z-index: 1; color:#fff; font-size:30px; line-height:30px;">
											<div class="line1" style="z-index: 1;">
												<?php echo $banner['title']; ?>
											</div>
											</h2>
											<div style="color: #FFF; text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.5); font-size: 19px;">
											  <?php echo $banner['description']; ?>
											</div>

											<?php if ($banner['url']) { ?>
											</a>
											<?php } ?>
						                    <!--
						                      <div class="button red">
						                        <a href="#" onclick="return false;" title="">Далее</a>
						                      </div>
						                        -->
											<div style="display: none;" class="article-reveal"><div class="close-article">
												<a href="#">Close</a>
											</div>
											<p style="text-decoration: none;">
												<?php echo $banner['title']; ?>
											</p>
										</div>
									</div>
								</div>
								<?php if ($banner['url']) { ?>
								<a href="<?php echo $banner['url']; ?>">
								<?php } ?>
								<div style="text-align: center;
									 background:url(<?php echo $banner['thumb']; ?>) center  center no-repeat scroll;
								 	-webkit-background-size: cover;
									-moz-background-size: cover;
									-o-background-size: cover;
									background-size: cover;">
									<img src="catalog/view/javascript/blog/flexslider/image/empty.png" style="height: <?php echo $height; ?>px;"  alt="empty">
								</div>
								<?php if ($banner['url']) { ?>
								</a>
								<?php } ?>
							</li>
							<?php
							}
						}
						?>
					</div> <!-- /flexslider -->
				</div> <!-- /vignettes -->
			</div> <!-- /scrollblock -->
		</div>
	</div>

<div style=" overflow: hidden;  margin-bottom: 5px; height: <?php echo $height; ?>px; ">&nbsp;</div>
<script>
$(document).ready(function(){
$('#column-left').css('padding-top', '<?php echo $height; ?>px');
$('#column-right').css('padding-top', '<?php echo $height; ?>px');
});
</script>