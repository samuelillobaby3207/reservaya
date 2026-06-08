<?php
// Engañar a PHP para que sepa que el usuario navega con HTTPS gracias a Cloudflare
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
    $_SERVER['HTTPS'] = 'on';
    $_SERVER['SERVER_PORT'] = 443;
}
include 'db.php';
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $stmt_check = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt_check->execute([$email]);
        
        if ($stmt_check->rowCount() > 0) {
            $mensaje = "<p style='color:red;'>Este email ya está registrado.</p>";
        } else {
            $password_encriptada = password_hash($password, PASSWORD_BCRYPT);
            $sql = "INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, 'cliente')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $email, $password_encriptada]);
            $mensaje = "<p style='color:green;'>¡Registro completado! Ya puedes iniciar sesión.</p>";
        }
    } catch (Exception $e) {
        $mensaje = "<p style='color:red;'>Error al registrar: " . $e->getMessage() . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - ReservaYa</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="icon" type="image/jpeg" href="Capturas/Logotipo.jpg">
</head>
<body class="bg-auth">
    <div class="watermark">ReservaYa</div>
    <div class="glass-card">
        <div style="text-align: center; margin-bottom: 15px;">
            <img src="Capturas/Logotipo.jpg" alt="Logo ReservaYa" style="max-width: 120px; border-radius: 50%; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
        </div>
        <h3 style="text-align: center; color: #333; margin-bottom: 20px;">Crear Cuenta</h3>
        
        <?php echo $mensaje; ?>
        
        <form method="POST">
            <label>Nombre Completo:</label>
            <input type="text" name="nombre" placeholder="Tu nombre" required>
            
            <label>Email:</label>
            <input type="email" name="email" placeholder="correo@ejemplo.com" required>
            
            <label>Contraseña:</label>
            <input type="password" name="password" placeholder="Mínimo 6 caracteres" required>
            
            <button type="submit" class="btn-primary">Registrarse</button>
        </form>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="index.php" style="font-weight: bold;">¿Ya tienes cuenta? Inicia sesión</a>
        </div>
    </div>
</body>
</html>
