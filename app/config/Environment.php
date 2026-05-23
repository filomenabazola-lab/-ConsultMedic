<?php

namespace App\config;

class Environment
{
  protected $path;

  /**
   * Método responsável por carregar as variáveis de ambiente do projeto
   *  $dir @param string Caminho absoluto da pasta onde encontra-se o arquivo .env
   */

   public function __construct(string $path)
   {
    //Ver se existem arquivos de ambientes
    if (!file_exists($path . '/.env')) {
      return false;
    }
    $this->path=$path; 
   }


  public function load()
  { 
    //Carregando os arquivos de ambiente
      $lines = file($this->path.'/.env');
      // print_r($lines); 
      // echo'<hr>';
      foreach ($lines as $line) {
        [$key,$value] = explode('=', $line,2);
        $key = trim($key);
        $value = trim($value);
       putenv(sprintf("%s=%s",$key,$value));
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
    // print_r(getenv());
      
    
  }
}
