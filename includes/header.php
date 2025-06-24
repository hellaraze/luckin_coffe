<?php
session_start();
require_once __DIR__ . '/db.php';
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Ваши кастомные стили -->
  <link href="/css/custom.css" rel="stylesheet">
  <title>Luckin Coffee</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="/">☕ Luckin Coffee</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Переключить навигацию">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <?php if (isset($_SESSION['role'])): ?>
          <?php if ($_SESSION['role'] === 'client'): ?>
            <li class="nav-item"><a class="nav-link" href="/client/menu.php">Меню</a></li>
            <li class="nav-item"><a class="nav-link" href="/client/cart.php">Корзина</a></li>
          <?php elseif ($_SESSION['role'] === 'cook'): ?>
            <li class="nav-item"><a class="nav-link" href="/cook/orders.php">Заказы</a></li>
          <?php elseif ($_SESSION['role'] === 'admin'): ?>
            <li class="nav-item"><a class="nav-link" href="/admin/users.php">Пользователи</a></li>
            <li class="nav-item"><a class="nav-link" href="/admin/orders.php">Заказы</a></li>
          <?php endif; ?>
          <li class="nav-item"><a class="nav-link" href="/logout.php">Выйти</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="/login.php">Войти</a></li>
          <li class="nav-item"><a class="nav-link" href="/register.php">Регистрация</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<main class="container py-4">