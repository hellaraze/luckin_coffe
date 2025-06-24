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
    <!-- <link rel="stylesheet" href="assets/style.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Регистрация</h2>
                        <form method="post" action="">
                            <div class="mb-3">
                                <label for="username" class="form-label">Логин</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Пароль</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Роль</label>
                                <select class="form-select" id="role" name="role">
                                    <option value="client">Клиент</option>
                                    <option value="cook">Повар</option>
                                    <option value="admin">Администратор</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Зарегистрироваться</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>