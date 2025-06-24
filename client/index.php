<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Клиент — Luckin Coffee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Добро пожаловать, <?= htmlspecialchars($_SESSION['login']) ?>!</h2>
    <p>Вы вошли как клиент.</p>

    <div class="list-group mt-4">
        <a href="menu.php" class="list-group-item list-group-item-action">Меню</a>
        <a href="cart.php" class="list-group-item list-group-item-action">Моя корзина</a>
        <a href="../logout.php" class="list-group-item list-group-item-action text-danger">Выйти</a>
    </div>
</div>
</body>
</html>
