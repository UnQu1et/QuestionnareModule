<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
  <div class="breadcrumb">
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
	<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
	<?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <?php if ($thumb || $description) { ?>
  <div class="category-info">
	<?php if ($thumb) { ?>
	<div class="image"><img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" /></div>
	<?php } ?>
	<?php if ($description) { ?>
	<?php echo $description; ?>
	<?php } ?>
  </div>
  <?php } ?>
  <?php if ($categories) { ?>
  <h2><?php echo $text_refine; ?></h2>
  <div class="category-list">
	<?php if (count($categories) <= 5) { ?>
	<ul>
	  <?php foreach ($categories as $category) { ?>
	  <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
	  <?php } ?>
	</ul>
	<?php } else { ?>
	<?php for ($i = 0; $i < count($categories);) { ?>
	<ul>
	  <?php $j = $i + ceil(count($categories) / 4); ?>
	  <?php for (; $i < $j; $i++) { ?>
	  <?php if (isset($categories[$i])) { ?>
	  <li><a href="<?php echo $categories[$i]['href']; ?>"><?php echo $categories[$i]['name']; ?></a></li>
	  <?php } ?>
	  <?php } ?>
	</ul>
	<?php } ?>
	<?php } ?>
  </div>
  <?php } ?>
  
  <div class="product-filter">
  
	
	<div class="limit"><p><?php echo $text_limit; ?></p>
	  <select>
		<?php foreach ($limits as $limits) { ?>
		<?php if ($limits['value'] == $limit) { ?>
		<option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
		<?php } else { ?>
		<option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
		<?php } ?>
		<?php } ?>
	  </select>
	</div>
	<div class="sort"><p><?php echo $text_sort; ?></p>
	  <select>
		<?php foreach ($sorts as $sorts) { ?>
		<?php if ($sorts['value'] == $sort . '-' . $order) { ?>
		<option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
		<?php } else { ?>
		<option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
		<?php } ?>
		<?php } ?>
	  </select>
	</div>
	<?php echo $content_top; ?>
  </div>
  <?php if ($products) { ?>
  <div class="pagination"><?php echo $pagination; ?></div>
  <div class="product-list">
  <table class="product-list-table">
	<thead>
		<tr>
		<td>Модель</td>
		<td>Название товара</td>
		 <? if($option_products_names){?>
			<?foreach($option_products_names as $val){?>
				<td><?php echo $val; ?></td>
			<?}?>
		  <?}?>
		 <? if($attribute_products_names){?>
			<?foreach($attribute_products_names as $val){?>
				<td><?php echo $val; ?></td>
			<?}?>
		  <?}?>
		<td>Остаток</td>
		<td>Количество</td>
		<td>Действие</td>
		</tr>
	</thead>
	<?php foreach ($products as $product) { ?>
	<tr>
	  <td class="quantity"><?php echo $product['model']; ?></td>
	  <td class="name"><a><?php echo $product['name']; ?><?php if ($product['special']) { ?> <div class="spec"></div> <?php } ?></a></td>
	  
		<? if(!empty($option_products_names)){?>
			<?foreach($option_products_names as $option_id => $option_name){?>
				<?if($option_products[$product['product_id']][$option_id]){?>
					<?foreach($option_products[$product['product_id']] as $val){?>
						<td>
						<?php $metka = false; foreach($val as $opt_val){?>
							<?php echo $opt_val['option_val_name']; if($metka){ echo ', ';} $metka = true;?>
						<?}?>
						</td>
					<?}?>
				<?}else{?>
					<td>&mdash;</td>
				<?}?>
			<?}?>
		<?}?>
		<? if(!empty($attribute_products_names)){?>
			<?foreach($attribute_products_names as $attr_id => $attr_val){?>			
				<?if($attribute_products[$product['product_id']][$attr_id]){?>			
					<td><?php echo $attribute_products[$product['product_id']][$attr_id]['value'];?></td>
				<?}else{?>
					<td>&mdash;</td>
				<?}?>
			<?}?>
		<?}?>
		<td class="quantity"><?php echo $product['quantity']; ?></td>
	  <td class="cart">
		<?
		preg_match("#min_quantity:([0-9\.\,]+)#",$_GET['filter'],$get_quantity);
		
			if($get_quantity['1']){
				$product['quantity'] = $get_quantity['1'];
			}
		?>
		<input type="text" class="quantity_input" id="quantity-<?php echo $product['product_id']; ?>" value="<?php echo $product['quantity']; ?>">
	  </td>
	  <td class="cart">
		<input type="button" value="<?php echo $button_cart; ?>" onclick="var qty = $('#quantity-<?php echo $product['product_id']; ?>').val(); addToCart('<?php echo $product['product_id']; ?>',qty);" class="button" />
		 <div class="wishlist"><a onclick="addToWishList('<?php echo $product['product_id']; ?>');"><?php echo $button_wishlist; ?></a></div>
	  </td>
	
	 
	  
	
	</tr>
	<?php } ?>
  </table>
  </div>
  <div class="pagination"><?php echo $pagination; ?></div>
  <?php } ?>
  <?php if (!$categories && !$products) { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <div class="buttons">
	<div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
  </div>
  <?php } ?>
  <?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
/*function display(view) {
	if (view == 'list') {
		$('.product-grid').attr('class', 'product-list');
		
		$('.product-list > div').each(function(index, element) {
			html  = '<div class="right">';
			html += '  <div class="cart">' + $(element).find('.cart').html() + '</div>';
			html += '  <div class="wc-options"> <div class="wishlist">' + $(element).find('.wishlist').html() + '</div>';
			
			html += '</div>';			
			
			html += '<div class="left">';
			
			var image = $(element).find('.image').html();
			
			if (image != null) { 
				//html += '<div class="image">' + image + '</div>';
			}
			
			var price = $(element).find('.price').html();
			
			if (price != null) {
				html += '<div class="price">' + price  + '</div>';
			}
					
			html += '  <div class="name">' + $(element).find('.name').html() + '</div>';
			html += '  <div class="description">' + $(element).find('.description').html() + '</div>';
			
			var rating = $(element).find('.rating').html();
			
			if (rating != null) {
				html += '<div class="rating">' + rating + '</div>';
			}
				
			html += '</div>';
						
			$(element).html(html);
$(document).ready(function() {
		// Tooltip only Text
		$('.masterTooltip').hover(function(){
				// Hover over code
				var title = $(this).attr('title');
				$(this).data('tipText', title).removeAttr('title');
				$('<p class="tooltip"></p>')
				.text(title)
				.appendTo('body')
				.fadeIn('slow');
		}, function() {
				// Hover out code
				$(this).attr('title', $(this).data('tipText'));
				$('.tooltip').remove();
		}).mousemove(function(e) {
				var mousex = e.pageX + 5; //Get X coordinates
				var mousey = e.pageY + 5; //Get Y coordinates
				$('.tooltip')
				.css({ top: mousey, left: mousex })
		});
});
		});		
		
		$('.display').html('<b><?php echo $text_display; ?></b> <?php echo $text_list; ?> <b>/</b> <a onclick="display(\'grid\');"><?php echo $text_grid; ?></a>');
		
		$.totalStorage('display', 'list'); 
	} else {
		$('.product-list').attr('class', 'product-grid');
		
		$('.product-grid > div').each(function(index, element) {
			html = '';
			
			var image = $(element).find('.image').html();
			
			if (image != null) {
				//html += '<div class="image">' + image + '</div>';
			}
			
			html += '<div class="name">' + $(element).find('.name').html() + '</div>';
			html += '<div class="description">' + $(element).find('.description').html() + '</div>';
			
			var price = $(element).find('.price').html();
			
			if (price != null) {
				html += '<div class="price">' + price  + '</div>';
			}
			
			var rating = $(element).find('.rating').html();
			
			if (rating != null) {
				html += '<div class="rating">' + rating + '</div>';
			}
						
			html += '<div class="cart">' + $(element).find('.cart').html() + '</div>';
			html += '<div class="wishlist">' + $(element).find('.wishlist').html() + '</div>';
			
			
			$(element).html(html);
			$(document).ready(function() {
		// Tooltip only Text
		$('.masterTooltip').hover(function(){
				// Hover over code
				var title = $(this).attr('title');
				$(this).data('tipText', title).removeAttr('title');
				$('<p class="tooltip"></p>')
				.text(title)
				.appendTo('body')
				.fadeIn('slow');
		}, function() {
				// Hover out code
				$(this).attr('title', $(this).data('tipText'));
				$('.tooltip').remove();
		}).mousemove(function(e) {
				var mousex = e.pageX + 5; //Get X coordinates
				var mousey = e.pageY + 5; //Get Y coordinates
				$('.tooltip')
				.css({ top: mousey, left: mousex })
		});
});
		});	
					
		$('.display').html('<b><?php echo $text_display; ?></b> <a onclick="display(\'list\');"><?php echo $text_list; ?></a> <b>/</b> <?php echo $text_grid; ?>');
		
		$.totalStorage('display', 'grid');
	}
}

view = $.totalStorage('display');

if (view) {
	display(view);
} else {
	display('list');
}*/
//--></script> 
<?php echo $footer; ?>