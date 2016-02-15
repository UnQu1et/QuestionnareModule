<?php if ($filters) { ?>
<noindex>
<div class="box">
	<div class="box-heading"><?php echo $heading_title; ?></div>
	<div class="box-content">
	<?php foreach ($filters as $filter) { ?>
		<?php if (isset($filter['filters'])) { ?>
            <?php if ($filter['style_id'] == 'list') { ?>
                <div class="filter-item filter-item-list">
                    <b><?php echo $filter['name']; ?></b>
                    <ul>
                    <?php foreach ($filter['filters'] as $filter_value) { ?>
                        <?php if ($filter_value['count'] || !$count_enabled) { ?>
							<li><a href="<?php echo $filter_value['href']; ?>" <?php if($filter_value['active']) { ?>class="filter_active"<?php } ?> data-key="<?php echo $filter_value['key']; ?>" data-value="<?php echo $filter_value['value']; ?>"><?php echo $filter_value['name']; ?></a> <?php echo $filter_value['view_count']; ?></li>
						<?php } else { ?>
							<li><?php echo $filter_value['name']; ?> <?php echo $filter_value['view_count']; ?></li>
						<?php } ?>
                    <?php } ?>
                    </ul>
                </div>
            <?php } ?>
            <?php if ($filter['style_id'] == 'checkbox') { ?>
                <div class="filter-item filter-item-checkbox">
                    <b><?php echo $filter['name']; ?></b>
                    <ul>
                    <?php foreach ($filter['filters'] as $filter_value) { ?>
						<?php if ($filter_value['count'] || !$count_enabled) { ?>
							<li><input type="checkbox" <?php if($filter_value['active']) { ?>checked="checked"<?php } ?>><a href="<?php echo $filter_value['href']; ?>" <?php if($filter_value['active']) { ?>class="filter_active"<?php } ?> data-key="<?php echo $filter_value['key']; ?>" data-value="<?php echo $filter_value['value']; ?>"><?php echo $filter_value['name']; ?></a> <?php echo $filter_value['view_count']; ?></li>
						<?php } else { ?>
							<li><input type="checkbox" disabled="disabled"><?php echo $filter_value['name']; ?> <?php echo $filter_value['view_count']; ?></li>
						<?php } ?>
                    <?php } ?>
                    </ul>
                </div>
            <?php } ?>
			<?php if ($filter['style_id'] == 'select') { ?>
                <div class="filter-item filter-item-select">
                    <div class="filter-item-select-head"><?php echo $filter['name']; ?><div class="filter-item-select-button"></div></div>
                    <div class="filter-item-select-list">
						<ul>
						<?php foreach ($filter['filters'] as $filter_value) { ?>
							<?php if ($filter_value['count'] || !$count_enabled) { ?>
								<li><input type="checkbox" <?php if($filter_value['active']) { ?>checked="checked"<?php } ?>><a href="<?php echo $filter_value['href']; ?>" <?php if($filter_value['active']) { ?>class="filter_active"<?php } ?> data-key="<?php echo $filter_value['key']; ?>" data-value="<?php echo $filter_value['value']; ?>"><?php echo $filter_value['name']; ?></a> <?php echo $filter_value['view_count']; ?></li>
							<?php } else { ?>
								<li><input type="checkbox" disabled="disabled"><?php echo $filter_value['name']; ?> <?php echo $filter_value['view_count']; ?></li>
							<?php } ?>
						<?php } ?>
						</ul>
					</div>
                </div>
            <?php } ?>
			
			<?php if ($filter['style_id'] == 'image') { ?>
                <div class="filter-item filter-item-image">
                    <div class="filter-item-image-head"><?php echo $filter['name']; ?></div>
					<?php foreach ($filter['filters'] as $filter_value) { ?>
						<?php if ($filter_value['count'] || !$count_enabled) { ?>
							<a href="<?php echo $filter_value['href']; ?>" <?php if($filter_value['active']) { ?>class="filter_active"<?php } ?> data-key="<?php echo $filter_value['key']; ?>" data-value="<?php echo $filter_value['value']; ?>"><img src="<?php echo $filter_value['image']; ?>" alt="<?php echo $filter_value['name']; ?><?php echo $filter_value['view_count']; ?>" title="<?php echo $filter_value['name']; ?><?php echo  $filter_value['view_count']; ?>"></a>
						<?php } else { ?>
							<img src="<?php echo $filter_value['image']; ?>" alt="<?php echo $filter_value['name']; ?><?php echo  $filter_value['view_count']; ?>" title="<?php echo $filter_value['name']; ?><?php echo $filter_value['view_count']; ?>">
						<?php } ?>
					<?php } ?>
                </div>
            <?php } ?>
			<?php if ($filter['style_id'] == 'slider') { ?>
                <div class="filter-item filter-item-slider">
                    <b><?php echo $filter['name']; ?></b>
					<div class="filter-item-slider-body">
					<input type="text" id="price" style="border:0; color:#f6931f; background:#fff; font-weight:bold;" class="filter_active" data-key="p" data-value="<?php echo $filter['filters'][0]['value'] . ',' . $filter['filters'][1]['value']; ?>" disabled="disabled" />
					<div id="slider-range" class="slider-range"></div>
					</div>
					<script>
					$(function() {
						if (/\Wp:[\d\.]+,[\d\.]+/.test(location.href)) {
							var myRe = /\Wp:([\d\.]+),([\d\.]+)/;
							var priceFilterValue = myRe.exec(location.href);
							startValue = priceFilterValue[1];
							endValue = priceFilterValue[2];
							$("#price").attr('data-value', startValue + ',' + endValue);
						} else {
							startValue = <?php echo $filter['filters'][0]['value']; ?>;
							endValue = <?php echo $filter['filters'][1]['value']; ?>;
						}
						$( "#slider-range" ).slider({
							range: true,
							min: <?php echo $filter['filters'][0]['value']; ?>,
							max: <?php echo $filter['filters'][1]['value']; ?>,
							values: [ startValue, endValue ],
							slide: function( event, ui ) {
								$( "#price" ).val( "<?php echo $currency_symbol_left; ?>" + ui.values[ 0 ].toFixed(<?php echo $count_symbols; ?>) + "<?php echo $currency_symbol_right; ?> - <?php echo $currency_symbol_left; ?>" + ui.values[ 1 ].toFixed(<?php echo $count_symbols; ?>) + "<?php echo $currency_symbol_right; ?>" );
							},
							change: function( event, ui ) {
								/*var href = '<?php echo htmlspecialchars_decode($filter['filters'][0]['href']); ?>';
								var exp = /p:[\d\.,]+/g;
								href = href.replace(exp, "p:" + ui.values[ 0 ] + "," + ui.values[ 1 ]);
								location = href;*/
								$( "#price" ).attr("data-value", ui.values[ 0 ] + "," + ui.values[ 1 ]);
							}
						});
						$( "#price" ).val( "<?php echo $currency_symbol_left; ?>" + $( "#slider-range" ).slider( "values", 0 ).toFixed(<?php echo $count_symbols; ?>) + 
							"<?php echo $currency_symbol_right; ?> - <?php echo $currency_symbol_left; ?>" + $( "#slider-range" ).slider( "values", 1 ).toFixed(<?php echo $count_symbols; ?>) + "<?php echo $currency_symbol_right; ?>" );
					});
					</script>
                </div>
            <?php } ?>
		<?php } ?>
	<?php } ?>
	<a id="filter_apply_button" class="button"><span><?php echo $text_apply; ?></span></a>
  </div>
  <div class="bottom">&nbsp;</div>
</div>
</noindex>
<?php } ?>
<script>
	$("#filter_apply_button").click(function(){
		var filter = '';
		var arr = {};
		$(".filter_active").each(function(i){
			var key = $(this).attr("data-key");
			var value = $(this).attr("data-value");
			if (arr[key] === undefined) {
				arr[key] = '';
				arr[key] += value;
			} else {
				arr[key] += ',' + value;
			}
			
		});
		
		$.each(arr, function(index,val){
			filter += index + ':' + val + ';';
		});
		filter = filter.substr(0, filter.length - 1);
		setUrl(filter);
	});
	
	function setUrl(filter) {
		var href = location.href;
		var exp = /&filter=(.*?)(&|$)/g;
		href = href.replace(exp, "$2") + '&filter=' + filter;
		location = href;
	}
	
	function addButtonReset() {
		var href = location.href;
		if (/(\?|&)filter=(.*?)/.test(href)) {
			$("#filter_apply_button").after('<br><br>[ <a onclick="resetFilter();"><?php echo $text_reset_filter; ?></a> ]');
		}
	}
	
	addButtonReset();
	
	function resetFilter() {
		var href = location.href;
		var exp = /(\?|&)filter=(.*?)(&|$)/g;
		href = href.replace(exp, "");
		location = href;
	}
	
	$(".filter-item-select-head").click(function(){
		$(".filter-item-select-list").not($(this).next(".filter-item-select-list")).hide();
		$(this).next(".filter-item-select-list").toggle(); 
		return false;
	});
	
	$(document).click(function(e){ 
		var $target = $(e.target);
		if (!$target.is("a") && !$target.is("input:checkbox")) { 
			$(".filter-item-select-list").hide(); 
		} 
	});
	
	$(".filter-item a").click(function(e){ 
		e.preventDefault();
		$(this).toggleClass("filter_active");
		var checkbox = $(this).siblings("input:checkbox");
		if (checkbox.is(':checked')) {
			checkbox.attr('checked', false);
		} else {
			checkbox.attr('checked', true);
		}
	});
	
	
	 $(".filter-item-checkbox input:checkbox, .filter-item-select input:checkbox").click(function(){
		$(this).siblings("a").toggleClass("filter_active");
		$(this).parents(".filter-item-select-list").show();
    });
	
</script>