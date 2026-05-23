<?php



namespace App\Controllers\Admin;

use App\Helpers\Sessao;
use App\Helpers\Url;
use App\Helpers\Valida;
use App\Libraries\Controller;

class Config extends Controller
{
  private $Data;
  public function __construct()
  {
    $this->Data = $this->model("admin\Perfil");
    if (!Sessao::nivel0()) {
      Url::redireciona("admin/login");
    }
  }

  public function index()
  {

    $senha = $this->Data->viewperfil($_SESSION['teste0']);

    $formulario = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if (isset($formulario['cad'])) :
      $dados = [
        'senha' => trim($formulario['password']),
        'novasenha' => trim($formulario['newpassword']),
        'rnovasenha' => trim($formulario['renewpassword']),
        'err_senha' => '',
        'name' => $senha['name'],
        'email' => $senha['email'],
        'err_newpass' => '',
        'err_renewpass' => ''
      ];
      if (in_array("", $formulario)) :
        if (empty($formulario['password'])) :
          $dados['err_senha'] = 'Preencha o campo*';
        endif;
        if (empty($formulario['newpassword'])) :
          $dados['err_newpass'] = 'Preencha o campo Nova Senha*';
        endif;
        if (empty($formulario['renewpassword'])) :
          $dados['err_renewpass'] = 'Porfavor repita a Nova Senha*';
        endif;

      else :
        if (!password_verify($dados['senha'], $senha['password'])) :
          $dados['err_senha'] = 'Senha errada';
        elseif ($formulario['newpassword'] != $formulario['renewpassword']) :
          $dados['err_renewpass'] = 'Senhas diferentes*';
        else :
          $dados['novasenha'] = password_hash(trim($formulario['newpassword']), PASSWORD_DEFAULT);
          $newpass = $this->Data->newpass($dados, $_SESSION['teste0']);
          if ($newpass) :
            Sessao::notify('success', 'Senha atualizada', null, null, null);
            Url::redireciona('admin/config');
            exit;
          else :
            Sessao::notify('success', 'Senha não atualizada', "error", null, null);

          endif;
        endif;
      // var_dump($dados);
      endif;

    else :
      $dados = [
        'senha' => '',
        'novasenha' => '',
        'rnovasenha' => '',
        'err_senha' => '',
        'err_newpass' => '',
        'name' => $senha['name'],
        'email' => $senha['email'],
        'err_renewpass' => ''
      ];
    endif;



    $file = 'admin/config';
    $title = 'config';
    return $this->view('layouts/admin/app', compact('file', 'title', 'dados'));
  }
  public function changename()
  {
    $formulario = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if ($formulario['btn']) {
      $dados = [
        'nome' => trim($formulario['nome']),
        'email' => trim($formulario['email']),
        'err_nome' => '',
        'err_email' => ''
      ];

      if (in_array("", $formulario)) {
        if (empty($formulario['nome'])) {
          $dados['err_nome'] = 'Preencha o campo*';
        }
        if (empty($formulario['email'])) {
          $dados['err_email'] = 'Preencha o campo*';
        }
      } else {
        if(Valida::email($dados['email'])){
          $dados['err_email'] = 'Preecha correctamente o email';
        }else{
          $newname = $this->Data->updateperfil($dados, $_SESSION['teste0']);
        if ($newname) :
          Sessao::notify('success', 'Dados atualizado com suucesso', null, null, null);
          Url::redireciona('admin/config');
          exit;
        else :
          Sessao::notify('success', 'Dados não atualizado', "error", null, null);

          Url::redireciona('admin/config');
          exit;
        endif;
        }
      }
    } else {
      $dados = ['nome' => '', 'err_nome' => '', 'email'=>'','err_email'=>''];
    }
    $file = 'admin/config';
    $title = 'config';
    return $this->view('layouts/admin/app', compact('file', 'title', 'dados'));;
  }
}
