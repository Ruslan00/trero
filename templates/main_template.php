<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Тестовое задание - загрузка файлов AJAX</title>

    <link rel="stylesheet" type="text/css" href="templates/css/style.css">
  </head>

  <body>
  	<?php require_once 'templates/upload_form.php'; ?>
  	<div id="container">
      <?php if ($images->getImages()) {
              foreach ($images->getImages() as $image) { ?>
                <div class="item">
                  <img src="/uploads/<?php echo $image; ?>" alt="">
                </div>
        <?php } ?>
     <?php } else {?>
        Изображения отсутствуют
    <?php }?>    
    </div>
  </body>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script src="templates/js/jquery.knob.js"></script>

  <!-- jQuery File Upload Dependencies -->
  <script src="templates/js/jquery.ui.widget.js"></script>
  <script src="templates/js/jquery.iframe-transport.js"></script>
  <script src="templates/js/jquery.fileupload.js"></script>
  <script src="templates/js/script.js"></script>
		
</html>