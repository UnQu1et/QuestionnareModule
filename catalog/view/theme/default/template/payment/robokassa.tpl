<?php /* robokassa metka */ ?>
<form action='<?php echo $action; ?>' method=GET id="payment2">
      <input type=hidden name=MrchLogin value="<?php echo $mrh_login; ?>">
      <input type=hidden name=OutSum value="<?php echo $out_summ; ?>">
      <input type=hidden name=InvId value="<?php echo $inv_id; ?>">
      <input type=hidden name=Desc value="<?php echo $inv_desc; ?>">
      <input type=hidden name=SignatureValue value="<?php echo $crc; ?>">
      <input type=hidden name=Shp_item value="<?php echo $shp_item; ?>">
      <input type=hidden name=IncCurrLabel value="<?php echo $in_curr; ?>">
      <input type=hidden name=Culture value="<?php echo $culture; ?>">
      <div class="buttons">
		<?php if( $robokassa_confirm_status=='before' ) { ?>
		<div class="right"><a onclick="makePreorder();" class="button"><span><?php echo $button_confirm; ?></span></a></div>
		<?php } else { ?>
		<div class="right"><a onclick="$('#payment2').submit();" class="button"><span><?php echo $button_confirm; ?></span></a></div>
		<?php } ?>
	  </div>
</form>
<?php if( $robokassa_confirm_status=='before' ) { ?>
<script>
	function makePreorder()
	{
		$.ajax({
					url: 'index.php?route=payment/robokassa/preorder',
					dataType: 'html',	
					data: { 
						MrchLogin: "<?php echo $mrh_login; ?>", 
						OutSum: "<?php echo $out_summ; ?>", 
						InvId: "<?php echo $inv_id; ?>", 
						Desc: "<?php echo $inv_desc; ?>", 
						SignatureValue: "<?php echo $crc; ?>", 
						Shp_item: "<?php echo $shp_item; ?>", 
						IncCurrLabel: "<?php echo $in_curr; ?>", 
						Culture: "<?php echo $culture; ?>"					
					},
					success: function(html) {
						$('#payment2').submit();
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
		});	
	}
	
</script>
<?php } ?>