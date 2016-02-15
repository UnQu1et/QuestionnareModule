<?php 
$simple_page = '';
include $simple->tpl_header();
include $simple->tpl_static();
?>

  <div class="content"><?php echo $text_error; ?></div>
  <div class="simplecheckout-button-block buttons">
    <div class="simplecheckout-button-right right"><a href="<?php echo $continue; ?>" class="button btn"><span><?php echo $button_continue; ?></span></a></div>
  </div>

<?php include $simple->tpl_footer() ?>