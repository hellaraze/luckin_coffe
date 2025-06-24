<?php
session_start();
if (isset($_SESSION['user_id'])) {
    // Уже вошёл — перенаправляем по роли
    switch ($_SESSION['role']) {
        case 'admin': header("Location: admin/index.php"); break;
        case 'client': header("Location: client/index.php"); break;
        case 'cook': header("Location: cook/index.php"); break;
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Вход — Luckin Coffee</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <h3 class="mb-3 text-center">Вход</h3>
      <form action="login.php" method="POST">
        <div class="mb-3">
          <label for="login" class="form-label">Логин</label>
          <input type="text" name="login" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Пароль</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Войти</button>
      </form>
    </div>
  </div>
</div>
</body>
</html>