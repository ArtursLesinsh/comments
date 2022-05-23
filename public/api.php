<?php

include __DIR__ . '/../private/bootstrap.php';

use Helpers\Comments;
use Helpers\Images;

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
        'image' => ['upload', 'getAll']
    ];

    if (
        array_key_exists($object_name, $supported_objects_and_actions) &&
        in_array($action_name, $supported_objects_and_actions[$object_name])
    ) {
        $helper = ($object_name == 'comment')
            ? new Comments()
            :  new Images();
        $output = $helper->{$action_name}();
    }
}

echo json_encode($output, JSON_PRETTY_PRINT);