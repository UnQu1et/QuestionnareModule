<?php echo $header; ?>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>

  <div style="border-bottom: 1px solid #DDD; background-color: #EFEFEF; height: 40px; min-width: 900px; overflow: hidden;">
    <div style="float:left; margin-top: 10px;" >
    <img src="view/image/blog-icon.png" style="height: 21px; margin-left: 10px; " >
    </div>

<div style="margin-left: 10px; float:left; font-size: 16px; margin-top: 10px;">
<ins style="color: green; padding-top: 17px; text-shadow: 0 2px 1px #FFFFFF; padding-left: 3px; font-size: 17px; font-weight: bold;  text-decoration: none; ">
<?php echo strip_tags($heading_title); ?>
</ins> ver.: <?php echo $blog_version; ?>
</div>

    <div style=" height: 40px; float:right; background:#aceead;">
   <div style="margin-top: 2px; line-height: 18px; margin-left: 9px; margin-right: 9px; font-size: 13px; overflow: hidden;"><?php echo $this->language->get('heading_dev'); ?></div>
    </div>


</div>

<div id="content" style="border: none;">

<!--
<div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo strip_tags($breadcrumb['text']); ?></a>
  <?php } ?>
</div>
-->
<div style="clear: both; line-height: 1px; font-size: 1px;"></div>


<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>

<?php if (isset($this->session->data['success'])) {unset($this->session->data['success']);
?>
<div class="success"><?php echo $this->language->get('text_success'); ?></div>
<?php } ?>


<div class="box1">

<div class="content">

<div style="margin-left:0px;">
<a href="<?php echo $url_blog; ?>" class="markbutton"><div style="float: left;"><img src="view/image/blog-icon-m.png" style="" ></div><div style="float: left; margin-left: 7px; margin-top: 3px; "><?php echo $url_blog_text; ?></div></a>
<a href="<?php echo $url_record; ?>" class="markbutton"><div style="float: left;"><img src="view/image/blog-rec-m.png"  style="" ></div><div style="float: left; margin-left: 7px; margin-top: 3px; "><?php echo $url_record_text; ?></div></a>
<a href="<?php echo $url_comment; ?>" class="markbutton"><div style="float: left;"><img src="view/image/blog-com-m.png"  style="" ></div><div style="float: left; margin-left: 7px; margin-top: 3px; "><?php echo $url_comment_text; ?></div></a>
<a href="<?php echo $url_modules; ?>" class="markbutton"><div style="float: left;"><img src="view/image/blog-back-m.png"  style="" ></div><div style="float: left; margin-left: 7px; margin-top: 3px; "><?php echo $url_modules_text; ?></div></a>
</div>

<div style="margin-left: 0px;">
<div style="margin-right:5px; float:left;">
<a href="<?php echo $url_options; ?>" class="markbutton"><div style="float: left;"><img src="view/image/agoodonut-options-m.png"  style="" ></div>
<div style="float: left; margin-left: 7px; margin-top: 4px; "><?php echo $this->language->get('tab_options'); ?></div></a>
</div>


<div style="margin-right:5px; float:left;">
<a href="<?php echo $url_schemes; ?>" class="markbutton-active"><div style="float: left;"><img src="view/image/agoodonut-schemes-m.png"  style="" ></div>
<div style="float: left; margin-left: 7px; margin-top: 4px; "><?php echo $tab_general; ?></div></a>
</div>

<div style="margin-right:5px; float:left;">
<a href="<?php echo $url_widgets; ?>" class="markbutton"><div style="float: left;"><img src="view/image/agoodonut-widgets-m.png"  style="" ></div>
<div style="float: left; margin-left: 7px; margin-top: 4px; "><?php echo $tab_list; ?></div></a>
</div>
</div>





<div style="margin:5px; float:right;">
   <a href="#" class="mbutton blog_save"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="mbutton"><?php echo $button_cancel; ?></a>
</div>

<div style="clear: both; line-height: 1px; font-size: 1px;"></div>

<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

<script type="text/javascript">
function delayer(){
    window.location = 'index.php?route=module/blog/schemes&token=<?php echo $token; ?>';
}
</script>





  <div id="tab-general">

          <?php

      // print_r("<PRE>");
     //  print_r($modules);
       // print_r($mylist);
     //   print_r("</PRE>");

        ?>

   <table class="mytable" id="module" style="width: 100%; ">
     <thead>
      <tr>
       <td class="left"><?php echo $entry_layout; ?></td>
       <td class="left"><?php echo $entry_position; ?></td>
       <td class="left"><?php echo $entry_status; ?></td>
       <td class="right"> <?php echo $this->language->get('type_list'); ?></td>
       <td class="right"><?php echo $entry_sort_order; ?></td>
       <td style="width: 200px;"><?php echo $this->language->get('text_action'); ?></td>
      </tr>
     </thead>
        <?php
          $module_row = 0;
        ?>
        <?php foreach ($modules as $module)
        {
         while (!isset($modules[$module_row])) {          $module_row++;
         }
        ?>

          <tr id="module-row<?php echo $module_row; ?>">


            <td class="left"><!-- <?php echo $module_row; ?>&nbsp; -->

            <!--
            <select name="blog_module[<?php echo $module_row; ?>][layout_id]">
                <?php foreach ($layouts as $layout) { ?>
                <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
                -->

				<div>
				<div class="scrollbox" style="width: auto; height: 100px;">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($layouts as $layout) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php
                    if (!is_array($module['layout_id'])) {                     $module_array = Array();                     $module_array[] = $module['layout_id'];
                     $module['layout_id'] = $module_array;
                    }

                    if ((isset($module['layout_id']) && is_array($module['layout_id']) ) && in_array($layout['layout_id'], $module['layout_id'])) { ?>
                    <input type="checkbox" name="blog_module[<?php echo $module_row; ?>][layout_id][]" value="<?php echo $layout['layout_id']; ?>" checked="checked" />
                    <?php echo $layout['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="blog_module[<?php echo $module_row; ?>][layout_id][]" value="<?php echo $layout['layout_id']; ?>" />
                    <?php echo $layout['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div>

                <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $this->language->get('text_select_all'); ?></a> / <br><a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $this->language->get('text_unselect_all'); ?></a>

                </div>


                <div style="color: #777; font-size: 12px; line-height: 14px; ">
                <?php foreach ($layouts as $layout) { ?>
                    <?php
                    if (!is_array($module['layout_id'])) {
                     $module_array = Array();
                     $module_array[] = $module['layout_id'];
                     $module['layout_id'] = $module_array;
                    }

                    if ((isset($module['layout_id']) && is_array($module['layout_id']) ) && in_array($layout['layout_id'], $module['layout_id'])) { ?>
                     <?php echo $layout['name']."<br>"; ?>
                    <?php } ?>



                <?php } ?>
                </div>

              </td>

            <td class="left"><select name="blog_module[<?php echo $module_row; ?>][position]">
                <?php if ($module['position'] == 'content_top') { ?>
                <option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
                <?php } else { ?>
                <option value="content_top"><?php echo $text_content_top; ?></option>
                <?php } ?>
                <?php if ($module['position'] == 'content_bottom') { ?>
                <option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
                <?php } else { ?>
                <option value="content_bottom"><?php echo $text_content_bottom; ?></option>
                <?php } ?>
                <?php if ($module['position'] == 'column_left') { ?>
                <option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
                <?php } else { ?>
                <option value="column_left"><?php echo $text_column_left; ?></option>
                <?php } ?>
                <?php if ($module['position'] == 'column_right') { ?>
                <option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
                <?php } else { ?>
                <option value="column_right"><?php echo $text_column_right; ?></option>
                <?php } ?>
              </select></td>
            <td class="left"><select name="blog_module[<?php echo $module_row; ?>][status]">
                <?php if ($module['status']) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>

              <td class="left">
               <select name="blog_module[<?php echo $module_row; ?>][what]">
           	<?php
				foreach ($mylist as $num =>$list) {
                   // echo $module['what']."->".$list['type']."<br>";
					if (isset($list['title_list_latest'][ $this->config->get('config_language_id')]) &&  $list['title_list_latest'][ $this->config->get('config_language_id')]!='')
					{
				     $title=$list['title_list_latest'][ $this->config->get('config_language_id')];
					}
					else
					{
					 $title="List-".$num;
					}


		    ?>
                <?php if ($module['what']==$num) { ?>
                <option value="<?php echo $num; ?>" selected="selected"><?php echo $title; ?></option>
                <?php } else { ?>
                <option value="<?php echo $num; ?>"><?php echo $title; ?></option>
                <?php } ?>

              <?php
              }
              ?>

               <?php if ($module['what']=='what_hook') { ?>
                <option value="what_hook" selected="selected"><?php echo $text_what_hook; ?></option>
                <?php } else { ?>
                <option value="what_hook"><?php echo $text_what_hook; ?></option>
                <?php } ?>
              </select>
              </td>


            <td class="right"><input type="text" name="blog_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
            <td class="left">
            <?php if ($module['what']!='what_hook' ) {
            $button_class ='mbuttonr';
			}
            else
            {           	 $button_class ='markbuttono';
           	}             ?>
           <div style="float:left; width: 100px;">
             <a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="<?php echo $button_class; ?>"><?php echo $button_remove; ?></a>
           </div>

             <?php if ($button_class =='markbuttono') {

             ?>
             <div style="float:left;  width: 50%;">
             <?php
             //echo $this->language->get('hook_not_delete');
             ?>
             </div>
             <?php
            }
            ?>


          </td>
         </tr>
        <?php
         $module_row++;
        }
        ?>
        <tfoot>
          <tr>
            <td colspan="5"></td>
            <td class="left"><a onclick="addModule();" class="markbutton"><?php echo $button_add_module; ?></a></td>
          </tr>
        </tfoot>
      </table>

    </div>


 <div style="clear: both; line-height: 1px; font-size: 1px;"></div>
    <div class="buttons right" style="margin-top: 20px;float: right;"><a href="#" class="mbutton blog_save"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="mbutton"><?php echo $button_cancel; ?></a></div>
</form>
  </div>
</div>


<script type="text/javascript">
var amodule_row = Array();

<?php
 foreach ($modules as $indx => $module) {
?>
amodule_row.push(<?php echo $indx; ?>);
<?php
}
?>
var module_row = <?php echo $module_row; ?>;

function addModule() {	var aindex = -1;
	for(i=0; i<amodule_row.length; i++) {	 flg = jQuery.inArray(i, amodule_row);
	 if (flg == -1) {	  aindex = i;
	 }
	}
	if (aindex == -1) {
	  aindex = amodule_row.length;
	}
	module_row = aindex;
	amodule_row.push(aindex);

	html  = '<tbody id="module-row' + module_row + '">';
	html += '  <tr>';
	html += '    <td class="left">';



	html += '<div class="scrollbox" style="width: auto; height: 100px;">';
    html += '<?php $class = 'odd'; ?>';
             <?php foreach ($layouts as $layout) { ?>
              <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
   html += '               <div class="<?php echo $class; ?>">';
   html += '               <input type="checkbox" name="blog_module[' + module_row + '][layout_id][]" value="<?php echo $layout['layout_id']; ?>" />';
   html += '                 <?php echo $layout['name']; ?>';
   html += '               </div>';
             <?php } ?>
   html += '             </div>';
   html += '             <a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', true);"><?php echo $this->language->get('text_select_all'); ?></a> / <a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', false);"><?php echo $this->language->get('text_unselect_all'); ?></a></td>';






	html += '    </td>';
	html += '    <td class="left"><select name="blog_module[' + module_row + '][position]">';
	html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
	html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
	html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
	html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
	html += '    </select></td>';
	html += '    <td class="left"><select name="blog_module[' + module_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';


	html += '    <td class="left"><select name="blog_module[' + module_row + '][what]">';

    <?php
	if (count($mylist)>0) {
  	 foreach ($mylist as $num =>$list) {
					if (isset($list['title_list_latest'][ $this->config->get('config_language_id')]) &&  $list['title_list_latest'][ $this->config->get('config_language_id')]!='')
					{
				     $title=$list['title_list_latest'][ $this->config->get('config_language_id')];
					}
					else
					{
					 $title="List-".$num;
					}

		    ?>
	html += '        <option value="<?php echo $num; ?>"><?php echo $title; ?></option>';
	<?php
	 }
	}
	 ?>

	html += '      <option value="what_hook"><?php echo $text_what_hook; ?></option>';
	html += '    </select></td>';





	html += '    <td class="right"><input type="text" name="blog_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="mbuttonr"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';

	$('#module tfoot').before(html);

	module_row++;
}
</script>
	<script type="text/javascript">

	 form_submit = function() {
		$('#form').submit();
		return false;
	}
	$('.blog_save').bind('click', form_submit);
	</script>

</div>

<?php echo $footer; ?>