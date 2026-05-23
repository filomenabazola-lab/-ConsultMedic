<?php
use App\Helpers\Sessao;
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
  <link href="<?=asset("img/favicon-32x32.png")?>" rel="icon">
  

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Raleway:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
  
  <!-- Vendor CSS Files -->
  <link href="<?= asset("assets/vendor/bootstrap/css/bootstrap.min.css") ?>" rel="stylesheet">
  
  <link href="<?= asset("assets/vendor/bootstrap-icons/bootstrap-icons.css") ?>" rel="stylesheet">
  <link href="<?= asset("assets/vendor/aos/aos.css") ?>" rel="stylesheet">
  <link href="<?= asset("assets/vendor/glightbox/css/glightbox.min.css") ?>" rel="stylesheet">
  <link href="<?= asset("assets/vendor/swiper/swiper-bundle.min.css") ?>" rel="stylesheet">
  <link href="<?= asset("assets/vendor/remixicon/remixicon.css") ?>" rel="stylesheet">
  
  <!-- Template Main CSS File -->
  <link href="<?= asset("assets/css/main.css") ?>" rel="stylesheet">
  
  <script src="<?=asset(JQUERY)?>"></script>
  <script src="<?=asset(NOTIFY)?>"></script>



</head>

<body class="page-index">

  


   <!-- ======= Header ======= -->
   <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

      <a href="index.html" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <h1 class="d-flex align-items-center">Blog IPPA</h1>
      </a>

      <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
      <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>

      <nav id="navbar" class="navbar">
        <ul>
          <li><a href="<?= URL ?>/home" class="<?= $title == 'home' ? 'active' : '' ?>">Home</a></li>
          <li><a href="<?= URL ?>/about" class="<?= $title == 'about' ? 'active' : '' ?>">Sobre</a></li>
          <li><a href="<?= URL ?>/cursos" class="<?= $title == 'cursos' ? 'active' : '' ?>">Cursos</a></li>
          <li><a href="<?= URL ?>/team" class="<?= $title == 'team' ? 'active' : '' ?>">Team</a></li>
          <!--  -->
          <?php if (isset($_SESSION['BlogUser_id'])) : ?>
            <li><a href="<?= URL ?>/blog" class="<?= $title == 'blog' ? 'active' : '' ?>">Blog</a></li>
          <?php else : echo '';
          endif; ?>

          <li><a href="<?= URL ?>/contact" class="<?= $title == 'contact' ? 'active' : '' ?>">Contact</a></li>

          <?php if (!isset($_SESSION['BlogUser_id'])) : ?>
            <li style=" margin-left: 10px; background-color: #56B8E6;" class="p-2 rounded-2 btn "><a href="<?= URL ?>/login" class="p-0 <?= $title == 'login' ? 'active' : '' ?>">Login</a></li>
          <?php else : ?>
            <li style=" margin-left: 10px; background-color: #56B8E6;" class="p-2 rounded-2 btn "><a href="<?= URL ?>/sair" class="p-0 <?= $title == 'login' ? 'active' : '' ?>">sair</a></li>
          <?php endif; ?>
        </ul>
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->

  <!-- ======= Hero Section ======= -->
  <section id="hero" class="hero d-flex align-items-center">
    <div class="container">
      <div class="row">
        <div class="col-xl-4">
          <h2 class="fs-1 text-center mb-3">Criar Conta</h2>
          <form action="<?=URL?>/createUser" method="post" >
          <?=Sessao::sms("valid")?>
            <div class="mb-3">
              <label for="exampleInputText1" class="form-label text-white">Nome</label>
              <input type="text" class="form-control <?= $dados['error']?'is-invalid': ''?>" id="exampleInputText1" aria-describedby="textHelp" placeholder="ex: Ezequiel Ribeiro" name="nome" value="<?=$dados['nome']?>">
              <!-- <div id="textHelp" class="invalid-feedback">We'll never share your texts with anyone else.</div> -->
            </div>
            <div class="mb-3">
              <label for="exampleInputText1" class="form-label text-white">Email</label>
              <input type="email" class="form-control <?= $dados['error']?'is-invalid': ''?>" id="exampleInputText1" aria-describedby="textHelp" placeholder="ex: Ezequiel@gmail.com" name="email" value="<?=$dados['email']?>">
              <!-- <div id="textHelp" class="invalid-feedback">We'll never share your texts with anyone else.</div> -->
            </div>
            <div class="mb-3">
              <label for="exampleInputPassword1" class="form-label text-white">Senha</label>
              <input type="password" class="form-control <?= $dados['error']?'is-invalid': ''?>" id="exampleInputPassword1" aria-describedby="passwordHelp" placeholder="****" name="senha" value="<?=$dados['senha']?>">
              <div id="passwordHelp" class="invalid-feedback"><?=$dados['error']?></div>
              <a href="<?=URL?>/login" id="passwordHelp" class="">Já tenho conta</a>
            </div>
            <div class="text-center"><button type="submit" class="btn-get-started border-0" name="create" value="submit">Criar</button></div>
          </form>
        </div>

      </div>
    </div>
    </div>
  </section><!-- End Hero Section-->



  <!-- ======= Footer ======= -->

  <!-- End Footer --><!-- End Footer -->


  <div id="preloader"></div>
  <script src="<?= asset("assets/vendor/bootstrap/js/bootstrap.bundle.min.js") ?>"></script>
  <script src="<?= asset("assets/vendor/aos/aos.js") ?>"></script>
  <script src="<?= asset("assets/vendor/glightbox/js/glightbox.min.js") ?>"></script>
  <script src="<?= asset("assets/vendor/swiper/swiper-bundle.min.js") ?>"></script>
  <script src="<?= asset("assets/vendor/isotope-layout/isotope.pkgd.min.js") ?>"></script>
  <script src="<?= asset("assets/vendor/php-email-form/validate.js") ?>"></script>

  <!-- Template Main JS File -->
  <script src="<?= asset("assets/js/main.js") ?>"></script>

</body>

</html>