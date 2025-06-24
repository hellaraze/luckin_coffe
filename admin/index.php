<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Админ-панель — Luckin Coffee</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2 class="mb-4">Панель администратора</h2>
  <p>Добро пожаловать, админ!</p>
  
  <div class="mb-3">
    <a href="users.php" class="btn btn-outline-primary">Пользователи</a>
    <a href="orders.php" class="btn btn-outline-secondary">Все заказы</a>
    <a href="../logout.php" class="btn btn-danger float-end">Выйти</a>
  </div>
</div>
</body>
</html>