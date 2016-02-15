<?php
#####################################################################################
#  Module TOTAL IMPORT PRO for Opencart 1.5.1.x From HostJars opencart.hostjars.com #
#####################################################################################
?>
<?php echo $header; ?>
<style type="text/css">
.info_image{ 
	vertical-align: middle;
	padding-bottom: 3px;
}
</style>
<div id="content">
  <div class="breadcrumb">
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
	<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
	<?php } ?>
  </div>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<?php if ($attention) { ?>
<div class="attention"><?php echo $attention; ?></div>
<?php } ?>
<div class="box">
  <div class="heading">
	<h1><img src='view/image/feed.png' /><?php echo $heading_title; ?></h1>
  </div>
  <div class="content">
	  <form action="<?php echo $action; ?>" method="post" name="settings_form" enctype="multipart/form-data" id="settings_form">
		<table class="form">
			<tr class="instructions"><td colspan="2">You can use the buttons below to skip to the steps you require. You will usually need to run at least Step 1 and Step 5. If you are using this module for the first time, you should run all steps in order from Step 1.</td></tr>
			<?php if (count($saved_settings)) { ?>
			<tr>
				<td>
					<span><label for="settings_groupname">Load Settings Profile:&nbsp;<a href="http://helpdesk.hostjars.com/entries/21991386-using-profiles" target="_blank" alt="Profiles"><img class="info_image" src="view/image/information.png" title="Information About Using Profiles"/></a></label>
					<select name="settings_groupname">
							<?php foreach ($saved_settings as $setting_name) { ?><option value="<?php echo $setting_name; ?>"><?php echo $setting_name; ?></option><?php } ?>
					</select></span>
				</td>
				<td>
					<a href="#" class="button" onclick="$('#settings_form').submit();return false;" ><span>Load</span></a>
					<a href="#" class="button" id="deleteProfile"><span>Delete</span></a>
				</td>
			</tr>
			<?php } ?>
			<?php foreach ($pages as $page => $page_info) { ?>
			<tr>
				<td style="width:80px;"><a href="<?php echo $page_info['link']; ?>" class="button"><span><?php echo $page_info['button']?></span></a></td>
				<td><a href="<?php echo $page_info['helpdesk']; ?>" target="_blank" alt="Profiles"><img class="info_image" src="view/image/information.png" title="Details on <?php echo $page_info['button']?>"/></a>&nbsp;<?php echo $page_info['title']?></td>
			</tr>
			<?php } ?>
		</table>
	  </form>
  <? if($hj_import_category) {?>
	  <style type="text/css">
		.cat_table{
			width:600px;
			margin:auto;
		}
		.cat_table p{
			text-align:center;
			font-size:20px;
			font-weight:bold;
		}
		.cat_table thead td{
			text-align:center;
			font-size:16px;
			
		}
		.cat_table td p{
			margin:0px;
			
		}
		.cat_table td select,.cat_table td input{
			width:250px;
			margin:auto;
		}
		.cat_table .del_cat_field{
			cursor:pointer;
			font-weight:100;
			font-size:14px;
		}
		.del_cat_field:hover{
			text-decoration:underline;
			
		}
		.hidden_cat_table{
			display:none;
		}
	  </style>
	  <form action="#" class="hj_import_category_form">
	  <table class="cat_table">
		<thead>
			<tr>
				<td colspan="2"><p>Настройки Категорий</p></td>
			</tr>
			<tr>
				<td>Категория в магазине</td>
				<td>Категория в файле</td>
				<td>Действие</td>
			</tr>
		</thead>
		<tbody>
			<? $cat_html = ''; foreach($site_categories as $site_categories_v){ 
				$cat_html .= "<option value='{$site_categories_v['category_id']}'>{$site_categories_v['name']}</option>";
			} ?>
			<? foreach($hj_import_category as $category_name => $category_id){?>
				<tr>
					<td>
						<p align="center">						   
						<select name="hj_import_category[]">
							<option value="0" selected="selected">None</option>
							<?php $cat_html_new = str_replace('value=\''.$category_id.'\'','value=\''.$category_id.'\' selected="selected"',$cat_html);?>    
							<?=$cat_html_new;?>							
						</select>
						</p>
					</td>
					<td><p align="center"><input type="text" name="cat_name[]" value="<?php echo $category_name; ?>"></p></td>
					<td><p align="center" class="del_cat_field" title="Удалить">X</span></p>
				</tr>
			<?}?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2" align="right">
					<a class="button add_cat_field">
						<span>Добавить</span>
					</a>
					<a class="button save_cat_field">
						<span>Сохранить</span>
					</a>
				</td>
			</tr>
		</tfoot>
	  </table>
	  </form>
	  <script type="text/javascript">
		$(".add_cat_field").live("click",function(){
			var hidden_cat_table_val = $(".hidden_cat_table tbody").html();
			$(".cat_table tbody").append(hidden_cat_table_val);			
		});
		$(".del_cat_field").live("click",function(){
			$(this).parents("tr").remove();	
		});
		$(".save_cat_field").live("click",function(){
			$.ajax({
				url: 'index.php?route=tool/total_import/savecat&token=<?php echo $token; ?>',
				type: 'POST',
				data:$(".hj_import_category_form").serialize(),
				success: function() {		
					alert('Категории сохранены!');
				}
			});
		});
	  </script>
	  <table class="hidden_cat_table">
		<tr>
			<td>
				<p align="center">
				<select name="hj_import_category[]">
					<? foreach($site_categories as $site_categories_v){?>
						<option value="<?=$site_categories_v['category_id'];?>"><?=$site_categories_v['name'];?></option>
					<?}?>
				</select>
				</p>
			</td>
			<td><p align="center"><input type="text" name="cat_name[]" value=""></p></td>
			<td><p align="center" class="del_cat_field" title="Удалить">X</span></p>
		</tr>
	  </table>
	  <?}?>
  </div>
</div>
<script type="text/javascript">	
 $("#deleteProfile").click(function(e) {
	current_selected = document.settings_form.settings_groupname.value;
	$.post('<?php echo htmlspecialchars_decode($ajax_action); ?>', {'profile_name':current_selected}, function(data) {
		if (data != 'error') {
			$("option[value='"+current_selected+"']").remove();
		}	
		alert(data);
	});
	e.preventDefault();
	return false;
 });
</script>
<?php echo $footer; ?>