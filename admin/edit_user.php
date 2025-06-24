<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: users.php");
    exit;
}

$user_id = (int)$_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $login = trim($_POST['login']);
    $role_id = (int)$_POST['role_id'];
    $active = isset($_POST['active']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE users SET name = ?, login = ?, role_id = ?, active = ? WHERE id = ?");
    $stmt->bind_param("ssiii", $name, $login, $role_id, $active, $user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: users.php");
    exit;
}

$stmt = $conn->prepare("SELECT name, login, role_id, active FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Редактировать пользователя</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2>Редактировать пользователя</h2>
  <form method="post">
    <div class="mb-3">
      <label class="form-label">Имя</label>
      <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Логин</label>
      <input type="text" name="login" class="form-control" value="<?= htmlspecialchars($user['login']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Роль</label>
      <select name="role_id" class="form-select" required>
        <option value="1" <?= $user['role_id'] == 1 ? 'selected' : '' ?>>Администратор</option>
        <option value="2" <?= $user['role_id'] == 2 ? 'selected' : '' ?>>Клиент</option>
        <option value="3" <?= $user['role_id'] == 3 ? 'selected' : '' ?>>Повар</option>
      </select>
    </div>
    <div class="form-check mb-3">
      <input type="checkbox" name="active" class="form-check-input" id="activeCheck" <?= $user['active'] ? 'checked' : '' ?>>
      <label class="form-check-label" for="activeCheck">Активен</label>
    </div>
    <button type="submit" class="btn btn-primary">Сохранить</button>
    <a href="users.php" class="btn btn-secondary">Отмена</a>
  </form>
</div>
</body>
</html>
