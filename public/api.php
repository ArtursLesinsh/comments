<?php

include __DIR__ . '/../private/bootstrap.php';

use Helpers\Comments;
use Helpers\Images;
use Helpers\Batch;

header('Content-Type: application/json');
$output = ['status' => false];
if (
    isset($_GET['object']) && is_string($_GET['object']) &&
    isset($_GET['action']) && is_string($_GET['action'])
) {
    $object_name = $_GET['object'];
    $action_name = $_GET['action'];

    $supported_objects_and_actions = [
        'comment' => ['add', 'update', 'getAll', 'delete', 'get'],
        'image' => ['upload', 'getAll', 'delete'],
        'batch' => ['getAll']
    ];

    $helper_names = [
        'comment' => 'Comments',
        'image' => 'Images',
        'batch' => 'Batch'
    ];

    if (
        array_key_exists($object_name, $supported_objects_and_actions) &&
        in_array($action_name, $supported_objects_and_actions[$object_name])
    ) {
        $class_name = 'Helpers\\' . $helper_names[$object_name];
        $helper = new $class_name();
        $output = $helper->{$action_name}();

        /*
 $class_name = 'Helpers\\' . $helper_names[$object_name];
 - также сначала указываем полный путь ('Helpers\\')
 - здесь мы находим какой элемент нам нужен из всех возможный name
        */
    }
}

echo json_encode($output, JSON_PRETTY_PRINT);

/*
Логика для отправки преобразованного коментария в базу данных
elseif ($_GET[name] === 'update-comment') {
    if (
        isset($_POST['author']) && is_string($_POST['author']) && { - здесь идет проверка, получили ли мы данные.
        isset($_POST['message']) && is_string($_POST['message']) &&
        isset($_POST['id']) && is_string($_POST['id'])
        ) {
            $id = (int) $_POST['id'];
            $author = trim($_POST['author']);
            $message = trim($_POST['message']);
             
            $comment_manager = new DB(''comments); 
            $output = [
                'status' => true,
                'id' => $id,
                'comment' => $this->db_comments->updateEntry($id, [
                    'author' => $author,
                    'message' => $message
                    ]
            ]
        } 
    }
Логика для получения интересующего нас коментария из базы данных
elseif ($_GET[name] === 'get-comment') {
    if (isset($_POST['id']) && is_string($_POST['id'])) { - здесь идет проверка, получили ли мы данные.
        $comment_manager = new DB('comments'); - создаём новый коментарий
        $id = (int) $_post['id']; - получаем конкретный id
        $output = [
            'status' => true,
            'comment' => $comment_manager->getEntry($id); - здесь уже конкретно после всех проверок получаем нас интересующие данные
        ];
    }
}

Логика для отправки файла (картинки) в базу данных
elseif ($_GET['name'] == 'upload-image') {
    if (
        isset($_POST['author'] && is_string($_POST['author'])
        !empty($_FILES) && isset($_FILES['upload-image'])
    ) {
        $image_arr = $_FILES['upload-image'];
        - только при исполнении всего наивысшего, произойдет логика $output.
         if ($image_arr->[error] == 0) {

             $author = trim($_POST['author']);  

             $db_image_manager = new DB ('images'); - производится связь с базой данных
             $id = $db_image_manager->addEntry([
                  'author' =>$author,
                  'file_name' =>  explode(',', $image_arr['name'])[0]
             ])
        - здесь мы подаём название строк, на нашем сервере, куда надо вписывать имя автора и файла. 
        - explode - выполняет разбивку строки на части

                if ($id == flase) {
                    $file_content = file_get_contents($image_arr[tmp_name]);
                file_put_contents(UPLOAD_DIR . "image_id", $file_content); 
       - это полный путь, как записать нами загруженный файл.
                    $output = [
                        'status' => true,
                        'file_name' => $image_arr['name'],
                        'id' => $id
                }
         else {
             $output = [
                'status' => false,
                'error_msg' => $db_image_manager->getError()
            ];   
         }
    
    - Здесь мы получаем все файлы (фото) из бызы данной
    elseif ($_GET['name'] == 'getALL-image') {
        $db_image_manager = new DB ('images');

        $all_images = $db_image_manager ->getAll();

        if ($all_images != false) {
             $output = [
                'status' => true,
                'images' => $all_images
             ];
        }
        else {
             $output = [
                'status' => false,
                'error_msg' => $db_image_manager->getError()
            ];   
         }
    }     
}

!empty($_FILES) - если файл не пустой, как то так читается эта строка
*/