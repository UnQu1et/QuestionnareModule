<?php
  if ($logo) {  ?>
<div>
<img src="<?php echo $logo;?>">
</div>

<?php
  }
?>
<div>
<?php echo sprintf($this->language->get('text_greeting'), $data['signer_customer']['firstname']." ".$data['signer_customer']['lastname']); ?>
<br>
<?php echo $this->language->get('text_customer')." ".$data['comment']['name']." ".$this->language->get('text_write')." ";?>
<a href="<?php echo $record_info['link'];?>"><?php echo $record_info['name'];?></a>

<?php
foreach ($data['comment'] as $par => $val) {
if ($par == 'text') {
}
}
?>
<div style="padding: 10px; background-color: #EFEFEF;border: 1px solid #DDD;">
<?php

foreach ($data['fields'] as $name =>$field) {
echo "<b>".$field['field_name'].": </b>".$field['text']."<br>";

}

echo  $data['text'];
?>
</div>
<br>
 <?php echo $this->language->get('text_rating'); ?><?php echo $data['comment']['rating']; ?>
<br>
<?php echo $data['date'];?>
<br>
 <?php echo $this->language->get('text_no_answer'); ?>
</div>