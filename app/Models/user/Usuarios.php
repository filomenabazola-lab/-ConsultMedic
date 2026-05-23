<?php

namespace App\Models\user;

use App\Libraries\Conexao;

class Usuarios
{

  private $db;
  public function __construct()
  {
    $this->db = new Conexao();
  }



  public function checalogin($email, $senha)
  {
    $this->db->query("SELECT * FROM usuarios WHERE email=:email AND adm=:nivel");
    $this->db->bind(':email', $email);
    $this->db->bind(':nivel', "1");
    $this->db->executa();
    if ($this->db->executa() and $this->db->total()) :
      $resultado = $this->db->resultado();

      if (password_verify($senha, $resultado['senha'])) :
        return $resultado;
      else :
        return false;
      endif;

    else :
      return false;
    endif;
  }
  public function checaEmail(string $email)
  {
    $this->db->query("SELECT email FROM usuarios WHERE email=:email");
    $this->db->bind(':email', $email);
    $this->db->executa();
    if ($this->db->executa() and $this->db->total()) :
      return true;
    else :
      return false;
    endif;
  }
  public function storeUser($dados)
  {
    $this->db->query("INSERT INTO usuarios(nome, senha, adm, email) VALUES(:nome, :senha, :nivel, :email)");
    $this->db->bind(':nome', $dados['nome']);
    $this->db->bind(':senha', $dados['senha']);
    $this->db->bind(':nivel', '1');
    $this->db->bind(':email', $dados['email']);

    if ($this->db->executa() and $this->db->total()) :
      return true;
    else :
      return false;
    endif;
  }

  // contact form
  public  function  storeMessage($dados)
  {
    $this->db->query("INSERT INTO contact(nome, email, assunto, mensagem) VALUES(:nome, :email, :assunto, :mensagem)");
    $this->db->bind(':nome', $dados['nome']);
    $this->db->bind(':email', $dados['email']);
    $this->db->bind(':assunto', $dados['assunto']);
    $this->db->bind(':mensagem', $dados['mensagem']);

    if ($this->db->executa() and $this->db->total()) :
      return true;
    else :
      return false;
    endif;
  }
}
