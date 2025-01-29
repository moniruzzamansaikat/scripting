<?php

namespace Src\Controllers;

use Src\Database;

class Controller
{
    private $templates;

    private $db;

    private $postData;

    public function db()
    {
        $this->db = Database::getInstance();

        return $this->db;
    }

    public function __construct()
    {
        $this->templates = new \League\Plates\Engine('views');
        $this->postData = $_POST;
    }

    public function render($view, $data = [])
    {
        echo $this->templates->render($view, $data);
    }

    public function get($key = null)
    {
        $this->postData = arrayToObject($_POST);

        if (!$key) {
            return $this->postData;
        }

        return @$this->postData->$key ?? null;
    }

    public function json($data = [], $statusCode = 200, $headers = [])
    {
        header('Content-Type: application/json', true);

        foreach ($headers as $key => $value) {
            header("$key: $value");
        }

        http_response_code($statusCode);

        echo json_encode($data);
        exit();
    }
}
