<?php



namespace App\Controllers\Admin;

use App\Helpers\Sessao;
use App\Helpers\Url;
use App\Libraries\Controller;
use App\Libraries\uploads;

class Departamento extends Controller
{
  private $Data;
  public function __construct()
  {
    $this->Data = $this->model("admin\Departamentos");
   if (!Sessao::nivel0()) {
      Url::redireciona("admin/login");
    }
  }   
  public function index()  
  {
    $dados=$this->Data->departamentos();
    $file = 'admin/Departamentos/listar';
    $title = 'departamentos';
    return $this->view('layouts/admin/app', compact('file', 'title', 'dados'));
  }
  public function create() 
  {
    if(Sessao::nivel1()){
      Sessao::notify("error", "Acesso Negado", "error");
      Url::redireciona('admin/home');
      exit;
    }
    
    $form = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if ($form['depart']) {
      $dados = [
        'nome' => trim($form['nome']),
        'desc' => trim($form['desc']),
        'error' => ''
      ];

      if (in_array("", $form)) {
        if (empty($dados['nome']) || empty($dados['desc'])) {
          $dados['error'] = "Preencha todos os campos";
          Sessao::sms("noticia", "Alerta: *Não deixe nunhum campo vazio", "alert alert-info");
        }
      } else {
          $save = $this->Data->create($dados);
          if ($save) {
            Sessao::notify("success", "Cadastrado com sucesso");
            Url::redireciona("admin/departamentos");
            exit;
          } else {
            Sessao::notify("error", "Erro", "error");
            Url::redireciona("admin/departamentos");
          }
        } 
    } else {
      $dados = [
        'nome' => '',
        'desc' => '',
        'error' => ''
      ];
    }

    $file = 'admin'.DIRECTORY_SEPARATOR.'Departamentos'.DIRECTORY_SEPARATOR.'novo';
    $title = 'Novo Depart';
    return $this->view('layouts/admin/app', compact('file', 'title','dados'));
  }
  public function edit($id)
  {
    if(Sessao::nivel1()){
      Sessao::notify("error", "Acesso Negado", "error");
      Url::redireciona('admin/home');
      exit;
    }

    $id=filter_var($id['id'], FILTER_VALIDATE_INT);
    $form = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    
  

      if (in_array("", $form)) {
          $dados['error'] = "Preencha todos os campos";
          Sessao::notify("error", "Alerta: *Não deixe nunhum campo vazio", "error");
        
      } else {
        $dados=['nome'=>trim($form['nome']), 'media'=>trim($form['media']), 'desc'=>trim($form['desc'])];
          $save = $this->Data->update($dados,$id);
          if ($save) {
            Sessao::notify("success", "Atualizado com sucesso");
            Url::redireciona("admin/departamentos");
            exit;
          } else {
            Sessao::notify("error", "Não atualizado", "error");
            Url::redireciona("admin/departamentos");
          }
      }
   
  }
  public function delete($id) 
  {
    if(Sessao::nivel1()){
      Sessao::notify("error", "Acesso Negado", "error");
      Url::redireciona('admin/home');
      exit;
    }

    $id=filter_var($id['id'], FILTER_VALIDATE_INT);
    $delete =$this->Data->delete($id);
    if($delete){
      Sessao::notify('success', 'Deletado com sucesso', null, null, null);
      Url::redireciona("admin/departamentos");
      exit;
    }else{
      Sessao::notify('error', 'Erro, não deletado', "error", null, null);
      Url::redireciona("admin/departamentos");
      exit;
    }
  }
  
}
