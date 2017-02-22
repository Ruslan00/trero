<?php
	error_reporting(E_ALL);

	/* Логирование */
	ini_set('log_errors', 'On');
	ini_set('error_log', $_SERVER['DOCUMENT_ROOT'].'/error.log');

	header('Content-Type: text/html; charset=utf-8');

	/* Константы для соединения с базой данных */
	define('HOST', 'localhost');
	define('USERNAME', '');
	define('PASSWORD', '');
	define('DB', '');
?>