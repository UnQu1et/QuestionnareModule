<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <h2><?php echo $text_location; ?></h2>
    <div class="contact-info">
      <div class="content"><div class="left"><b><?php echo $text_address; ?></b><br />
        <?php echo $store; ?><br />
        <?php echo $address; ?></div>
      <div class="right">
        <?php if ($telephone) { ?>
        <b><?php echo $text_telephone; ?></b><br />
        <?php echo $telephone; ?><br />
        <br />
        <?php } ?>
        <?php if ($fax) { ?>
        <b><?php echo $text_fax; ?></b><br />
        <?php echo $fax; ?>
        <?php } ?>
      </div>
    </div>
    </div>
    <h2><?php echo $text_contact; ?></h2>
    <div class="content">
    <div style="width:48%; display:inline-block;">
    <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?> " style="width:100%;" />
    <br />
    <?php if ($error_name) { ?>
    <span class="error"><?php echo $error_name; ?></span>
    <?php } ?>
    <br />
    </div>
	<div style="width:48%; display:inline-block; margin-left:25px;">
    <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" style="width:100%;"/>
    <br />
    <?php if ($error_email) { ?>
    <span class="error"><?php echo $error_email; ?></span>
    <?php } ?>
    <br />
   	 </div>
    <textarea name="enquiry" placeholder="<?php echo $entry_enquiry; ?>" cols="40" rows="10" style="width: 99%; font-family: Arial;"><?php echo $enquiry; ?></textarea>
    <br />
    <?php if ($error_enquiry) { ?>
    <span class="error"><?php echo $error_enquiry; ?></span>
    <?php } ?>
    <br />
    <div style="clear:both;"></div>
 <div style="float:left; margin-top:15px; margin-right:10px; "> <input  type="text" name="captcha" value="<?php echo $captcha; ?>" placeholder="<?php echo $entry_captcha; ?>" style="min-width:300px;" /> </div>
<div style="float:left; margin-top:10px;"> <img style="display:inline;" src="index.php?route=information/contact/captcha" alt="" /></div>
   
    
    <?php if ($error_captcha) { ?>
    <span class="error"><?php echo $error_captcha; ?></span>
    <?php } ?>      
	<div class="right" style="float:right; width:auto; margin-top:15px;"><input type="submit" value="<?php echo $button_continue; ?>" class="button" /></div>

    </div> 

  </form>
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>