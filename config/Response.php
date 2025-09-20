<?php
namespace Config;

trait Response
{
    public function json($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function text($data)
    {
        header('Content-Type: text/plain');
        echo $data;
        exit;
    }

    public function html($data)
    {
        header('Content-Type: text/html');
        echo $data;
        exit;
    }
}