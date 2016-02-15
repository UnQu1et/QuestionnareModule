<?php foreach ($products as $product) { ?>
	<div>
	  
	  <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?><?php if ($product['special']) { ?> <div class="spec"></div> <?php } ?></a></div>
	  <?php if ($product['price']) { ?>
	  <div class="price">
		<?php if (!$product['special']) { ?>
		<?php echo $product['price']; ?>
		<?php } else { ?>
		<span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
		<?php } ?>
		<?php if ($product['tax']) { ?>
		<br />
		<span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
		<?php } ?>
	  </div>
	  <?php } ?>
	  <?php if ($product['rating']) { ?>
	  <div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>
	  <?php } ?>
	  <div class="cart">
		<input type="button" value="<?php echo $button_cart; ?>" onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button" />
	  </div>
	
	  <div class="wishlist"><a onclick="addToWishList('<?php echo $product['product_id']; ?>');"><?php echo $button_wishlist; ?></a></div>
	  
	
	</div>
	<?php } ?>