

<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

// Проверка авторизации: только администратор
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

// Обработка формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $login    = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    $roleId   = (int)($_POST['role_id'] ?? 2);

    if ($name && $login && $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("
            INSERT INTO users (name, login, password, role_id, active)
            VALUES (?, ?, ?, ?, 1)
        ");
        $stmt->bind_param("sssi", $name, $login, $hashedPassword, $roleId);
        $stmt->execute();

        header('Location: users.php');
        exit;
    } else {
        $error = 'Все поля обязательны для заполнения.';
    }
}

// Получение списка ролей
$roles = [];
$res = $conn->query("SELECT id, name FROM roles");
while ($row = $res->fetch_assoc()) {
    $roles[] = $row;
}
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Добавить пользователя</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2>Добавить пользователя</h2>
  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <form method="post">
    <div class="mb-3">
      <label class="form-label">Имя</label>
      <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Логин</label>
      <input type="text" name="login" class="form-control" required value="<?= htmlspecialchars($_POST['login'] ?? '') ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Пароль</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Роль</label>
      <select name="role_id" class="form-select">
        <?php foreach ($roles as $r): ?>
          <option value="<?= $r['id'] ?>" <?= isset($_POST['role_id']) && $_POST['role_id'] == $r['id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($r['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <button type="submit" class="btn btn-primary">Сохранить</button>
    <a href="users.php" class="btn btn-secondary">Отмена</a>
  </form>
</div>
</body>
</html>