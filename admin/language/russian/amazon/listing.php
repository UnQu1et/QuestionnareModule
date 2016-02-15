<?php
$_['lang_title'] = 'Новое объявление Amazon';
$_['lang_openbay'] = 'OpenBay Pro';
$_['lang_amazon'] = 'Amazon';

$_['button_search'] = 'Поиск';
$_['button_new'] = 'Создать новый подукт';
$_['button_return'] = 'Назад, к продуктам';
$_['button_amazon_price'] = "Получить цены Amazon";
$_['button_list'] = "List on Amazon";

$_['text_view_on_amazon'] = 'Просмотреть на Amazon';
$_['text_list'] = 'Список';
$_['text_new'] = 'Новый';
$_['text_used_like_new'] = 'Used - Похож на новый';
$_['text_used_very_good'] = 'Used - Очень хорош';
$_['text_used_good'] = 'Used - Хорошо';
$_['text_used_acceptable'] = 'Used - Приемлимо';
$_['text_collectible_like_new'] = 'Collectible - Похож на новый';
$_['text_collectible_very_good'] = 'Collectible - Очень хорош';
$_['text_collectible_good'] = 'Collectible - Хорошо';
$_['text_collectible_acceptable'] = 'Collectible - Приемлимо';
$_['text_refurbished'] = 'Обновленный';
$_['text_germany'] = 'Германия';
$_['text_france'] = 'Франция';
$_['text_italy'] = 'Италия';
$_['text_spain'] = 'Испания';
$_['text_united_kingdom'] = 'Англия';

$_['text_product_sent'] = 'Этот продукт был успешно отправлен в Amazon.';
$_['text_product_not_sent'] = 'Продукт не был отправлено на Amazon. Причина: %s';

$_['lang_no_results'] = 'Неичего не найдено';

$_['column_image'] = 'Изображение';
$_['column_asin'] = 'ASIN';
$_['column_name'] = 'Имя';
$_['column_price'] = 'Цена';
$_['column_action'] = 'Действие';

$_['entry_sku'] = 'SKU:';
$_['entry_condition'] = 'Состояние:';
$_['entry_condition_note'] = 'Примечание:';
$_['entry_price'] = 'Цена:';
$_['entry_sale_price'] = 'Сниженная цена:';
$_['entry_quantity'] = 'Количество:';
$_['entry_start_selling'] = 'Доступен до:';
$_['entry_restock_date'] = 'Дата пополнения:';
$_['entry_country_of_origin'] = 'Страна производитель:';
$_['entry_release_date'] = 'Дата релиза:';
$_['entry_from'] = 'Дата с';
$_['entry_to'] = 'Дата по';

$_['help_sku'] = "Unique product's ID assigned by the merchant";
$_['help_restock_date'] = "This is the date you will be able to ship any back-ordered items to a customer. This date should not be greater than 30 days from the date listed or orders received may automatically be canceled.";
$_['help_sale_price'] = "Sale Price must have a start and end date";

$_['lang_not_in_catalog'] = 'Or, if its not in the catalog&nbsp;&nbsp;&nbsp;';

$_['error_text_missing'] = 'You must enter some search details';
$_['error_data_missing'] = 'Required data is missing';
$_['error_missing_asin'] = 'ASIN is missing';
$_['error_marketplace_missing'] = 'Please select a marketplace';
$_['error_condition_missing'] = "Please select condition";
$_['error_fetch'] = 'Could not get the data';
$_['error_amazon_price'] = 'Could not get the price from Amazon';
$_['error_stock'] = 'You cannot list an item with less than 1 item in stock';
$_['error_sku'] = 'You must enter an SKU for the item';
$_['error_price'] = 'You must enter a price for the item';

$_['tab_required_info'] = 'Required info';
$_['tab_additional_info'] = 'Дополнительные опции';

$_['lang_placeholder_search'] = 'Enter product name, UPC, EAN, ISBN or ASIN';
$_['lang_placeholder_condition'] = 'Use this box to describe the condition of your products.';

/* Headers, tab names */
$_['item_links_header_text'] = 'Item links';
$_['quick_listing_header_text'] = 'Быстрый список объявлений';
$_['advanced_listing_header_text'] = 'Расширенный список объявлений';
$_['saved_heder_text'] = 'Сохраненные объявления';
$_['lang_tab_main'] = 'Главная';

$_['item_links_tab_text'] = 'Item links';
$_['quick_listing_tab_text'] = 'Быстрый список объявлений';
$_['advanced_listing_tab_text'] = 'Расширенный список объявлений';
$_['saved_tab_text'] = 'Сохраненные объявления';

$_['text_error_connecting'] = 'Warning: There was problem connecting to Welford Media API servers. Please check your OpenBay Pro Amazon extension settings. If the problem persists, please contact Welford support.';

/* Quick/advanced listing tabs */
$_['quick_listing_description'] = 'Use this method when matching product already exist in Amazon catalogue. Matching is found by Amazon using Standard product id (ASIN, ISBN, UPS, EAN)';
$_['advanced_listing_description'] = 'Use this method to create new listings on Amazon.';
$_['listing_row_text'] = 'Listing for product:';
$_['already_saved_text'] = 'This product is already in saved listings. Click edit if you want to revise.';
$_['save_button_text'] = 'Сохранить';
$_['save_upload_button_text'] = 'Сохранить и загрузить';
$_['saved_listings_button_text'] = 'Просмотреть сохраненные объявления';
$_['cancel_button_text'] = 'Отмена';
$_['field_required_text'] = 'Это обязательное поле!';
$_['not_saved_text'] = 'Объявление не сохранено. Проверьте все поля.';
$_['chars_over_limit_text'] = 'слишком много знаков.';
$_['minimum_length_text'] = 'Минимальная длина ';
$_['characters_text'] = 'символов';
$_['delete_confirm_text'] = 'Вы уверены?';

$_['clear_image_text'] = 'Очистить';
$_['browse_image_text'] = 'Обзор';

$_['category_selector_field_text'] = 'Amazon category:';

/* Item links tab */
$_['item_links_description'] = 'Here you can add and edit product links to already existing Amazon items without listing them from OpenCart. This will allow stock control between enabled marketplaces. If you have openStock installed - this will also allow you to link item options to individual Amazon SKUs. (Uploading products from OpenCart to Amazon will automaticaly add links)';
$_['new_link_table_name'] = 'Новая ссылка';
$_['new_link_product_column'] = 'Продукт';
$_['new_link_sku_column'] = 'SKU';
$_['new_link_amazon_sku_column'] = 'Amazon item SKU';
$_['new_link_action_column'] = 'Действие';

$_['item_links_table_name'] = 'Item links';


/* Marketplaces */
$_['marketplaces_field_text'] = 'Marketplace';
$_['marketplaces_help'] = 'You can choose default marketplace in Amazon extension settings.';


/* Saved listings tab */
$_['saved_listings_description'] = 'This is the list of product listings which are saved locally and ready to be uploaded to Amazon. Click upload to post.';
$_['actions_edit_text'] = 'Изменить';
$_['actions_remove_text'] = 'Удалить';
$_['upload_button_text'] = 'Загрузить';

$_['name_column_text'] = 'Имя';
$_['model_column_text'] = 'Модель';
$_['sku_column_text'] = 'SKU';
$_['amazon_sku_column_text'] = 'Amazon item SKU';
$_['actions_column_text'] = 'Действие';
$_['saved_localy_text'] = 'Объявление сохранено локально.';
$_['uploaded_alert_text'] = 'Сохраненные объявления загружены!';
$_['upload_failed'] = 'Failed uploading product with SKU: "%s". Reason: "%s" Uploading process canceled.';


/* ITEM LINKS */
$_['links_header_text'] = 'Link items';
$_['links_desc1_text'] = 'Linking your items will allow for stock control on your Amazon listings.<br /> For each item that is updated the local stock (the stock available in your OpenCart store) will update your Amazon listing';
$_['links_desc2_text'] = 'You can link items manualy by entering Amazon SKU and product name or load all unlinked products and then enter amazon SKUs. (Uploading products from OpenCart to Amazon will automaticaly add links)';
$_['links_load_btn_text'] = 'Загрузить';
$_['links_new_link_text'] = 'New link';
$_['links_autocomplete_product_text'] = 'Product<span class="help">(Autocomplete from name)</span>';
$_['links_amazon_sku_text'] = 'Amazon item SKU';
$_['links_action_text'] = 'Действие';
$_['links_add_text'] = 'Добавить';
$_['links_add_sku_tooltip'] = 'Add another sku';
$_['links_remove_text'] = 'Удалить';
$_['links_linked_items_text'] = 'Linked items';
$_['links_unlinked_items_text'] = 'Unlinked items';
$_['links_name_text'] = 'Имя';
$_['links_model_text'] = 'Модель';
$_['links_sku_text'] = 'SKU';
$_['links_amazon_sku_text'] = 'Amazon item SKU';
$_['links_sku_empty_warning'] = 'Amazon SKU can not be empty!';
$_['links_name_empty_warning'] = 'Product name can not be empty!';
$_['links_product_warning'] = 'Product does not exist. Please use autocomplete values.';

$_['option_default'] = '-- Select option --';
$_['lang_error_load_nodes'] = 'Unable to load browse nodes';

/* Listin edit page */
$_['text_edit_heading'] = 'Listing overview';

$_['text_has_saved_listings'] = 'This product has one or more localy saved listings.';

$_['text_product_links'] = 'Product links';
$_['button_create_new_listing'] = "Create new listing";
$_['button_remove_links'] = "Remove links";
$_['button_saved_listings'] = "View saved listings";

$_['column_name'] = 'Название продукта';
$_['column_model'] = 'Модель';
$_['column_combination'] = 'Комбинация';
$_['column_sku'] = 'SKU';
$_['column_amazon_sku'] = 'Amazon item SKU'; 
?>