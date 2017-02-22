<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/config/config.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/classes/db.php';

	class Image
	{
		//Возвращает тип файла
		private function typeFile($image) {
			$name = basename($image);
			$name = explode('.', $name);
			return end($name);
		}

		public function getImages() {
			$db = new DB();
			$img = '';

			$result = $db->getLink()->query("SELECT * FROM `images`");
			while ($data = $result->fetch_assoc()) {
	        	$img[] = $data['name'];
			}

	        return $img;
		}
	}

?>