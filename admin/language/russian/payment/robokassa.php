<?php /* robokassa metka */
// Heading
$_['heading_title']      = 'Робокасса 20 методов';

// Text 
$_['text_success']       = 'Выполнено: Вы изменили настройки модуля RoboKassa!';
$_['text_robokassa']	 = '<a onclick="window.open(\'http://robokassa.ru/\');"
><img src="view/image/payment/robokassa.png" 
alt="Робокасса 20 методов" 
title="Робокасса 20 методов" style="border: 1px solid #EEEEEE;" /></a>';

// Entry
$_['entry_geo_zone']     = 'Географическая зона:';
$_['entry_status']       = 'Статус:';
$_['entry_sort_order']   = 'Порядок сортировки:';

$_['entry_paynotify']   = 'Уведомлять администратора об оплате заказа:';
$_['entry_paynotify_email']   = 'E-mail для уведомления администратора:';

$_['entry_robokassa_desc'] = 'Описание заказа (отображается на сайте Робокассы 
<a href="../image/data/robokassa_desc.gif" target="_blank">Пример</a>)<br><br>{number} - номер заказа<br>
{siteurl} - адрес сайта';

$_['entry_robokassa_desc_default'] = 'Заказ №{number} на {siteurl}';
 
$_['entry_commission'] = 'Кто оплачивает комиссию Робокассы<br>
(опция актуальна, только если Вы зарегистрированы в Робокассе как физ.лицо)';	

$_['text_commission_j'] = 'Я зарегистрировался в Робокассе как юр.лицо';

$_['text_commission_customer'] = 'Покупатель (то есть в заказе он видит одну сумму, а на сайте Робокассы придется платить кроме этой суммы еще и комиссию)';

$_['text_commission_shop'] = 'Продавец (покупатель оплачивает ту же сумму которую видит в заказе)';

$_['entry_order_comment'] = 'Комментарий в письме отправляемом после заказа';
$_['entry_order_comment_notice']  = 'Тэги:<br>
{link} - ссылка на оплату в Робокассе.';
$_['text_order_comment_default'] = 'Ссылка для оплаты в Робокассе (на случай если при оформлении заказа оплата не удалась) {link}';

$_['tab_general'] 		 = 'Настройки';
$_['tab_support'] 		 = 'Тех.поддержка';
$_['tab_instruction'] 	 = 'Инструкция по подключению Робокассы';


$_['entry_dopcost'] 		 = 'Дополнительная надбавка/скидка к заказу:
<br><span class="help">Чтобы задать скидку - поставьте минус перед суммой. <br>
Для того чтобы надбавка/скидка работала <b>необходимо</b> включить её в разделе "Дополнения" => "Учитывать в заказе" => "Робокасса 20 методов".
<br>Не забудьте там указать в поле "Порядок сортировки" число меньшее чем порядок сортировки в приложении "Итого"</span>';
$_['text_dopcost_int'] 		 = 'Фиксированная сумма';
$_['text_dopcost_percent'] 	 = 'Процент от стоимости заказа';
$_['entry_dopcostname'] 	 = 'Заголовок дополнительной надбавки <br><span class="help">Будет отображаться на странице оформления заказа и в письме</span>';


$_['entry_sms_status'] 	 = 'Уведомлять по SMS об оплате';
$_['entry_sms_instruction'] = 'Для того чтобы уведомление по SMS работало - Вы должны подключить модуль 
<a href="http://opencartforum.ru/files/file/1103-sms-%D0%BE%D0%BF%D0%BE%D0%B2%D0%B5%D1%89%D0%B5%D0%BD%D0%B8%D1%8F-%D0%BA%D0%BB%D0%B8%D0%B5%D0%BD%D1%82%D1%83-%D0%BF%D1%80%D0%B8-%D1%81%D0%BC%D0%B5%D0%BD%D0%B5-%D1%81%D1%82%D0%B0%D1%82%D1%83%D1%81%D0%B0-%D0%B8-%D0%BD%D0%BE%D0%B2%D0%BE%D0%BC-%D0%B7/" 
target=_blank>SMS оповещения клиенту при смене статуса и новом заказе</a> 
и указать настройки SMS-шлюза в "Система" => "Настройки" => Вкладка "SMS" => раздел "SMS Gateway"';
$_['entry_sms_phone'] 	 = 'Телефон для уведомления по SMS
<br><i>В международном формате, только цифры 7926xxxxxxx</i>';

$_['entry_sms_message'] 	 = 'SMS сообщение<br><br>
Можно использовать теги:<br>
{ID} - номер заказа<br>
{DATE} - дата заказа<br>
{TIME} - время заказа<br>
{SUM} - сумма заказа<br>
{FIRSTNAME} - имя клиента<br>
{LASTNAME} - фамилия клиента<br>
{PRODUCTS} - список товаров<br>
{PHONE} - телефон клиента';

$_['entry_sms_message_default'] = 'Оплачен заказ #{ID} на {SUM}. Товары: {PRODUCTS} . Клиент: {FIRSTNAME} {LASTNAME} {PHONE}';


$_['entry_interface_language'] = 'Язык интерфейса робокассы:';
$_['entry_interface_language_ru'] = 'Русский';
$_['entry_interface_language_en'] = 'Английский';
$_['entry_interface_language_detect'] = 'Определять в зависимости от языка на котором пользователь просматривал сайт.';
$_['entry_interface_language_notice'] = 'Данная настройка определяет язык страницы оплаты заказа на сайте Робокассы, куда пользователь попадает после того как оформил заказ на Вашем сайте и нажал на кнопку "Подтверждение заказа"';

$_['entry_default_language'] = 'Язык интерфейса робокассы, если на сайте был выбран не русский и не английский язык:';
$_['entry_default_language_ru'] = 'Русский';
$_['entry_default_language_en'] = 'Английский';
$_['entry_default_language_notice'] = 'Данная настройка устанавливает язык страницы Робокассы для ситуаций когда пользователь использовал какой-то другой язык кроме русского и английского.';

$_['entry_preorder_status'] = 'Статус заказа после подтверждения но до оплаты:';
$_['entry_order_status'] = 'Статус заказа после оплаты:';

$_['entry_order_status2'] = 'Статус заказа от которого покупатель отказался:';
$_['entry_shop_login']   = '* Идентификатор магазина на http://robokassa.ru';

$_['entry_test_mode'] 	 = 'Тестовый режим:';

$_['notice'] 	 		 = 'Настройки модуля должны соответствовать настройкам в Вашем аккаунте на сайте robokassa.ru <a href="https://www.roboxchange.com/Environment/Partners/Login/Merchant/Administration.aspx" target=_blank>по ссылке</a>.';

/* kin insert metka: a4 */
$_['entry_currency'] 	   = 'Валюта заказа робокассы:';
$_['text_currency_notice'] = 'Это валюта в которой заказ передается в Робокассу. Пользователь видит её, сразу же, после перехода из магазина в Робокассу. В большинстве случаев - это рубль. Валюта заказа может быть другой, в том случае, если Вы регистрировались в Робокассе как физ.лицо и поставили в качестве валюты вывода средств, не WMR, а WMZ или какую-то другую валюту. Сейчас робокасса отменила это и оставили только WMR, но раньше так можно было.';
/* end kin metka: a4 */

$_['text_img_notice'] 	   = 'Примечания: <i>
<div>1. Если нет иконки для метода - попробуйте найти подходящую иконку в директории image/robokassa_icons</div>
<div>2. Если иконки нет в image/robokassa_icons или они Вам не нравятся - загрузите свои.</div>
<div>3. Иконки будут отображаться без изменений размера, с тем размером который Вы загрузите.</div></i>';

/* start update: a3 */
$this->data['text_robokassa_method'] = 'Робокасса (все методы)';
/* end update: a3 */

/* start update: a1 */
$_['text_saved'] 	 = '<b><font color=green>Пароль сохранен</font></b>';
$_['entry_icons'] 	 = 'Отображение иконок';
$_['text_mode_notice'] 	 = '<b><font color=red>Внимание! если Ваш магазин уже активирован - отключите тестовый режим. А если еще не активирован - наоборот включите. Иначе способы оплаты отображаться не будут.</font></b>';
/* end update: a1 */

$_['entry_password1'] 	 = 'Пароль #1:';
$_['entry_password2'] 	 = 'Пароль #2:';


$_['entry_log'] = 'Режим отладки <br><i>(в файл <a target=_blank href="#url#">/system/logs/robokassa_log.txt будут добавляться метки</a>)</i>:';

$_['entry_confirm_status']  = 'Когда заказу присваивается статус:';
$_['entry_confirm_status_notice'] = 'В момент присвоения статуса, заказ появляется в админке в разделе "Продажи" -> "Заказы", а пользователю отправляется письмо о том что заказ принят.';

$_['entry_confirm_status_before'] = 'Сразу после того как пользователь оформил заказ и нажал на кнопку "Подтверждение заказа"';
$_['entry_confirm_status_after']  = 'Только после оплаты заказа пользователем';

$_['entry_confirm_notify']  = 'Отправлять покупателю письмо о смене статуса заказа, после того как пройдет оплата';
$_['entry_confirm_comment'] = 'Комментарий в письме подтверждающем оплату';
$_['text_confirm_comment_default'] = 'Заказ был успешно оплачен, в ближайшее время наш менеджер свяжется с Вами.';


$_['entry_result_url']	 = 'Result URL';
$_['entry_result_method'] = "Метод отсылки данных по Result URL:";

$_['entry_success_url'] = "Success URL:";
$_['entry_success_method'] = "Метод отсылки данных по Success URL:";

$_['entry_fail_url'] = "Fail URL:";
$_['entry_fail_method'] = "Метод отсылки данных по Fail URL:";

$_['entry_no_methods']	  = 'Методы появятся после того как Вы укажите Логин';
$_['entry_methods']	  = 'Отображаемые методы оплаты';

$_['text_frame']	  = 'Информация ниже отображается во фрэйме. Список вопросов периодически пополняется, так же здесь могут появляться объявления.';

$_['text_contact']	  = '<p>Если Вы не нашли ответов на возникшие у Вас вопросы - свяжитесь с разработчиком модуля:</p>
			<p>Скайп: kin154</p>
			<p>E-mail: internetstartru@gmail.com</p>			
			<p>Я бываю на работе как правило с 12 до 20 по мск, по будням. По выходным - иногда тоже бываю на связи.</p>
			<p>---------------<br>
			С уважением,<br>
			программист Константин Петров.</p>
			<p>Приятной работы!</p>
			-------------------------------------------------
			<p><b>ДРУГИЕ МОДУЛИ АВТОРА:</b></p>
			<p>Логинза - авторизация через соц.сети (платный модуль)<br>
<a href="http://opencartforum.ru/files/file/806-loginza-avtorizatciia-cherez-sotcseti-platnyi-mod/"
 target="_blank">http://opencartforum.ru/files/file/806-loginza-avtorizatciia-cherez-sotcseti-platnyi-mod/</a></p>


<p>Авторизация через Вконтакте, Facebook, Одноклассники, Twitter<br>
<a href="http://opencartforum.ru/files/file/741-avtorizatciia-cherez-vkontakte-facebook-odnoklassniki-twitte/"  
 target="_blank">http://opencartforum.ru/files/file/741-avtorizatciia-cherez-vkontakte-facebook-odnoklassniki-twitte/</a></p>

<p>EMS Почта России<br>
<a href="http://opencartforum.ru/files/file/306-ems-pochta-rossii/" 
target="_blank">http://opencartforum.ru/files/file/306-ems-pochta-rossii</a></p>

<p>Меню категорий разворачивающееся с эффектом скольжения<br>
<a href="http://opencartforum.ru/files/file/472-meniu-kategorii-razvorachivaiuscheesia-s-effektom-s/" 
target="_blank">http://opencartforum.ru/files/file/472-meniu-kategorii-razvorachivaiuscheesia-s-effektom-s/</a></p>';

$_['entry_no_robokass_methods']	  = '<b><font color=red>Способы оплаты недоступны, это могло произойти по одной из следующих причин:</font></b>
<div>1. Ваш аккаунт в Робокассе <b>НЕактивен</b>, при этом в настройках модуля <b>отключен</b> "Тестовый режим".</div>
<div>2. Наоборот, Ваш аккаунт в Робокассе <b>активен</b>, при этом в настройках модуля <b>включен</b> "Тестовый режим".</div>
<div>3. Неправильно указан логин (правильнось паролей в данном случае неважна).</div>
<div>4. Произошел сбой при соединении с Робокассой, попробуйте перезагрузить страницу.</div>
<div>5. Для данного логина в Робокассе не доступны методы оплаты.</div>
<div>6. Неправильно работает приложение. Чтобы проверить это, <br>
для не активаного аккаунта, откройте ссылку:<br>
http://test.robokassa.ru/Webservice/Service.asmx/GetCurrencies?MerchantLogin=ВАШ_ЛОГИН_В_РОБОКАССЕ&Language=ru<br> 
для активного аккаунта, откройте ссылку:<br>
http://merchant.roboxchange.com/Webservice/Service.asmx/GetCurrencies?MerchantLogin=ВАШ_ЛОГИН_В_РОБОКАССЕ&Language=ru<br>
<br>
ВАШ_ЛОГИН_В_РОБОКАССЕ замените соответственно на Ваш логин.<br><br>
Если открыв ссылку в браузере Вы увидите список способов оплаты в формате XML, то значит приложение "Робокасса 10 методов" работает неправильно.<br>
<div>В этом случае свяжитесь с разработчиком модуля: Skype - kin154, e-mail - internetstartru@gmail.com</div></div>';

$_['methods_col1'] = 'Отображаемый платёжный метод';
$_['methods_col2'] = 'Валюта по-умолчанию после перехода на сайт robokassa.ru<br>(покупатель будет иметь возможность сменить валюту)';
$_['methods_col3'] = 'Изображение (отображается если изображения включены)';

$_['text_payment'] = 'Модули';
$_['select_currency']	  = 'Выбрать валюту';

$_['text_image_manager']     = 'Менеджер изображений';
$_['text_browse']            = 'Обзор';
$_['text_clear']             = 'Очистить';
// Error
$_['error_robokassa_password_symbols']	  = 'Пароль может состоять только из латинских букв (больших и маленьких) и/или цифр';

$_['error_permission']   = 'У Вас нет прав для управления модулем RoboKassa!';
$_['error_robokassa_shop_login'] = 'Пожалуйста укажите Ваш логин на сайте http://robokassa.ru';
$_['error_robokassa_password1']	  = 'Не указан Пароль #1';
$_['error_robokassa_password2']	  = 'Не указан Пароль #2';

$_['error_rub']	  = 'Для работы метода необходимо добавить рубль в список валют в разделе Система=> Локализация => Валюты. Код валюты должен быть RUB или RUR';

$_['button_save_and_go']	  = 'Сохранить и выйти';
$_['button_save_and_stay']	  = 'Сохранить и остаться';

?>