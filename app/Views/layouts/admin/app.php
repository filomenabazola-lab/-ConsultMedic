<?php

use App\Helpers\Sessao;
use App\Helpers\ResumirTexto as text;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?= $title ?></title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="<?= asset("img/favicon-32x32.png") ?>" rel="icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="<?= asset("assets1/vendor/bootstrap/css/bootstrap.min.css") ?>" rel="stylesheet">
  <link href="<?= asset("assets1/vendor/bootstrap-icons/bootstrap-icons.css") ?>" rel="stylesheet">
  <link href="<?= asset("assets1/vendor/boxicons/css/boxicons.min.css") ?>" rel="stylesheet">
  <link href="<?= asset("assets1/vendor/quill/quill.snow.css") ?>" rel="stylesheet">
  <link href="<?= asset("assets1/vendor/quill/quill.bubble.css") ?>" rel="stylesheet">
  <link href="<?= asset("assets1/vendor/remixicon/remixicon.css") ?>" rel="stylesheet">
  <link href="<?= asset("assets1/vendor/simple-datatables/style.css") ?>" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="<?= asset("assets1/css/style.css") ?>" rel="stylesheet">

  <script src="<?= asset(JQUERY) ?>"></script>
  <script src="<?= asset(NOTIFY) ?>"></script>



</head>

<body>
  <?= Sessao::notify("auth1") ?>
  <?= Sessao::notify("success") ?>
  <?= Sessao::notify("error") ?>
  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="<?= URL ?>/admin/home" class="logo d-flex align-items-center">
        <img src="assets/img/logo.png" alt="">
        <span class="d-none d-lg-block"><?= getenv("APP_NAME") ?></span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="POST" action="#">
        <input type="text" name="query" placeholder="Search" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div><!-- End Search Bar -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->

        <a href="<?= URL ?>" class="btn btn-primary" target="__blank">Site</a>
        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0 " href="#" data-bs-toggle="dropdown">
            <span class="d-none d-md-block dropdown-toggle p-2 w-100 h-100  bg-primary text-white" style="border-radius:25px;"> <?= text::perfil($_SESSION['teste0_nome']) ?>
            </span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?= $_SESSION['teste0_nome'] ?></h6>
              <span><?= $_SESSION['teste0_type'] == '0' ? 'admin' : 'doctor' ?></span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="<?= URL ?>/admin/config">
                <i class="bi bi-gear"></i>
                <span>Config</span>
              </a>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modalSS">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sair</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link <?= ucwords($title) == 'Home' ? '' : 'collapsed' ?>" href="<?= URL ?>/admin/home">
          <i class="bi bi-grid"></i>
          <span>Painel Principal</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-heading">Páginas</li>

      <li class="nav-item">
        <a class="nav-link <?= ucwords($title) == 'Consults' ? '' : 'collapsed' ?>" href="<?= URL ?>/admin/consults">
          <i class="bi bi-journal-medical"></i>
          <span>Consultas</span>
        </a>
      </li>
      <!-- End Consulta Nav -->

      <li class="nav-item ">
        <a class="nav-link <?= ucwords($title) == ('departamentos') || ucwords($title) == ('Novo Depart') ? '' : 'collapsed' ?>" data-bs-target="#honras-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-person-lines-fill"></i><span>Departamentos</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="honras-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="<?= URL ?>/admin/newDepart" class="<?= ucwords($title) == ('Novo Depart') ? 'active' : '' ?>">
              <i class="bi bi-circle"></i><span>Novo</span>
            </a>
          </li>
          <li>
            <a href="<?= URL ?>/admin/departamentos" class="<?= ucwords($title) == ('departamentos') ? 'active' : '' ?>">
              <i class="bi bi-circle"></i><span>Listar</span>
            </a>
          </li>
        </ul>
      </li><!-- End departaments Nav -->

      <li class="nav-item ">
        <a class="nav-link <?= ucwords($title) == ('Users') ? '' : 'collapsed' ?>" data-bs-target="#users-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-person"></i><span>Usuários</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="users-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <!-- <li>
            <a href="<?= URL ?>/admin/usuarios" class="<?= ucwords($title) == ('Users') ? 'active' : '' ?>">
              <i class="bi bi-circle"></i><span>Novo</span>
            </a>
          </li> -->
          <li>
            <a href="<?= URL ?>/admin/usuarios" class="<?= ucwords($title) == ('Users') ? 'active' : '' ?>">
              <i class="bi bi-circle"></i><span>Listar/ criar</span>
            </a>
          </li>
        </ul>
      </li><!-- End Usuarios Nav -->

      <li class="nav-item">
        <a class="nav-link <?= ucwords($title) == 'Config' ? '' : 'collapsed' ?>" href="<?= URL ?>/admin/config">
          <i class="bi bi-gear"></i>
          <span>Config</span>
        </a>
      </li><!-- End config Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-bs-toggle="modal" data-bs-target="#modalSS">
          <i class="bi bi-box-arrow-in-right"></i>
          <span>Sair</span>
        </a>
      </li><!-- End Login Page Nav -->





    </ul>




    <script>
      var modalId = document.getElementById('modalId');

      modalId.addEventListener('show.bs.modal', function(event) {
        // Button that triggered the modal
        let button = event.relatedTarget;
        // Extract info from data-bs-* attributes
        let recipient = button.getAttribute('data-bs-whatever');

        // Use above variables to manipulate the DOM
      });
    </script>


  </aside><!-- End Sidebar-->

  <!-- ================================================================= -->
  <?php
  $file = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . str_replace('.php', '', $file) . '.php';
  require_once $file;

  ?>
  <!-- =============================================================== -->
  <!-- Modal -->
  <div class="modal fade" id="modalSS" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitleId">Sessão</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="container-fluid">
            Tem certeza que quer terminar?
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <a href="<?= URL ?>/admin/sair" class="btn btn-primary">Sair</a>
        </div>
      </div>
    </div>
  </div>
  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">

    <div class="credits">

      Made by <a href="">FL</a>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="<?= asset("assets1/vendor/apexcharts/apexcharts.min.js") ?>"></script>
  <script src="<?= asset("assets1/vendor/bootstrap/js/bootstrap.bundle.min.js") ?>"></script>
  <script src="<?= asset("assets1/vendor/chart.js/chart.min.js") ?>"></script>
  <script src="<?= asset("assets1/vendor/echarts/echarts.min.js") ?>"></script>
  <script src="<?= asset("assets1/vendor/quill/quill.min.js") ?>"></script>
  <script src="<?= asset("assets1/vendor/simple-datatables/simple-datatables.js") ?>"></script>
  <script src="<?= asset("assets1/vendor/tinymce/tinymce.min.js") ?>"></script>
  <script src="<?= asset("assets1/vendor/php-email-form/validate.js") ?>"></script>

  <!-- Template Main JS File -->
  <script src="<?= asset("assets1/js/main.js") ?>"></script>

</body>

</html>