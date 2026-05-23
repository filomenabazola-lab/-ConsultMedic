<?php



namespace App\Controllers\Admin;

use App\Helpers\Sessao;
use App\Helpers\Url;
use App\Helpers\Valida;
use App\Libraries\Controller;
// phpMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Home extends Controller
{
  private $Data;
  private $Agenda;
  private $email;

  public function __construct()
  {
    $this->email = new PHPMailer(true);
    $this->Data = $this->model("admin\Home");
    $this->Agenda = $this->model("admin\Consultas");
    if (!Sessao::nivel0()) {
      Url::redireciona("admin/login");
    }
  }
  public function index()
  {
    $departaments = $this->Data->getCountDepartaments();
    $user = $this->Data->getCountUsers();
    $consultsCount = $this->Data->getCountConsults();
    $consults = $this->Agenda->consultaPC();
    // var_dump($consults);
    // exit;
   
    $file = 'admin/home';
    $title = 'home';
    return $this->view('layouts/admin/app', compact('file', 'title', 'departaments', 'user', 'consultsCount', 'consults'));
  }
  public function consultsRecents($id){
    $id=filter_var($id['id'], FILTER_VALIDATE_INT);
    $form = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    // var_dump($form);
    // exit;
    if ($form['btn1']) {
      $dados = [
        'desc' => trim($form['desc']),
        'nomeU' => trim($form['nomeU']),
        'nomeC' => trim($form['nomeC']),
        'emailC' => trim($form['emailC']),
        'emailU' => trim($form['emailU']),
        'status' => trim($form['radio']),
        'error' => ''
      ];

      if (!isset($form['radio'])) {
        $dados['error'] = "Confirme ou cancele";
        Sessao::notify("error", $dados['error'], "error"); 
        Url::redireciona("admin/home");
        exit;
      } else {
       
        $update = $this->Agenda->update($dados, $id);
          if ($update) {

            try {
              //Server settings
              // $this->email->SMTPDebug = SMTP::DEBUG_SERVER;                      
              $this->email->CharSet = 'UTF-8';
              $this->email->isSMTP();
              $this->email->Host       = getenv('MAIL_HOST');
              $this->email->SMTPAuth   = true;
              $this->email->Username   = getenv('MAIL_USERNAME');
              $this->email->Password   = getenv('MAIL_PASSWORD');
              $this->email->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
              $this->email->Port       = getenv('MAIL_PORT');

              //Recipients
              $this->email->setFrom('laurindabernardajulio@gmail.com', getenv("APP_NAME"));
              $this->email->addAddress($dados['emailC'], $dados['nomeC']); 
              $this->email->addAddress($dados['emailU'], $dados['nomeU']); 
              //Add a recipient



              //Content
              $this->email->isHTML(true);                                  //Set email format to HTML
              $this->email->Subject = "Nova consulta";
              $this->email->Body    = "Paciente: <strong>" . $dados['nomeC'] . "</strong> <br>A consulta marcada na data:".Valida::dataExtenso($dados['data'])." foi atualizada para o Estado: ".$dados['status'] ."<br>".$dados['desc'];
              $this->email->AltBody = "This is the body in plain text for non-HTML mail clients";

              $this->email->send();

              Sessao::notify('success', 'Atualizada com sucesso', null, null, null);
            } catch (Exception $e) {
              Sessao::notify('error', "{$this->email->ErrorInfo}", "error", null, null);;
            }

            // Sessao::notify("success", "Atualizada com sucesso");
            Url::redireciona("admin/home");
            exit;
          } else {
            Sessao::notify("error", "Erro", "error");
            Url::redireciona("admin/home");
          }
        } 
    } else {
      $dados = [
        'status' => '',
        'desc' => '',
        'error' => ''
      ];
    }
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
}
