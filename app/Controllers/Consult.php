<?php

namespace App\Controllers;

use App\Helpers\Sessao;
use App\Helpers\Url;
use App\Helpers\Valida;
use App\Libraries\Controller;
use App\Services\ConsultNotificationService;


class Consult extends controller
{
  private $Data;
  private $notificationService;


  public function __construct()
  {
    $this->Data = $this->model("user\Home");
    $this->notificationService = new ConsultNotificationService();
  }
  public function index()
  {
    $departs = $this->Data->getDepartaments();
    $file = 'consultpage';
    $title = 'consult';
    return $this->view("consultpage", compact("departs", "consult"));
  }
  public function agenda()
  {
    $formulario = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
    if (isset($formulario['bu'])) :
      
      $dados = [
        'nome' => trim($formulario['nome']),
        'email' => trim($formulario['email']),
        'phone' => trim($formulario['phone']),
        'data' => trim($formulario['data']),
        'departamento' => trim($formulario['departamento']),
        'mensagem' => trim($formulario['mensagem']),
        'error_nome' => '',
        'error_email' => '',
        'error_phone' => '',
        'error_data' => '',
        'error_departamento' => '',
      ];
      $formularioFiltrado = array_filter($formulario, function($key) {
        return !in_array($key, ['mensagem', 'bu'], true);
    }, ARRAY_FILTER_USE_KEY);
      
      if (in_array("", $formularioFiltrado, true)) :
        
        Sessao::notify('error', 'Preencha todos campos', "error", null, null);

        if (empty($formulario['nome'])) :
          $dados['error_nome'] = "preencha o campo nome";
        endif;

        if (empty($formulario['email'])) :
          $dados['error_email'] = "preencha o campo email";
        endif;

        if (empty($formulario['phone'])) :
          $dados['error_phone'] = "preencha o campo phone";
        endif;

        if (empty($formulario['data'])) :
          $dados['error_data'] = "preencha a data do consulta";
        endif;

        if (empty($formulario['departamento'])) :
          $dados['error_departamento'] = "preencha o campo departamento";
        endif;

      else :
       
        if (Valida::nomeComNumeros($dados['nome'])) {
          Sessao::notify('error', 'O nome não pode conter números.', "error", null, null);
          Url::redireciona('do');
          exit;
        }

        if (Valida::emailInvalido($dados['email'])) {
          Sessao::notify('error', 'Informe um e-mail valido (exemplo: nome@email.com).', "error", null, null);
          Url::redireciona('do');
          exit;
        }

        if (Valida::telefoneInvalido($dados['phone'])) {
          Sessao::notify('error', 'Informe um telefone angolano valido: 9 digitos iniciando com 9 (ex: 923456789).', "error", null, null);
          Url::redireciona('do');
          exit;
        }

        $dados['email'] = strtolower($dados['email']);
        $dados['phone'] = Valida::normalizarTelefone($dados['phone']);

          $dataHoraConsulta = $this->normalizeLocalDatetime($dados['data']);
          if ($dataHoraConsulta === null) {
            Sessao::notify('error', 'Data e horario invalidos para agendamento.', "error", null, null);
            Url::redireciona('do');
            exit;
          }

          $medicoAtribuido = $this->Data->resolveDoctorForBooking($dados['departamento'], $dataHoraConsulta);
          if (!$medicoAtribuido) {
            $mensagemMedico = method_exists($this->Data, 'getLastError') ? $this->Data->getLastError() : '';
            Sessao::notify('error', $mensagemMedico ?: 'Nao foi possivel atribuir medico para este agendamento.', 'error', null, null);
            Url::redireciona('do');
            exit;
          }

          $doctorId = (int) ($medicoAtribuido['id_users'] ?? 0);
          $dados['data'] = $dataHoraConsulta;
          $save = $this->Data->agenda($dados);
          if ($save) :
            try {
              $departamentoNome = $this->getDepartamentName($dados['departamento']);
              [$dataConsulta, $horaConsulta] = $this->formatAppointmentDateAndTime($dados['data']);
              $medicoNome = $medicoAtribuido['name'] ?? 'A definir pela Clinica Agosmil';
              $recomendacoesPrevias = !empty($dados['mensagem']) ? $dados['mensagem'] : 'Chegar com 15 minutos de antecedencia e levar documento de identificacao.';

              $dadosNotificacao = [
                'paciente' => $dados['nome'],
                'email' => $dados['email'],
                'telefone' => Valida::formatarTelefone($dados['phone']),
                'especialidade' => $departamentoNome,
                'id_departament' => $dados['departamento'],
                'id_users' => $doctorId,
                'medico' => $medicoNome,
                'data' => $dataConsulta,
                'horario' => $horaConsulta,
                'mensagem' => $dados['mensagem'],
                'recomendacoes' => $recomendacoesPrevias,
                'status' => 'pendente',
                'admin_emails' => $this->Data->getAdminEmails(),
              ];

              $errosEnvio = [];

              try {
                $this->notificationService->sendAppointmentNotification($dadosNotificacao);
              } catch (\Throwable $e) {
                $errosEnvio[] = 'Paciente: ' . $e->getMessage();
              }

              try {
                $this->notificationService->sendAdminPendingAppointmentAlert($dadosNotificacao);
              } catch (\Throwable $e) {
                $errosEnvio[] = 'Admin: ' . $e->getMessage();
              }

              if (empty($errosEnvio)) {
                Sessao::notify('success', 'Consulta marcada com sucesso. Notificacoes enviadas.', null, null, null);
              } elseif (count($errosEnvio) === 1 && str_starts_with($errosEnvio[0], 'Admin:')) {
                Sessao::notify('success', 'Consulta marcada. Falha ao avisar administradores por e-mail.', null, null, null);
              } else {
                Sessao::notify('error', 'Consulta marcada, mas houve falha no envio: ' . implode(' | ', $errosEnvio), 'error', null, null);
              }
            } catch (\Throwable $e) {
              $erroEnvio = $e->getMessage();
              Sessao::notify('error', "Erro apos agendamento: {$erroEnvio}", "error", null, null);
            }
            Url::redireciona('home');
            exit;

          else :
            $modelError = method_exists($this->Data, 'getLastError') ? $this->Data->getLastError() : '';
            $message = !empty($modelError) ? $modelError : 'Erro, agendamento não registrado';
            Sessao::notify('error', $message, "error", null, null);
          endif;
      endif;
      
    else :
      $dados = [
        'nome' => '',
        'email' => '',
        'phone' => '',
        'data' => '',
        'departamento' => '',
        'mensagem' => '',
        'error_nome' => '',
        'error_email' => '',
        'error_phone' => '',
        'error_data' => '',
        'error_departamento' => '',
      ];
    endif;
  }

  public function getDoctors()
  {
    header('Content-Type: application/json');

    $departamentoId = $_GET['departamento'] ?? null;

    if (!$departamentoId) {
      echo json_encode([]);
      exit;
    }
    $doutores = $this->Data->getUsersByDepart($departamentoId);

    echo json_encode($doutores);
  }

  public function getBookedSlots()
  {
    header('Content-Type: application/json');

    $doctorId = isset($_GET['doutor']) ? (int)$_GET['doutor'] : 0;
    $date = $_GET['data'] ?? '';

    if ($doctorId <= 0 || !$this->isValidDate($date)) {
      echo json_encode([]);
      exit;
    }

    $slots = $this->Data->getBookedSlotsByDoctorAndDate($doctorId, $date);
    $occupied = [];

    foreach ($slots as $slot) {
      $dateObj = \DateTime::createFromFormat('Y-m-d H:i:s', $slot['datetime']);
      if (!$dateObj) {
        continue;
      }
      $occupied[] = [
        'time' => $dateObj->format('H:i'),
        'status' => $slot['status'],
      ];
    }

    echo json_encode($occupied);
    exit;
  }

  private function getDepartamentName($departamentId): string
  {
    $departaments = $this->Data->getDepartaments();

    if (!$departaments) {
      return 'Nao informado';
    }

    foreach ($departaments as $departament) {
      if ((string)$departament['id'] === (string)$departamentId) {
        return $departament['name'];
      }
    }

    return 'Nao informado';
  }

  private function formatAppointmentDateAndTime(string $rawDatetime): array
  {
    $date = \DateTime::createFromFormat('Y-m-d\TH:i', $rawDatetime);

    if (!$date) {
      $timestamp = strtotime($rawDatetime);
      if ($timestamp === false) {
        return ['Nao informada', 'Nao informado'];
      }
      $date = new \DateTime();
      $date->setTimestamp($timestamp);
    }

    return [$date->format('d/m/Y'), $date->format('H:i')];
  }

  private function isValidDate(string $date): bool
  {
    $dateObj = \DateTime::createFromFormat('Y-m-d', $date);
    return $dateObj && $dateObj->format('Y-m-d') === $date;
  }

  private function isValidInterval(string $time): bool
  {
    if (!preg_match('/^\d{2}:\d{2}$/', $time)) {
      return false;
    }
    [, $minute] = explode(':', $time);
    return in_array((int)$minute, [0, 30], true);
  }

  private function combineDateAndTime(string $date, string $time): ?string
  {
    if (!$this->isValidDate($date) || !$this->isValidInterval($time)) {
      return null;
    }
    $dateTime = \DateTime::createFromFormat('Y-m-d H:i', $date . ' ' . $time);
    if (!$dateTime) {
      return null;
    }
    return $dateTime->format('Y-m-d H:i:s');
  }

  private function normalizeLocalDatetime(string $value): ?string
  {
    $dateTime = \DateTime::createFromFormat('Y-m-d\TH:i', $value);
    if (!$dateTime) {
      return null;
    }

    return $dateTime->format('Y-m-d H:i:s');
  }

}
