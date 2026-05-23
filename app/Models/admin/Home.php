<?php

namespace App\Models\admin;

use App\Libraries\Conexao;
use DateTime;

class Home
{

  private $db;
  public function __construct()
  {
    $this->db = new Conexao();
  }


  public function getCountUsers()
  {
    $this->db->query("SELECT COUNT(id_users) as userTotal FROM users WHERE type_user != :type");
    $this->db->bind(":type", '0');
    if ($this->db->executa() and $this->db->total()):
      return $this->db->resultado();
    else:
      return false;
    endif;
  }
  public function getCountDepartaments()
  {
    $this->db->query("SELECT COUNT(id_departaments) as departTotal FROM departaments ");


    if ($this->db->executa() and $this->db->total()):
      return $this->db->resultado();
    else:
      return false;
    endif;
  }
  // realizadas apenas
  public function getCountConsults()
  {
    if ($_SESSION['teste0_type'] == '0') {
      $this->db->query("SELECT COUNT(id_consults) as consultTotal FROM consults WHERE status = :confirm ");

      $this->db->bind(":confirm", "realizada");
    } else {
      $this->db->query("SELECT COUNT(id_consults) as consultTotal FROM consults WHERE status = :confirm AND id_departament = :id ");
      $this->db->bind(":confirm", "realizada");
      $this->db->bind(":id", $_SESSION['teste0_depart']);
    }
    if ($this->db->executa() and $this->db->total()):
      return $this->db->resultado();
    else:
      return false;
    endif;
  }
}
