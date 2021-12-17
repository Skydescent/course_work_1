<?php
error_reporting(E_ALL);
ini_set('display_errors', true);

require_once $_SERVER['DOCUMENT_ROOT'] . '/helpers/helperContent.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/helpers/helperDb.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/helpers/helperAuth.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/data/constants.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
helperAuth\startSession();
helperAuth\authController();

?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>Fashion</title>

  <meta name="description" content="Fashion - интернет-магазин">
  <meta name="keywords" content="Fashion, интернет-магазин, одежда, аксессуары">

  <meta name="theme-color" content="#393939">

  <link rel="preload" href="/img/intro/coats-2018.jpg" as="image">
  <link rel="preload" href="/fonts/opensans-400-normal.woff2" as="font">
  <link rel="preload" href="/fonts/roboto-400-normal.woff2" as="font">
  <link rel="preload" href="/fonts/roboto-700-normal.woff2" as="font">

  <link rel="icon" href="/img/favicon.png">
  <link rel="stylesheet" href="/css/style.min.css">

  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="/js/scripts.js" defer=""></script>
</head>
<body>
<header class="page-header">
  <a class="page-header__logo" href="/">
    <img src="/img/logo.svg" alt="Fashion">
  </a>
  <nav class="page-header__menu">
    <ul class="main-menu main-menu--header">
        <?php helperContent\showMenu(); ?>
    </ul>
  </nav>
</header>
