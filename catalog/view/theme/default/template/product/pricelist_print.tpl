<?php if (isset($_SERVER['HTTP_USER_AGENT']) && !strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6')) echo '<?xml version="1.0" encoding="UTF-8"?>'. "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" xml:lang="<?php echo $lang; ?>">
<head>
<title><?php echo $title; ?></title>
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo str_replace('&', '&amp;', $link['href']); ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $template; ?>/stylesheet/stylesheet.css" />
<!--[if lt IE 7]>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/ie6.css" />
<script type="text/javascript" src="catalog/view/javascript/DD_belatedPNG_0.0.8a-min.js"></script>
<script>
DD_belatedPNG.fix('img, #header .div3 a, #content .left, #content .right, .box .top');
</script>
<![endif]-->
<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.3.2.min.js"></script>
</head>
<body>
<style type="text/css">
* {

    font-family: "Times New Roman", Georgia, serif;
    font-size: 14px;
}
.left {
    float: left;
}
.right {
    float: right;
}
.clear {
    clear: both;
}
.tleft {
    text-align: left;
}
.tright {
    text-align: right;
}
.tcenter {
    text-align: center;
}

#wrapper {
    margin: 10px auto;
    max-width: 800px;
}

#header {
}
#header .logo {
    text-align: left;
}
#header .info {
    text-align: right;
}
#content {
    margin: 0;
}
.plist {
    margin: 5px 0;
    border-collapse: collapse;
    border: 1px solid #aaa;
}
.plist th {
    padding: 4px;
    text-align: center;
    background-color: #efefef;
    font-size: 1em;
}
.plist tbody tr:nth-child(even) {
    background-color: #eaeaea;
}
.plist td {
    padding: 4px;
    border: 1px solid #aaa;
    font-size: 1em;
}
</style>
<!-- -->
<div id="wrapper">
   <div id="header">
        <div class="logo left">
            <?php if ($logo) { ?>
            <img src="<?php echo $logo; ?>" title="<?php echo $store; ?>" alt="<?php echo $store; ?>" />
            <br />
            <span style="text-decoration: underline;"><b><?php echo $url; ?></b></span>
            <?php } ?>
        </div>
        <div class="info right">
            <h2><?php echo $store; ?></h2>
            <span><?php echo $address; ?></span><br />
            <span><b><?php echo $email; ?></b></span><br />
            <span><?php echo $telephone; ?></span><br />
        </div>
    </div>
    <div class="clear"></div>
    <div id="content">
        <table class="plist">
          <thead>
            <tr>
              <th>№</th>
              <th>Изображение</th>
              <th style="width: 100px;">Название</th>
              <th style="width: 100px;">Модель</th>
              <th>Описание</th>
              <th style="width: 80px;">Цена</th>
              
            </tr>
          </thead>
          <tbody>
            <?php if(!empty($products)) { ?>
            <?php $count = 0; ?>
            <?php foreach($products as $product) { ?>
            <tr>
              <td align="center"><?php echo ++$count; ?></td>
              <td align="center"><img src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" /></td>
              <td align="center"><b><?php echo $product['name']; ?></b></td>
              <td align="center"><?php echo $product['model']; ?></td>
              <td class="tleft"><?php echo $product['description']; ?></td>
              <td align="center"><?php if(!$product['special']) { ?>
              <span><?php echo $product['price']; ?></span>
              <?php } else { ?>
              <span style="text-decoration: line-through;"><?php echo $product['price']; ?></span><br /><span><?php echo $product['special']; ?></span>
              <?php } ?>
              
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td colspan="7" style="width: 800px"><?php echo $text_notfound; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
window.print();
</script>
</body>
</html>