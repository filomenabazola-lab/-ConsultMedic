<?php
namespace App\Models\admin;

use App\Libraries\Conexao;
use PDO;

class Auth {

   private $db;
    public function __construct()
    {
       $this->db = new Conexao();
    }

    public function checalogin($email,$senha){
      $this->db->query("SELECT * FROM users WHERE email=:email");
      $this->db->bind(':email',$email);
      if (!$this->db->executa()) {
          return false;
      }

      $resultado = $this->db->resultado();
      if (!$resultado) {
          return false;
      }

      if (password_verify($senha, $resultado['password'])) {
          return $resultado;
      }

      return false;
  }
    public function checanome(string $nome){
      $this->db->query("SELECT name FROM users WHERE name=:nome");
      $this->db->bind(':nome',$nome);
      if (!$this->db->executa()) {
          return false;
      }

      return (bool) $this->db->resultado();
  }
    public function checaemail(string $email){
      $this->db->query("SELECT email FROM users WHERE email=:email");
      $this->db->bind(':email',$email);
      if (!$this->db->executa()) {
          return false;
      }

      return (bool) $this->db->resultado();
  }

    public function createUser($data){
      $this->db->query("INSERT INTO users(id_departament, name, email, password, type_user, created_at, updated_at) VALUES(:depart, :nome, :email, :senha, :nivel, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP())");
      $this->bindDepartament($data['depart'] ?? null);
      $this->db->bind(":nome", $data['nome']); 
      $this->db->bind(":email", $data['email']);
      $this->db->bind(":senha", $data['senha']);
      $this->db->bind(":nivel", (int) $data['nivel'], PDO::PARAM_INT);

      if (!$this->db->executa()) {
        return false;
      }

      return (int) $this->db->ultimoid() > 0;
    }

    public function updateUser($data,$id){
      $this->db->query("UPDATE users SET id_departament=:depart, name=:nome, email=:email, type_user=:nivel, updated_at = CURRENT_TIMESTAMP() WHERE id_users=:id");
      $this->bindDepartament($data['depart'] ?? null);
      $this->db->bind(":nome", $data['nome']); 
      $this->db->bind(":email", $data['email']);
      $this->db->bind(":nivel", (int) $data['nivel'], PDO::PARAM_INT);
      $this->db->bind(":id", (int) $id, PDO::PARAM_INT);

      return $this->db->executa() !== false;
    }

    public function getUsers(){
      $this->db->query("SELECT users.*, users.name AS nameU, departaments.name AS nameD FROM users LEFT JOIN departaments ON users.id_departament = departaments.id_departaments ORDER BY users.id_users DESC");
      if (!$this->db->executa()) {
          return false;
      }

      $resultados = $this->db->resultados();
      return !empty($resultados) ? $resultados : false;
    }

    public function deleteUser($id){
      $this->db->query("DELETE FROM users WHERE id_users = :id");
      $this->db->bind(":id", (int) $id, PDO::PARAM_INT);

      return $this->db->executa() !== false;
    }

    private function bindDepartament($depart): void
    {
      if ($depart === null || $depart === '') {
        $this->db->bind(':depart', null, PDO::PARAM_NULL);
        return;
      }

      $this->db->bind(':depart', (int) $depart, PDO::PARAM_INT);
    }
}
