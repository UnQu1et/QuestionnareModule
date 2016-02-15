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

  <h1>Счет-фактура</h1><br /><br />
<b>Счет-фактура № <?php echo $order['order_id']; ?> от <?php echo $order['date_added']; ?></b><br /><br />
Продавец: Общество с ограниченной ответственностью «Моя фирма»<br />
ИНН/КПП продавца: ХХХХХХХХХХХ/ХХХХХХХХХХ<br />
Грузоотправитель и его адрес: Общество с ограниченной ответственностью   «Моя фирма», 115093,Москва г , ул. Ленина, дом ХХ, офис ХХ<br />
Грузополучатель и его адрес:  <?php echo $order['payment_address']; ?><br />
К платежно-расчетному документу № <?php echo $order['order_id']; ?><br />
Покупатель: <?php echo $order['firstname'] . $order['lastname']; ?><br />
Адрес: <?php echo $order['shipping_address']; ?> <br />
ИНН/КПП покупателя:  <br />
Валюта: наименование, код Российский рубль, 643 <br /><br />


  <table class="product">
    <tr class="heading">

      <td><b>Наименование товара</b></td>
      <td ><b>Единица измерения</b></td>
      <td ><b><?php echo $column_quantity; ?></b></td>
      <td ><b><?php echo $column_price; ?></b></td>
      <td ><b>Цена за единицу<br />без НДС</b></td>
      <td ><b>Налоговая ставка</b></td>
      <td ><b>Сумма налога, <br />предъявляемая <br />покупателю</b></td>
      <td width="15%"><b><?php echo $column_total; $n=0;?></b></td>
    </tr>
    <tr >
      <td align="center">1</td>
      <td align="center">2</td>
      <td align="center">3</td>
      <td align="center">4</td>
      <td align="center">5</td>
      <td align="center">6</td>
      <td align="center">7</td>
      <td align="center">8</td>
    </tr>
    <?php foreach ($order['product'] as $product) { ?>
    <tr>

      <td><?php echo $product['name']; ?>
      <td align="right">шт.</td>
      <td align="right"><?php echo $product['quantity']; ?></td>
      <td align="right"><?php echo $product['price']; ?></td>
      <td align="right"><?php echo $product['wo_nds']; ?></td>
      <td align="right">18%</td>
      <td align="right"><?php echo $product['nds']; ?></td>
      <td align="right"><?php echo $product['total']; ?></td>
    </tr>
    <?php } ?>
    <?php foreach ($order['total'] as $total) { ?>
    <tr>
      <td align="right" colspan="7"><b><?php echo $total['title']; ?>:</b></td>
      <td align="right"><?php echo $total['text']; ?></td>

    </tr>
    <?php } ?>
  </table>
<br />

 
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
