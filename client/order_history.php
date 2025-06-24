<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT orders.id, orders.created_at, orders.status, 
               GROUP_CONCAT(CONCAT(menu_items.name, ' x', order_items.quantity) SEPARATOR ', ') AS items
        FROM orders
        JOIN order_items ON orders.id = order_items.order_id
        JOIN menu_items ON order_items.item_id = menu_items.id
        WHERE orders.user_id = ?
        GROUP BY orders.id, orders.created_at, orders.status
        ORDER BY orders.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>История заказов</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2>История ваших заказов</h2>
  <a href="index.php" class="btn btn-secondary mb-3">← Назад в меню</a>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>ID заказа</th>
        <th>Дата</th>
        <th>Статус</th>
        <th>Блюда</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= $row['created_at'] ?></td>
          <td>
            <?php
              switch ($row['status']) {
                case 0: echo "Создан"; break;
                case 1: echo "Принят"; break;
                case 2: echo "Готовится"; break;
                case 3: echo "Готов"; break;
                default: echo "Неизвестно"; break;
              }
            ?>
          </td>
          <td><?= htmlspecialchars($row['items']) ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>