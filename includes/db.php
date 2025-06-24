<?php
$host = 'localhost';
$port = '8889'; 
$user = 'root';
$pass = 'root'; 
$db   = 'luckin_coffee';

$conn = new mysqli($host, $user, $pass, $db, $port);
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>