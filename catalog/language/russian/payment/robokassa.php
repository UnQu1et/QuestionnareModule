<?php
// Heading
$_['heading_title'] = 'Оплата не удалась, попробуйте позже';

// Text
$_['text_message'] = '<ul>
<li><a href="%1">Оформление заказа</a></li>
<li><a href="/">Главная страница</a></li>
</ul>';

$_['text_fail']  = 'Оплата не удалась';
$_['text_basket']   = 'Корзина';
$_['text_checkout'] = 'Оформить заказ';

$_['paynotify_subject']	= 'Заказ #{order_id} был оплачен через Робокассу';
$_['paynotify_html']	= 'Номер заказа: #{order_id}<br>
						   Сумма: {out_summ}<br>
						   Дата оплаты: {pdate}<br>
						   Дата добавления заказа: {cdate}<br>
						   Клиент: {customer_name}<br>
						   <a href="{order_link}" target=_blank>Перейти на страницу заказа</a>';
?>