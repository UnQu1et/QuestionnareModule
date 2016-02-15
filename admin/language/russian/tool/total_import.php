<?php
#####################################################################################
#  Module Total Import PRO for Opencart 1.5.x From HostJars opencart.hostjars.com 	#
#####################################################################################

// Heading
$_['heading_title']    	= 'Total Import PRO';

// Tabs
$_['tab_fetch'] 		= 'Step 1: Fetch Feed File';
$_['tab_global'] 		= 'Step 2: Global Settings';
$_['tab_adjust'] 		= 'Step 3: Adjust Import Data';
$_['tab_mapping'] 		= 'Step 4: Field Mapping';
$_['tab_import'] 		= 'Step 5: Import';

// Text
$_['text_add']     					= 'Add';
$_['text_reset']     				= 'Reset';
$_['text_update']     				= 'Add/Update';
$_['text_adjust_help']     			= 'If you need to adjust any data from your feed before importing it you can use the Operations below on the fields in your database. You can also leave this screen open and use phpmyadmin to directly adjust the database table hj_import and then return here to complete the import.';
$_['text_operation']     			= 'Operation';
$_['text_operation_field_name'] 	= 'Field';
$_['text_operation_data'] 			= 'Data';
$_['text_sample'] 					= 'Feed Sample';
$_['text_select_operation']			= '-- Select operation --';
$_['text_select']					= '-- Select --';
$_['text_operation_type']			= 'Operation Type';
$_['text_operation_desc']			= 'Description';
$_['text_most_popular']				= 'Most Popular';
$_['text_advanced']					= 'Advanced';
$_['text_more']						= 'More';


// Entry
$_['entry_import_file']     		= 'Import Feed File:';
$_['entry_feed_source']				= 'Feed Source:';
$_['entry_file_upload']				= 'File Upload';
$_['entry_file_system']				= 'File System';
$_['entry_feed_format']     		= 'Feed Format:';
$_['entry_max_file_size']     		= 'Max Size: %d mb';
$_['entry_import_url']    	 		= 'Import Feed URL:';
$_['entry_import_ftp'] 				= 'Import Feed FTP:';
$_['entry_ftp_server'] 				= 'FTP Server:';
$_['entry_ftp_user'] 				= 'Username:';
$_['entry_ftp_pass'] 				= 'Password:';
$_['entry_ftp_path'] 				= 'Absolute path to file:';
$_['entry_import_filepath']    		= 'Import Feed Local File Path:';
$_['entry_xml_product_tag']     	= 'XML Product Tag:';
$_['entry_delimiter']  				= 'CSV Field Delimiter:';
$_['entry_data_feed']  				= 'CSV Data Feed:';
$_['entry_out_of_stock']  			= 'Статус заказа при отсутствии поля наличие в файле:';
$_['entry_weight_class']  			= 'Вес товара:';
$_['entry_length_class']  			= 'Длинна:';
$_['entry_customer_group']  		= 'Customer Group:';
$_['entry_tax_class']  				= 'Налоги:';
$_['entry_subtract_stock']  		= 'Вычитывать количество:';
$_['entry_requires_shipping']  		= 'Необходима доставка:';
$_['entry_minimum_quantity'] 	 	= 'Minimum Quantity:';
$_['entry_product_status']  		= 'Статус продукта:';
$_['entry_product_status_hide']  		= 'Статус продукта в скрытой категории:';
$_['entry_hide_category']  		= 'Выберите скрытую категорию:';
$_['entry_language']  				= 'Language:';
$_['entry_store']  	   				= 'Store:';
$_['entry_remote_images']  	   		= 'Download Remote Images:';
$_['entry_remote_images_warning']  	= 'Warning: This will probably timeout for product feeds larger than around 500 products.';
$_['entry_image_subfolder']	   		= 'Image Subfolder:';
$_['entry_ignore_fields'] 			= 'Skip Products Where:';
$_['entry_field_mapping']			= 'Field Mapping:';
$_['entry_import_type']				= 'Import Type:';
$_['entry_price_multiplier']		= 'Price Multiplier:';
$_['entry_image_remove']			= 'Image Remove Text:';
$_['entry_image_prepend']			= 'Image Prepend Text:';
$_['entry_image_append']			= 'Image Append Text:';
$_['entry_split_category']			= 'Разделитель категорий(например несколько вложенных категорий в одной ячейке разделенных знаком > Категория1 > Категория2):';
$_['entry_top_categories']			= 'Add Categories to Top Bar:';
$_['entry_bottom_category_only']	= 'Add Products to:';
$_['entry_new_items'] 				= 'Что делать с новыми товарами:';
$_['entry_download_tovars'] 				= 'Загружать товары в скрытую категорию:';
$_['entry_category_method'] 				= 'Метод назначения категорий:';
$_['entry_new_items'] 				= 'New Items:';
$_['entry_existing_items'] 			= 'Что делать с уже имеющимися товарами:';
$_['entry_delete_diff'] 			= 'Items in store but not in file(Что делать с товарами, которых нет в импортируемом прайсе, но есть в магазине):';
$_['entry_reset'] 					= 'Очистить весь магазин перед импортом! ОСТОРОЖНО!!:';
$_['entry_file_encoding']     		= 'File Encoding:';
$_['entry_first_row_is_headings']	= 'First Row Is Headings:';
$_['entry_use_safe_headings']		= 'Use Safe Headings:';
$_['entry_use_safe_headings_help']	= 'ignore the feed\'s headings';
$_['entry_unzip_feed']				= 'Unzip Feed:';
$_['entry_file_encoding']			= 'File Encoding:';
$_['entry_file_encoding_help']		= 'If you are unsure, use UTF-8:';
$_['entry_required']				= 'Required';
$_['entry_advanced']				= 'Advanced (optional settings)';
$_['entry_yes']						= 'Да';
$_['entry_no']						= 'Нет';
$_['entry_all_categories']			= 'All Categories';
$_['entry_bottom_category_only']	= 'Bottom Category Only';
$_['entry_none'] 					= 'None';
$_['entry_add'] 					= 'Add';
$_['entry_skip'] 					= 'Skip';
$_['entry_update'] 					= 'Update';
$_['entry_ignore'] 					= 'Ignore';
$_['entry_delete'] 					= 'Delete';
$_['entry_disable'] 				= 'Disable';
$_['entry_zero_quantity']			= 'Quantity to Zero';
$_['entry_import_range'] 			= 'Импорт частями:';
$_['entry_to'] 						= 'to';
$_['entry_from'] 					= 'From product';
$_['entry_import_range_help']		= 'Внимание!! Если делать импорт частями, поле "Items in store but not in file" нужно поставить в статус "Ignore" так как мы делаем импорт части товаров. Import only part of your product feed eg: From 1 to 100 will import the first 100 items in your file';
$_['entry_range'] 					= 'Range';
$_['entry_all'] 					= 'All';


// Fields
$_['text_field_oc_title']	 		= 'OpenCart Field';
$_['text_field_feed_title']	 		= 'Feed Field';
$_['text_field_name']     			= 'Name';
$_['text_field_price']     			= 'Price';
$_['text_field_special_price']     	= 'Special Price';
$_['text_field_discount_price']    	= 'Discount Price';
$_['text_field_model']    	 		= 'MODEL';
$_['text_field_sku']     			= 'SKU';
$_['text_field_upc']     			= 'UPC';
$_['text_field_ean']     			= 'EAN';
$_['text_field_jan']     			= 'JAN';
$_['text_field_isbn']     			= 'ISBN';
$_['text_field_mpn']     			= 'MPN';
$_['text_field_points']     		= 'Points';
$_['text_field_manufacturer']     	= 'Manufacturer';
$_['text_field_attribute']     		= 'Attribute';
$_['text_field_category']     		= 'Category';
$_['text_field_quantity']     		= 'Quantity';
$_['text_field_image']     			= 'Image';
$_['text_field_additional_image']   = 'Additional Image';
$_['text_field_description']     	= 'Description';
$_['text_field_meta_desc']     		= 'Meta Description';
$_['text_field_meta_keyw']     		= 'Meta Keywords';
$_['text_field_weight']     		= 'Weight';
$_['text_field_length']     		= 'Length';
$_['text_field_height']     		= 'Height';
$_['text_field_width']     			= 'Width';
$_['text_field_location']    	 	= 'Location';
$_['text_field_keyword']     		= 'SEO Keyword';
$_['text_field_tags']     			= 'Product Tags';
$_['text_field_sort_order']			= 'Sort Order';
$_['text_field_option']				= 'Options';
$_['text_field_reward']				= 'Reward Points';
$_['text_field_product_related']	= 'Related Products';
$_['text_field_subtract_stock']		= 'Subtract Stock';
$_['text_field_requires_shipping']	= 'Requires Shipping';
$_['text_field_minimum_quantity']	= 'Minimum Quantity';
$_['text_field_product_status']		= 'Product Status';
$_['text_field_product_reward_group'] = 'Reward Group';
$_['text_field_layout']				= 'Layout';
$_['text_field_download']			= 'Download';
$_['text_mapping_description']		= 'The OpenCart field column contains the name of the field in OpenCart, the Feed Field is where you must enter the heading name of the column that you want to import to each OpenCart field. You can set each field to "None" if you do not have anything to import there. None of the fields are required, but the more you can provide the better your import will be.';
$_['text_feed_sample'] 				= 'Sample From Your Feed';
$_['text_save_profile'] 			= 'Enter a name to save a new settings profile: ';
$_['text_identify_existing'] 		= 'Идентификация товара идет по полю: ';
$_['text_documentation'] 			= 'Documentation';

// Import
$_['button_fetch']	   			= 'Fetch';
$_['button_import']	   			= 'Import';
$_['button_next']	   			= 'Save &amp; Continue';
$_['button_skip']	   			= 'Skip';
$_['button_save'] 	   			= 'Save';
$_['button_cancel']	   			= 'Cancel';
$_['button_add_operation']	   	= 'Add Adjustment';
$_['button_add_operation']	   	= 'Save';


// Success
$_['text_success_step1']   		= 'Success: Feed fetched and parsed. Ready for import: %s;';
$_['text_success_step1_csv']    = ' Invalid CSV rows: %s';
$_['text_success_step2']     	= 'Success: Global Settings saved.';
$_['text_success_step3']     	= 'Success: Operations saved.';
$_['text_success_step4']     	= 'Success: Mappings saved.';
$_['text_success_step5']     	= 'Success: Added %s products, Updated %s products';
$_['text_success']     			= 'Success: Settings Saved';

// Error
$_['error_permission'] 			= 'Warning: You do not have permission to modify total import!';
$_['error_empty']      			= 'Warning: No file or empty file!';
$_['error_mac_csv']      		= 'Warning: CSV file contains only one line: If you create your CSV file with Mac you need to save it as "CSV (Windows)"!';
$_['error_wrong_delimiter']    	= 'Warning: The delimiter you have chosen does not seem to be correct!';
$_['error_csv_heading'] 		= "Warning: Your headings are not suitable for direct usage please check every column has a %s heading, or use Safe Headings!";
$_['error_xml_product_tag']		= 'Warning: You must specify a Product Tag for XML files!';
$_['error_xml_format']			= 'Warning: XML format error - please fix your XML file!';
$_['error_no_db'] 				= 'Warning: Step 1 must be completed correctly before proceeding!';
$_['error_upload_exceeds_dir']		= 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
$_['error_upload_exceeds_max'] 		= 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
$_['error_upload_partial']			= 'The uploaded file was only partially uploaded';
$_['error_upload_no_file'] 			= 'No file was uploaded';
$_['error_upload_missing'] 			= 'Missing a temporary folder';
$_['error_upload_failed_to_write'] 	= 'Failed to write file to disk';
$_['error_upload_stopped'] 			= 'File upload stopped by extension';
$_['error_timeout_reached']			= 'Your import did not complete as your server\'s maximum execution of %ss was insufficient. <a alt="How do I fix this?" href="http://helpdesk.hostjars.com/entries/21903992-Fatal-error-Maximum-execution-time-of-30-seconds-exceeded">How do I fix this?</a>';


// Operations Text
$_['operation_multiply_price']			= 'Adjust Price (Multiply)';
$_['operation_multiply']				= 'Multiply';
$_['operation_by']						= 'by';
$_['operation_add_price']				= 'Adjust Price (Add)';
$_['operation_add']						= 'Add';
$_['operation_to']						= 'to';
$_['operation_split_fields_category']	= 'Split Category on Delimiter';
$_['operation_split']					= 'Split';
$_['operation_on']						= 'on';
$_['operation_append_image']			= 'Append Text to Image';
$_['operation_append']					= 'Append';
$_['operation_after']					= 'after';
$_['operation_prepend_image']			= 'Prepend Text to Image';
$_['operation_prepend']					= 'Prepend';
$_['operation_append_text']				= 'Append Text to Any Field';
$_['operation_prepend_text']			= 'Prepend Text to Any Field';
$_['operation_multiply_field']			= 'Multiply any Field';
$_['operation_add_field']				= 'Add to any Field';
$_['operation_split_fields']			= 'Split Any Field';
$_['operation_replace_text']			= 'Replace Text';
$_['operation_replace']					= 'Replace';
$_['operation_with']					= 'with';
$_['operation_in']						= 'in';
$_['operation_remove_text']				= 'Remove Text';
$_['operation_remove']					= 'Remove';
$_['operation_delete_row_equals']		= 'Filter Products (equals)';
$_['operation_delete_row_not_equal']	= 'Filter Products (not equals)';
$_['operation_delete_row_containing']	= 'Filter Products (containing)';
$_['operation_delete_row_not_containing']	= 'Filter Products (not containing)';
$_['operation_exclude_products']		= 'Exclude products where';
$_['operation_equals']					= 'equals';
$_['operation_does_not_equal']			= 'does not equal';
$_['operation_contains']				= 'contains';
$_['operation_does_not_contain']		= 'does not contain';
$_['operation_duplicate_feed']			= 'Clone field';
$_['operation_duplicate']				= 'Duplicate';
$_['operation_merge_columns']			= 'Append Field to Field';
$_['operation_separated_by']			= 'separated by';
$_['operation_merge_rows']				= 'Merge multi-row products';
$_['operation_common_field']			= 'Common field';
$_['operation_merge_the_following']		= 'Merge the following fields';
$_['operation_replace_newlines']		= 'Convert Newlines to HTML';

//Operations Labels
$_['operation_label_most_popular']	= 'Most Popular';
$_['operation_label_advanced']	= 'Advanced';
$_['entry_out_of_stock_status_insert']	= 'Наличие товара при добавлении';
$_['entry_default_quantity']	= 'Дефолтовое значение количества';
$_['entry_split_image']	= 'Разделитель картинок';
$_['entry_tovar_type']	= 'Тип товара';
$_['entry_location']	= 'Расположение';

?>