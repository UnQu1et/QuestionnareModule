<?php echo $header; ?>

<style type="text/css">
.ems_calc {color: #000; background-color: #fff; border: 1px dotted #bebebe; text-align: left; padding: 5px;}
hr {border: 0px; border-bottom: 1px dotted #999; }
</style>

<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script language="javascript" type="text/javascript">
<!--
function test_calc() 
{	
	var link = 'http://<?php echo $_SERVER['HTTP_HOST']; ?>/admin/controller/shipping/ems_ajax/';
	var form = document.getElementById('test_calc-form');
	$("#ytr").html("<b>Рассчитываю...</b>");
	
	$.post(link + "ems_ajax.php", { city_from: form.city_from.value, city_to: form.city_to.value, weight: form.weight.value, val: form.val.value }, function(data)
	{
	$("#ytr").html('Результат:');
	$("#yt").html('<b>' + data + '</b>');
	});	

	
};
//-->
</script>

<script type="text/javascript">
//Вставка BB тегов
function text_add(idd, str1)
{
obj = document.getElementById(idd);
 obj.focus(); 

 //IE
 if(document.selection) { var s = document.selection.createRange(); if(s.text) { s.text = str1; s.select(); } else { s.text = str1 + s.text; s.select();} return true; }
 
 // Opera, FireFox
 else if (typeof(obj.selectionStart) == "number")
 {
   var start = obj.selectionStart;
   tmp=obj.value.substr(start);
   obj.value = obj.value.substr(0, start) + str1;
   tmpsel = obj.value.length - str1.length;
   tmpscroll=obj.scrollHeight;
   cnt = obj.value.split(/[\r\n]/g).length;
   obj.value+=tmp;
   obj.selectionStart = obj.selectionEnd = tmpsel;
   if(cnt>obj.rows)obj.scrollTop = tmpscroll;
 return true;
 }
 return false;
}
</script>


<div id="content">

  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><?php echo $heading_title; ?>, <font color=red>версия 5.5a</font> (разработчик: Эльхан Исаев)</h1>

      <div class="buttons">
	<a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a>
	<a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a>
      </div>

</div>


    <div class="content">

      <div class="vtabs">
	<a href="#ems_1">Основное</a>
	<a href="#ems_2">Дополнительно</a>
	<a href="#ems_3">Описание</a>
	<a href="#ems_4">Шаблоны</a>
	<a href="#ems_5">Калькулятор</a>
	<a href="#ems_6">О модуле</a>
	<a href="#ems_7">Благодарности и тестеры</a>
      </div>

      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
       

<div id="ems_1" class="vtabs-content">
<table class="form">

<tr>
<td class="center" colspan="8">
<div class="ems_calc">
<b><font color=darkgreen>Проверка системы:</font></b><br />
<p>
<?php
if  (!in_array  ('curl', get_loaded_extensions())) 
echo '1. <b>Библиотека cURL:</b> <font color=red>не подключена и не доступна</font><br>'; else echo '1. <b>Библиотека cURL:</b> <font color=green>включена и доступна</font><br>';
if  (!in_array  ('json', get_loaded_extensions())) 
echo '2. <b>Библиотека JSON:</b> <font color=red>не подключена и не доступна</font><br>'; else echo '2. <b>Библиотека JSON:</b> <font color=green>включена и доступна</font><br>';
if  (!in_array  ('mbstring', get_loaded_extensions())) 
echo '3. <b>Библиотека MBSTRING:</b> <font color=red>не подключена и не доступна</font><br>'; else echo '3. <b>Библиотека MBSTRING:</b> <font color=green>включена и доступна</font><br>';
if  (!in_array  ('iconv', get_loaded_extensions())) 
echo '4. <b>Библиотека ICONV:</b> <font color=red>не подключена и не доступна</font><br>'; else echo '4. <b>Библиотека ICONV:</b> <font color=green>включена и доступна</font><br>';

?>
</p>
</div>
</td>
</tr>

	<tr>
	<td>Название метода:</td> <td><input type="text" name="ems_mname" value="<?php echo $ems_mname; ?>"></td>
	</tr>

        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="ems_status">
              <?php if ($ems_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>

	<tr>
          <td><?php echo $entry_max_weight; ?></td>
          <td><input type="text" name="ems_max_weight" value="<?php echo $ems_max_weight; ?>" size="5" readonly /></td>
        </tr>

        <tr>
          <td><?php echo $entry_sort_order; ?></td>
          <td><input type="text" name="ems_sort_order" value="<?php echo $ems_sort_order; ?>" size="1" /></td>
        </tr>



<tr>
<td>Город (с настроек сайта):</td> <td><b><?php echo $mycity; ?></b>*</td>
<td><small>* <font color=red>город (с настроек сайта) будет, пунктом отправления, если пункт отправления не выбран ниже!</font></small></td>
</tr>

<tr>
<td><?php echo 'Пункт отправления'; ?></td>
<td>
			<?php if ($locations) { ?>
				<select name="ems_city_from">
					<option value="0"><?php echo 'Не выбрано:'; ?></option>
					<?php foreach($locations AS $location) { ?>
						<option value="<?php echo $location['value']; ?>" <?php if ($location['value'] == $ems_city_from) echo 'selected'; ?>>
<?php 
    

$rez = mb_convert_case($location['name'], MB_CASE_TITLE, 'UTF-8');
$rez = str_replace(array('Район', 'Край', 'Область', 'Автономный Округ', 'Автономная Область', 'Республика', 'Ао'), array('район', 'край', 'область', 'автономный округ', 'автономная область', 'республика', 'АО'), $rez);    
echo $rez;
?>
</option>
					<?php } ?>
				</select>
			<?php } else { ?>
				<?php echo 'Нет соединения с EMS'; ?>
				<input type="hidden" name="ems_city_from" value="<?php echo $ems_city_from; ?>" />
			<?php } ?>
</td>
</tr>

</table>
</div>


<div id="ems_2" class="vtabs-content">
<table class="form">


        <tr>
          <td>Разрешить отправку внутри региона?</td>
          <td><select name="ems_in">
              <?php if ($ems_in) { ?>
              <option value="1" selected="selected">Да</option>
              <option value="0">Нет</option>
              <?php } else { ?>
              <option value="1">Да</option>
              <option value="0" selected="selected">Нет</option>
              <?php } ?>
            </select></td>
        </tr>

	<tr>
        <td>Добавлять дней к периоду доставки:</td> <td><input type="text" name="ems_plus" value="<?php echo $ems_plus; ?>" size="1"></td>
	</tr>

	<tr>
        <td>Сумма, добавляемая за доставку (к каждому товару в чеке):</td> <td><input type="text" name="ems_dopl" value="<?php echo $ems_dopl; ?>" size="3"></td>
	</tr>

	<tr>
        <td>Вес, добавляемый к суммарному (граммы):</td> <td><input type="text" name="ems_dopl_ves" value="<?php echo $ems_dopl_ves; ?>" size="6"></td>
	</tr>

        <tr>
          <td>Разрешить объявление ценности (объявляется на товары не более 50000 руб.)?</td>
          <td><select name="ems_ob">
              <?php if ($ems_ob) { ?>
              <option value="1" selected="selected">Да</option>
              <option value="0">Нет</option>
              <?php } else { ?>
              <option value="1">Да</option>
              <option value="0" selected="selected">Нет</option>
              <?php } ?>
            </select></td>
        </tr>

</table>
</div>



<div id="ems_3" class="vtabs-content">
<table class="form">


<tr>
<td class="center" colspan="8">
<b><font color=green>Это будет отображено только под методом EMS (в способах доставки):</font><b>
</td>
</tr>

        <tr>
          <td>Выводить описание о EMS?</td>
          <td><select name="ems_desc">
              <?php if ($ems_desc) { ?>
              <option value="1" selected="selected">Да</option>
              <option value="0">Нет</option>
              <?php } else { ?>
              <option value="1">Да</option>
              <option value="0" selected="selected">Нет</option>
              <?php } ?>
            </select></td>
        </tr>




        <tr>
	<td class="center" colspan="8">Текст описания о EMS:*<br><small>*выведется при включении опции выше, если текста тут не будет, выведется дефолтный текст EMS (как на оф.сайте.)  </small><br>
	<textarea name="ems_description" id="ems_description"><?php echo $ems_description; ?></textarea>
	</td>
        </tr>

<script type="text/javascript">
var ckeditor = CKEDITOR.replace('ems_description');
</script>


</table>
</div>

<div id="ems_4" class="vtabs-content">
<table class="form">

<tr>
<td class="center" colspan="8">
<br /><b><font color=red>Внимание! Следующие шаблоны будут фиксироваться и в  чеке:</font><b>
</td>
</tr>


        <tr>
	<td class="center" colspan="8">
<font color=green>Шаблон чека и отображения в способах доставки (внутренний)*</font>
<br />
<div class="ems_calc">
Вставить шаблон:
<br />
<input type="button" onclick="text_add('ems_vid','%from%')" value="откуда отпр.">
<input type="button" onclick="text_add('ems_vid','%to%')" value="куда отпр.">
<input type="button" onclick="text_add('ems_vid','%ves%')" value="вес">
<input type="button" onclick="text_add('ems_vid','%mind%')" value="мин. кол-во дн.">
<input type="button" onclick="text_add('ems_vid','%maxd%')" value="макс. кол-во дн.">
</div>
<small><b>*для правильной работы модуля используйте выражения дней только из этих видов: "дня, дней, дн."</b> </small>
<br />
<textarea id="ems_vid" name="ems_vid" cols="130" rows="3"><?php echo $ems_vid; ?></textarea>
	</td>
        </tr>

        <tr>
	<td class="center" colspan="8">
<font color=purple>Шаблон чека и отображения в способах доставки (международный)</font>
<br />
<div class="ems_calc">
Вставить шаблон: <br />
<input type="button" onclick="text_add('ems_vid_out','%from%')" value="страна, откуда отпр.">
<input type="button" onclick="text_add('ems_vid_out','%to%')" value="страна, куда отпр.">
<input type="button" onclick="text_add('ems_vid_out','%from_city%')" value="город, откуда отпр.">
<input type="button" onclick="text_add('ems_vid_out','%to_city%')" value="город, куда отпр.">
<input type="button" onclick="text_add('ems_vid_out','%ves%')" value="вес">
</div>
<br />
	<textarea id="ems_vid_out" name="ems_vid_out" cols="130" rows="3"><?php echo $ems_vid; ?></textarea>
	</td>
        </tr>

</table>
</div>
</form>


<div id="ems_5" class="vtabs-content">
<table width="100%" class="ems_calc">
<tr>

<td><b>Тестовый калькулятор EMS:</b> <br><br><br> Пункт отправления: </td>

<td>
<form method=POST id="test_calc-form" name="test_calc-form" action="">
<?php if ($locations) { ?>		
<br><br><br><select name="city_from">
<option value="0"><?php echo 'Не выбрано:'; ?></option>
<?php foreach($locations AS $location) { ?>
<option value="<?php echo $location['value']; ?>" <?php if ($location['value'] == $ems_city_from) echo 'selected'; ?>>
<?php 
$rez = mb_convert_case($location['name'], MB_CASE_TITLE, 'UTF-8');
$rez = str_replace(array('Район', 'Край', 'Область', 'Автономный Округ', 'Автономная Область', 'Республика', 'Ао'), array('район', 'край', 'область', 'автономный округ', 'автономная область', 'республика', 'АО'), $rez);    
echo $rez;
?>
</option>
<?php } ?>
</select>
<?php } else { ?>
<input type="hidden" name="city_from" value="" />
<?php } ?>
</td>
</tr>

<tr>
<td>  Пункт доставки: </td>

<td>
<?php if ($locations) { ?>
<select name="city_to">
<option value="0"><?php echo 'Не выбрано:'; ?></option>
<?php foreach($locations AS $location) { ?>
<option value="<?php echo $location['value']; ?>" <?php if ($location['value'] == $ems_city_from) echo 'selected'; ?>>
<?php 
$rez = mb_convert_case($location['name'], MB_CASE_TITLE, 'UTF-8');
$rez = str_replace(array('Район', 'Край', 'Область', 'Автономный Округ', 'Автономная Область', 'Республика', 'Ао'), array('район', 'край', 'область', 'автономный округ', 'автономная область', 'республика', 'АО'), $rez);    
echo $rez;
?>
</option>
<?php } ?>
</select>
<?php } else { ?>
<input type="hidden" name="city_to" value="" />
<?php } ?>
</td>
</tr>

<tr>
<td>Вес товара:</td>
<td><input type="text" name="weight" value="" size="1" /> кг.</td>
</tr>

<tr>
<td>Объявить ценность:</td>
<td><input type="text" name="val" size="3"></td>
</tr>

<tr>
<td><span id="ytr">Результат:</span></td>
<td><div id='yt'>--</div></td>
</tr>
	
<script language="javascript" type="text/javascript">	
<!--
$(function(){

	$('#test_calc-form').submit(function() {
	  test_calc();
	  return false;
	});

});

//-->
</script>


<tr>
<td>
<br><input type="submit" name="submit" value="Рассчитать">
</form>
</td>
</tr>
</table>

</div>


<div id="ems_6" class="vtabs-content">
<table class="form">

        <tr>
        <td class="center" colspan="8">
	Модуль: <b>EMS Почта России</b> <br>
	Версия: <b><font color=red>5.5a</font></b> <br>
	Автор и разработчик: Эльхан Исаев a.k.a. dj-avtosh (дополнения и исправления от SFR)<br>
	ГитХаб: <strong><a target="_blank" href="https://github.com/ocStore">Russian Project to optimize OpenCart for Russian realities and for rewriting some features</a></strong>
	<br />Поддержка: <strong><a target="_blank" href="http://opencartforum.ru/topic/8624-">Обсуждения и новые версии на opencartforum.ru</a></strong>

	<br>
	<br><b>Что умеет модуль:</b> <br>
	1. Расчет стоимости доставки <br>
	- внутренний <br>
	- международний <br>
	- добавление суммы к конечной (к каждому товару в чеке) <br>
	- выбирает города с сайта EMS <br>
	2. Определение сроков доставки <br>
	- добавление дней к конечному периоду <br>
	3. Расчет объявленной ценности <br>
	- +1% от стоимости каждого товара к цене <br>
	- учитывает каждый товар ценой менее 50000 руб. <br>
	4. Шаблоны способов доставки <br>
	- на внутренний <br>
	- на международний <br>
	5. Online-калькулятор EMS<br>
	- рассчет с объявленной ценностью и без<br>
	6. Мультивалютность<br>
	- конфертация в выбранные валюты<br>
	7. Описание метода<br>
	- возможность описания метода EMS (во всех деталях)<br>
	8. Проверка системы<br>
	- проверка нужных подключенных модулей<br>
	<br>
	<br>
	



</table>
</div>

<div id="ems_7" class="vtabs-content">
<table class="form">

<b><i>Благодарю за тестирование и идеи:</i></b> shoma, mva, gemchug74, Alexey1, netalert.
<br> И всем остальным пользователям http://opencartforum.ru за критику и пожелния.

<br><br> Благодарности автору: <br>
<b>QIWI:</b> 9241133333 <br>
<b>Пополнив баланс мобильного телефона:</b> +79241133333 (Мегафон Дальний Восток) <br>
<b>Пополнив баланс мобильного телефона:</b> +79143130111 (МТС) <br>

	<br>
	Стартовая дата: 14.04.12* <br> 
	<small>
	*дополнения от 21.04.12, 
	*дополнения от 27.04.12, 
	*дополнения от 01.05.12, 
	*дополнения от 11.05.12, 
	*дополнения от 14.05.12,
	*дополнения от 21.06.12,
	*дополнения от 29.07.12,
	*дополнения от 30.07.12
	</small> 

	</td>
        </tr>

</table>
</div>

      
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('.vtabs a').tabs(); 
//--></script> 
<?php echo $footer; ?> 