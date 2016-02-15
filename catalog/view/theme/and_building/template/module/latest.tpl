<div class="box" style="position:relative;">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content" style="padding-right:0;">
    <div class="box-product"><div class="jcarousel" style="width:100%; position:static; margin-right:0;">
                    <ul>
      <?php foreach ($products as $product) { ?> <li>
      <div>
        <?php if ($product['thumb']) { ?>
        <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /><?php if ($product['special']) { ?> <div class="spec"></div> <?php } ?></a></div>
        <?php } ?>
        <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
        <?php if ($product['price']) { ?>
        <div class="price">
          <?php if (!$product['special']) { ?>
          <?php echo $product['price']; ?>
          <?php } else { ?>
          <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
          <?php } ?>
        </div>
        <?php } ?>
        <?php if ($product['rating']) { ?>
        <div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>
        <?php } ?>
        <div class="cart"><input type="button" value="<?php echo $button_cart; ?>" onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button" /></div>
      </div> </li>
      <?php } ?></ul>
               

                <a style="position:absolute;right:25px; top:15px; left:auto; " href="#" class="jcarousel-control-prev">&lsaquo;</a>
                <a style="position:absolute;right:0px; top:15px;" href="#" class="jcarousel-control-next">&rsaquo;</a>

                
             </div>
    </div>
  </div>
</div>
