<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $language; ?>" xml:lang="<?php echo $language; ?>">
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<head>
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<link rel="stylesheet" type="text/css" href="view/stylesheet/invoice.css" />
</head>
<body>
<?php $iteration=1;
			foreach ($orders as $order) { 
			if ($iteration < count($orders)){?>
<div style="page-break-after: always;">
<?php }else{?>
<div>
<?php }?>
<?php $iteration++;?>

 <h1><img style="margin-right:15%;" title="" alt="" src="view/image/logo_1.png">Счет на оплату</h1>
  <table class="store">
    <tr>
      <td width="30%">Банк получателя:<br />
        ЗАО «Банк» г. Москва<br />
        
        ИНН  ХХХХХХХХХХ  КПП YYYYYYYY <br />
        Общество с ограниченной ответственностью «Моя фирма»<br />
        Получатель: <?php echo $order['payment_address']; ?>
      <td align="left" valign="top"><table>
          <tr>
            <td>БИК </td>
            <td>044525243</td>
          </tr>
          <tr>
            <td>Сч. №</td>
            <td>11111111111111111111111</td>
          </tr>

          <tr>
            <td>Сч. №</td>
            <td>22222222222222222222222</td>
          </tr>
        </table></td>
    </tr>
  </table>
                <b style="font-size: 16px;">Счет на оплату № <?php echo $order['order_id']; ?> от <?php echo $order['date_added']; ?></b>
  <table class="address">
    <tr >
      <td style="font-size: 16px;" width="30%">Поставщик:</td>
      <td ><b>Общество с ограниченной ответственностью «Моя фирма», ИНН XXXXXXXXX, КПП YYYYYYYY, 115093, Россия, Москва г , ул. Ленина, дом ХХ, офис ХХ</b></td>
    </tr>
    <tr>
      <td style="font-size: 16px;">Грузоотправитель:</td>
      <td ><b>Общество с ограниченной ответственностью «Моя фирма», ИНН XXXXXXXXX, КПП YYYYYYYY, 115093, Россия, Москва г , ул. Ленина, дом ХХ, офис ХХ</b></td>
    </tr>
    <tr>
      <td style="font-size: 16px;">Покупатель:</td>
      <td ><b><?php echo $order['payment_address']; ?></b></td>
    </tr>
    <tr>
      <td style="font-size: 16px;">Грузополучатель:</td>
      <td ><b><?php echo $order['payment_address']; ?></b></td>
    </tr>
  </table><br />
  <table class="product">
    <tr class="heading">
      <td><b>№</b></td>
      <td><b><?php echo $column_model; ?></b></td>
      <td><b><?php echo $column_product; ?></b></td>
      <td ><b><?php echo $column_quantity; ?></b></td>
      <td ><b><?php echo $column_price; ?></b></td>
      <td ><b><?php echo $column_total; $n=0;?></b></td>
    </tr>
    <?php foreach ($order['product'] as $product) { ?>
    <tr>
      <td><?php echo $n=$n+1; ?></td>
      <td><?php echo $product['model']; ?></td>
      <td><?php echo $product['name']; ?>

      <td align="right"><?php echo $product['quantity']; ?></td>
      <td align="right"><?php echo $product['price']; ?></td>
      <td align="right"><?php echo $product['total']; ?></td>
    </tr>
    <?php } ?>
    <?php foreach ($order['total'] as $total) { ?>
    <tr>
      <td align="right" colspan="5"><b><?php echo $total['title']; ?>:</b></td>
      <td align="right"><?php echo $total['text']; ?></td>
    </tr>
    <?php } ?>
  </table>
<br />
  Всего наименований <?php echo $n; ?> на сумму <?php echo $total_text; ?><br />
  <b><?php echo $total_2_str; ?></b><br /><br />
 
  <table width="100%">
    <tr >
      <td><b>Руководитель</b></td>
      <td><b>Генеральный директор</b></td>
      <td><b></b></td>
      <td align="right"><b>Иванов И.И.</b></td>
    </tr>
    <tr>
      <td><b></b></td>
      <td>должность</td>
      <td>подпись</td>
      <td align="right">расшифровка подписи</td>
    </tr>
    <tr >
      <td></td>
      <td><b>Главный бухгалтер</b></td>
      <td><b></b></td>
      <td align="right"><b>Иванов И.И.</b></td>
    </tr>
    <tr>
      <td><b></b></td>
      <td>должность</td>
      <td>подпись</td>
      <td align="right">расшифровка подписи</td>
    </tr>
  </table>

</div>
<?php } ?>
</body>
</html>
