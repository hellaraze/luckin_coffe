<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: ../login.php");
    exit;
}

// Получаем список блюд
$result = $conn->query("SELECT id, name, price FROM menu_items WHERE active = 1");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Меню — Luckin Coffee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Меню</h2>
    <div class="row">
        <?php while ($item = $result->fetch_assoc()): ?>
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($item['name']) ?></h5>
                    <p class="card-text">Цена: <?= $item['price'] ?> ₸</p>
                    <form action="add_to_cart.php" method="POST">
                        <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                        <input type="number" name="quantity" value="1" min="1" class="form-control mb-2" required>
                        <button type="submit" class="btn btn-primary">Добавить в корзину</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <a href="cart.php" class="btn btn-secondary">Перейти в корзину</a>
</div>
</body>
</html>
