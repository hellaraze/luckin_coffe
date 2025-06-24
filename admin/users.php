<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$result = $conn->query("SELECT u.id, u.name, u.login, u.active, r.name AS role 
                        FROM users u 
                        JOIN roles r ON u.role_id = r.id
                        ORDER BY u.id DESC");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Пользователи — Luckin Coffee</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2 class="mb-4">Пользователи</h2>
  <a href="index.php" class="btn btn-sm btn-secondary mb-3">← Назад</a>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>ID</th>
        <th>Имя</th>
        <th>Логин</th>
        <th>Роль</th>
        <th>Активен</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($user = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $user['id'] ?></td>
        <td><?= htmlspecialchars($user['name']) ?></td>
        <td><?= htmlspecialchars($user['login']) ?></td>
        <td><?= $user['role'] ?></td>
        <td><?= $user['active'] ? '✅' : '❌' ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>