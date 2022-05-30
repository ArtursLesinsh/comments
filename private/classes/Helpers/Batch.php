<?php
namespace Helpers;

class Batch
{
    private $images;
    private $comments;
    public function __construct() {
        $this->images = new Images();
        $this->comments = new Comments();
    }

    public function getAll() {
        $all_images = $this->images->getAll();
        if (!$all_images['status']) {
            return ['status' => false];
        }

        $all_comments = $this->comments->getAll();
        if (!$all_comments['status']) {
            return ['status' => false];
        }

        return array_merge($all_images, $all_comments);
/* эта функция (array_merge) обьединяет несколько масивов вместе и тут нужен return, а не output, 
так как это функция исполняется в другом месте и тем самым мы данные отправляем туда*/
    }
}