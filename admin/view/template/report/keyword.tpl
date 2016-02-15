<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
	<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
	<?php } ?>
  </div>
  <div class="box">
	<div class="heading">
	  <h1><img src="view/image/report.png" alt="" /> <?php echo $heading_title; ?></h1>	 
	  <div class="buttons">
	  <a class="button" onclick="$('#form_export').submit();"><?php echo $button_export; ?></a>
	  <a onclick="$('#form').attr('action', '<?php echo $delete; ?>'); $('#form').attr('target', '_self'); $('#form').submit();" class="button"><?php echo $button_delete; ?></a></div>
	</div>
  <div class="content">
  <form action="<?php echo $action_export; ?>" method="post" id="form_export" enctype="multipart/form-data" class="form-horizontal">
  </form>
	<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
	<table class="list">
	  <thead>
		<tr>
		<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
		<td class="left">
		  <?php if ($sort == 'manufacturer') { ?>
			<a href="<?php echo $sort_manufacturer; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_manufacturer; ?></a>
			<?php } else { ?>
			<a href="<?php echo $sort_manufacturer; ?>"><?php echo $column_manufacturer; ?></a>
			<?php } ?>
		  </td>		  
		 <td class="left">
		  <?php if ($sort == 'term') { ?>
			<a href="<?php echo $sort_term; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
			<?php } else { ?>
			<a href="<?php echo $sort_term; ?>"><?php echo $column_name; ?></a>
			<?php } ?>
		  </td>
		  <td class="left">
		  <?php if ($sort == 'quantity') { ?>
			<a href="<?php echo $sort_quantity; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_quantity; ?></a>
			<?php } else { ?>
			<a href="<?php echo $sort_quantity; ?>"><?php echo $column_quantity; ?></a>
			<?php } ?>
		  </td>
		  <td class="left">
		  <?php if ($sort == 'result') { ?>
			<a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_user; ?></a>
			<?php } else { ?>
			<a href="<?php echo $sort_name; ?>"><?php echo $column_user; ?></a>
			<?php } ?>
		  </td>
		  <td class="left">
		  <?php if ($sort == 'time') { ?>
			<a href="<?php echo $sort_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_model; ?></a>
			<?php } else { ?>
			<a href="<?php echo $sort_date; ?>"><?php echo $column_model; ?></a>
			<?php } ?>
		  </td>
		  <td class="right"><?php echo $column_total; ?></td>
		</tr>
	  </thead>
	  <tbody>
		<?php if ($products) { ?>
		<?php foreach ($products as $product) {  ?>
		<tr>
			 <td style="text-align: center;"><input type="checkbox" name="selected[]" value="<?php echo $product['report_search_id']; ?>" /></td>
		  <td class="left"><?php echo $product['manufacturer']; ?></td>
		  <td class="left"><?php echo $product['name']; ?></td>
		  <td class="left"><?php echo $product['quantity']; ?></td>
		  <td class="left"><?php echo $product['result']; ?></td>
		  <td class="left"><?php echo $product['model']; ?></td>
		  <td class="right"><?php echo $product['total']; ?></td>
		</tr>
		<?php } ?>
		<?php } else { ?>
		<tr>
		  <td class="center" colspan="7"><?php echo $text_no_results; ?></td>
		</tr>
		<?php } ?>
	  </tbody>
	</table>
	</form>
	<div class="pagination"><?php echo $pagination; ?></div>
  </div>
</div>
<?php echo $footer; ?>