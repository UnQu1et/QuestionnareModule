<div class="container_comments" id="container_comments_<?php echo $mark;?>_<?php echo $mark_id;?>" style="display: none;">
	<noindex>
	<div class="container_comments_vars" id="container_comments_vars_<?php echo $mark;?>_<?php echo $mark_id;?>" style="display: none">
		<div class="mark"><?php echo $mark; ?></div>
		<div class="mark_id"><?php echo $mark_id; ?></div>
		<div class="text_rollup_down"><?php echo $text_rollup_down; ?></div>
		<div class="text_rollup"><?php echo $text_rollup; ?></div>
		<div class="visual_editor"><?php echo $visual_editor; ?></div>
		<div class="sorting"><?php echo $sorting; ?></div>
		<div class="page"><?php echo $page; ?></div>
	    <div class="mylist_position"><?php echo $mylist_position; ?></div>
	    <div class="text_voted_blog_plus"><?php echo  $this->language->get('text_voted_blog_plus'); ?></div>
	    <div class="text_voted_blog_minus"><?php echo  $this->language->get('text_voted_blog_minus'); ?></div>
	    <div class="text_all"><?php echo  $this->language->get('text_all'); ?></div>
	    <div class="prefix"><?php echo $prefix;?></div>
	</div>
	</noindex>
<?php  if (isset($mycomments) && $mycomments) {  ?>

<?php
	$opendiv=0;
	foreach ($mycomments as $num => $comment) {
    	$opendiv++;
?>

<div id="comment_link_<?php  echo $comment['comment_id']; ?>" class="<?php echo $prefix;?>form_customer_pointer comment_content level_<?php  echo $comment['level']; ?>" style="overflow: hidden; ">

<div class="container_comment_vars" id="container_comment_<?php echo $mark;?>_<?php echo $mark_id;?>_<?php echo  $comment['comment_id']; ?>" style="display: none">
	<div class="comment_id"><?php echo  $comment['comment_id']; ?></div>
</div>

<div class="padding10">
<b><?php  echo $comment['author']; ?></b>
<?php if (isset($settings_widget['rating']) && $settings_widget['rating'] && $comment['rating_mark']==0 ) { ?>
<br>
<img style="border: 0px;"  title="<?php echo $comment['rating']; ?>" alt="<?php echo $comment['rating']; ?>" src="catalog/view/theme/<?php
$template = '/image/blogstars-'.$comment['rating'].'.png';
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . $template)) {
				$starpath = $this->config->get('config_template') . $template;
			} else {
				$starpath = 'default' . $template;
			}

echo $starpath;
?>">
<?php } ?>

<?php  if (isset($record_comment['karma']) && $record_comment['karma']) { ?>
<div class="voting  <?php  if ($comment['customer_delta'] < 0) echo 'voted_blog_minus';  if ($comment['customer_delta'] > 0) echo 'voted_blog_plus';?> floatright"  id="voting_<?php  echo $comment['comment_id']; ?>">
<?php if (!$comment['customer']){ ?>
<a href="#" class="textdecoration_none">
<ins  class="hrefajax customer_enter">
	<span class="blog_minus" title="<?php echo  $this->language->get('text_vote_will_reg'); ?>"></span>
	<span class="blog_plus" title="<?php echo  $this->language->get('text_vote_will_reg'); ?>"></span>
</ins>
</a>
<?php } else { ?>


					<a href="#blog_minus"  title="<?php echo  $this->language->get('text_vote_blog_minus'); ?>" class="karma_minus blog_minus comments_vote" ></a>
					<a href="#blog_plus"  title="<?php echo  $this->language->get('text_vote_blog_plus'); ?>" class="karma_plus blog_plus comments_vote" ></a>
<?php } ?>
					<div class="mark <?php  if($comment['delta']>=0) {  echo 'positive'; } else {  echo 'negative'; } ?> " >
						<span title="Всего <?php  echo $comment['rate_count']; ?>: ↑<?php  echo $comment['rate_count_blog_plus']; ?> и ↓<?php  echo $comment['rate_count_blog_minus']; ?>" class="score"><?php  if($comment['delta']>0) {  echo '+'; } ?><?php  echo sprintf("%d", $comment['delta']); ?></span>
					</div>
</div>
<?php } ?>
<br>
  <div class="com_date_added"><?php echo $comment['date_added']; ?></div>
   <div class="com_text  color_<?php  if($comment['delta']>=0) {  echo '000'; } else {  echo 'AAA'; } ?>;">
   <table style="width:100%;">
   <?php
     foreach ($comment['fields'] as  $num =>$field) {
     if($field['value']!="") {
     ?>
<tr>
<td style="width: 16px;">


     <?php
      $themeFile = $this->getThemeFile('image/'.$field['field_name'].'.png');
      if ($themeFile) {
      ?>
      <img style="border: 0px;"  title="<?php echo $field['field_description'][$this->config->get('config_language_id')]; ?>" alt="<?php echo $field['field_description'][$this->config->get('config_language_id')]; ?>" src="catalog/view/theme/<?php echo $themeFile; ?>">
     <?php } ?>

</td>

<td>
 <!--<b><ins class="color_entry_name"><?php echo $field['title']; ?></ins></b><br>-->
 <ins class="field_title"><?php echo $field['field_description'][$this->config->get('config_language_id')]; ?>:&nbsp;</ins><ins class="field_text"><?php echo $field['value']; ?></ins>
</td>
</tr>





     <?php
      }
     }
   ?>
   </table>

 <div class="bbcode-text" id="bbcode-text-<?php echo  $comment['comment_id']; ?>">
  <?php echo $comment['text']; ?>
  </div>

  </div>

  <div class="margintop10">
    <a href="#" id="comment_id_reply_<?php echo $comment['comment_id']; ?>" class="comment_reply comment_buttons hrefajax"><?php
        echo $text_reply_button;
?></a>
  </div>

<?php
        // determine the actual setting the mark rollup
        if (isset($mycomments[$num + 1]['parent_id']) && ($mycomments[$num + 1]['parent_id'] == $comment['comment_id'])) {
?>
	 <div class="floatright" >

	   <a href="#" id="rollup<?php echo $comment['comment_id']; ?>" class="hrefajax comments_rollup"><?php echo $text_rollup; ?></a>

	 </div>
<?php

        }
        // reply form the way we steal from record.tpl :)  through comment_reply js function, of course
?>
 <!-- for reply form -->
 <div class="overflowhidden width100 lineheight1 height1">&nbsp;</div>
 <div id="<?php echo $prefix;?>comment_work_<?php echo $comment['comment_id']; ?>" class="<?php echo $prefix;?>comment_work width100 margintop5">
 </div>
</div>
<div id="parent<?php echo $comment['comment_id']; ?>" class="comments_parent">
<?php
		if ($comment['flag_end']>0) {

		 if ($comment['flag_end']>$opendiv) {
		  $comment['flag_end']=$opendiv;
		 }
         //echo " Close div: ".$opendiv;
		for ($i=0; $i<$comment['flag_end']; $i++)
		{
        $opendiv--;

?>
 </div>
</div>
<?php

		}
	}
}
 // for not close div
 if ($opendiv>0 ) {
  for ($i=0; $i<$opendiv; $i++)
  {
?>
 </div>
 </div>
 <?php
   }
 }
?>


<div class="floatright displayinline"><?php  echo $entry_sorting; ?>
<!-- <select name="sorting" class="comments_sorting" onchange="$('#comment').comments(this[this.selectedIndex].value);"> -->
<select name="sorting" class="comments_sorting" onchange="$('#comment').comments(this[this.selectedIndex].value);">
    <option <?php if ($sorting == 'desc')  echo 'selected="selected"'; ?> value="desc"><?php echo $text_sorting_desc; ?></option>
    <option <?php if ($sorting == 'asc')   echo 'selected="selected"'; ?> value="asc"><?php  echo $text_sorting_asc;  ?></option>
</select>

</div>

<div class="pagination"><?php echo $pagination; ?></div>

<?php  }  else { ?>
<div class="content"><?php echo $text_no_comments; ?></div>
<?php
}
?>
</div>





