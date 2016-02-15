<?php /* robokassa metka */
// Heading
$_['heading_title']      = 'RoboKassa 20 methods';

// Text 
$_['text_success']       = 'Success: You have modified RoboKassa account details!';
$_['text_robokassa']	 = '<a onclick="window.open(\'http://robokassa.ru/en/\');"
><img src="view/image/payment/robokassa.png" 
alt="Robokassa 20 methods" 
title="Robokassa 20 methods" style="border: 1px solid #EEEEEE;" /></a>';
      
// Entry
$_['entry_geo_zone']     = 'Geo Zone:';
$_['entry_status']       = 'Status:';
$_['entry_sort_order']   = 'Sort Order:';

$_['tab_general'] 		 = 'General';
$_['tab_support'] 		 = 'Support';
$_['tab_instruction'] 	 = 'Instruction';

$_['entry_dopcost'] 		 = 'Additional Fee to order:
<br><span class="help">If you want to set Discount - enter the minus sign before number <br>
Dont forget Install and Enable Total Extension in "Extensions" => "Order totals" => "Robokassa 20 methods".</span>';
$_['text_dopcost_int'] 		 = 'Fixed cost';
$_['text_dopcost_percent'] 	 = 'Percent of the order';
$_['entry_dopcostname'] 	 = 'Order Total header';


$_['entry_paynotify']   = 'Notify admin when order was paid:';
$_['entry_paynotify_email']   = 'Admin E-mail for notification:';

$_['entry_interface_language'] = 'Robokassa page language:';
$_['entry_interface_language_ru'] = 'Russian';
$_['entry_interface_language_en'] = 'English';
$_['entry_interface_language_detect'] = 'Site language';
$_['entry_interface_language_notice'] = 'Language of the page, where the user makes a payment order';

$_['entry_sms_status'] 	 = 'SMS notification about Robokassa payment';
$_['entry_sms_instruction'] = 'SMS notification works only if installed module 
<a href="http://opencartforum.ru/files/file/1103-sms-%D0%BE%D0%BF%D0%BE%D0%B2%D0%B5%D1%89%D0%B5%D0%BD%D0%B8%D1%8F-%D0%BA%D0%BB%D0%B8%D0%B5%D0%BD%D1%82%D1%83-%D0%BF%D1%80%D0%B8-%D1%81%D0%BC%D0%B5%D0%BD%D0%B5-%D1%81%D1%82%D0%B0%D1%82%D1%83%D1%81%D0%B0-%D0%B8-%D0%BD%D0%BE%D0%B2%D0%BE%D0%BC-%D0%B7/" 
target=_blank>SMS оповещения клиенту при смене статуса и новом заказе</a> 
and if options on "System" => "Settings" => "SMS" tab => "SMS Gateway" is entered';
$_['entry_sms_phone'] 	 = 'Phone for SMS-notification
<br><i>Format: 7926xxxxxxx</i>';

$_['entry_sms_message'] 	 = 'SMS message<br><br>
Available Tags:<br>
{ID} - order ID<br>
{DATE} - order date<br>
{TIME} - order time<br>
{SUM} - order total<br>
{FIRSTNAME} - Customer firstname<br>
{LASTNAME} - Customer lastname<br>
{PRODUCTS} - order products list<br>
{PHONE} - Customer phone';

$_['entry_sms_message_default'] = 'Order #{ID} was paid - {SUM}. Products: {PRODUCTS} . Customer: {FIRSTNAME} {LASTNAME} {PHONE}';


/* kin insert metka: d1 */
$_['entry_robokassa_desc'] = 'Order description (will appear on robokassa.ru after checkout 
<a href="../image/data/robokassa_desc.gif" target="_blank">Example</a>)<br><br>{number} - Order ID<br>
{siteurl} - site URL';
$_['entry_robokassa_desc_default'] = 'Order #{number} in {siteurl}';
/* end kin metka: d1 */

$_['entry_order_comment'] = 'Order mail comment:';
$_['entry_order_comment_notice']  = 'Tags:<br>
{link} - link for robokassa payment.';
$_['text_order_comment_default'] = 'Link for robokassa payment {link} (if you dont make payment before)';

$_['entry_commission'] = 'Who will be pay Robokassa commission? <br><i>(option is actual only if you have individual (not corporate) account in Robokassa)</i>';
$_['text_commission_shop'] = 'Seller (this mean - you)';
$_['text_commission_customer'] = 'Customer (will be paid Order price + Robokassa commission)';
$_['text_commission_j'] = 'I have Robokassa corporate account';

$_['text_frame']	  = 'Информация ниже отображается во фрэйме. Список вопросов периодически пополняется, так же здесь могут появляться объявления.';
$_['text_contact']	  = '<p>Если Вы не нашли ответов на возникшие у Вас вопросы - свяжитесь с разработчиком модуля:</p>
			<p>Скайп: kin154</p>
			<p>E-mail: internetstartru@gmail.com</p>			
			<p>Я бываю на работе как правило с 12 до 20 по мск, по будням. По выходным - иногда тоже бываю на связи.</p>
			<p>---------------<br>
			С уважением,<br>
			программист Константин Петров.</p>
			<p>Приятной работы!</p>';
$_['entry_default_language'] = 'Robokassa page language, if the language is not English and Russian (ROBOKASSA supports only these two languages​​)';
$_['entry_default_language_ru'] = 'Russian';
$_['entry_default_language_en'] = 'English';
$_['entry_default_language_notice'] = 'This option sets the language of the page Robokassa for situations where the user has used a language other than English and Russian.';

$_['entry_preorder_status'] = 'Order Status after confirmation:';
$_['entry_order_status'] = 'Order Status after payment:';

$_['entry_shop_login']   = 'Shop Login (your login in robokassa.ru):';
$_['entry_order_status2'] = 'Order status which the buyer refused to pay:';

$_['entry_test_mode'] 	 = 'Test Mode:';
$_['entry_password1'] 	 = 'Password #1:';
$_['entry_password2'] 	 = 'Password #2:';

/* kin insert metka: a4 */
$_['entry_currency'] 	 = 'Robokassa order Currency:';
$_['text_currency_notice'] 	 = '';
/* end kin metka: a4 */

$_['text_default']           = 'Default';
$_['text_image_manager']     = 'Image Manager';
$_['text_browse']            = 'Browse Files';
/* start update: a3 */
$this->data['text_robokassa_method'] = 'ROBOKASSA (all methods)';
/* end update: a3 */

/* start update: a1 */
$_['text_saved'] 	 = '<b><font color=green>Password is saved</font></b>';
$_['entry_icons'] 	 = 'Show icons';
$_['text_mode_notice'] 	 = '';
/* end update: a1 */

$_['text_img_notice'] 	   = 'Note: <i>payment icons are located in image/robokassa_icons directory</i>';

$_['notice'] 	 		 = 'Module settings should match the settings in your account on the site robokassa.ru <a href="https://www.roboxchange.com/Environment/Partners/Login/Merchant/Administration.aspx" target=_blank>at the link</a>.';

/* start update: a2 */
$this->data['entry_other_methods_status'] = 'Show general method<br>
<i>(user will be redirected to page where he can choose one payment method from list methods)</i>';

$this->data['entry_other_methods'] = 'Name of General method';
/* end update: a2 */

$_['entry_result_url']	  = 'Result URL:';
$_['entry_result_method'] = "Result URL Method:";

$_['text_clear']             = 'Clear';
$_['entry_success_url']    = "Success URL:";
$_['entry_success_method'] = "Success URL Method:";

$_['entry_fail_url']    = "Fail URL:";
$_['entry_fail_method'] = "Fail URL Method:";

$_['select_currency']	  = 'Choose currency';
$_['entry_no_robokass_methods']	  = 'To Shop Login you chose no method of payment. Perhaps you entered an incorrect Login';

$_['methods_col1'] = 'Displayed payment method';
$_['methods_col2'] = 'Currency by default after redirect to robokassa <br>(The customer will be able to change currency)';
$_['methods_col3'] = 'Icon';
$_['text_payment'] = 'Modules'; 
// Error

$_['entry_log'] = 'Debug Mode';

$_['entry_confirm_status']  = 'When the order is confirmed:';
$_['entry_confirm_status_notice'] = 'At the time of confirmation, the order appears in the admin section "Sales" -> "Orders", and the buyer receives the confirm e-mail.';

$_['entry_confirm_status_before'] = 'When buyer click to button "Confirm Order" in the last page of Checkout';
$_['entry_confirm_status_after']  = 'When the buyer pays order';

$_['entry_confirm_notify']  = 'Send a notify email to the buyer when the order will be paid';
$_['entry_confirm_comment'] = 'Comment for notify email';
$_['text_confirm_comment_default'] = 'Order was successfully paid. Our manager will contact you.';

$_['error_robokassa_password_symbols'] = 'The password must consist of Latin characters or/and numbers only';


$_['error_permission']   = 'Warning: You do not have permission to modify payment RoboKassa!';
$_['error_robokassa_shop_login'] = 'Please specify Shop Login';
$_['error_robokassa_password1']	  = 'Please enter Password1';
$_['error_robokassa_password2']	  = 'Please enter Password2';
$_['error_rub']	  = 'Ruble currency must be added to the list in System => Localisation => Currencies. Currency Code must be a RUB or RUR';
$_['button_save_and_go']	  = 'Save and go';
$_['button_save_and_stay']	  = 'Save and stay';

$_['entry_no_methods']	  = 'Methods will appear after you enter Shop Login';
$_['entry_methods']	  = 'Displayed payment methods';

?>