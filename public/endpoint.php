<?php

include __DIR__ . '/../private/bootstrap.php';

if(isset($_GET['name']) && is_string($_GET['name'])) {
    if ($_GET['name'] == 'png') {
        if (isset($_GET['id']) && is_string($_GET['id'])) {
            $id = (int) $_GET['id'];

            $file_path = UPLOAD_DIR . "image_$id.png";
            if (file_exists($file_path)) {
                header('Content-Type: image/png');

                echo file_get_contents($file_path);
                return;
            }
        }
    }
}

echo "wrong path";