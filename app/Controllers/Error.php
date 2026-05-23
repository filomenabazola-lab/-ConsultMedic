<?php

namespace App\Controllers;

use App\Helpers\Sessao;
use App\Helpers\Url;
use App\Libraries\Controller;

class  Error  extends Controller
{
    
    public function index(){
    
      //  echo'pagina de erro' ;
    $this->view('404page');
      
    }
}