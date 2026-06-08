<?php
// Engañar a PHP para que sepa que el usuario navega con HTTPS gracias a Cloudflare
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
    $_SERVER['HTTPS'] = 'on';
    $_SERVER['SERVER_PORT'] = 443;
}
// Configuración de la base de datos (USANDO DNS DE DOCKER PARA MÁXIMA FIABILIDAD)
$servidor_db = 'mysql_db'; 
$nombre_bd   = 'reservaya';
$usuario_db  = 'root';
$clave_db    = 'root';

try {
    $dsn = "mysql:host=$servidor_db;dbname=$nombre_bd;charset=utf8mb4";
    $pdo = new PDO($dsn, $usuario_db, $clave_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("❌ Error de conexión: " . $e->getMessage());
}
?>
