<?php if ($filters) { ?>
<div class="box filter-box">
	<div class="box-heading"><?php echo $heading_title; ?></div>
	<div class="box-content">
	<? if($filter_error){?>
	<p class="error"><?php echo $filter_error;?></p>
	<?}?>
	
	<?php foreach ($filters as $filter) { ?>
		<?php if (isset($filter['filters'])) { ?>
			<?php if ($filter['style_id'] == 'list') { ?>
				<div class="filter-item filter-item-list">
					<b><?php if($filter['required']){?><span class="red">*</span><?}?><?php echo $filter['name']; ?></b>
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
					<b><?php if($filter['required']){?><span class="red">*</span><?}?><?php echo $filter['name']; ?></b>
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
					<div class="filter-item-select-head"><?php if($filter['required']){?><span class="red">*</span><?}?><?php echo $filter['name']; ?><div class="filter-item-select-button"></div></div>
					<div class="filter-item-select-list">
						<select  <?php if($filter_get['m'] AND $filter['type_index'] == 'manufacter' OR ($filter_get['n'] AND $filter_get['n'] != '-- Выберите товар --')){?>disabled="disabled" title="Чтобы выбрать другого производителя нужно сбросить фильтр"<?}?>>
						<option class="first_option_name"><?php echo $entry_text_select; ?></option>
						<?php foreach ($filter['filters'] as $filter_value) { ?>
							<?php if ($filter_value['count'] || !$count_enabled) { ?>
								<option data-key="<?php echo $filter_value['key']; ?>" data-value="<?php echo $filter_value['value']; ?>" <?php if($filter_value['active']) { ?>selected="selected"<?php } ?>><?php echo $filter_value['name']; ?> <?php echo $filter_value['view_count']; ?></option>
							<?php } else { ?>
								<option disabled="disabled"><?php echo $filter_value['name']; ?> <?php echo $filter_value['view_count']; ?></option>
							<?php } ?>
						<?php } ?>
						</select>
					</div>
				</div>
			<?php } ?>
			
			<?php if ($filter['style_id'] == 'text' AND $filter['type_index'] == 'name') { ?>
			
				<div class="filter-item filter-item-select">
					<div class="filter-item-select-head"><?php if($filter['required']){?><span class="red">*</span><?}?><?php echo $filter['name']; ?><div class="filter-item-select-button"></div></div>
					<div class="filter-item-select-list">
						<select <?php $metka_name = false; if($filter_get['n'] AND $filter_get['n'] != '-- Выберите товар --' AND $filter['type_index'] == 'name' OR (count($filter['filters']) == 1 AND $filter['filters'][1] == '')){?>disabled="disabled" title="Чтобы выбрать другое наименование товара нужно сбросить фильтр"<?}?>>
						<!-- <option class="first_option_name"><?php echo $text_all; ?></option> -->
						<option data-key="n" data-value="-- Выберите товар --" class="first_option_name">-- Выберите товар --</option>
						<?php foreach ($filter['filters'] as $filter_value) { ?>
							<?php if ($filter_value['count'] || !$count_enabled) { ?>
								<option data-key="<?php echo $filter_value['key']; ?>" data-value="<?php echo $filter_value['value']; ?>" <?php if($filter_value['active']) { $metka_name = true; ?>selected="selected"<?php } ?>><?php echo $filter_value['name']; ?> <?php echo $filter_value['view_count']; ?></option>
							<?php } else { ?>
								<option disabled="disabled"><?php echo $filter_value['name']; ?> <?php echo $filter_value['view_count']; ?></option>
							<?php } ?>
							<? if(!$metka_name AND $filter_get['n'] AND $filter_get['n'] != '-- Выберите товар --'){?>
								<option value="<?php echo $filter_get['n'];?>" selected="selected"><?php echo $filter_get['n'];?></option>
							<?}?>
						<?php } ?>
						</select>						
					</div>
				</div>
			<?php } ?>
			
			
			<?php if ($filter['style_id'] == 'textform' AND $filter['type_index'] == 'quantity') { ?>
			
				<div class="filter-item filter-item-select">
					<div class="filter-item-select-head"><?php if($filter['required']){?><span class="red">*</span><?}?><?php echo $filter['name']; ?><div class="filter-item-select-button"></div></div>
					<div class="filter-item-select-list">
						<input <?php if($filter_get['min_quantity'] AND $filter['type_index'] == 'quantity'){?>disabled="disabled" title="Чтобы выбрать другое количество нужно сбросить фильтр"<?}?> type="text" class="min_quantity" value="<?php echo $filter_get['min_quantity']; ?>"> <!-- до <input type="text" class="max_quantity" value="<?php echo $filter_get['max_quantity']; ?>"> -->						
					</div>
				</div>
			<?php } ?>
			
			<?php if ($filter['style_id'] == 'image') { ?>
				<div class="filter-item filter-item-image">
					<div class="filter-item-image-head"><?php if($filter['required']){?><span class="red">*</span><?}?><?php echo $filter['name']; ?></div>
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
					<b><?php if($filter['required']){?><span class="red">*</span><?}?><?php echo $filter['name']; ?></b>
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
	<span class="filter_note"><span class="red">*</span> - Обязательный параметр для поиска</span>
	<a id="filter_apply_button" class="button"><span><?php echo $text_apply; ?></span></a>
	<a class="resetFilter button" onclick="resetFilter();"><?php echo $text_reset_filter; ?></a>
  </div>
  <div class="bottom">&nbsp;</div>
</div>
<?php } ?>
<style type="text/css">
	.red{
		color:red;
	}
</style>
<script>
	$("#filter_apply_button").click(function(){
		var filter = '';
		var arr = {};
		$(".filter_active").each(function(i){
			var key = $(this).attr("data-key");
			var value = $(this).attr("data-value");
			if(key !== undefined && value !== undefined){
				if (arr[key] === undefined) {
					arr[key] = '';
					arr[key] += value;
				} else {
					arr[key] += ',' + value;
				}
			}
			
		});
		$(".filter-item select option:selected").each(function(i){
			var key = $(this).attr("data-key");
			var value = $(this).attr("data-value");
			if(key !== undefined && value !== undefined){
				if (arr[key] === undefined) {
					arr[key] = '';
					arr[key] += value;
				} else {
					arr[key] += ',' + value;
				}
			}			
			
		});
		var min_quantity = $(".filter-item .min_quantity").val();
		var max_quantity = $(".filter-item .max_quantity").val();
		
		if(min_quantity !== undefined && min_quantity != ''){
			arr['min_quantity'] = min_quantity;
		}
		if(max_quantity !== undefined && max_quantity != ''){
			arr['max_quantity'] = max_quantity;
		}
		
		$.each(arr, function(index,val){
			filter += index + ':' + val + ';';
		});
		filter = filter.substr(0, filter.length - 1);
		
		setUrl(filter);
	});
	
	$(".filter-item select").change(function(){
		$("#filter_apply_button").click();
	});
	
	function setUrl(filter) {
		var href = location.href;
		if(!location.search){
			var exp = /\?filter=(.*?)(&|$)/g;		
			href = href.replace(exp, "$2") + '?filter=' + filter;
		}else{		
			if(location.search.match('&filter')){
				var exp = /&filter=(.*?)(&|$)/g;		
				href = href.replace(exp, "$2") + '&filter=' + filter;
				
			}else{
				if(location.search.match('[\&]')){
					var exp = /\&filter=(.*?)(&|$)/g;		
					href = href.replace(exp, "$2") + '&filter=' + filter;
				}else{
					var exp = /\?filter=(.*?)(&|$)/g;		
					href = href.replace(exp, "$2") + '?filter=' + filter;
				}
				
			}			
		}		
		
		location = href;
	}
	
	function addButtonReset() {
		var href = location.href;
		if (/(\?|&)filter=(.*?)/.test(href)) {
			$("#filter_apply_button").after('<a class="resetFilter" onclick="resetFilter();"><?php echo $text_reset_filter; ?></a>');
		}
	}
	
	//addButtonReset();
	
	function resetFilter() {
		var href = location.href;
		var exp = /(\?|&)filter=(.*?)(&|$)/g;
		href = href.replace(exp, "");
		location = href;
	}
	
	/*$(".filter-item-select-head").click(function(){
		$(".filter-item-select-list").not($(this).next(".filter-item-select-list")).hide();
		$(this).next(".filter-item-select-list").toggle(); 
		return false;
	});*/
	
	/*$(document).click(function(e){ 
		var $target = $(e.target);
		if (!$target.is("a") && !$target.is("input:checkbox")) { 
			$(".filter-item-select-list").hide(); 
		} 
	});*/
	
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