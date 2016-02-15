<!-- // for the lazy. no need to thanks. no offense, i wanted to help them :) -->
<?php if ($captcha_status) { ?>
<div class="captcha_title"><?php echo $entry_captcha_title; ?>&nbsp;&darr;</div>
<div class="entry_captcha"><?php echo $entry_captcha; ?></div>

<div class="height30">
<img src="index.php?route=record/record/captcham5&v=<?php echo rand(0,1000); ?>" alt="captcha" id="imgcaptcha" class="captcha_img">
<input type="text" name="captcha" value="" id="captcha_fun" class="captcha captchainput captcha_img" maxlength="5" size="5">
</div>

<div>
	<div class="floatleft align_center">
	 <a href="" class="captcha_update"><?php echo $entry_captcha_update; ?></a>
	</div>

	<div class="captcha_left">
	<?php
 	for ($i=0; $i<strlen($captcha_keys); $i++) { ?><input type="button" class="bkey width24" value='<?php echo $captcha_keys[$i];?>'><?php }  ?>
 	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){

	$('.bkey').bind('click', subcaptcha);

	$('.captcha_update').click(function() {
	 captcha_fun();
	 return false;
	});

});
</script>


<?php } ?>