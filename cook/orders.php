

<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'cook') {
    header("Location: ../login.php");
    exit;
}

// Обработка действий повара: начать готовить или отметить готовым
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['action'])) {
    $orderId = (int) $_POST['order_id'];
    if ($_POST['action'] === 'start') {
        $stmt = $conn->prepare("UPDATE orders SET status = 2 WHERE id = ?");
    } elseif ($_POST['action'] === 'complete') {
        $stmt = $conn->prepare("UPDATE orders SET status = 3 WHERE id = ?");
    }
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    header("Location: orders.php");
    exit;
}

// Получение заказов, ожидающих и в процессе приготовления
$sql = "SELECT orders.id, users.name AS client_name, orders.created_at, orders.status
        FROM orders
        JOIN users ON orders.user_id = users.id
        WHERE orders.status IN (1, 2)
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
          <form method="post">
            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
            <?php if ($order['status'] == 1): ?>
              <button type="submit" name="action" value="start" class="btn btn-primary btn-sm">Начать готовить</button>
            <?php elseif ($order['status'] == 2): ?>
              <button type="submit" name="action" value="complete" class="btn btn-success btn-sm">Готово</button>
            <?php endif; ?>
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