<?php
include 'db.php';
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $pass = $_POST['password']; // En un entorno real, usaríamos password_hash()

    // Intentamos guardar el usuario
    try {
        $sql = "INSERT INTO usuarios (nombre, email, password, rol) VALUES ('$nombre', '$email', '$pass', 'cliente')";
        $pdo->exec($sql);
        $mensaje = "Usuario registrado correctamente. <a href='index.php'>Ir al Login</a>";
    } catch(PDOException $e) {
        $mensaje = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<body style="font-family: Arial; padding: 50px; text-align: center;">
    <div style="border: 1px solid black; padding: 20px; width: 300px; margin: 0 auto;">
        <h3>Registro de Usuario</h3>
        <form method="POST">
            <p>Nombre:</p> <input type="text" name="nombre" required>
            <p>Email:</p> <input type="email" name="email" required>
            <p>Contraseña:</p> <input type="password" name="password" required><br><br>
            <button type="submit">Crear Cuenta</button>
        </form>
        <p><?php echo $mensaje; ?></p>
        <a href="index.php">Volver</a>
    </div>
</body>
</html>
