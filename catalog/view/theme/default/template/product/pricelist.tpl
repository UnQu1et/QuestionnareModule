<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <style type="text/css">
    /* page limit */
    .ctrl {
        float:right;
        padding: 5px 20px;
    }
    .print {
        float:right;
        width: 50%;
    }
    .list {
        border-collapse: collapse;
        border: 1px solid #eee;
		
    }
    .list th {
        padding: 4px;
        text-align: center;
        background-color: #efefef;
    }
    .list tbody tr:nth-child(even) {
        background-color: #e4eef7;
		
    }
	.list td {
		padding:10px;
	}
  
  </style>
  <div class="product-filter">
    <div class="display"><b><?php echo $text_catagory; ?></b>
      <select name="catid" onchange="location=this.value">
        <?php foreach ($categories as $c) { ?>
        <?php if(isset($c['text'])) { ?>
        <?php if (($catid) == $c['value']) { ?>
        <option value="<?php echo $c['href']; ?>" selected="selected"><?php echo $c['text']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $c['href']; ?>"><?php echo $c['text']; ?></option>
        <?php } ?>
        <?php } ?>
        <?php } ?>
      </select></div>
    <div class="limit"><b><?php echo $text_limit; ?></b>
      <select onchange="location = this.value;">
        <?php foreach ($limits as $limits) { ?>
        <?php if ($limits['value'] == $limit) { ?>
        <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
    <div class="sort"><b><?php echo $text_sort; ?></b>
      <select onchange="location = this.value;">
        <?php foreach ($sorts as $sorts) { ?>
        <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
        <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
  </div>
  <div class="ctrl">
    <?php if(!empty($products)) { ?>
      <a href="<?php echo $print; ?>" class="button print" target="_blank"><span><?php echo $text_print; ?></span></a>
    <?php } ?>
  </div>
  <table class="list">
    <thead>
    <tr>
      <th>Изображение</th>
      <th>Детали</th>
      <th>Цена</th>
      <th>Действие</th>
    </tr>
    </thead>
    <tbody>
    <?php if(!empty($products)) { ?>
    <?php for ($i = 0; $i < sizeof($products); $i++) { ?>
    <tr>
      <?php if (isset($products[$i])) { ?>
      <td width="10%" align="center" style="vertical-align:middle;">
        <a href="<?php echo $products[$i]['href']; ?>"><img src="<?php echo $products[$i]['thumb']; ?>" title="<?php echo $products[$i]['name']; ?>" alt="<?php echo $products[$i]['name']; ?>" id="image_<?php echo $products[$i]['id']; ?>" /></a><br />
      </td>
      <td align="left" valign="middle" style="vertical-align:middle;">
        <a href="<?php echo $products[$i]['href']; ?>"><?php echo $products[$i]['name']; ?></a><br />
        <span style="color: #999; font-size: 11px;"><strong>Model: </strong><?php echo $products[$i]['model']; ?></span><br />
        <?php if(!empty($products[$i]['sku'])) { ?><span style="color: #999; font-size: 11px;"><strong>SKU: </strong><?php echo $products[$i]['sku']; ?></span><br /><?php } ?>
        <?php if ($products[$i]['rating']) { ?>
        <img src="catalog/view/theme/default/image/stars_<?php echo $products[$i]['rating'] . '.png'; ?>" alt="<?php echo $products[$i]['stars']; ?>" />
        <?php } ?>
      </td>
      <td align="center" width="15%" style="vertical-align:middle;">
        <?php if (!$products[$i]['special']) { ?>
        <span style="color: #900; font-weight: bold;"><?php echo $products[$i]['price']; ?></span><br />
        <?php } else { ?>
        <span style="color: #900; font-weight: bold; text-decoration: line-through;"><?php echo $products[$i]['price']; ?></span> <span style="color: #F00;"><?php echo $products[$i]['special']; ?></span>
        <?php } ?>
      </td>
      <td align="center" width="25%" style="vertical-align:middle;">
        <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" class="product">
          <?php echo $text_qty; ?><input type="text" name="quantity" size="3" value="1" />
         
          <input type="hidden" name="product_id" class="product_id" value="<?php echo $products[$i]['id']; ?>" />
          <a onclick="$(this).parent().submit();" class="button add_to_cart"><span><?php echo $text_addcart; ?></span></a>
        </form>
      </td>
        <?php } ?>
    </tr>
    <?php } ?>
    </tbody>
  </table>
  <div class="pagination"><?php echo $pagination; ?></div>
    <?php } else { ?>
    <tr>
      <td colspan="4"><?php echo $text_notfound; ?></td>
    </tr>
    </tbody>
  </table>
    <?php } ?>
  <?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
$('.add_to_cart').removeAttr('onclick');

$('.add_to_cart').click(function () {
    var this_form = $(this).parent();
	$.ajax({
		type: 'post',
		url: 'index.php?route=checkout/cart/update',
		dataType: 'json',
		data: this_form.find(':input'),
        success: function(json) {
			$('.success, .warning, .attention, information, .error').remove();

			if (json['error']) {
				if (json['error']['warning']) {
					$('#notification').html('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

					$('.warning').fadeIn('slow');
				}

				for (i in json['error']) {
					$('#option-' + i).after('<span class="error">' + json['error'][i] + '</span>');
				}
			}

			if (json['success']) {
				$('#notification').html('<div class="attention" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

				$('.attention').fadeIn('slow');

				$('#cart_total').html(json['total']);

				$('html, body').animate({ scrollTop: 0 }, 'slow');
			}
		}
	});
});
//--></script>
<?php echo $footer; ?>