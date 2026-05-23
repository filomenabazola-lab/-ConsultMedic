<?php

namespace App\Models\admin;

use App\Libraries\Conexao;

class Departamentos
{
  private $db;
  public function __construct()
  {
    $this->db = new Conexao;
  }
  // relatorios dde vendas
  public function departamentos()
  {
    $this->db->query("SELECT * FROM  departaments ORDER BY id_departaments DESC ");

    if ($this->db->executa() and $this->db->total()) :
      $resultado = $this->db->resultados();
      return $resultado;

    else :
      return false;

    endif;
  }
  public function departamento($id)
  {
    $this->db->query("SELECT * FROM  departaments WHERE id_departments=:id");
    $this->db->bind(":id", $id);
    if ($this->db->executa() and $this->db->total()) :
      $resultado = $this->db->resultados();
      return $resultado;

    else :
      return false;

    endif;
  }
  public function create($dados)
  {
    $this->db->query("INSERT INTO departaments(name,description, created_at, updated_at) VALUES(:nome,:descricao, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP()) ");
    $this->db->bind(":nome", $dados['nome']);
    $this->db->bind(":descricao", $dados['desc']);
    if ($this->db->executa() and $this->db->total()) :
      return true;

    else :
      return false;

    endif;
  }
  public function update($dados,$id)
  {
    $this->db->query("UPDATE departaments SET name=:nome, description=:descricao, updated_at = CURRENT_TIMESTAMP() WHERE id_departaments=:id");
    $this->db->bind(":nome", $dados['nome']);
    $this->db->bind(":descricao", $dados['desc']);
    $this->db->bind(":id", $id);
    if ($this->db->executa() and $this->db->total()) :
      return true;

    else :
      return false;

    endif;
  }
  public function delete($id)
  {
    $this->db->query("DELETE FROM departaments WHERE id_departaments=:id ");
    $this->db->bind(":id", $id);
    
    if ($this->db->executa() and $this->db->total()) :
      return true;

    else :
      return false;

    endif;
  }
}
