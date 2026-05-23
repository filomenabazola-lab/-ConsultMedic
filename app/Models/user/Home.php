<?php

namespace App\Models\user;

use App\Libraries\Conexao;

class Home
{

    private $db;
    private string $lastError = '';
    public function __construct()
    {
        $this->db = new Conexao();
    }
    
    public function getDepartaments()
    {
        $this->db->query("SELECT *, id_departaments as id from departaments");
        $this->db->executa();
        if ($this->db->executa() and $this->db->total()) {
            $result = $this->db->resultados();
            return $result;
        } else {
            return false;
        }
    }
    public function getUsersByDepart($id)
    {
        $this->db->query("SELECT id_users, name, email FROM users WHERE id_departament = :id AND type_user = :nivel ORDER BY name ASC");
        $this->db->bind(":id", $id);
        $this->db->bind(":nivel", '1');
        if (!$this->db->executa()) {
            return false;
        }

        $result = $this->db->resultados();
        return !empty($result) ? $result : false;
    }

    public function getDoctorById(int $doctorId): ?array
    {
        $this->db->query("SELECT id_users, id_departament, name, email FROM users WHERE id_users = :id AND type_user = :nivel LIMIT 1");
        $this->db->bind(":id", $doctorId);
        $this->db->bind(":nivel", '1');
        if (!$this->db->executa()) {
            return null;
        }

        $row = $this->db->resultado();
        return is_array($row) ? $row : null;
    }

    public function getDoctorNameByDepartment($departamentId): ?string
    {
        $doctors = $this->getUsersByDepart($departamentId);
        if (!$doctors) {
            return null;
        }

        return $doctors[0]['name'] ?? null;
    }

    public function getAdminEmails(): array
    {
        $this->db->query("SELECT name, email FROM users WHERE type_user = 0 AND email <> '' ORDER BY name ASC");
        if (!$this->db->executa()) {
            return [];
        }

        $rows = $this->db->resultados();
        return is_array($rows) ? $rows : [];
    }

    public function resolveDoctorForBooking($departamentId, string $datetime): ?array
    {
        $doctors = $this->getUsersByDepart($departamentId);
        if (!$doctors) {
            $this->lastError = 'Nenhum medico cadastrado neste departamento.';
            return null;
        }

        if (!$this->isSlotAvailableByDepartment($departamentId, $datetime)) {
            $this->lastError = 'Este horario ja esta ocupado. Escolha outra data ou hora.';
            return null;
        }

        return $doctors[0];
    }

    public function agenda($dados)
    {
        $this->db->query("INSERT INTO consults (id_departament, name, email, phone, description, datetime, created_at, updated_at) VALUES(:id_departament, :name, :email, :phone, :description, :datetime, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP())");
        $this->db->bind(":id_departament", $dados['departamento']);
        $this->db->bind(":name", $dados['nome']);
        $this->db->bind(":email", $dados['email']);
        $this->db->bind(":phone", $dados['phone']);
        $this->db->bind(":description", $dados['mensagem']);
        $this->db->bind(":datetime", $dados['data']);
        if (!$this->db->executa()) {
            $this->lastError = 'Falha ao inserir agendamento no banco de dados.';
            return false;
        }

        if ((int) $this->db->ultimoid() <= 0) {
            $this->lastError = 'Agendamento nao foi registrado corretamente.';
            return false;
        }

        return true;
    }

    public function isSlotAvailableByDepartment($departamentId, string $datetime): bool
    {
        $this->db->query("SELECT id_consults FROM consults WHERE id_departament = :id_departament AND datetime = :datetime AND status IN ('pendente', 'confirmada', 'realizada') LIMIT 1");
        $this->db->bind(":id_departament", $departamentId);
        $this->db->bind(":datetime", $datetime);
        if ($this->db->executa() && $this->db->total()) {
            return false;
        }

        return true;
    }

    public function isSlotAvailable(int $doctorId, string $datetime): bool
    {
        $doctor = $this->getDoctorById($doctorId);
        if (!$doctor || empty($doctor['id_departament'])) {
            return false;
        }

        return $this->isSlotAvailableByDepartment($doctor['id_departament'], $datetime);
    }

    public function getBookedSlotsByDoctorAndDate(int $doctorId, string $date): array
    {
        $doctor = $this->getDoctorById($doctorId);
        if (!$doctor || empty($doctor['id_departament'])) {
            return [];
        }

        $start = $date . ' 00:00:00';
        $end = $date . ' 23:59:59';

        $this->db->query("SELECT datetime, status FROM consults WHERE id_departament = :id_departament AND datetime BETWEEN :start AND :end AND status IN ('pendente', 'confirmada', 'realizada') ORDER BY datetime ASC");
        $this->db->bind(":id_departament", $doctor['id_departament']);
        $this->db->bind(":start", $start);
        $this->db->bind(":end", $end);
        if ($this->db->executa() && $this->db->total()) {
            return $this->db->resultados();
        }

        return [];
    }

    public function getLastError(): string
    {
        return $this->lastError;
    }
   
}
