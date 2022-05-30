<?php

include __DIR__ . '/../private/bootstrap.php';

if(isset($_GET['name']) && is_string($_GET['name'])) {
    if ($_GET['name'] == 'png') {
        if (isset($_GET['id']) && is_string($_GET['id'])) { //поддаём id, чтобы проверить его 
            $id = (int) $_GET['id'];                       // переделываем id в число простое

            $file_path = UPLOAD_DIR . "image_$id.png"; //путь к папке с файлами
            header('Content-Type: image/png');
            if (file_exists($file_path)) {          //проверяем существует ли файл
                echo file_get_contents($file_path);
                return;
            }
            else {
                echo file_get_contents(PRIVATE_DIR . 'default_image.png');
                //при ошибке, будет выводить картинку, путь к которой мы предоставили.
            }
        }
    }
}