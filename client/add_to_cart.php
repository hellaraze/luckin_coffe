

<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

// Проверка авторизации: только клиент
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header('Location: ../login.php');
    exit;
}

$userId = $_SESSION['user_id'];
if (!isset($_POST['item_id'], $_POST['quantity'])) {
    header('Location: menu.php');
    exit;
}

$itemId = (int)$_POST['item_id'];
$quantity = (int)$_POST['quantity'];
if ($quantity <= 0) {
    header('Location: menu.php');
    exit;
}

// Поиск существующего заказа со статусом 1 (Принят)
$stmt = $conn->prepare("SELECT id FROM orders WHERE user_id = ? AND status = 1");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $order = $result->fetch_assoc();
    $orderId = $order['id'];
} else {
    // Создание нового заказа
    $stmt = $conn->prepare("INSERT INTO orders (user_id, status, created_at) VALUES (?, 1, NOW())");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $orderId = $stmt->insert_id;
}

// Проверка наличия позиции в заказе
$stmt = $conn->prepare("SELECT id, quantity FROM order_items WHERE order_id = ? AND item_id = ?");
$stmt->bind_param("ii", $orderId, $itemId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $item = $result->fetch_assoc();
    $newQty = $item['quantity'] + $quantity;
    $stmt = $conn->prepare("UPDATE order_items SET quantity = ? WHERE id = ?");
    $stmt->bind_param("ii", $newQty, $item['id']);
    $stmt->execute();
} else {
    // Добавление новой позиции
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, item_id, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $orderId, $itemId, $quantity);
    $stmt->execute();
}

// Перенаправление в корзину
header('Location: cart.php');
exit;