<div style="background: #f7f7f7; border: 1px solid #dddddd; padding: 10px; margin-bottom: 10px;text-align:right;">
<?php echo $text_instruction; ?><br /><br />
<a href="index.php?route=payment/payment_schet/printpay" class="button" style="text-decoration:none;" target="_blank"><span><?php echo $text_printpay; ?></span></a>
  <br /><br />
  <?php echo $text_payment; ?></div>

<div class="buttons">
  <div class="right"><a id="button-confirm" class="button"><span><?php echo $button_confirm; ?></span></a></div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
	$.ajax({
		type: 'GET',
		url: 'index.php?route=payment/payment_schet/confirm',
		success: function() {
			location = '<?php echo $continue; ?>';
		}
	});
});
//--></script>
