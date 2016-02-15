<?php echo $header; ?>


		
<div id="content">
<div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  <?php } ?>
</div>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="heading">
    <h1><img src="view/image/module.png" alt="" /><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
   <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
	<input type="hidden" name="andtemp_module[status]" value="1">
    
    <table class="form">
            <tbody>
			<tr>
                <td><?php echo $and_colorstyle; ?></td>
                <td>
                    <select name="andtemp_module[color_style]">
		                <option value="orange" <?php echo ((!isset($modules['color_style']) || ($modules['color_style']=='orange')) ? ' selected="selected"':'');?>>Orange</option>
		                <option value="blue"  <?php echo (isset($modules['color_style']) && ($modules['color_style']=="blue") ? ' selected="selected"':'');?>>Blue</option>			               
		                <option value="red"  <?php echo (isset($modules['color_style']) && ($modules['color_style']=="red") ? ' selected="selected"':'');?>>Red</option>			               
                    </select>
                </td>
            </tr>
<tr>
                <td><?php echo $and_infofooter; ?></td>
                <td>
                    <select name="andtemp_module[info_footer]">
                            <option value="1" <?php echo ((!isset($modules['info_footer']) || ($modules['info_footer'])) ? ' selected="selected"':'');?>><?php echo $and_enabled; ?></option>
                            <option value="0" <?php echo (isset($modules['info_footer']) && !$modules['info_footer']?' selected="selected"':'');?>><?php echo $and_disabled; ?></option>
                    </select>
                </td>
            </tr>
		<tr>
                <td colspan="2"><h3><?php echo $and_about; ?></h3></td>
            </tr>	
			<tr>
                <td><?php echo $and_about_name; ?></td>
                <td><input type="text" id='about_name' name="andtemp_module[about_name]" value="<?php echo (isset($modules['about_name']) ? $modules['about_name'] : '');?>" />
                    
              </td>
            </tr>
			<tr>
                <td><?php echo $and_about_text; ?> </br></br> <em><?php echo $and_html; ?></em></td>
                <td>
			<textarea style="width: 300px"
			name="andtemp_module[about_text]"
			cols="52" rows="10"><?php echo (isset($modules['about_text']) ? $modules['about_text'] : '');?></textarea>
                    
                &nbsp; </td>
            </tr>	
			<tr>
                <td colspan="2"><h3><?php echo $and_contacts; ?></h3></td>
            </tr>	
			<tr>
                <td><?php echo $and_contacts_name; ?></td>
                <td><input type="text" id='contacts_name' name="andtemp_module[contacts_name]" value="<?php echo (isset($modules['contacts_name']) ? $modules['contacts_name'] : '');?>" />
                    
              </td>
            </tr>
			<tr>
                <td><?php echo $and_contacts_phone1; ?></td>
                <td><input type="text" id='phone' name="andtemp_module[phone]" value="<?php echo (isset($modules['phone']) ? $modules['phone'] : '');?>" />
                    
              </td>
            </tr>
			<tr>
                <td><?php echo $and_contacts_phone2; ?></td>
                <td><input type="text" id='phone2' name="andtemp_module[phone2]" value="<?php echo (isset($modules['phone2']) ? $modules['phone2'] : '');?>" />
                    
              </td>
            </tr>
			<tr>
                <td><?php echo $and_contacts_email; ?></td>
                <td><input type="text" id='email' name="andtemp_module[email]" value="<?php echo (isset($modules['email']) ? $modules['email'] : '');?>" />
                    
              </td>
            </tr>
			<tr>
                <td><?php echo $and_contacts_skype; ?></td>
                <td><input type="text" name="andtemp_module[skype]" value="<?php echo (isset($modules['skype']) ? $modules['skype'] : '');?>" />             
                </td>
            </tr> 
			<tr>
                <td><?php echo $and_contacts_adress; ?></td>
                <td><input type="text" id='adress' name="andtemp_module[adress]" value="<?php echo (isset($modules['adress']) ? $modules['adress'] : '');?>" />
                    
              </td>
            </tr>
			<tr>
                <td><?php echo $and_feedback; ?></td>
                <td>
                    <select name="andtemp_module[feedback]">
                            <option value="1" <?php echo ((!isset($modules['feedback']) || ($modules['feedback'])) ? ' selected="selected"':'');?>><?php echo $and_enabled; ?></option>
                            <option value="0" <?php echo (isset($modules['feedback']) && !$modules['feedback']?' selected="selected"':'');?>><?php echo $and_disabled; ?></option>
                    </select>
                &nbsp; <em></em></td>
            </tr>
            <tr>
                <td colspan="2"><h3><?php echo $and_anyhtml; ?></h3></td>
            </tr>	
			<tr>
                <td><?php echo $and_anyhtml_name; ?></td>
                <td><input type="text" id='anyhtml_name' name="andtemp_module[anyhtml_name]" value="<?php echo (isset($modules['anyhtml_name']) ? $modules['anyhtml_name'] : '');?>" />
                    
              </td>
            </tr>
			<tr>
                <td><?php echo $and_anyhtml_text; ?> </br></br> <em><?php echo $and_html2; ?></em></td>
                <td>
			<textarea style="width: 300px"
			name="andtemp_module[anyhtml_text]"
			cols="52" rows="10"><?php echo (isset($modules['anyhtml_text']) ? $modules['anyhtml_text'] : '');?></textarea>
                    
                &nbsp; </td>
            </tr>	
            
         
	
        </tbody></table>
    </form>
  </div>
</div>

<?php echo $footer; ?>