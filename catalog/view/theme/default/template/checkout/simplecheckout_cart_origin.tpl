<?php if ($attention) { ?>
	<div class="simplecheckout-warning-block"><?php echo $attention; ?></div>
<?php } ?>    
<?php if ($error_warning) { ?>
	<div class="simplecheckout-warning-block"><?php echo $error_warning; ?></div>
<?php } ?>
	<table class="simplecheckout-cart">
		<colgroup>
			<col class="image">
			<col class="name">
			<col class="model">
			<col class="quantity">
			<col class="price">
			<col class="total">
			<col class="remove">
		</colgroup>
		<thead>
			<tr>
				<th class="image"><?php echo $column_image; ?></th>
				<th class="name"><?php echo $column_name; ?></th>
				<th class="model"><?php echo $column_model; ?></th>
				<th class="quantity"><?php echo $column_quantity; ?></th>
				<th class="price"><?php echo $column_price; ?></th>
				<th class="total"><?php echo $column_total; ?></th>
				<th class="remove"></th>        
			</tr>
		</thead>
	<tbody>
	<?php foreach ($products as $product) { ?>
		<tr>
			<td class="image">
				<?php if ($product['thumb']) { ?>
					<a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
				<?php } ?>
			</td> 
			<td class="name">
				<?php if ($product['thumb']) { ?>
					<div class="image">
						<a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
					</div>
				<?php } ?>
				<a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
				<?php if (!$product['stock'] && ($config_stock_warning || !$config_stock_checkout)) { ?>
					<span class="product-warning">***</span>
				<?php } ?>
				<div class="options">
				<?php foreach ($product['option'] as $option) { ?>
				&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small><br />
				<?php } ?>
				</div>
				<?php if ($product['reward']) { ?>
				<small><?php echo $product['reward']; ?></small>
				<?php } ?>
			</td>
			<td class="model"><?php echo $product['model']; ?></td>
			<td class="quantity">
				<img src='<?php echo $simple->tpl_joomla_path() ?>catalog/view/image/minus.png' border='0' <?php if ($quantity > 1) { ?>onclick="jQuery(this).next().val(~~jQuery(this).next().val()-1);simplecheckout_reload('cart_value_decreased');"<?php } ?>>
				<input type="text" name="quantity[<?php echo $product['key']; ?>]" value="<?php echo $product['quantity']; ?>" size="1" onchange="simplecheckout_reload('cart_value_changed')" />
				<img src='<?php echo $simple->tpl_joomla_path() ?>catalog/view/image/plus.png' border='0' onclick="jQuery(this).prev().val(~~jQuery(this).prev().val()+1);simplecheckout_reload('cart_value_increased');">
			</td>
			<td class="price"><nobr><?php echo $product['price']; ?></nobr></td>
			<td class="total"><nobr><?php echo $product['total']; ?></nobr></td>
			<td class="remove">
			<img style="cursor:pointer;" src="<?php echo $simple->tpl_joomla_path() ?>catalog/view/image/close.png" onclick="jQuery('#simplecheckout_remove').val('<?php echo $product['key']; ?>');simplecheckout_reload('product_removed');" />
			</td>        
			</tr>
			<?php } ?>
			<?php foreach ($vouchers as $voucher_info) { ?>
				<tr>
					<td class="image"></td>  
					<td class="name"><?php echo $voucher_info['description']; ?></td>
					<td class="model"></td>
					<td class="quantity">1</td>
					<td class="price"><nobr><?php echo $voucher_info['amount']; ?></nobr></td>
					<td class="total"><nobr><?php echo $voucher_info['amount']; ?></nobr></td>
					<td class="remove">
					<img style="cursor:pointer;" src="<?php echo $simple->tpl_joomla_path() ?>catalog/view/image/close.png" onclick="jQuery('#simplecheckout_remove').val('<?php echo $voucher_info['key']; ?>');simplecheckout_reload('product_removed');" />
					</td>
				</tr>
			<?php } ?>
	</tbody>
</table>
	
<?php foreach ($totals as $total) { ?>
	<div class="simplecheckout-cart-total" id="total_<?php echo $total['code']; ?>">
		<span><b><?php echo $total['title']; ?>:</b></span>
		<span class="simplecheckout-cart-total-value"><nobr><?php echo $total['text']; ?></nobr></span>
		<span class="simplecheckout-cart-total-remove">
			<?php if ($total['code'] == 'coupon') { ?>
			<img src="<?php echo $simple->tpl_joomla_path() ?>catalog/view/image/close.png" onclick="jQuery('input[name=coupon]').val('');simplecheckout_reload('coupon_removed');" />
			<?php } ?>
			<?php if ($total['code'] == 'voucher') { ?>
			<img src="<?php echo $simple->tpl_joomla_path() ?>catalog/view/image/close.png" onclick="jQuery('input[name=voucher]').val('');simplecheckout_reload('voucher_removed');" />
			<?php } ?>
			<?php if ($total['code'] == 'reward') { ?>
			<img src="<?php echo $simple->tpl_joomla_path() ?>catalog/view/image/close.png" onclick="jQuery('input[name=reward]').val('');simplecheckout_reload('reward_removed');" />
			<?php } ?>
		</span>
	</div>
<?php } ?>
<?php if (isset($modules['coupon'])) { ?>
	<div class="simplecheckout-cart-total">
		<span class="inputs"><?php echo $entry_coupon; ?>&nbsp;<input type="text" name="coupon" value="<?php echo $coupon; ?>" onchange="simplecheckout_reload('coupon_changed')"  /></span>
	</div>
<?php } ?>
<?php if (isset($modules['reward']) && $points > 0) { ?>
	<div class="simplecheckout-cart-total">
		<span class="inputs"><?php echo $entry_reward; ?>&nbsp;<input type="text" name="reward" value="<?php echo $reward; ?>" onchange="simplecheckout_reload('reward_changed')"  /></span>
	</div>
<?php } ?>
<?php if (isset($modules['voucher'])) { ?>
	<div class="simplecheckout-cart-total">
		<span class="inputs"><?php echo $entry_voucher; ?>&nbsp;<input type="text" name="voucher" value="<?php echo $voucher; ?>" onchange="simplecheckout_reload('voucher_changed')"  /></span>
	</div>
<?php } ?>
<?php if (isset($modules['coupon']) || isset($modules['reward']) || isset($modules['voucher'])) { ?>
	<div class="simplecheckout-cart-total simplecheckout-cart-buttons">
		<span class="inputs buttons"><a id="simplecheckout_button_cart" onclick="simplecheckout_reload('cart_changed');" class="button btn"><span><?php echo $button_update; ?></span></a></span>
	</div>
<?php } ?>
	
<input type="hidden" name="remove" value="" id="simplecheckout_remove">
<div style="display:none;" id="simplecheckout_cart_total"><?php echo $cart_total ?></div>
<script type="text/javascript">
	jQuery(function(){
		jQuery('#cart_total').html('<?php echo $cart_total ?>');
		jQuery('#cart-total').html('<?php echo $cart_total ?>');
		jQuery('#cart_menu .s_grand_total').html('<?php echo $cart_total ?>');
		<?php if ($simple_show_weight) { ?>
		jQuery('#weight').text('<?php echo $weight ?>');
		<?php } ?>
		<?php if ($template == 'shoppica2') { ?>
		jQuery('#cart_menu div.s_cart_holder').html('');
		$.getJSON('index.php?<?php echo $simple->tpl_joomla_route() ?>route=tb/cartCallback', function(json) {
			if (json['html']) {
				jQuery('#cart_menu span.s_grand_total').html(json['total_sum']);
				jQuery('#cart_menu div.s_cart_holder').html(json['html']);
			}
		});
		<?php } ?>
		<?php if ($template == 'shoppica') { ?>
			jQuery('#cart_menu div.s_cart_holder').html('');
			jQuery.getJSON('index.php?<?php echo $simple->tpl_joomla_route() ?>route=module/shoppica/cartCallback', function(json) {
				if (json['output']) {
					jQuery('#cart_menu span.s_grand_total').html(json['total_sum']);
					jQuery('#cart_menu div.s_cart_holder').html(json['output']);
				}
			});
		<?php } ?>
	});
</script>
<?php if ($simple->get_simple_steps() && $simple->get_simple_steps_summary()) { ?>
<div id="simple_summary" style="display: none;margin-bottom:15px;">
	<table class="simplecheckout-cart">
		<colgroup>
			<col class="image">
			<col class="name">
			<col class="model">
			<col class="quantity">
			<col class="price">
			<col class="total">
		</colgroup>
		<thead>
			<tr>
				<th class="image"><?php echo $column_image; ?></th>
				<th class="name"><?php echo $column_name; ?></th>
				<th class="model"><?php echo $column_model; ?></th>
				<th class="quantity"><?php echo $column_quantity; ?></th>
				<th class="price"><?php echo $column_price; ?></th>
				<th class="total"><?php echo $column_total; ?></th>
			</tr>
		</thead>
	<tbody>
	<?php foreach ($products as $product) { ?>
		<tr>
			<td class="image">
				<?php if ($product['thumb']) { ?>
					<a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
				<?php } ?>
			</td> 
			<td class="name">
				<a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
				<?php if (!$product['stock'] && ($config_stock_warning || !$config_stock_checkout)) { ?>
					<span class="product-warning">***</span>
				<?php } ?>
				<div class="options">
				<?php foreach ($product['option'] as $option) { ?>
				&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small><br />
				<?php } ?>
				</div>
				<?php if ($product['reward']) { ?>
				<small><?php echo $product['reward']; ?></small>
				<?php } ?>
			</td>
			<td class="model"><?php echo $product['model']; ?></td>
			<td class="quantity"><?php echo $product['quantity']; ?></td>
			<td class="price"><nobr><?php echo $product['price']; ?></nobr></td>
			<td class="total"><nobr><?php echo $product['total']; ?></nobr></td>
			</tr>
			<?php } ?>
			<?php foreach ($vouchers as $voucher_info) { ?>
				<tr>
					<td class="image"></td>  
					<td class="name"><?php echo $voucher_info['description']; ?></td>
					<td class="model"></td>
					<td class="quantity">1</td>
					<td class="price"><nobr><?php echo $voucher_info['amount']; ?></nobr></td>
					<td class="total"><nobr><?php echo $voucher_info['amount']; ?></nobr></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
		
	<?php foreach ($totals as $total) { ?>
		<div class="simplecheckout-cart-total" id="total_<?php echo $total['code']; ?>">
			<span><b><?php echo $total['title']; ?>:</b></span>
			<span class="simplecheckout-cart-total-value"><nobr><?php echo $total['text']; ?></nobr></span>
		</div>
	<?php } ?>
</div>
<?php } ?>