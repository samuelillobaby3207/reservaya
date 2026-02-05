<?php
$host = 'localhost';
$db = 'reservaya';
$user = 'reserva_user';
$pass = 'Usuario_123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
} catch (PDOException $e) {
    die("Error de conexión");
}
?>
