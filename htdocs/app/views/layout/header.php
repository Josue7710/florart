<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="descripcion" content="FlorArte: arreglos florales personalizados, naturales o sintéticos, para cada ocasión.">
  <title><?= e(($title ?? 'FlorArte') . ' | FlorArte') ?></title>
  <link rel="icon" type="image/svg+xml" href="assets/img/favicon.svg">
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<header class="topbar">
  <a class="brand" href="<?= e(url('home')) ?>"><span class="brand-mark">✿</span> FlorArte</a>
  <nav>
    <a href="<?= e(url('home')) ?>">Inicio</a>
    <a href="<?= e(url('catalog')) ?>">Catálogo</a>
    <a href="<?= e(url('customize')) ?>">Personalizar</a>
    <?php if (Auth::check()): ?>
      <?php if (Auth::isAdmin()): ?>
        <a href="<?= e(url('admin')) ?>">Panel</a>
        <a href="<?= e(url('admin-products')) ?>">Productos</a>
        <a href="<?= e(url('admin-orders')) ?>">Pedidos</a>
      <?php else: ?>
        <a href="<?= e(url('my-orders')) ?>">Mis pedidos</a>
      <?php endif; ?>
      <form class="nav-form" method="post" action="<?= e(url('logout')) ?>"><?= csrf_field() ?><button type="submit">Salir (<?= e(Auth::user()['nombre']) ?>)</button></form>
    <?php else: ?>
      <a href="<?= e(url('login')) ?>">Iniciar sesión</a>
    <?php endif; ?>
  </nav>
</header>
<main class="container">
<?php if (!empty($_SESSION['flash'])): $flash = $_SESSION['flash']; unset($_SESSION['flash']); ?>
  <div class="notice <?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
<?php endif; ?>
