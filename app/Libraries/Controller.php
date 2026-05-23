<?php
namespace App\Libraries;

class Controller{
    public function model($model){
        $db= "App\\Models\\".$model;
        $db=new $db;
        return $db;
    }
    public function view($view, $dados = []){
        extract($dados);
        $arquivo = dirname(__DIR__) . DIRECTORY_SEPARATOR . "Views" . DIRECTORY_SEPARATOR . $view . ".php";
        if(file_exists($arquivo)):
            require_once $arquivo;
        else:
            die('O arquivo requerido nao foi encontrado');
        endif;            
    }
}