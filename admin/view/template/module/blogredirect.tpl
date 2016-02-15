<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Redirect to <?php echo $link; ?></title>
</head>
<body>
<form id="redirect_post" name="redirectform" action=<?php echo $link; ?> method="POST">
<?php
foreach ($post as $name=>$value) { ?>
<input type="hidden" name="<?php echo $name; ?>" value="<?php echo $value; ?>">
<?
}
?>
</form>
<script>
 document.redirectform.submit();
</script>
</body>
</html>