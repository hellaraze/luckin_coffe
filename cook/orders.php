

<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'cook') {
    header("Location: ../login.php");
    exit;
}

// Получение заказов в процессе готовки
$sql = "SELECT orders.id, users.name AS client_name, orders.created_at
        FROM orders
        JOIN users ON orders.user_id = users.id
        WHERE orders.status = 2
        ORDER BY orders.created_at ASC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Заказы в процессе — Luckin Coffee</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2>Заказы в процессе приготовления</h2>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>ID заказа</th>
        <th>Клиент</th>
        <th>Дата</th>
        <th>Действие</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($order = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $order['id'] ?></td>
        <td><?= htmlspecialchars($order['client_name']) ?></td>
        <td><?= $order['created_at'] ?></td>
        <td>
          <form action="complete_order.php" method="POST">
            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
            <button type="submit" class="btn btn-success btn-sm">Готово</button>
          </form>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <a href="index.php" class="btn btn-secondary mt-3">← Назад</a>
</div>
</body>
</html>