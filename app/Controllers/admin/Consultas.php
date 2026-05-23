<?php



namespace App\Controllers\Admin;

use App\Helpers\Sessao;
use App\Helpers\Url;
use App\Libraries\Controller;
use App\Services\ConsultNotificationService;

class Consultas extends Controller
{
  private $Data;
  private $Agenda;
  private $notificationService;
  public function __construct()
  {
    $this->Agenda = $this->model("admin\Consultas");
    $this->Data = $this->model("admin\Consultas");
    $this->notificationService = new ConsultNotificationService();
    if (!Sessao::nivel0()) {
      Url::redireciona("admin/login");
    }
  }
  public function index()
  {
    $consultas=$this->Data->consultas();
   
    // var_dump($consultas);
    // exit;

    $file = 'admin/Consultas/index';
    $title = 'Consults';
    return $this->view('layouts/admin/app', compact('file', 'title', 'consultas'));
  }

  public function consultsRecents($id)
  {
    $consultId = filter_var($id['id'] ?? null, FILTER_VALIDATE_INT);
    $form = filter_input_array(INPUT_POST, FILTER_DEFAULT) ?: [];

    if (!$consultId) {
      Sessao::notify('error', 'Consulta invalida.', 'error');
      Url::redireciona('admin/home');
      exit;
    }

    if (!isset($form['btn1'])) {
      Url::redireciona('admin/home');
      exit;
    }

    $novoStatus = trim((string)($form['radio'] ?? ''));
    if (!in_array($novoStatus, ['confirmada', 'cancelada'], true)) {
      Sessao::notify('error', 'Selecione Confirmar ou Cancelar.', 'error');
      Url::redireciona('admin/home');
      exit;
    }

    $motivo = trim((string)($form['desc'] ?? ''));

    if (!$this->Agenda->updateStatus($consultId, $novoStatus)) {
      Sessao::notify('error', 'Nao foi possivel atualizar o estado da consulta.', 'error');
      Url::redireciona('admin/home');
      exit;
    }

    try {
      $this->enviarNotificacaoPaciente($consultId, $motivo);
      $statusLabel = $novoStatus === 'confirmada' ? 'confirmada' : 'cancelada';
      Sessao::notify('success', "Consulta {$statusLabel} e notificacao enviada ao paciente.");
    } catch (\Throwable $e) {
      Sessao::notify('error', 'Estado atualizado, mas falha ao enviar e-mail ao paciente: ' . $e->getMessage(), 'error');
    }

    Url::redireciona('admin/home');
    exit;
  }

  private function enviarNotificacaoPaciente(int $consultId, string $motivo = ''): void
  {
    $statusDb = $this->Agenda->getStatusById($consultId);
    if (!in_array($statusDb, ['confirmada', 'cancelada'], true)) {
      throw new \RuntimeException(
        'Estado na base de dados invalido para notificacao (' . ($statusDb ?? 'vazio') . ').'
      );
    }

    $consulta = $this->Agenda->consultaDetalhada($consultId);
    if (!$consulta) {
      throw new \RuntimeException('Consulta nao encontrada.');
    }

    if (empty(trim((string)($consulta['emailC'] ?? '')))) {
      throw new \RuntimeException('E-mail do paciente nao cadastrado.');
    }

    [$dataConsulta, $horaConsulta] = $this->formatAppointmentDateAndTime($consulta['datetime']);

    $this->notificationService->sendAppointmentNotification([
      'id_consults' => $consultId,
      'paciente' => $consulta['nameC'],
      'email' => $consulta['emailC'],
      'especialidade' => $consulta['nameD'],
      'id_departament' => $consulta['id_departament'] ?? null,
      'medico' => $consulta['doctor_name'] ?? '',
      'data' => $dataConsulta,
      'horario' => $horaConsulta,
      'recomendacoes' => !empty($consulta['descrC']) ? $consulta['descrC'] : 'Chegar com 15 minutos de antecedencia e levar documento de identificacao.',
      'motivo' => $motivo,
    ]);
  }
  public function realized($id){
    $id=filter_var($id['id'], FILTER_VALIDATE_INT);
    $dados = ['status' => "realizada"];
    $update =$this->Agenda->update($dados, $id);
    if($update){
      Sessao::notify('success', 'Atualizada com sucesso', null, null, null);
      Url::redireciona("admin/home");
      exit;
    }else{
      Sessao::notify('error', 'Erro, não atualizada', "error", null, null);
      Url::redireciona("admin/home");
      exit;
    }
  }

  private function formatAppointmentDateAndTime(string $rawDatetime): array
  {
    $date = \DateTime::createFromFormat('Y-m-d H:i:s', $rawDatetime);

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
  
}
