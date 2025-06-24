<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// Получение заказов
$sql = "SELECT orders.id, users.name AS client_name, orders.status, orders.created_at
        FROM orders
        JOIN users ON orders.user_id = users.id
        ORDER BY orders.created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Все заказы — Luckin Coffee</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2 class="mb-4">Все заказы</h2>
  <a href="index.php" class="btn btn-sm btn-secondary mb-3">← Назад</a>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>ID</th>
        <th>Клиент</th>
        <th>Статус</th>
        <th>Создан</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($order = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $order['id'] ?></td>
        <td><?= htmlspecialchars($order['client_name']) ?></td>
        <td>
          <?php
            echo match((int)$order['status']) {
              0 => 'Создан',
              1 => 'Принят',
              2 => 'Готовится',
              3 => 'Готов',
              default => 'Неизвестно'
            };
          ?>
        </td>
        <td><?= $order['created_at'] ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
