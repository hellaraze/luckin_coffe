

<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: ../index.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Получаем последний незавершённый заказ клиента (например, статус = 1 — Принят)
$orderSql = "SELECT id FROM orders WHERE user_id = ? AND status = 1 ORDER BY created_at DESC LIMIT 1";
$stmt = $conn->prepare($orderSql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$orderId = null;
if ($row = $result->fetch_assoc()) {
    $orderId = $row['id'];
}
$stmt->close();

$items = [];
if ($orderId) {
    $itemSql = "SELECT mi.name, oi.quantity 
                FROM order_items oi 
                JOIN menu_items mi ON oi.item_id = mi.id 
                WHERE oi.order_id = ?";
    $stmt = $conn->prepare($itemSql);
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $items = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Корзина</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Моя корзина</h2>
    <?php if ($orderId && $items->num_rows > 0): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Количество</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($item = $items->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= $item['quantity'] ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="submit_order.php?order_id=<?= $orderId ?>" class="btn btn-success">Оформить заказ</a>
    <?php else: ?>
        <p>Ваша корзина пуста.</p>
    <?php endif; ?>
    <a href="menu.php" class="btn btn-secondary mt-3">← Вернуться в меню</a>
</div>
</body>
</html>