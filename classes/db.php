<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/config/config.php';

	class DB
	{
		function __construct() {
			$this->link = new mysqli(HOST, USERNAME, PASSWORD, DB);

			if ($this->link->connect_errno) {
			    echo "Не удалось подключиться к базе данных. Подробности смотрите в журнале логов";
			    exit();
			}
		}

		function getLink() {
			return $this->link;
		}
	}

	$db = new DB();