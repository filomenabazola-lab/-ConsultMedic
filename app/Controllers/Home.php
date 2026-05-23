<?php

namespace App\Controllers;

use App\Helpers\Sessao;
use App\Helpers\Url;
use App\Helpers\Valida;
use App\Libraries\Controller;

class  Home  extends Controller
{
  private $Data;
  private $Posts;
  private $Honra;
  public function __construct()
  {

    // $this->Data = $this->model("user\Usuarios");
    // $this->Posts = $this->model("user\Posts");
    // $this->Honra = $this->model("admin\Honra");
  }
  public function index()
  {
   
      $file = 'homepage';
    $title = 'home';
    return $this->view('layouts/user/app', compact('file', 'title'));
  }

}
