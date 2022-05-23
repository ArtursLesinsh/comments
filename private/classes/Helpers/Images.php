<?php
namespace Helpers;

use Storage\DB;

class Images
{
    private $db_images;
    public function __construct() {
        $this->db_images = new DB('images');
    }

    public function getAll() {
        $result = $this->db_images->getAll();

        if ($result === false) {
            return [
                'status' => false,
                'error_msg' => $this->db_images->getError()
            ];
        }

        return [
            'status' => true,
            'images' => $result
        ];
    }

    /**
     * validateData: {
     *  Pārbauda vai ir saņemti atbilstoši dati,
     *  atbilstošā formātā
     * } un
     * addToDB: {
     *  pieveino faila nosaukumu un
     *  autoru datubāzē
     * } un
     * saveFile:{
     *  pagaidu faila satura nolasīšana un
     *  ierakstīšana jaunizveidotajā failā
     * } un
     * output{
     *  atgriež $output masīvu;
     * }
     * 
     */

    /*
    validateData {
        addToDB() -> saveFile() -> output()
    }
    output();
    */

    /**
     * Uploads image and saves data to DB
     * @return array ['status' => bool, ...]
     */
    public function upload():array {
        if (
            !isset($_POST['author']) || !is_string($_POST['author']) ||
            empty($_FILES) || !isset($_FILES['upload_image'])
        ) {
            return ['status' => true, 'message' => 'wrong data suplied'];
        }

        $image_arr = $_FILES['upload_image'];
        if ($image_arr['error'] != 0) {
            return ['status' => true, 'message' => 'wrong data suplied'];
        }

        $id = $this->addToDB($_POST['author'], $image_arr['name']);
        if ($id == false) {
            return [
                'status' => false,
                'error_msg' => $this->db_images->getError()
            ];
        }

        $this->saveFile($image_arr['tmp_name'], $id);

        return [
            'status' => true,
            'file_name' => $image_arr['name'],
            'id' => $id
        ];
    }

    /**
     * Add entry about image to DB
     * @return integer || false
     */
    private function addToDB(string $author, string $image_name) {
        return $this->db_images->addEntry([
            'author' => trim($author),
            'file_name' => explode('.', $image_name)[0]
        ]);
    }

    /**
     * saves file to upload directory
     * @param string $tmp_name - temporary uploaded file name
     * @param int $id - id of uploaded image
     */
    private function saveFile(string $tmp_name, int $id) {
        $file_content = file_get_contents($tmp_name);
        file_put_contents(UPLOAD_DIR . "image_$id.png", $file_content);
    }
}