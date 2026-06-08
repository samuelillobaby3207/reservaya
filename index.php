<?php
// 1. PARCHE PARA CLOUDFLARE (Detectar HTTPS)
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
    $_SERVER['HTTPS'] = 'on';
    $_SERVER['SERVER_PORT'] = 443;
}

session_start();
include 'db.php';
$error = "";

// Lógica de Login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identificador = $_POST['identificador']; 
    $pass_ingresada = $_POST['password'];

    // Lógica corregida:
    // 1. Si es un acceso especial de admin (por nombre 'admin' o por los emails conocidos)
    if ($identificador === 'admin' || $identificador === 'admin@reservaya.es' || $identificador === 'admin@reservayasamuel.es') {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE rol = 'admin' LIMIT 1");
        $stmt->execute();
    } else {
        // 2. Acceso normal para cualquier otro usuario
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? OR nombre = ?");
        $stmt->execute([$identificador, $identificador]);
    }
    
    $user = $stmt->fetch();

    if ($user && password_verify($pass_ingresada, $user['password'])) {
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['usuario'] = $user['nombre'];
        $_SESSION['rol'] = $user['rol'];
        
        if ($user['rol'] === 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: panel.php");
        }
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - ReservaYa</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="icon" type="image/jpeg" href="Capturas/Logotipo.jpg">
</head>
<body class="bg-auth">
    <div class="watermark">ReservaYa</div>
    <div class="glass-card">
        <div style="text-align: center; margin-bottom: 15px;">
            <img src="Capturas/Logotipo.jpg" alt="Logo ReservaYa" style="max-width: 120px; border-radius: 50%; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
        </div>
        <h3 style="text-align: center; color: #606770; font-weight: normal; margin-bottom: 25px;">Iniciar Sesión</h3>
        
        <?php if ($error): ?>
            <p style="color: red; text-align: center;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" autocomplete="off">
            <label>Email o Usuario:</label>
            <input type="text" name="identificador" placeholder="ejemplo@correo.com o usuario123" required autocomplete="off">
            
            <label>Contraseña:</label>
            <input type="password" name="password" placeholder="••••••••" required autocomplete="new-password">
            
            <button type="submit" class="btn-primary">Entrar</button>
        </form>
        
        <p style="text-align: center; margin-top: 20px;">
            ¿No tienes cuenta? <a href="registro.php" style="color: #1877f2; text-decoration: none; font-weight: bold;">Regístrate aquí</a>
        </p>
    </div>
</body>
</html>
