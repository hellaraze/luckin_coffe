<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: ../index.php");
    exit;
}

$userId = $_SESSION['user_id'];

$sql = "SELECT id, status, created_at FROM orders WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$orders = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Мои заказы — Luckin Coffee</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2>Мои заказы</h2>
  <?php if ($orders->num_rows > 0): ?>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Статус</th>
          <th>Дата</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($order = $orders->fetch_assoc()): ?>
        <tr>
          <td><?= $order['id'] ?></td>
          <td>
            <?= match ($order['status']) {
              1 => 'Принят',
              2 => 'Готовится',
              3 => 'Готов',
              default => 'Неизвестно'
            }; ?>
          </td>
          <td><?= $order['created_at'] ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>У вас пока нет заказов.</p>
  <?php endif; ?>
  <a href="index.php" class="btn btn-secondary mt-3">← Назад</a>
</div>
</body>
</html>
