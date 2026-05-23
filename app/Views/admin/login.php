<?php

use App\Helpers\Sessao;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?=$title?></title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="<?=asset("img/favicon-32x32.png")?>" rel="icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="<?=asset("assets1/vendor/bootstrap/css/bootstrap.min.css")?>" rel="stylesheet">
  <link href="<?=asset("assets1/vendor/bootstrap-icons/bootstrap-icons.css")?>" rel="stylesheet">
  <link href="<?=asset("assets1/vendor/boxicons/css/boxicons.min.css")?>" rel="stylesheet">
  <link href="<?=asset("assets1/vendor/quill/quill.snow.css")?>" rel="stylesheet">
  <link href="<?=asset("assets1/vendor/quill/quill.bubble.css")?>" rel="stylesheet">
  <link href="<?=asset("assets1/vendor/remixicon/remixicon.css")?>" rel="stylesheet">
  <link href="<?=asset("assets1/vendor/simple-datatables/style.css")?>" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="<?=asset("assets1/css/style.css")?>" rel="stylesheet">

  <script src="<?= asset(JQUERY) ?>"></script>
  <script src="<?= asset(NOTIFY) ?>"></script>



</head>

<body>
<?=Sessao::notify("auth1")?>
  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="index.html" class="logo d-flex align-items-center w-auto">
                
                  <!-- <span class="d-none d-lg-block">Administrador</span> -->
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4" >Administrador</h5>
                  </div>

                  <form class="row g-3 " action="<?=URL?>/admin/login" method="post" id="formAuth">

                    <div class="col-12">
                      <label for="yourUsername" class="form-label">Email</label>
                      <div class="input-group ">
                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                        <input type="email" name="email" class="form-control <?=$dados['erro_email']?'is-invalid':'' ?>" id="yourUsername" value="<?=$dados['email']?>">
                        <div class="invalid-feedback"><?=$dados['erro_email']?></div>
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="yourPassword" class="form-label">Password</label>
                      <input type="password" name="senha" class="form-control <?=$dados['erro_senha']?'is-invalid':'' ?>" id="yourPassword" value="<?=$dados['senha']?>">
                      <div class="invalid-feedback"><?=$dados['erro_senha']?></div>
                    </div>

                   
                    <div class="col-12">
                      <button class="btn w-100 text-white" type="submit" style="background:#56b8e6;" name="btn_log" value="submit">Login</button>
                    </div>
                    
                  </form>
                  
                </div>
              </div>
              
              <div class="credits">
         
            Made by <a href="#" style="color: #56b8e6;">FL</a>
          </div>
            

            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="<?=asset("assets1/vendor/apexcharts/apexcharts.min.js")?>"></script>
  <script src="<?=asset("assets1/vendor/bootstrap/js/bootstrap.bundle.min.js")?>"></script>
  <script src="<?=asset("assets1/vendor/chart.js/chart.min.js")?>"></script>
  <script src="<?=asset("assets1/vendor/echarts/echarts.min.js")?>"></script>
  <script src="<?=asset("assets1/vendor/quill/quill.min.js")?>"></script>
  <script src="<?=asset("assets1/vendor/simple-datatables/simple-datatables.js")?>"></script>
  <script src="<?=asset("assets1/vendor/tinymce/tinymce.min.js")?>"></script>
  <script src="<?=asset("assets1/vendor/php-email-form/validate.js")?>"></script>

  <!-- Template Main JS File -->
  <script src="<?=asset("assets1/js/main.js")?>"></script>

</body>

</html>