<?php echo $header; ?>
<div id="content">
 <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>

<div style="margin:5px;">
<a href="<?php echo $url_blog; ?>" class="markbutton"><div style="float: left;"><img src="view/image/blog-icon-m.png" style="" ></div><div style="float: left; margin-left: 7px; margin-top: 3px; "><?php echo $url_blog_text; ?></div></a>
<a href="<?php echo $url_record; ?>" class="markbutton"><div style="float: left;"><img src="view/image/blog-rec-m.png"  style="" ></div><div style="float: left; margin-left: 7px; margin-top: 3px; "><?php echo $url_record_text; ?></div></a>
<a href="<?php echo $url_comment; ?>" class="markbutton-active"><div style="float: left;"><img src="view/image/blog-com-m.png"  style="" ></div><div style="float: left; margin-left: 7px; margin-top: 3px; "><?php echo $url_comment_text; ?></div></a>
<a href="<?php echo $url_back; ?>" class="markbutton"><div style="float: left;"><img src="view/image/blog-back-m.png"  style="" ></div><div style="float: left; margin-left: 7px; margin-top: 3px; "><?php echo $url_back_text; ?></div></a>
</div>



      <div class="buttons" style="float:right; clear: both;"><a onclick="location = '<?php echo $insert; ?>'" class="button"><?php echo $button_insert; ?></a>
      <a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a>
      </div>
     <div style="width: 100%; overflow: hidden; clear: both; height: 1px; line-height: 1px;">&nbsp;</div>



  <div class="box">
   <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">

               <div>
                <a  onclick="$('.comment_text').toggle(); return false;">
                <?php echo $this->language->get('entry_comment_text'); ?>
                </a>
                </div>

        <table class="mytable">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>

              <td class="left" >
				<?php echo $column_comment_id; ?>
               </td>


              <td class="left"><?php if ($sort == 'pd.name') { ?>
                <a href="<?php echo $sort_record; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_record; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_record; ?>"><?php echo $column_record; ?></a>
                <?php } ?>
                </td>




              <td class="left"><?php if ($sort == 'r.author') { ?>
                <a href="<?php echo $sort_author; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_author; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_author; ?>"><?php echo $column_author; ?></a>
                <?php } ?></td>
              <td class="right"><?php if ($sort == 'r.rating') { ?>
                <a href="<?php echo $sort_rating; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_rating; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_rating; ?>"><?php echo $column_rating; ?></a>
                <?php } ?></td>

                 <td class="left">
                  <?php echo $this->language->get('column_karma'); ?>
                 </td>


              <td class="left"><?php if ($sort == 'r.status') { ?>
                <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'r.date_added') { ?>
                <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                <?php } ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>

            <tr class="filter">
              <td></td>
              <td></td>
              <td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" /></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
               <td></td>
             <td align="right"><a onclick="filter();" class="button"><?php echo $button_filter; ?></a></td>
            </tr>



            <?php if ($comments) { ?>
            <?php foreach ($comments as $comment) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($comment['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $comment['comment_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $comment['comment_id']; ?>" />
                <?php } ?></td>
              <td class="left" style="color: #999;"><?php echo $comment['comment_id']; ?></td>
              <td class="left"><a  onclick="$('#comment_text_<?php echo $comment['comment_id']; ?>').toggle(); return false;"><?php echo $comment['name']; ?></a>

              <div id="comment_text_<?php echo $comment['comment_id']; ?>" style="display: none;" class="comment_text">
              <?php echo $comment['text']; ?>
              </div>

              </td>

              <td class="left"><?php echo $comment['author']; ?></td>
              <td class="right"><?php echo $comment['rating']; ?></td>
              <td class="right"><?php
             foreach ($comment['karma_all'] as $num => $karma_all_val) { ?>


            <input type="text" name="karma_delta" value="<?php if (isset($karma_all_val['rate_delta'])) { echo (int)$karma_all_val['rate_delta']; } ?>" disabled="disabled" size="2" style="border: 1px solid <?php if (isset($karma_all_val['rate_delta']) && $karma_all_val['rate_delta']>0) { echo 'green'; } ?><?php if (isset($karma_all_val['rate_delta']) && $karma_all_val['rate_delta']<0) { echo 'red'; } ?>; ">
            <input type="text" name="karma_rate_count" value="<?php if (isset($karma_all_val['rate_count'])) { echo $karma_all_val['rate_count']; } ?>" disabled="disabled" size="2">
            <input type="text" name="karma_rate_delta_blog_plus" value="<?php if (isset($karma_all_val['rate_delta_blog_plus'])) { echo $karma_all_val['rate_delta_blog_plus']; } ?>" disabled="disabled" size="2">
            <input type="text" name="karma_rate_delta_blog_minus" value="<?php if (isset($karma_all_val['rate_delta_blog_minus'])) { echo $karma_all_val['rate_delta_blog_minus']; } ?>" disabled="disabled" size="2">

           <?php } ?>
           </td>

              <td class="left"><?php echo $comment['status']; ?></td>
              <td class="left"><?php echo $comment['date_added']; ?></td>

              <td class="right"><?php foreach ($comment['action'] as $action) { ?>
                <a href="<?php echo $action['href']; ?>"  class="markbuttono"><?php echo $action['text']; ?></a>
                <?php } ?></td>

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
</div>

<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=catalog/comment&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').attr('value');

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
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
			url: 'index.php?route=catalog/comment/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
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
		$('input[name=\'filter_name\']').val(ui.item.label);

		return false;
	}
});
//--></script>



<?php echo $footer; ?>