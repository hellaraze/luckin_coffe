

<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

// Проверка авторизации: только клиент
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header('Location: ../login.php');
    exit;
}

// Получение ID заказа из GET
if (!isset($_GET['order_id'])) {
    header('Location: cart.php');
    exit;
}

$orderId = (int) $_GET['order_id'];
$userId  = $_SESSION['user_id'];

// Проверка, что заказ принадлежит текущему пользователю и имеет статус 1 (в корзине)
$stmt = $conn->prepare("SELECT status FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $orderId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Нет такого заказа или не ваш — возвращаем в корзину
    header('Location: cart.php');
    exit;
}

$row = $result->fetch_assoc();
if ((int) $row['status'] !== 1) {
    // Заказ уже оформлен или не в корзине
    header('Location: cart.php');
    exit;
}

// Обновление статуса заказа на 2 (Принят и передан на приготовление)
$stmt = $conn->prepare("UPDATE orders SET status = 2 WHERE id = ?");
$stmt->bind_param("i", $orderId);
$stmt->execute();

// Перенаправление на страницу истории заказов клиента
header('Location: orders.php');
exit;