<?php



namespace App\Controllers\Admin;

use App\Helpers\Sessao;
use App\Helpers\Url;
use App\Helpers\Valida;
use App\Libraries\Controller;

class Usuarios extends Controller
{
  private $Data;
  private $Depart;
  public function __construct()
  {
    $this->Data = $this->model("admin\Auth");
    $this->Depart = $this->model("admin\Departamentos");

    if (!Sessao::nivel0()) {
      Url::redireciona("admin/login");
    }
    if(Sessao::nivel1()){
      Sessao::notify("error", "Acesso Negado", "error");
      Url::redireciona('admin/home');
      exit;
    }
  }
  public function index()
  {
    $departs = $this->Depart->departamentos();
    $users = $this->Data->getUsers();
    
    $file = 'admin/Usuarios/usuarios';
    $title = 'users';
    return $this->view('layouts/admin/app', compact('file', 'title', 'users', 'departs'));
  }
  public function create()
  {
    $form = filter_input_array(INPUT_POST, FILTER_DEFAULT) ?: [];

    $nome = trim((string)($form['nome'] ?? ''));
    $email = trim((string)($form['email'] ?? ''));
    $senha = trim((string)($form['senha'] ?? ''));
    $nivel = trim((string)($form['nivel'] ?? ''));
    $depart = trim((string)($form['depart'] ?? ''));

    if ($nome === '' || $email === '' || $senha === '' || !in_array($nivel, ['0', '1'], true)) {
      Sessao::notify('error', 'Preencha nome, email, senha e nivel.', 'error');
      Url::redireciona('admin/usuarios');
      exit;
    }

    if ($nivel === '1' && $depart === '') {
      Sessao::notify('error', 'Selecione o departamento para o medico.', 'error');
      Url::redireciona('admin/usuarios');
      exit;
    }

    if (Valida::email($email)) {
      Sessao::notify('error', 'Preencha o email corretamente', 'error');
      Url::redireciona('admin/usuarios');
      exit;
    }

    if ($this->Data->checaemail($email)) {
      Sessao::notify('error', 'Email ja cadastrado', 'error');
      Url::redireciona('admin/usuarios');
      exit;
    }

    $dados = [
      'nome' => $nome,
      'email' => $email,
      'senha' => Valida::pass_segura($senha),
      'nivel' => $nivel,
      'depart' => $nivel === '0' ? null : $depart,
    ];

    if ($this->Data->createUser($dados)) {
      Sessao::notify('success', 'Cadastrado com sucesso');
    } else {
      Sessao::notify('error', 'Erro ao cadastrar no banco de dados.', 'error');
    }

    Url::redireciona('admin/usuarios');
    exit;
  }
  public function edit($id)
  {
    $id = filter_var($id['id'] ?? null, FILTER_VALIDATE_INT);
    $form = filter_input_array(INPUT_POST, FILTER_DEFAULT) ?: [];

    if (!$id) {
      Sessao::notify('error', 'Usuario invalido.', 'error');
      Url::redireciona('admin/usuarios');
      exit;
    }

    $nome = trim((string)($form['nome'] ?? ''));
    $email = trim((string)($form['email'] ?? ''));
    $nivel = trim((string)($form['nivel'] ?? ''));
    $depart = trim((string)($form['depart'] ?? ''));

    if ($nome === '' || $email === '' || !in_array($nivel, ['0', '1'], true)) {
      Sessao::notify('error', 'Preencha nome, email e nivel.', 'error');
      Url::redireciona('admin/usuarios');
      exit;
    }

    if ($nivel === '1' && $depart === '') {
      Sessao::notify('error', 'Selecione o departamento para o medico.', 'error');
      Url::redireciona('admin/usuarios');
      exit;
    }

    if (Valida::email($email)) {
      Sessao::notify('error', 'Preencha o email corretamente', 'error');
      Url::redireciona('admin/usuarios');
      exit;
    }

    $dados = [
      'nome' => $nome,
      'email' => $email,
      'nivel' => $nivel,
      'depart' => $nivel === '0' ? null : $depart,
    ];

    if ($this->Data->updateUser($dados, $id)) {
      Sessao::notify('success', 'Atualizado com sucesso');
    } else {
      Sessao::notify('error', 'Erro ao atualizar no banco de dados.', 'error');
    }

    Url::redireciona('admin/usuarios');
    exit;
  }
  public function delete($id)
  {    
    $nivel = filter_var($id['nivel'], FILTER_VALIDATE_INT);
    $id = filter_var($id['id'], FILTER_VALIDATE_INT);
    if ($nivel == '0') {
      Sessao::notify('error', 'Não pode deletar admin', "error", null, null);
      Url::redireciona("admin/usuarios");
      exit;
      
    } 
    else {
      $delete = $this->Data->deleteUser($id);
        if ($delete) {
          Sessao::notify('success', 'Conta deletada', null, null, null);
          Url::redireciona("admin/usuarios");
          exit;
        } else {
          Sessao::notify('error', 'Conta não deletada', "error", null, null);
          Url::redireciona("admin/usuarios");
          exit;
        }
    }
  }
}
