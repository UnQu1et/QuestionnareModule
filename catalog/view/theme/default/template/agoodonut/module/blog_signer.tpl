<?php if (isset($signer_code) && $signer_code=='customer_id') { ?>
<div class="form_customer_signer">

<?php echo $this->language->get('error_customer_id'); ?>

      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <div class="form_customer_content">
			<a href="#" style="float: right;" class="hrefajax" onclick="$('.form_customer_signer').hide('slide', {direction: 'up' }, 'slow'); return false;"><?php echo $this->language->get('hide_block'); ?></a>

         <!-- <p><?php echo $text_i_am_returning_customer; ?></p> -->
          <b><?php echo $entry_email; ?></b><br />
          <input type="text" name="email" value="<?php echo $email; ?>" />
          <br />
          <br />
          <b><?php echo $entry_password; ?></b><br />
          <input type="password" name="password" value="<?php echo $password; ?>" />
          <br />
          <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a><br />
          <br />
          <input type="submit" value="<?php echo $button_login; ?>" class="button" />
          <a href="<?php echo $register; ?>" class="marginleft10"><?php echo $this->language->get('error_register'); ?></a>
          <?php if ($redirect) { ?>
          <input type="hidden" name="redirect" value="<?php echo $redirect; ?>#comments_signer" />
          <?php } ?>
        </div>
      </form>



</div>
<?php } ?>

<?php if (isset($signer_code) && $signer_code=='record_id') { ?>
<div class="warning">
<?php echo $this->language->get('error_record_id'); ?>
</div>
<?php } ?>

<?php if (isset($signer_code) && $signer_code=='no_signer') { ?>
<div class="warning">
<?php echo $this->language->get('error_no_signer'); ?>
</div>
<?php } ?>

<?php if (isset($signer_code) && $signer_code=='set') { ?>
<div class="success">
<?php echo $this->language->get('success_set'); ?>
</div>
<?php } ?>

<?php if (isset($signer_code) && $signer_code=='remove') { ?>
<div class="success">
<?php echo $this->language->get('success_remove'); ?>
</div>
<?php } ?>
