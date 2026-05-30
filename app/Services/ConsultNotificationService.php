<?php

namespace App\Services;

use App\Libraries\Conexao;
use PHPMailer\PHPMailer\PHPMailer;

class ConsultNotificationService
{
    public function sendAppointmentNotification(array $appointmentData): void
    {
        $patientName = $appointmentData['paciente'] ?? 'Paciente';
        $patientEmail = $appointmentData['email'] ?? '';
        $status = $this->resolveStatus($appointmentData);

        if (empty($patientEmail)) {
            throw new \RuntimeException('E-mail do paciente nao informado.');
        }

        [$statusLabel, $statusHeadline, $statusDescription] = $this->statusPresentation($status, $appointmentData['motivo'] ?? '');

        $payload = [
            'paciente' => $patientName,
            'especialidade' => $appointmentData['especialidade'] ?? 'Não informada',
            'medico' => $this->resolveDoctorName($appointmentData),
            'data' => $appointmentData['data'] ?? 'Não informada',
            'horario' => $appointmentData['horario'] ?? 'Não informado',
            'status' => $statusLabel,
            'status_headline' => $statusHeadline,
            'status_description' => $statusDescription,
            'motivo' => $appointmentData['motivo'] ?? '',
        ];

        $logoPath = $this->resolveLogoPath();
        $logoCid = 'agosmil-logo';

        $pdfBinary = null;
        try {
            $pdfBinary = $this->generateAppointmentPdf($payload);
        } catch (\Throwable $pdfError) {
            throw new \RuntimeException('Falha ao gerar PDF da consulta: ' . $pdfError->getMessage(), 0, $pdfError);
        }

        $sendErrors = [];
        $attempts = $this->buildTransportAttempts();

        foreach ($attempts as $attempt) {
            $mail = $this->buildMailer($attempt['encryption'], $attempt['port']);
            $mail->setFrom((string)getenv('MAIL_FROM_ADDRESS') ?: (string)getenv('MAIL_USERNAME'), (string)getenv('APP_NAME') ?: 'Clinica AGOSMIL');
            $mail->addAddress($patientEmail, $patientName);
            $mail->isHTML(true);
            $mail->Subject = $this->emailSubjectForStatus($status);
            if ($logoPath !== null) {
                $mail->addEmbeddedImage($logoPath, $logoCid);
            }
            $mail->Body = $this->buildHtmlBody($payload, $logoPath !== null ? $logoCid : null);
            $mail->AltBody = "Clinica AGOSMIL - {$statusHeadline} Status atual: {$statusLabel}. Data: {$payload['data']} {$payload['horario']}.";
            $mail->addStringAttachment($pdfBinary, 'comprovante_consulta_agosmil.pdf', 'base64', 'application/pdf');

            try {
                $mail->send();
                return;
            } catch (\Throwable $sendError) {
                $sendErrors[] = "[{$attempt['encryption']}:{$attempt['port']}] " . $sendError->getMessage();
            }
        }

        throw new \RuntimeException('Falha no envio SMTP. Tentativas: ' . implode(' | ', $sendErrors));
    }

    public function sendAdminPendingAppointmentAlert(array $appointmentData): void
    {
        $recipients = $this->resolveAdminRecipients($appointmentData['admin_emails'] ?? null);
        if (empty($recipients)) {
            throw new \RuntimeException('Nenhum e-mail de administrador valido configurado.');
        }

        $paciente = trim((string)($appointmentData['paciente'] ?? 'Paciente'));
        $subject = 'Nova consulta pendente: ' . $paciente . ' - Clinica AGOSMIL';
        $logoPath = $this->resolveLogoPath();
        $logoCid = 'agosmil-admin-logo';
        $htmlBody = $this->buildAdminPendingHtmlBody($appointmentData, $logoPath !== null ? $logoCid : null);
        $altBody = $this->buildAdminPendingAltBody($appointmentData);
        $sendErrors = [];
        $attempts = $this->buildTransportAttempts();

        foreach ($attempts as $attempt) {
            foreach ($recipients as $recipient) {
                $mail = $this->buildMailer($attempt['encryption'], $attempt['port']);
                $mail->setFrom(
                    (string)getenv('MAIL_FROM_ADDRESS') ?: (string)getenv('MAIL_USERNAME'),
                    (string)getenv('APP_NAME') ?: 'Clinica AGOSMIL'
                );
                $mail->addAddress($recipient['email'], $recipient['name']);
                $mail->isHTML(true);
                $mail->Subject = $subject;
                if ($logoPath !== null) {
                    $mail->addEmbeddedImage($logoPath, $logoCid);
                }
                $mail->Body = $htmlBody;
                $mail->AltBody = $altBody;

                try {
                    $mail->send();
                    return;
                } catch (\Throwable $sendError) {
                    $sendErrors[] = "[{$recipient['email']}][{$attempt['encryption']}:{$attempt['port']}] " . $sendError->getMessage();
                }
            }
        }

        throw new \RuntimeException('Falha no envio SMTP para administradores. Tentativas: ' . implode(' | ', $sendErrors));
    }

    private function resolveAdminRecipients(?array $provided): array
    {
        if (is_array($provided) && !empty($provided)) {
            return $this->normalizeRecipients($provided);
        }

        return $this->normalizeRecipients($this->fetchAdminEmails());
    }

    private function fetchAdminEmails(): array
    {
        $db = new Conexao();
        $db->query("SELECT name, email FROM users WHERE type_user = 0 AND email <> '' ORDER BY name ASC");
        if (!$db->executa()) {
            return [];
        }

        $rows = $db->resultados();
        return is_array($rows) ? $rows : [];
    }

    private function normalizeRecipients(array $rows): array
    {
        $recipients = [];

        foreach ($rows as $row) {
            $email = strtolower(trim((string)($row['email'] ?? '')));
            if ($email === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                continue;
            }

            $recipients[$email] = [
                'email' => $email,
                'name' => trim((string)($row['name'] ?? 'Administrador')) ?: 'Administrador',
            ];
        }

        if (empty($recipients)) {
            $fallback = strtolower(trim((string)getenv('MAIL_FROM_ADDRESS') ?: (string)getenv('MAIL_USERNAME')));
            if ($fallback !== '' && filter_var($fallback, FILTER_VALIDATE_EMAIL)) {
                $recipients[$fallback] = ['email' => $fallback, 'name' => 'Administrador'];
            }
        }

        return array_values($recipients);
    }

    private function buildAdminPendingHtmlBody(array $data, ?string $logoCid = null): string
    {
        $logoSrc = $logoCid !== null
            ? 'cid:' . $logoCid
            : htmlspecialchars($this->assetUrl('assets/images/logo2.png'));
        $paciente = htmlspecialchars($data['paciente'] ?? 'Paciente');
        $email = htmlspecialchars($data['email'] ?? '');
        $telefone = htmlspecialchars($data['telefone'] ?? '');
        $especialidade = htmlspecialchars($data['especialidade'] ?? 'Não informada');
        $medico = htmlspecialchars($data['medico'] ?? 'A definir pela clínica');
        $dataConsulta = htmlspecialchars($data['data'] ?? '');
        $horario = htmlspecialchars($data['horario'] ?? '');
        $mensagem = trim((string)($data['mensagem'] ?? ''));
        $mensagemHtml = $mensagem !== ''
            ? htmlspecialchars($mensagem)
            : 'Sem mensagem adicional.';

        $iconAlert = $this->emailIconCircle('&#9888;', '#92400e', 36, '#fef3c7');
        $iconUser = $this->emailIconCircle('&#128100;', '#0a3d6b');
        $iconMail = $this->emailIconCircle('&#9993;', '#0a3d6b');
        $iconPhone = $this->emailIconCircle('&#128222;', '#0a3d6b');
        $iconSpecialty = $this->specialtyIcon($data['especialidade'] ?? '');
        $iconDoctor = $this->emailIconCircle('&#129657;', '#0a3d6b');
        $iconCalendar = $this->emailCalendarIcon();
        $iconClock = $this->emailIconCircle('&#128336;', '#0a3d6b');

        $rows = $this->adminDetailRow($iconUser, 'Paciente', $paciente)
            . $this->adminDetailRow($iconMail, 'E-mail', $email)
            . $this->adminDetailRow($iconPhone, 'Telefone', $telefone)
            . $this->adminDetailRow($iconSpecialty, 'Especialidade', $especialidade)
            . $this->adminDetailRow($iconDoctor, 'Médico do departamento', $medico)
            . $this->adminDetailRow($iconCalendar, 'Data', $dataConsulta)
            . $this->adminDetailRow($iconClock, 'Horário', $horario)
            . $this->adminDetailRow($iconAlert, 'Estado', '<span style="display:inline-block;background:#f5c518;color:#1f2937;font-weight:700;font-size:13px;padding:4px 14px;border-radius:20px;">Pendente</span>', true);

        return '
        <!DOCTYPE html>
        <html lang="pt">
        <head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
        <body style="margin:0;padding:24px 12px;background:#f0f6fb;font-family:Arial,Helvetica,sans-serif;">
        <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;margin:0 auto;">
          <tr>
            <td style="background:#ffffff;border-radius:18px;overflow:hidden;box-shadow:0 8px 24px rgba(10,61,107,0.12);">
              <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                  <td align="center" style="padding:24px 24px 12px;background:#ffffff;">
                    <img src="' . $logoSrc . '" alt="Clínica AGOSMIL" width="120" style="display:block;border:0;max-width:120px;height:auto;margin:0 auto;">
                  </td>
                </tr>
                <tr>
                  <td style="background:#0a3d6b;padding:16px 24px;text-align:center;color:#ffffff;font-family:Arial,sans-serif;">
                    <div style="font-size:20px;font-weight:700;">Nova consulta pendente</div>
                    <div style="font-size:12px;color:#9fd4f7;margin-top:6px;">Requer confirmação no painel administrativo</div>
                  </td>
                </tr>
                <tr>
                  <td style="padding:20px 24px 8px;font-family:Arial,sans-serif;">
                    <div style="color:#0a3d6b;font-size:15px;font-weight:700;margin-bottom:6px;">Resumo do agendamento</div>
                    <div style="color:#3d5f7a;font-size:14px;line-height:1.5;">Um paciente marcou consulta e aguarda a sua validação.</div>
                  </td>
                </tr>
                <tr>
                  <td style="padding:6px 24px 16px;">
                    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border:2px solid #b8d9f0;border-radius:14px;background:#f7fbff;">
                      ' . $rows . '
                    </table>
                  </td>
                </tr>
                <tr>
                  <td style="padding:0 24px 20px;">
                    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background:#fff8e6;border:1px solid #fcd34d;border-radius:12px;">
                      <tr>
                        <td style="padding:14px 16px;font-family:Arial,sans-serif;color:#78350f;font-size:14px;line-height:1.5;">
                          <strong>Mensagem do paciente:</strong><br>' . nl2br($mensagemHtml) . '
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td style="padding:0 24px 24px;text-align:center;font-family:Arial,sans-serif;">
                    <div style="color:#3d5f7a;font-size:13px;line-height:1.5;">Aceda ao painel admin para <strong style="color:#0a3d6b;">confirmar</strong> ou <strong style="color:#0a3d6b;">cancelar</strong> esta consulta.</div>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
        </body>
        </html>';
    }

    private function adminDetailRow(string $icon, string $label, string $value, bool $last = false): string
    {
        $border = $last ? '' : 'border-bottom:1px solid #d6e9f7;';

        return '
        <tr>
          <td style="padding:14px 16px;' . $border . '">
            <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <td width="48" valign="top">' . $icon . '</td>
                <td valign="top" style="font-family:Arial,sans-serif;">
                  <div style="color:#5a7a94;font-size:12px;margin-bottom:2px;">' . htmlspecialchars($label) . '</div>
                  <div style="color:#0a3d6b;font-size:15px;font-weight:700;">' . $value . '</div>
                </td>
              </tr>
            </table>
          </td>
        </tr>';
    }

    private function buildAdminPendingAltBody(array $data): string
    {
        $mensagem = trim((string)($data['mensagem'] ?? ''));

        return implode("\n", [
            'Nova consulta pendente - Clinica AGOSMIL',
            'Paciente: ' . ($data['paciente'] ?? ''),
            'E-mail: ' . ($data['email'] ?? ''),
            'Telefone: ' . ($data['telefone'] ?? ''),
            'Especialidade: ' . ($data['especialidade'] ?? ''),
            'Medico: ' . ($data['medico'] ?? ''),
            'Data: ' . ($data['data'] ?? ''),
            'Horario: ' . ($data['horario'] ?? ''),
            'Estado: Pendente',
            'Mensagem: ' . ($mensagem !== '' ? $mensagem : 'Sem mensagem'),
            'Aceda ao painel admin para confirmar ou cancelar.',
        ]);
    }

    private function buildMailer(string $forcedEncryption, int $forcedPort): PHPMailer
    {
        $host = (string)getenv('MAIL_HOST');
        $username = (string)getenv('MAIL_USERNAME');
        $password = (string)getenv('MAIL_PASSWORD');
        $port = $forcedPort > 0 ? $forcedPort : (int)getenv('MAIL_PORT');

        $missing = [];
        if ($host === '') $missing[] = 'MAIL_HOST';
        if ($username === '') $missing[] = 'MAIL_USERNAME';
        if ($password === '') $missing[] = 'MAIL_PASSWORD';
        if ($port <= 0) $missing[] = 'MAIL_PORT';
        if (!empty($missing)) {
            throw new \RuntimeException('Configuracao SMTP incompleta: ' . implode(', ', $missing));
        }

        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 0;
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->Host = $host;
        $mail->SMTPAuth = true;
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->Port = $port;
        $mail->Timeout = 20;
        $mail->SMTPKeepAlive = false;
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ];

        if ($forcedEncryption === 'tls') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        } elseif ($forcedEncryption === 'ssl' || $forcedEncryption === 'smtps') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } else {
            // Fallback automatico pelo porto.
            $mail->SMTPSecure = $port === 587 ? PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS;
        }

        return $mail;
    }

    private function buildHtmlBody(array $data, ?string $logoCid = null): string
    {
        $logoSrc = $logoCid !== null
            ? 'cid:' . $logoCid
            : htmlspecialchars($this->assetUrl('assets/images/logo2.png'));
        $paciente = htmlspecialchars($data['paciente'] ?? 'Paciente');
        $especialidade = htmlspecialchars($data['especialidade'] ?? 'Não informada');
        $medico = htmlspecialchars($data['medico'] ?? 'A definir pela Clínica Agosmil');
        $dataConsulta = htmlspecialchars($data['data'] ?? 'Não informada');
        $horario = htmlspecialchars($data['horario'] ?? 'Não informado');
        $status = htmlspecialchars($data['status'] ?? 'Pendente');
        $descricao = htmlspecialchars($data['status_description'] ?? 'Sua consulta foi registrada com sucesso. Consulte o PDF para mais detalhes.');
        $badgeStyle = $this->statusBadgeStyle($data['status'] ?? 'Pendente');

        $iconBell = $this->emailIconCircle('&#128276;', '#0a3d6b');
        $iconCalendarTitle = $this->emailCalendarIcon(28);
        $iconSpecialty = $this->specialtyIcon($data['especialidade'] ?? '');
        $iconUser = $this->emailIconCircle('&#128100;', '#0a3d6b');
        $iconCalendar = $this->emailCalendarIcon();
        $iconClock = $this->emailIconCircle('&#128336;', '#0a3d6b');
        $iconClipboard = $this->emailIconCircle('&#128203;', '#0a3d6b');
        $iconPdf = $this->emailIconCircle('&#128196;', '#0a3d6b', 40);
        $iconHeart = $this->emailIconCircle('&#10084;', '#ffffff', 32, '#0a3d6b');
        $iconEyeFooter = $this->emailIconCircle('&#128065;', '#0a3d6b', 36);

        $detailRow = function (string $icon, string $label, string $value, bool $last = false) use ($badgeStyle, $status): string {
            $border = $last ? '' : 'border-bottom:1px solid #d6e9f7;';
            if ($label === 'Status') {
                $valueCell = '<span style="display:inline-block;background:' . $badgeStyle['bg'] . ';color:' . $badgeStyle['color'] . ';font-weight:700;font-size:13px;padding:4px 14px;border-radius:20px;">' . $status . '</span>';
            } else {
                $valueCell = '<strong style="color:#0a3d6b;font-size:15px;">' . $value . '</strong>';
            }

            return '
            <tr>
              <td style="padding:14px 16px;' . $border . '">
                <table cellpadding="0" cellspacing="0" width="100%">
                  <tr>
                    <td width="48" valign="top">' . $icon . '</td>
                    <td valign="top" style="font-family:Arial,sans-serif;">
                      <div style="color:#5a7a94;font-size:12px;margin-bottom:2px;">' . htmlspecialchars($label) . ':</div>
                      <div>' . $valueCell . '</div>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>';
        };

        return '
        <!DOCTYPE html>
        <html lang="pt">
        <head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
        <body style="margin:0;padding:24px 12px;background:#f0f6fb;font-family:Arial,Helvetica,sans-serif;">
        <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;margin:0 auto;">
          <tr>
            <td style="background:#ffffff;border-radius:18px;overflow:hidden;box-shadow:0 8px 24px rgba(10,61,107,0.12);">
              <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                  <td align="center" style="padding:28px 24px 16px;background:#ffffff;">
                    <img src="' . $logoSrc . '" alt="Cl&iacute;nica AGOSMIL" width="140" style="display:block;border:0;max-width:140px;height:auto;margin:0 auto;">
                  </td>
                </tr>
                <tr>
                  <td style="background:#0a3d6b;padding:18px 24px 20px;">
                    <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
                      <tr>
                        <td align="center" valign="middle" style="color:#ffffff;font-family:Arial,sans-serif;">
                          <div style="font-size:34px;font-weight:700;letter-spacing:3px;line-height:1;">AGOSMIL</div>
                          <table role="presentation" cellpadding="0" cellspacing="0" align="center" style="margin-top:8px;">
                            <tr>
                              <td style="width:42px;height:1px;background:#7eb8e8;font-size:0;line-height:0;">&nbsp;</td>
                              <td style="padding:0 10px;color:#9fd4f7;font-size:11px;letter-spacing:1px;font-weight:600;white-space:nowrap;">CL&Iacute;NICA AGOSMIL</td>
                              <td style="width:42px;height:1px;background:#7eb8e8;font-size:0;line-height:0;">&nbsp;</td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td style="padding:24px 24px 8px;">
                    <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
                      <tr>
                        <td width="52" valign="top">' . $iconBell . '</td>
                        <td valign="top" style="font-family:Arial,sans-serif;">
                          <div style="color:#0a3d6b;font-size:20px;font-weight:700;margin-bottom:6px;">Ol&aacute;, ' . $paciente . '!</div>
                          <div style="color:#3d5f7a;font-size:14px;line-height:1.55;">' . $descricao . '</div>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td style="padding:8px 24px 6px;">
                    <table role="presentation" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="32" valign="middle">' . $iconCalendarTitle . '</td>
                        <td valign="middle" style="color:#0a3d6b;font-size:13px;font-weight:700;letter-spacing:0.5px;padding-left:8px;">DETALHES DO AGENDAMENTO</td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td style="padding:6px 24px 18px;">
                    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border:2px solid #b8d9f0;border-radius:14px;background:#f7fbff;">
                      ' . $detailRow($iconSpecialty, 'Especialidade', $especialidade) . '
                      ' . $detailRow($iconUser, 'Médico', $medico) . '
                      ' . $detailRow($iconCalendar, 'Data', $dataConsulta) . '
                      ' . $detailRow($iconClock, 'Horário', $horario) . '
                      ' . $detailRow($iconClipboard, 'Status', $status, true) . '
                    </table>
                  </td>
                </tr>
                <tr>
                  <td style="padding:0 24px 22px;">
                    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background:#e8f4fc;border-radius:12px;">
                      <tr>
                        <td style="padding:14px 16px;">
                          <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                              <td width="52" valign="middle">' . $iconPdf . '</td>
                              <td valign="middle" style="font-family:Arial,sans-serif;color:#3d5f7a;font-size:14px;line-height:1.5;">
                                Os detalhes completos da sua consulta <strong style="color:#0a3d6b;">est&atilde;o dispon&iacute;veis no PDF em anexo.</strong>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td style="padding:0 24px 24px;border-top:1px solid #e5eef5;">
                    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="margin-top:18px;">
                      <tr>
                        <td width="44" valign="middle">' . $iconHeart . '</td>
                        <td align="center" valign="middle" style="font-family:Arial,sans-serif;color:#3d5f7a;font-size:14px;">
                          Atenciosamente,<br><strong style="color:#0a3d6b;">Equipe Cl&iacute;nica AGOSMIL</strong>
                        </td>
                        <td width="44" align="right" valign="middle">' . $iconEyeFooter . '</td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
        </body>
        </html>';
    }

    private function assetUrl(string $path): string
    {
        $host = $_SERVER['HTTP_HOST'] ?? getenv('APP_URL') ?? 'localhost';
        $host = preg_replace('#^https?://#i', '', (string)$host);
        $host = rtrim($host, '/');

        return 'https://' . $host . '/' . ltrim($path, '/');
    }

    private function resolveLogoPath(): ?string
    {
        $base = dirname(__DIR__, 2) . '/public/assets/images/';
        $candidates = ['logo2.png', 'logo.png', 'logo.jpg', 'logo.jpeg'];

        foreach ($candidates as $file) {
            $path = $base . $file;
            if (file_exists($path)) {
                return $path;
            }
        }

        return null;
    }

    private function resolveDoctorName(array $appointmentData): string
    {
        $provided = trim((string)($appointmentData['medico'] ?? ''));
        if ($provided !== '' && stripos($provided, 'definir') === false) {
            return $provided;
        }

        $idUsers = (int)($appointmentData['id_users'] ?? 0);
        if ($idUsers > 0) {
            $name = $this->fetchDoctorNameById($idUsers);
            if ($name !== null) {
                return $name;
            }
        }

        $departamentId = (int)($appointmentData['id_departament'] ?? $appointmentData['departamento'] ?? 0);
        if ($departamentId > 0) {
            $name = $this->fetchDoctorNameByDepartment($departamentId);
            if ($name !== null) {
                return $name;
            }
        }

        return 'A definir pela Clínica Agosmil';
    }

    private function fetchDoctorNameById(int $userId): ?string
    {
        $db = new Conexao();
        $db->query('SELECT name FROM users WHERE id_users = :id AND type_user = :nivel LIMIT 1');
        $db->bind(':id', $userId);
        $db->bind(':nivel', '1');
        if ($db->executa() && $db->total()) {
            $row = $db->resultado();
            return $row['name'] ?? null;
        }

        return null;
    }

    private function fetchDoctorNameByDepartment(int $departamentId): ?string
    {
        $db = new Conexao();
        $db->query('SELECT name FROM users WHERE id_departament = :id AND type_user = :nivel ORDER BY name ASC LIMIT 1');
        $db->bind(':id', $departamentId);
        $db->bind(':nivel', '1');
        if ($db->executa() && $db->total()) {
            $row = $db->resultado();
            return $row['name'] ?? null;
        }

        return null;
    }

    private function resolveStatus(array $appointmentData): string
    {
        $consultId = (int)($appointmentData['id_consults'] ?? $appointmentData['id_consult'] ?? 0);
        if ($consultId > 0) {
            $statusFromDb = $this->fetchConsultStatus($consultId);
            if ($statusFromDb !== null && trim($statusFromDb) !== '') {
                return $this->normalizeStatus($statusFromDb);
            }
        }

        return $this->normalizeStatus((string)($appointmentData['status'] ?? 'pendente'));
    }

    private function fetchConsultStatus(int $consultId): ?string
    {
        $db = new Conexao();
        $db->query('SELECT status FROM consults WHERE id_consults = :id LIMIT 1');
        $db->bind(':id', $consultId);
        if (!$db->executa()) {
            return null;
        }

        $row = $db->resultado();
        return is_array($row) ? ($row['status'] ?? null) : null;
    }

    private function emailCalendarIcon(int $size = 36): string
    {
        $bg = '#e8f4fc';
        $iconW = max(14, (int)round($size * 0.46));
        $headerH = max(4, (int)round($iconW * 0.28));
        $bodyH = max(8, $iconW - $headerH - 2);

        return '
        <table role="presentation" cellpadding="0" cellspacing="0">
          <tr>
            <td align="center" valign="middle" width="' . $size . '" height="' . $size . '" style="width:' . $size . 'px;height:' . $size . 'px;background:' . $bg . ';border-radius:50%;text-align:center;vertical-align:middle;">
              <table role="presentation" cellpadding="0" cellspacing="0" style="width:' . $iconW . 'px;border:2px solid #0a3d6b;border-radius:3px;background:#ffffff;margin:0 auto;">
                <tr>
                  <td style="height:' . $headerH . 'px;background:#0a3d6b;font-size:0;line-height:0;border-radius:1px 1px 0 0;">&nbsp;</td>
                </tr>
                <tr>
                  <td style="height:' . $bodyH . 'px;background:#ffffff;font-size:0;line-height:0;">&nbsp;</td>
                </tr>
              </table>
            </td>
          </tr>
        </table>';
    }

    private function specialtyIcon(string $especialidade): string
    {
        $key = $this->normalizeSpecialtyKey($especialidade);
        $symbols = [
            'cardiologia' => '&#10084;',
            'pediatria' => '&#128118;',
            'oftalmologia' => '&#128065;',
            'ortopedia' => '&#129460;',
            'dermatologia' => '&#129516;',
            'neurologia' => '&#129504;',
            'ginecologia' => '&#9792;',
            'urologia' => '&#9881;',
            'psiquiatria' => '&#129504;',
            'psicologia' => '&#129504;',
            'odontologia' => '&#128139;',
            'clinica geral' => '&#129657;',
            'medicina geral' => '&#129657;',
            'cirurgia' => '&#9876;',
            'nutricao' => '&#127822;',
            'fisioterapia' => '&#127947;',
            'otorrino' => '&#128066;',
            'endocrinologia' => '&#9878;',
            'pneumologia' => '&#127807;',
            'oncologia' => '&#127973;',
        ];

        foreach ($symbols as $needle => $symbol) {
            if (str_contains($key, $needle)) {
                return $this->emailIconCircle($symbol, '#0a3d6b');
            }
        }

        return $this->emailIconCircle('&#127973;', '#0a3d6b');
    }

    private function normalizeSpecialtyKey(string $value): string
    {
        $value = strtolower(trim($value));
        $value = str_replace(
            ['á', 'à', 'ã', 'â', 'é', 'ê', 'í', 'ó', 'ô', 'õ', 'ú', 'ç'],
            ['a', 'a', 'a', 'a', 'e', 'e', 'i', 'o', 'o', 'o', 'u', 'c'],
            $value
        );

        return $value;
    }

    private function emailIconCircle(string $symbol, string $symbolColor, int $size = 36, ?string $bgColor = null): string
    {
        $bg = $bgColor ?? '#e8f4fc';
        $fontSize = max(14, (int)round($size * 0.42));

        return '
        <table role="presentation" cellpadding="0" cellspacing="0">
          <tr>
            <td align="center" valign="middle" width="' . $size . '" height="' . $size . '" style="width:' . $size . 'px;height:' . $size . 'px;background:' . $bg . ';border-radius:50%;text-align:center;vertical-align:middle;">
              <span style="color:' . $symbolColor . ';font-size:' . $fontSize . 'px;line-height:' . $size . 'px;display:inline-block;">' . $symbol . '</span>
            </td>
          </tr>
        </table>';
    }

    private function statusBadgeStyle(string $status): array
    {
        $normalized = strtolower(trim($status));

        if ($normalized === 'confirmada') {
            return ['bg' => '#86efac', 'color' => '#14532d'];
        }

        if ($normalized === 'cancelada') {
            return ['bg' => '#fca5a5', 'color' => '#7f1d1d'];
        }

        return ['bg' => '#f5c518', 'color' => '#1f2937'];
    }

    private function generateAppointmentPdf(array $data): string
    {
        $fpdfPath = dirname(__DIR__) . '/Views/layouts/fpdf/fpdf.php';
        if (!file_exists($fpdfPath)) {
            throw new \RuntimeException('Biblioteca de PDF nao encontrada no projeto.');
        }

        require_once $fpdfPath;

        if (!class_exists('FPDF')) {
            throw new \RuntimeException('Classe FPDF nao carregada.');
        }

        $pdf = new \FPDF();
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(true, 20);

        // Logo e cabeçalho
        $logoPath = $this->resolveLogoPath();
        if ($logoPath !== null) {
            $pdf->Image($logoPath, 92, 10, 26);
            $pdf->Ln(24);
            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(0, 7, utf8_decode('Clínica AGOSMIL'), 0, 1, 'C');
        } else {
            $pdf->SetFont('Arial', 'B', 18);
            $pdf->Cell(0, 10, utf8_decode('AGOSMIL'), 0, 1, 'C');
            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(0, 7, utf8_decode('Clínica AGOSMIL'), 0, 1, 'C');
            $pdf->Ln(4);
        }
        $pdf->Ln(4);

        // Campos obrigatórios
        $this->addPdfLine($pdf, 'Nome do Paciente', $data['paciente']);
        $this->addPdfLine($pdf, 'Especialidade', $data['especialidade']);
        $this->addPdfLine($pdf, 'Nome do Doutor', $data['medico']);
        $this->addPdfLine($pdf, 'Horário', $data['horario']);
        $this->addPdfLine($pdf, 'Estado da Consulta', $data['status']);

        // Condicional: mostrar motivo apenas se cancelada
        $statusNormalized = strtolower($data['status']);
        if ($statusNormalized === 'cancelada' && !empty($data['motivo'] ?? '')) {
            $pdf->Ln(2);
            $this->addPdfLine($pdf, 'Motivo do Cancelamento', $data['motivo']);
        }

        return $pdf->Output('S');
    }

    private function addPdfLine(\FPDF $pdf, string $label, string $value): void
    {
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(55, 8, utf8_decode($label . ':'), 0, 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 8, utf8_decode($value), 0, 1);
    }

    private function emailSubjectForStatus(string $status): string
    {
        if ($status === 'confirmada') {
            return 'Consulta Confirmada - Clinica AGOSMIL';
        }

        if ($status === 'cancelada') {
            return 'Consulta Cancelada - Clinica AGOSMIL';
        }

        return 'Consulta Pendente - Clinica AGOSMIL';
    }

    private function statusPresentation(string $status, string $motivo): array
    {
        if ($status === 'confirmada') {
            return ['Confirmada', 'Sua consulta foi confirmada!', 'Seu agendamento foi validado. Estamos aguardando sua presença na data e horário informados.'];
        }

        if ($status === 'cancelada') {
            $descricao = !empty(trim($motivo))
                ? 'Sua consulta foi cancelada. Motivo: ' . trim($motivo)
                : 'Sua consulta foi cancelada. Para reagendar, entre em contato com a clínica.';
            return ['Cancelada', 'Sua consulta foi cancelada.', $descricao];
        }

        return ['Pendente', 'Sua consulta foi marcada com sucesso!', 'Sua consulta foi registrada com sucesso. Consulte o PDF para mais detalhes.'];
    }

    // private function statusCssClass(string $status): string
    // {
    //     $normalized = strtolower($status);
    //     if ($normalized === 'confirmada') {
    //         return 'status-confirmada';
    //     }
    //     if ($normalized === 'cancelada') {
    //         return 'status-cancelada';
    //     }
    //     return 'status-pendente';
    // }

    private function normalizeStatus(string $status): string
    {
        $normalized = strtolower(trim($status));
        if (in_array($normalized, ['pendente', 'confirmada', 'cancelada'], true)) {
            return $normalized;
        }
        return 'pendente';
    }

    private function buildTransportAttempts(): array
    {
        $envEncryption = strtolower(trim((string)getenv('MAIL_ENCRYPTION')));
        $envPort = (int)getenv('MAIL_PORT');

        $preferred = [
            'encryption' => in_array($envEncryption, ['ssl', 'smtps', 'tls'], true) ? $envEncryption : ($envPort === 587 ? 'tls' : 'ssl'),
            'port' => $envPort > 0 ? $envPort : ($envEncryption === 'tls' ? 587 : 465),
        ];

        $fallback1 = ['encryption' => 'ssl', 'port' => 465];
        $fallback2 = ['encryption' => 'tls', 'port' => 587];

        $attempts = [$preferred, $fallback1, $fallback2];

        $unique = [];
        foreach ($attempts as $attempt) {
            $key = $attempt['encryption'] . ':' . $attempt['port'];
            $unique[$key] = $attempt;
        }

        return array_values($unique);
    }
}
