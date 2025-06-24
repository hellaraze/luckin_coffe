

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Подключение к БД
require_once 'includes/db.php';

// Обработка формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = trim($_POST['role'] ?? 'client'); // по умолчанию

    // Проверка на уникальность логина
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE login = ?");
    $checkStmt->bind_param("s", $username);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        echo "Такой логин уже существует.";
        $checkStmt->close();
        exit;
    }

    $checkStmt->close();

    if ($username && $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $roleId = match ($role) {
            'admin'  => 1,
            'client' => 2,
            'cook'   => 3,
            default  => 2,
        };

        $stmt = $conn->prepare("INSERT INTO users (login, password, role_id, name, photo, active) VALUES (?, ?, ?, '', '', 1)");
        $stmt->bind_param("ssi", $username, $hashedPassword, $roleId);

        if ($stmt->execute()) {
            header('Location: login.php');
            exit;
        } else {
            echo "Ошибка при регистрации: " . htmlspecialchars($stmt->error);
        }

        $stmt->close();
    } else {
        echo "Пожалуйста, заполните все поля.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <h2>Регистрация</h2>
    <form method="post" action="">
        <label>Логин: <input type="text" name="username" required></label><br><br>
        <label>Пароль: <input type="password" name="password" required></label><br><br>
        <label>Роль:
            <select name="role">
                <option value="client">Клиент</option>
                <option value="cook">Повар</option>
                <option value="admin">Администратор</option>
            </select>
        </label><br><br>
        <button type="submit">Зарегистрироваться</button>
    </form>
</body>
</html>