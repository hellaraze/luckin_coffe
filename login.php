<?php
session_start();
require_once 'includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($login === '' || $password === '') {
        $error = 'Введите логин и пароль.';
    } else {
        // Проверка пользователя
        $sql = "SELECT u.id, u.password, r.name AS role
                FROM users u
                JOIN roles r ON u.role_id = r.id
                WHERE u.login = ? AND u.active = 1
                LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role']    = $user['role'];
                // Редирект по роли
                switch ($user['role']) {
                    case 'admin':
                        header("Location: admin/index.php");
                        break;
                    case 'client':
                        header("Location: client/index.php");
                        break;
                    case 'cook':
                        header("Location: cook/orders.php");
                        break;
                    default:
                        header("Location: login.php");
                }
                exit;
            }
        }
        $error = 'Неверный логин или пароль.';
    }
}
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Вход</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5" style="max-width:400px;">
  <h2 class="mb-4">Вход в систему</h2>
  <?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <form method="post" action="login.php">
    <div class="mb-3">
      <label for="login" class="form-label">Логин</label>
      <input type="text" id="login" name="login" class="form-control" value="<?= htmlspecialchars($_POST['login'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Пароль</label>
      <input type="password" id="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Войти</button>
  </form>
  <p class="mt-3 text-center"><a href="register.php">Регистрация</a></p>
</div>
</body>
</html>