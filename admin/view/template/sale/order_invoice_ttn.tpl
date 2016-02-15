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

  <table class="store" style="margin-bottom: 0px;">
    <tr>
      <td valign="top" width="80%"><table>


          <tr>
            <td colspan="2"><b>Общество с ограниченной ответственностью   «Моя фирма»,ИНН ХХХХХХХХХ,КПП ХХХХХХХХ, 115093,Москва г , ул. Ленина, дом ХХ, офис ХХ</b></td>
          </tr>
          <tr>
            <td valign="top" width="13%">Грузополучатель:</td>
            <td><?php echo $order['payment_address']; ?></td>
          </tr>
          <tr>
            <td valign="top">Поставщик:</td>
            <td>Общество с ограниченной ответственностью   «Моя фирма», 115093,Москва г , ул. Ленина, дом ХХ, офис ХХ</td>
          </tr>
          <tr>
            <td valign="top">Плательщик:</td>
            <td><?php echo $order['payment_address']; ?></td>
          </tr>
          <tr>
            <td >Основание:</td>
            <td>___________________________________________________</td>
          </tr>

        </table></td>
       <td align="right" valign="top"><table>
          <tr>
            <td style="line-height: 0.9;">
		<br />
	Форма по ОКУД<br />
	по ОКПО<br />
	Форма по ОКУД<br />
	Вид деятельности по ОКДП<br />
	по ОКПО<br />
	по ОКПО<br />
	по ОКПО<br />
	номер<br />
	дата<br />
	номер<br />
	дата<br />
	Вид операции

 </td>
        <td style="line-height: 0.9;" align="center" width="40%">

	Код<br />
	0330212<br />
	<br />
	<br />
	<br />
	<br />
	<br />
	<br />
	<br />
	<br />
	<br />
	<br />
	

 </td>
      </tr>
        </table></td>
    </tr>
  </table>
                
<table align="center">
    <tr >
      <td ></td>
      <td >Номер документа</td>
      <td >Дата составления </td>
    </tr>
    <tr >
      <td ><b>ТОВАРНАЯ НАКЛАДНАЯ </b> </td>
      <td align="center" style="border: 2px solid #CDDDDD;"><?php echo $order['order_id']; ?></td>
      <td align="center" style="border: 2px solid #CDDDDD;"><?php echo $order['date_added']; ?></td>
    </tr>
</table>



<br />
  <table class="product" style="margin-bottom: 0px;">
    <tr class="heading">
      <td><b>№</b></td>
      <td><b>Наименование товара</b></td>
      <td align="left"><b>Единица измерения</b></td>
      <td align="left"><b>Вид упаковки</b></td>
      <td align="left"><b><?php echo $column_quantity; ?></b></td>
      <td align="left"><b>Масса брутто</b></td>
      <td align="left"><b><?php echo $column_price; ?></b></td>

      <td align="left"><b>Сумма без учета <br />НДС, руб. коп</b></td>
      <td align="left"><b>НДС</b></td>
      <td align="left"><b><?php echo $column_total; $n=0;?></b></td>
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
      <td align="center">9</td>
      <td align="center">10</td>
    </tr>
    <?php foreach ($order['product'] as $product) { ?>
    <tr>
      <td><?php echo $n=$n+1; ?></td>
      <td><?php echo $product['name']; ?>
      <td align="center">шт.</td>
      <td align="right"></td>
      <td align="center"><?php echo $product['quantity']; ?></td>
      <td align="right"></td>
      <td align="right"><?php echo $product['price']; ?></td>

      <td align="right"><?php echo $product['price']; ?></td>
      <td align="right">Без НДС</td>
      <td align="right"><?php echo $product['total']; ?></td>
    </tr>
    <?php } ?>
    <?php foreach ($order['total'] as $total) { ?>
    <tr>
      <td align="right" colspan="9"><b><?php echo $total['title']; ?>:</b></td>
      <td align="right"><?php echo $total['text']; ?></td>

    </tr>
    <?php } ?>
  </table>

<table>
    <tr >
      <td ></td>
      <td >Масса груза (нетто)  ___________________________________</td>
    </tr>
    <tr >
      <td >Всего мест _____________________________  </td>
      <td >Масса груза (брутто) ___________________________________</td>
    </tr>
</table>

<table width="100%" style="line-height: 0.9;"> 
    <tr >
      <td >
  <table width="100%">
    <tr >
      <td width="40%">Приложение (паспорта, сертификаты, и т.п.) на </td>
      <td colspan="3">______________________________ листах</td>
    </tr>
    <tr>
      <td>Всего отпущено на сумму </td>
      <td colspan="3">___________________________________________</td>
    </tr>
    <tr >
      <td>Отпуск разрешил </td>
      <td>________________</td>
      <td>________________</td>
      <td>________________</td>
    </tr>
    <tr >
      <td></td>
      <td>должность</td>
      <td>подпись</td>
      <td>расшифровка подписи</td>
    </tr>
    <tr>
      <td>Главный (старший) бухгалтер   </td>
      <td></td>
      <td>________________</td>
      <td>________________</td>
    </tr>
    <tr >
      <td></td>
      <td></td>
      <td>подпись</td>
      <td>расшифровка подписи</td>
    </tr>
    <tr >
      <td>Отпуск груза произвел </td>
      <td>________________</td>
      <td>________________</td>
      <td>________________</td>
    </tr>
    <tr >
      <td></td>
      <td>должность</td>
      <td>подпись</td>
      <td>расшифровка подписи</td>
    </tr>
  </table>
</td>
<td>
  <table width="100%">
    <tr >
      <td width="40%">По доверенности № </td>
      <td colspan="2">____________________</td>
      <td>от ________________</td>
    </tr>
    <tr>
      <td>выданной</td>
      <td colspan="3">___________________________________________</td>
    </tr>
    <tr >
      <td>Груз принял </td>
      <td>________________</td>
      <td>________________</td>
      <td>________________</td>
    </tr>
    <tr >
      <td></td>
      <td>должность</td>
      <td>подпись</td>
      <td>расшифровка подписи</td>
    </tr>
    <tr >
      <td>Груз получил </td>
      <td>________________</td>
      <td>________________</td>
      <td>________________</td>
    </tr>
    <tr >
      <td></td>
      <td>должность</td>
      <td>подпись</td>
      <td>расшифровка подписи</td>
    </tr>
  </table>
  </td>
   </tr>
 </table>

</div>
<?php } ?>
</body>
</html>
