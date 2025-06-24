

<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

// Проверка авторизации: только администратор
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

// Получение ID пользователя для переключения активности
if (!isset($_GET['id'])) {
    header('Location: users.php');
    exit;
}
$userId = (int)$_GET['id'];

// Переключение поля active: если было 1 — станет 0, и наоборот
$stmt = $conn->prepare("UPDATE users SET active = IF(active = 1, 0, 1) WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();

// Возврат к списку пользователей
header('Location: users.php');
exit;