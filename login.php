<?php
session_start();
require_once 'includes/db.php';

$login = $_POST['login'];
$password = $_POST['password'];

$sql = "SELECT u.id, u.password, r.name AS role FROM users u 
        JOIN roles r ON u.role_id = r.id 
        WHERE u.login = ? AND u.active = 1 LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $login);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        // Редирект по роли
        switch ($user['role']) {
            case 'admin': header("Location: admin/index.php"); break;
            case 'client': header("Location: client/index.php"); break;
            case 'cook': header("Location: cook/index.php"); break;
        }
        exit;
    }
}

echo "Неверный логин или пароль.";