<?php
namespace App\config;
 use App\config\Environment;
 $req= new Environment(dirname(__DIR__,2));
 $req->load();

// ( new App\config\Environment(dirname(__DIR__)))->load();

global $base_url;
global $auth;
global $projecto;



$base_url = getenv('BASE_URL')? getenv('BASE_URL'):'http://localhost/consult';
$auth = getenv('AUTH_SESSION_KEY')? getenv('AUTH_SESSION_KEY'):'AUTH_USER';
$projecto = getenv('APP_NAME')? getenv('APP_NAME'):'Projecto';

// define('PU','http://localhost:8080/refeitorio/public/');
define('URL',$base_url);
define('AUTH_SESSION_KEY',$auth);
define('APP_NOME',$projecto);
define('APP', dirname(__FILE__));

// Credencias do banco de dados
define('DB',[
    'HOST'=>getenv('DB_HOST'),
    'PORT'=>getenv('DB_PORT'),
    'USER'=>getenv('DB_USER'),
    'PASS'=>getenv('DB_PASS'),
    'DBNAME'=>getenv('DB_NAME'),
    'SGBD'=>getenv('DB_SGBD')
]);

// Constantes de Bootstrap
        define('BOOTCSS', "bootstrap5.2".DIRECTORY_SEPARATOR."node_modules".DIRECTORY_SEPARATOR."bootstrap".DIRECTORY_SEPARATOR."dist".DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR."bootstrap.min.css");
        define('BOOTJS', "bootstrap5.2".DIRECTORY_SEPARATOR."node_modules".DIRECTORY_SEPARATOR."bootstrap".DIRECTORY_SEPARATOR."dist".DIRECTORY_SEPARATOR."js".DIRECTORY_SEPARATOR."bootstrap.bundle.min.js");
        define('BOOTPOPPER', "bootstrap5.2".DIRECTORY_SEPARATOR."node_modules".DIRECTORY_SEPARATOR."@popperjs".DIRECTORY_SEPARATOR."core".DIRECTORY_SEPARATOR."dist".DIRECTORY_SEPARATOR."umd".DIRECTORY_SEPARATOR."popper.min.js");

//Jquery
define('JQUERY', "js".DIRECTORY_SEPARATOR."jquery-3.6.1.min.js");

// Notify
define('NOTIFY', "js".DIRECTORY_SEPARATOR."notify.min.js");
define('IZOCSS', "css".DIRECTORY_SEPARATOR."iziToast.min.css");
define('IZOJS', "js".DIRECTORY_SEPARATOR."iziToast.min.js");




