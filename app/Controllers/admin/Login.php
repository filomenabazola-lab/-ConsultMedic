<?php



namespace App\Controllers\Admin;

use App\Helpers\Sessao;
use App\Helpers\Url;
use App\Libraries\Controller;

class Login extends Controller
{
  private $Data;
  public function __construct()
  {
    if(Sessao::nivel0()){
      Url::redireciona("admin/home");
    }
    $this->Data=$this->model("admin\Auth");
  }
  public function index()
  {
     $formulario = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    //  var_dump($formulario);
    if (isset($formulario['btn_log'])) :
      $dados = [
        'email' => trim($formulario['email']),
        'senha' => trim($formulario['senha']),
        'erro_email' => '',
        'erro_senha' => ''
      ];

      if (in_array("", $formulario)) :

        if (empty($formulario['email'])) :
          $dados['erro_email'] = "preencha o campo email";
        endif;

        if (empty($formulario['senha'])) :
          $dados['erro_senha'] = "preencha o campo senha";
        endif;

      else :

        
        $checarlogin = $this->Data->checalogin($dados['email'], $dados['senha']);
        // exit;
        if ($checarlogin) :

          
          Url::redireciona('admin/home');
          $this->criarsessao($checarlogin);
          Sessao::notify('auth1', 'Login realizado com sucesso', null, null, null);
          exit;
        // var_dump($_SESSION);

        else :
          Sessao::notify('auth1', 'Email ou senha estão errados', "error", null, "('#formAuth')");
          $dados['erro_email'] = "Dados invalidos";
          $dados['erro_senha'] = "Dados invalidos";
        endif;



      endif;
    //  var_dump($formulario);
    else :
      $dados = [
        'email' => '',
        'senha' => '',
        'erro_email' => '',
        'erro_senha' => ''
      ];
    endif;



    $file = 'admin'.DIRECTORY_SEPARATOR.'login';
    $title = 'admin/login';
    return $this->view($file, compact('title','dados'));
  }
  private function  criarsessao(array $usuario)
  {

    $_SESSION['teste0'] = $usuario['id_users'];
    $_SESSION['teste0_depart'] = $usuario['id_departament'];
    $_SESSION['teste0_nome'] = $usuario['name'];
    $_SESSION['teste0_email'] = $usuario['email'];
    $_SESSION['teste0_type'] = $usuario['type_user'];
  }
  public function sair()
  {
    unset($_SESSION['teste0']);
    unset($_SESSION['teste0_depart']);
    unset($_SESSION['teste0_nome']);
    unset($_SESSION['teste0_email']);
    unset($_SESSION['teste0_type']);
    session_destroy();
    Url::redireciona('admin/login');
  }
}
