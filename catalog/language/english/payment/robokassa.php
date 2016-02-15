<?php
// Heading
$_['heading_title'] = 'Your order has not been paid!';

// Text
$_['text_message'] = '<p>
<ul>
<li><a href="%1">Checkout again</a></li>
<li><a href="/">Main page</a></li>
</ul></p>';

$_['text_basket']   = 'Basket';
$_['text_checkout'] = 'Checkout';
$_['text_fail']  = 'Fail';

$_['paynotify_subject']	= 'Order #{order_id} was paid through Robokassa';
$_['paynotify_html']	= 'Order ID: #{order_id}<br>
						   Total sum: {out_summ}<br>
						   Pay date: {pdate}<br>
						   Order added date: {cdate}<br>
						   Customer: {customer_name}<br>
						   <a href="{order_link}" target=_blank>Link to order in admin Panel</a>';
						   
?>