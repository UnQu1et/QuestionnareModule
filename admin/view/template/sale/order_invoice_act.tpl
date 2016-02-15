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

  <h1>Акт выполненных работ</h1><br /><br />
<b>Акт № <?php echo $order['order_id']; ?> от <?php echo $order['date_added']; ?></b><br /><br />
<span style="font-size:18px;" >Исполнитель:Общество с ограниченной ответственностью «Моя фирма»</span><br /><br />

Заказчик: <?php echo $order['payment_address']; ?><br /><br />


  <table class="product">
    <tr class="heading">
      <td><b>№</b></td>
      <td><b>Наименование работ,услуг</b></td>

      <td align="right"><b><?php echo $column_quantity; ?></b></td>
      <td align="right"><b>Единица измерения</b></td>
      <td align="right"><b><?php echo $column_price; ?></b></td>

      <td align="right"><b><?php echo $column_total; $n=0;?></b></td>
    </tr>

    <?php foreach ($order['product'] as $product) { ?>
    <tr>
      <td><?php echo $n=$n+1; ?></td>
      <td><?php echo $product['name']; ?>

      <td align="right"><?php echo $product['quantity']; ?></td>
      <td align="right">шт.</td>
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
  Всего наименований <?php echo $n; ?> на сумму <?php echo $total_text; ?><br />
<b><?php echo $total_2_str; ?></b><br /><br />

Вышеперечисленные товары поставлены полностью и в срок. Заказчик претензий по объему, качеству и срокам поставки не имеет.<br /><br />

 
Исполнитель: Общество с ограниченной ответственностью «Моя фирма»<br /><br />
   Заказчик: ________________________________________________<br /><br />


</div>
<?php } ?>
</body>
</html>
