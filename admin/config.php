<?php

ini_set('memory_limit','1024M');
ini_set('max_execution_time','600');

// HTTP
define('HTTP_SERVER', 'http://bazd/admin/');
define('HTTP_CATALOG', 'http://bazd/');

// HTTPS
define('HTTPS_SERVER', 'http://bazd/admin/');
define('HTTPS_CATALOG', 'http://bazd/');

// DIR
define('DIR_APPLICATION', $_SERVER['DOCUMENT_ROOT'].'/admin/');
define('DIR_SYSTEM', $_SERVER['DOCUMENT_ROOT'].'/system/');
define('DIR_DATABASE', $_SERVER['DOCUMENT_ROOT'].'/system/database/');
define('DIR_LANGUAGE', $_SERVER['DOCUMENT_ROOT'].'/admin/language/');
define('DIR_TEMPLATE', $_SERVER['DOCUMENT_ROOT'].'/admin/view/template/');
define('DIR_CONFIG', $_SERVER['DOCUMENT_ROOT'].'/system/config/');
define('DIR_IMAGE', $_SERVER['DOCUMENT_ROOT'].'/image/');
define('DIR_CACHE', $_SERVER['DOCUMENT_ROOT'].'/system/cache/');
define('DIR_DOWNLOAD', $_SERVER['DOCUMENT_ROOT'].'/download/');
define('DIR_LOGS', $_SERVER['DOCUMENT_ROOT'].'/system/logs/');
define('DIR_CATALOG', $_SERVER['DOCUMENT_ROOT'].'/catalog/');

// DB
define('DB_DRIVER', 'mysql');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'bazalt-dealers');
define('DB_PREFIX', '');
?>