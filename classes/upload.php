<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/config/config.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/classes/db.php';

	class Upload
	{

		private $img_format = array('jpg', 'gif', 'png');
		private $watermark = '../templates/img/watermark.png';

		function __construct() {
			$this->db = new DB();
		}

		//Возвращает тип файла
		private function typeFile($image) {
			$name = basename($image);
			$name = explode('.', $name);
			return end($name);
		}

	    //Проверка является ли файл изображением и был ли ранее загружен
		private function checkImage($image) {
			if (!empty($image)) {
				//Проверка формата файла картинки
				if (in_array($this->typeFile($image), $this->img_format)) {
					//Формат соответсвует разрешенному, проверим была ли ранее загружена такая ссылка
					$query = $this->db->getLink()->query("SELECT `id` FROM `images` WHERE `url`='".$this->db->getLink()->escape_string($image)."'")->fetch_assoc();
	        		if (empty($query['id'])) return true; else return false;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}

	    //Ресайз и наложение водяного знака
		private function image_resize($source_path, $destination_path, $newwidth, $newheight = false, $quality = false)  {

	    	ini_set("gd.jpeg_ignore_warning", 1);

	    	list($oldwidth, $oldheight, $type) = getimagesize($source_path);

	    	switch ($type) {
	        	case IMAGETYPE_JPEG: $typestr = 'jpeg'; break;
	        	case IMAGETYPE_GIF: $typestr = 'gif' ;break;
	        	case IMAGETYPE_PNG: $typestr = 'png'; break;
	    	}
	    	$function = "imagecreatefrom$typestr";
	    	$src_resource = $function($source_path);

	    	if (!$newheight) { $newheight = round($newwidth * $oldheight/$oldwidth); }
	    	elseif (!$newwidth) { $newwidth = round($newheight * $oldwidth/$oldheight); }
	    	$destination_resource = imagecreatetruecolor($newwidth,$newheight);

	        //Водяной знак
	    	$watermark = imagecreatefrompng($this->watermark);
	    	list($mark_width, $mark_height) = getimagesize($this->watermark);
	    	imagecopyresampled($src_resource, $watermark, 0, 0, 0, 0, $mark_width, $mark_height, $mark_width, $mark_height);

	    	imagecopyresampled($destination_resource, $src_resource, 0, 0, 0, 0, $newwidth, $newheight, $oldwidth, $oldheight);

	    	if ($type = 2) {
	        	imageinterlace($destination_resource, 1); // чересстрочное формирование изображение
	        	imagejpeg($destination_resource, $destination_path, $quality);
	    	} else {
		        $function = "image$typestr";
	        	$function($destination_resource, $destination_path);
	    	}
	    	imagedestroy($destination_resource);
	    	imagedestroy($src_resource);
		}

	    //Обработка формы загрузки
		public function upload() {
			if ($_FILES['upl']['tmp_name']) {

				$images = file_get_contents($_FILES['upl']['tmp_name']);
				$images = explode("\r\n", $images);

				$time = date("His");

				foreach ($images as $key => $val) {

					if ($this->checkImage($val)) {
						$val = rawurldecode($val);
						$fileName = pathinfo($val, PATHINFO_FILENAME);
	                	//Если файл прошел проверку, запишем ссылку в БД и получим ИД инкримента для присвоения имени
	                	$this->db->getLink()->query("INSERT INTO `images` (`url`, `name`) VALUES ('".$this->db->getLink()->escape_string($val)."', '".$fileName.'_'.$time.'.'.$this->typeFile($val)."')");
	                	
	                    //Скачиваем картинку, сохраняем и ресайзим
	                    $content = file_get_contents($val);
	                    $file = '../uploads/' . $fileName.'_'.$time.'.'.$this->typeFile($val);
	                    file_put_contents($file, $content);
						$this->image_resize($file, $file, '', '200', '100');
					}
					
				}
			}
		}
	}


	$upload = new Upload();
	echo $upload->upload();
?>