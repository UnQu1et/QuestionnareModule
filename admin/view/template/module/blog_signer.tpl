<?php if (isset($signer_code) && $signer_code=='customer_id') { ?>
<div class="warning">
<?php echo $this->language->get('error_customer_id'); ?>
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
