<?php
namespace App\Helpers;

use Locale;

class Url{
    public static function redireciona($url){
        header("Location:".URL.DIRECTORY_SEPARATOR.$url);
    }
}
