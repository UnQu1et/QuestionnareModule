<?php echo $header; ?>
<div id="content">
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>

<div style="margin:5px;">
<a href="<?php echo $url_fields; ?>" class="markbutton-active"><div style="float: left;"><img src="view/image/blog-rec-m.png"  style="" ></div><div style="float: left; margin-left: 7px; margin-top: 3px; "><?php echo $url_fields_text; ?></div></a>
<a href="<?php echo $url_back; ?>" class="markbutton"><div style="float: left;"><img src="view/image/blog-back-m.png"  style="" ></div><div style="float: left; margin-left: 7px; margin-top: 3px; "><?php echo $url_back_text; ?></div></a>
</div>



	  <div class="buttons" style="float:right; clear: both;"><a onclick="location = '<?php echo $insert; ?>'" class="button"><?php echo $button_insert; ?></a>
	  <a onclick="$('#form').attr('action', '<?php echo $copy; ?>'); $('#form').submit();" class="button"><?php echo $button_copy; ?></a>
	  <a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a>
	  </div>
	 <div style="width: 100%; overflow: hidden; clear: both; height: 1px; line-height: 1px;">&nbsp;</div>





  <div class="box">
   <div class="content">
	  <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
		<table class="mytable">
		  <thead>
			<tr>
			  <td width="1" style="text-align: center;">
			  <input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" />
			  </td>
			  <td class="left">
				<?php echo $this->language->get('column_id'); ?>
			   </td>
			  <td class="left"><?php echo $column_image; ?></td>
			  <td class="left"><?php echo $column_name; ?></td>

			  <td class="left"><?php echo $this->language->get('column_type'); ?></td>
			  <td class="left"><?php echo $this->language->get('column_order'); ?></td>
			  <td class="left"><?php echo $this->language->get('column_must'); ?></td>

			  <td class="right"><?php echo $column_action; ?></td>
			</tr>
		  </thead>
		  <tbody>
		  <?php if ($fields) { ?>
			<?php foreach ($fields as $field) { ?>
			<tr>
			  <td style="text-align: center;"><?php if ($field['selected']) { ?>
				<input type="checkbox" name="selected[]" value="<?php echo $field['field_id']; ?>" checked="checked" />
				<?php } else { ?>
				<input type="checkbox" name="selected[]" value="<?php echo $field['field_id']; ?>" />
				<?php } ?></td>

				<td style="color: #999;">
				 <?php echo $field['field_id']; ?>
				</td>



			 <!-- <td class="center"><img src="<?php echo $field['field_image']; ?>" alt="<?php echo $field['field_name']; ?>" style="padding: 1px; border: 1px solid #DDDDDD;" /></td> -->
			  <td class="left">
			  <div style="float: left;">
			  <img src="<?php echo $field['field_image']; ?>" alt="<?php echo $field['field_name']; ?>" style="padding: 1px; margin-right: 15px;border: 1px solid #EEE;" />
			  </div>

			  </td>
			  <td class="left">
			  <div style="float: left; vertical-align: center; padding-top:10px;">
			   <?php foreach ($field['action'] as $action) { ?>
				 <a href="<?php echo $action['href']; ?>"  style="text-decoration: none; border-bottom: 1px solid;"><?php echo $field['field_name']; ?></a>
				<?php } ?>


			  </div>


			  </td>
			   <td class="left"><?php echo $field['field_type']; ?></td>
			   <td class="left"><?php echo $field['field_order']; ?></td>
			   <td class="left"><?php echo $field['field_must']; ?></td>

			  <td class="right"><?php foreach ($field['action'] as $action) { ?>
				<a href="<?php echo $action['href']; ?>" class="markbuttono" style="text-decoration: none; border-bottom: 1px solid;"><?php echo $action['text']; ?></a>
				<?php } ?></td>
			</tr>
			<?php } ?>
			<?php } else { ?>
			<tr>
			  <td class="center" colspan="8"><?php echo $text_no_results; ?></td>
			</tr>
			<?php } ?>
		  </tbody>
		</table>
	  </form>
	  <div class="pagination"></div>
	</div>
  </div>
</div>

<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=catalog/record&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').attr('value');

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

	var filter_blog = $('input[name=\'filter_blog\']').attr('value');

	if (filter_blog) {
		url += '&filter_blog=' + encodeURIComponent(filter_blog);
	}

	var filter_price = $('input[name=\'filter_price\']').attr('value');

	if (filter_price) {
		url += '&filter_price=' + encodeURIComponent(filter_price);
	}

	var filter_quantity = $('input[name=\'filter_quantity\']').attr('value');

	if (filter_quantity) {
		url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
	}

	var filter_status = $('select[name=\'filter_status\']').attr('value');

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

	location = url;
}
//--></script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>
<script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/record/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.field_id
					}
				}));
			}
		});
	},
	select: function(event, ui) {
		$('input[name=\'filter_name\']').val(ui.item.label);

		return false;
	}
});

$('input[name=\'filter_blog\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/record/autocomplete&ajax=1&token=<?php echo $token; ?>&filter_blog=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item.blog,
						value: item.blog_id
					}
				}));
			}
		});
	},
	select: function(event, ui) {
		$('input[name=\'filter_blog\']').val(ui.item.label);

		return false;
	}
});
//--></script>
<?php echo $footer; ?>