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


<div style="margin:5px;">
<a href="<?php echo $url_blog; ?>" class="markbutton"><div style="float: left;"><img src="view/image/blog-icon-m.png" style="" ></div><div style="float: left; margin-left: 7px; margin-top: 3px; "><?php echo $url_blog_text; ?></div></a>
<a href="<?php echo $url_record; ?>" class="markbutton"><div style="float: left;"><img src="view/image/blog-rec-m.png"  style="" ></div><div style="float: left; margin-left: 7px; margin-top: 3px; "><?php echo $url_record_text; ?></div></a>
<a href="<?php echo $url_comment; ?>" class="markbutton-active"><div style="float: left;"><img src="view/image/blog-com-m.png"  style="" ></div><div style="float: left; margin-left: 7px; margin-top: 3px; "><?php echo $url_comment_text; ?></div></a>
<a href="<?php echo $url_back; ?>" class="markbutton"><div style="float: left;"><img src="view/image/blog-back-m.png"  style="" ></div><div style="float: left; margin-left: 7px; margin-top: 3px; "><?php echo $url_back_text; ?></div></a>
</div>



      <div class="buttons" style="float:right; clear: both;"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a>
      	<a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a>
      </div>
     <div style="width: 100%; overflow: hidden; clear: both; height: 1px; line-height: 1px;">&nbsp;</div>


  <div class="box">
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <input type="hidden" name="thislist" value="<?php echo base64_encode(serialize($thislist)); ?>">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_author; ?></td>
            <td><input type="text" name="author" value="<?php echo $author; ?>" />
            <input type="hidden" name="name" value="<?php echo $author; ?>" />
              <?php if ($error_author) { ?>
              <span class="error"><?php echo $error_author; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_record; ?></td>
            <td><input type="text" name="record" value="<?php echo $record; ?>" style="width: 90%;">
              <input type="hidden" name="record_id" value="<?php echo $record_id; ?>" />
              <?php if ($error_record) { ?>
              <span class="error"><?php echo $error_record; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_text; ?></td>
            <td><textarea id="bbtext" name="text" cols="60" rows="8"><?php echo $text; ?></textarea>
              <?php if ($error_text) { ?>
              <span class="error"><?php echo $error_text; ?></span>
              <?php } ?></td>
          </tr>

           <tr>
              <td><?php echo $entry_date_available; ?></td>
              <td><input type="text" name="date_available" value="<?php echo $date_available; ?>" size="20" class="datetime" /></td>
            </tr>


          <tr>
            <td><?php echo $entry_rating; ?></td>
            <td><b class="rating"><?php echo $entry_bad; ?></b>&nbsp;
              <?php if ($rating == 1) { ?>
              <input type="radio" name="rating" value="1" checked />
              <?php } else { ?>
              <input type="radio" name="rating" value="1" />
              <?php } ?>
              &nbsp;
              <?php if ($rating == 2) { ?>
              <input type="radio" name="rating" value="2" checked />
              <?php } else { ?>
              <input type="radio" name="rating" value="2" />
              <?php } ?>
              &nbsp;
              <?php if ($rating == 3) { ?>
              <input type="radio" name="rating" value="3" checked />
              <?php } else { ?>
              <input type="radio" name="rating" value="3" />
              <?php } ?>
              &nbsp;
              <?php if ($rating == 4) { ?>
              <input type="radio" name="rating" value="4" checked />
              <?php } else { ?>
              <input type="radio" name="rating" value="4" />
              <?php } ?>
              &nbsp;
              <?php if ($rating == 5) { ?>
              <input type="radio" name="rating" value="5" checked />
              <?php } else { ?>
              <input type="radio" name="rating" value="5" />
              <?php } ?>
              &nbsp; <b class="rating"><?php echo $entry_good; ?></b>
              <?php if ($error_rating) { ?>
              <span class="error"><?php echo $error_rating; ?></span>
              <?php } ?></td>
          </tr>

         <?php if (isset($karma) && count($karma)>0) { ?>
          <tr>
            <td><?php echo $this->language->get('entry_karma'); ?></td>
            <td>
            <table style="text-align: center;">
            <tr>
            <td><?php echo $this->language->get('entry_karma'); ?></td>
            <td><?php echo $this->language->get('entry_karma_rate_count'); ?></td>
            <td><?php echo $this->language->get('entry_karma_rate_delta_blog_plus'); ?></td>
            <td><?php echo $this->language->get('entry_karma_rate_delta_blog_minus'); ?></td>
            </tr>
            <tr>
            <?php foreach ($karma_all as $num => $karma_all_val) { ?>


            <td><input type="text" name="karma_delta" value="<?php if (isset($karma_all_val['rate_delta'])) { echo $karma_all_val['rate_delta']; } ?>" disabled="disabled" size="3"></td>
            <td><input type="text" name="karma_rate_count" value="<?php if (isset($karma_all_val['rate_count'])) { echo $karma_all_val['rate_count']; } ?>" disabled="disabled" size="3"></td>
            <td><input type="text" name="karma_rate_delta_blog_plus" value="<?php if (isset($karma_all_val['rate_delta_blog_plus'])) { echo $karma_all_val['rate_delta_blog_plus']; } ?>" disabled="disabled" size="3"></td>
            <td><input type="text" name="karma_rate_delta_blog_minus" value="<?php if (isset($karma_all_val['rate_delta_blog_minus'])) { echo $karma_all_val['rate_delta_blog_minus']; } ?>" disabled="disabled" size="3"></td>

           <?php } ?>
           </tr>
           </table>
			</td>
          </tr>
           <?php } ?>





<?php   if (isset($fields) && count($fields)>0) { foreach   ($fields as $af_name => $field) { ?>
		 <tr>
            <td><?php echo html_entity_decode($field['field_description'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8'); ?></td>
            <td>
             <textarea name="af[<?php echo $field['field_name']; ?>]" cols="40" rows="1"><?php if (isset($field['value'])) echo $field['value'];  ?></textarea>
            </td>
          </tr>
<?php  }  } ?>



          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="status">
                <?php if ($status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>


        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
$('input[name=\'record\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/record/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.record_id
					}
				}));
			}
		});
	},
	select: function(event, ui) {
		$('input[name=\'record\']').val(ui.item.label);
		$('input[name=\'record_id\']').val(ui.item.value);

		return false;
	}
});

</script>

<script type="text/javascript">
	$('#bbtext').wysibb({
	  img_uploadurl:		"view/javascript/wysibb/iupload.php",
      buttons: 'bold,italic,underline,|,img,video,link,|,fontcolor,fontsize,|'
	});
   $('span.powered').hide();
</script>

<script type="text/javascript" src="view/javascript/blog/timepicker/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="view/javascript/blog/timepicker/localization/jquery-ui-timepicker-<?php echo $config_language; ?>.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('.datetime').datetimepicker({
		dateFormat: 'yy-mm-dd',
		timeFormat: 'HH:mm:ss'
	});
});
</script>



<?php echo $footer; ?>