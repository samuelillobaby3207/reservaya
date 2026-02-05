<?php
include 'db.php';
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    // Buscamos al usuario
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? AND password = ?");
    $stmt->execute([$email, $pass]);
    $user = $stmt->fetch();

    if ($user) {
        // Si existe, vamos al panel
        header("Location: panel.php?nombre=" . $user['nombre'] . "&id=" . $user['id']);
        exit();
    } else {
        $error = " Usuario o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html>
<body style="font-family: Arial; padding: 50px; text-align: center;">
    <div style="border: 1px solid black; padding: 20px; width: 300px; margin: 0 auto;">
        <h3>Iniciar Sesión</h3>
        <form method="POST">
            <p>Email:</p> <input type="email" name="email" required>
            <p>Contraseña:</p> <input type="password" name="password" required><br><br>
            <button type="submit">Entrar</button>
        </form>
        <p style="color:red"><?php echo $error; ?></p>
        <a href="registro.php">Crear cuenta nueva</a>
    </div>
</body>
</html>
