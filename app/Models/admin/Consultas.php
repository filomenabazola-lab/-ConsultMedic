<?php

namespace App\Models\admin;

use App\Libraries\Conexao;

class Consultas
{
  private $db;
  public function __construct()
  {
    $this->db = new Conexao;
  }
  // consultas marcadas
  
  public function consultas()
  {
    if($_SESSION['teste0_type'] == '0')
    {
      $this->db->query("SELECT *, consults.id_consults as idC, consults.name as nameC, departaments.name as nameD, consults.email as emailC, consults.description as descrC FROM  consults INNER JOIN departaments ON consults.id_departament = departaments.id_departaments ORDER BY consults.created_at DESC");
    }
    else
    {
      $this->db->query("SELECT *, consults.id_consults as idC, consults.name as nameC, departaments.name as nameD, consults.email as emailC, consults.description as descrC FROM  consults INNER JOIN departaments ON consults.id_departament = departaments.id_departaments WHERE consults.id_departament = :id ORDER BY consults.created_at DESC");
      $this->db->bind(":id", $_SESSION['teste0_depart']);
    }
    if ($this->db->executa() and $this->db->total()) :
      $resultado = $this->db->resultados();
      return $resultado;

    else :
      return false;

    endif;
  }
  public function consulta($id)
  {
    $this->db->query("SELECT * FROM  consults WHERE id_consults = :id");
    $this->db->bind(":id", $id);
    if ($this->db->executa() and $this->db->total()) :
      $resultado = $this->db->resultado();
      return $resultado;

    else :
      return false;

    endif;
  }

  public function getStatusById(int $id): ?string
  {
    $this->db->query('SELECT status FROM consults WHERE id_consults = :id LIMIT 1');
    $this->db->bind(':id', $id);
    if (!$this->db->executa()) {
      return null;
    }

    $row = $this->db->resultado();
    return is_array($row) ? ($row['status'] ?? null) : null;
  }

  public function consultaDetalhada($id)
  {
    $this->db->query("SELECT 
        consults.id_consults,
        consults.id_departament,
        consults.name AS nameC,
        consults.email AS emailC,
        consults.description AS descrC,
        consults.datetime,
        consults.status,
        consults.phone,
        departaments.name AS nameD,
        (
          SELECT u.name
          FROM users u
          WHERE u.id_departament = consults.id_departament
            AND u.type_user = '1'
          ORDER BY u.name ASC
          LIMIT 1
        ) AS doctor_name
      FROM consults
      INNER JOIN departaments ON consults.id_departament = departaments.id_departaments
      WHERE consults.id_consults = :id
      LIMIT 1");
    $this->db->bind(":id", $id);
    if (!$this->db->executa()) {
      return false;
    }

    $resultado = $this->db->resultado();
    return is_array($resultado) ? $resultado : false;
  }

  public function consultaPC()
  {
    if($_SESSION['teste0_type'] == '0')
    {
      $this->db->query("SELECT *, consults.id_consults as idC, consults.name as nameC, departaments.name as nameD, consults.email as emailC , consults.description as descrC FROM  consults INNER JOIN departaments ON consults.id_departament = departaments.id_departaments WHERE status = :status1 OR status = :status2 ORDER BY consults.created_at DESC LIMIT 5");
      $this->db->bind(":status1", "pendente");
      $this->db->bind(":status2", "confirmada");
    }
    else
    {

      $this->db->query("SELECT *, consults.id_consults as idC, consults.name as nameC, departaments.name as nameD, consults.email as emailC, consults.description as descrC FROM  consults INNER JOIN departaments ON consults.id_departament = departaments.id_departaments WHERE (status = :status1 OR status = :status2 )AND consults.id_departament = :id ORDER BY consults.created_at DESC LIMIT 5");
      $this->db->bind(":status1", "pendente");
      $this->db->bind(":status2", "confirmada");
      $this->db->bind(":id", $_SESSION['teste0_depart']);
    }
    if ($this->db->executa() and $this->db->total()) :
      $resultado = $this->db->resultados();
      return $resultado;

    else :
      return false;

    endif;
  }
  public function create($dados)
  {
    $this->db->query("INSERT INTO consults (id_departament, name, email, phone, description, datetime, created_at, updated_at) VALUES(:id_departament, :name, :email, :phone, :description, :datetime, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP())");
    // $this->db->bind(":id_users", $dados['doutor']);
    $this->db->bind(":id_departament", $dados['departamento']);
    $this->db->bind(":name", $dados['nome']);
    $this->db->bind(":email", $dados['email']);
    $this->db->bind(":phone", $dados['phone']);
    $this->db->bind(":description", $dados['mensagem']);
    $this->db->bind(":datetime", $dados['data']);
    if ($this->db->executa() and $this->db->total()) {
      return true;
    } else {
      return false;
    }
  }
  public function update($dados, $id)
  {
    $this->db->query("UPDATE consults SET status = :status, updated_at = CURRENT_TIMESTAMP() WHERE id_consults = :id");
    $this->db->bind(":status", $dados['status']);
    $this->db->bind(":id", $id);

    return $this->db->executa() !== false;
  }

  public function updateStatus(int $id, string $status): bool
  {
    return $this->update(['status' => $status], $id);
  }
  public function delete($id)
  {
    $this->db->query("DELETE FROM consults WHERE id_consults = :id ");
    $this->db->bind(":id", $id);

    if ($this->db->executa() and $this->db->total()) :
      return true;

    else :
      return false;

    endif;
  }
}
