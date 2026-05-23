<?php

use App\Helpers\Sessao;
use App\Helpers\ResumirTexto as Text;
?>

<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- title -->
  <title><?= $title ?></title>

  <!-- links -->
  <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap"
    rel="stylesheet" />


  <!-- icones -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- scripts -->
  <script src="https://unpkg.com/scrollreveal"></script>

  <script src="<?= asset(JQUERY) ?>"></script>
  <script src="<?= asset(NOTIFY) ?>"></script>
</head>

<body class="page-index" onscroll="onScroll()">
  <?= Sessao::notify("message") ?>
  <?= Sessao::notify("success") ?>
  <?= Sessao::notify("error") ?>

  <!-- navbar -->
  <!-- <x-navbar/> -->
  <?php require "nav.php" ?>

  <!-- content home-->
  <?php
  $file = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . str_replace('.php', '', $file) . '.php';
  require_once $file;
  ?>
  <!-- end content -->

  <!-- footer -->
  <?php require_once "footer.php" ?>
  <!-- <x-footer :year="date('Y')"/> -->

  <a id="backToTopButton" class="" href="#home">
    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
      <circle cx="20" cy="20" r="20" fill="#00856F" />
      <path d="M20 27V13" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
      <path d="M13 20L20 13L27 20" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
    </svg>

  </a>
  <script src="<?= asset('assets/js/main.js') ?>"></script>
</body>

</html>