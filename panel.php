<?php
// Engañar a PHP para que sepa que el usuario navega con HTTPS gracias a Cloudflare
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
    $_SERVER['HTTPS'] = 'on';
    $_SERVER['SERVER_PORT'] = 443;
}
session_start();
// CONTROL DE ACCESO: Solo usuarios logueados
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

// Si es admin, mandarlo a su panel (Evita confusiones si tiene dos pestañas abiertas)
if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') {
    header("Location: admin.php");
    exit();
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'db.php';

$id_u = $_SESSION['usuario_id'];

// GARANTÍA: Obtenemos el nombre real de la base de datos para evitar confusiones de sesión
$stmt_user = $pdo->prepare("SELECT nombre, email FROM usuarios WHERE id = ?");
$stmt_user->execute([$id_u]);
$user_data = $stmt_user->fetch();
$nombre_real = $user_data ? $user_data['nombre'] : 'Usuario';
$email_real = $user_data ? $user_data['email'] : '---';

$hoy = date("Y-m-d");

// 1. Lógica para cancelar una cita
if (isset($_GET['eliminar_id'])) {
    $id_eliminar = $_GET['eliminar_id'];
    $stmt_del = $pdo->prepare("DELETE FROM citas WHERE id = ? AND usuario_id = ?");
    $stmt_del->execute([$id_eliminar, $id_u]);
    header("Location: panel.php");
    exit();
}

// 2. Lógica para crear una nueva cita
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_reservar'])) {
    $dia = $_POST['dia'];
    $hora = $_POST['hora'];
    $serv = $_POST['servicio'];

    try {
        $check = $pdo->prepare("SELECT id FROM citas WHERE fecha = ? AND hora = ? AND estado != 'Cancelada'");
        $check->execute([$dia, $hora]);
        
        if ($check->rowCount() > 0) {
            echo "<script>alert('Esa hora ya está reservada. Elige otro horario.');</script>";
        } else {
            $sql = "INSERT INTO citas (usuario_id, servicio, fecha, hora, estado) VALUES (?, ?, ?, ?, 'Pendiente')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id_u, $serv, $dia, $hora]);
            header("Location: panel.php?success=1");
            exit();
        }
    } catch (Exception $e) {
        echo "<script>alert('Error al reservar: " . $e->getMessage() . "');</script>";
    }
}

// 3. Obtenemos las citas del usuario actual
$sql2 = "SELECT id, fecha, hora, servicio, estado FROM citas WHERE usuario_id = ? ORDER BY fecha DESC, hora DESC";
$res = $pdo->prepare($sql2);
$res->execute([$id_u]);
$lista = $res->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Panel - ReservaYa</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="icon" type="image/jpeg" href="Capturas/Logotipo.jpg">
</head>
<body class="bg-panel">
    <div class="watermark">ReservaYa</div>
    <div class="dashboard-container">
        
        <div class="header-info">
            <div style="display: flex; align-items: center; gap: 15px;">
                <img src="Capturas/Logotipo.jpg" alt="Logo" style="max-width: 60px; border-radius: 50%;">
                <h2 style="margin:0;">Panel de Control</h2>
            </div>
            <p>Bienvenido/a, <b><?php echo htmlspecialchars($nombre_real); ?></b> (<?php echo htmlspecialchars($email_real); ?>) | <a href="logout.php" class="btn-danger" style="padding: 5px 15px; border-radius: 5px; color: white; text-decoration: none;">Cerrar Sesión</a></p>
        </div>
        
        <?php if (isset($_GET['success'])): ?>
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">✅ Cita reservada correctamente.</div>
        <?php endif; ?>

        <hr style="margin-bottom: 30px; opacity: 0.2;">
        
        <!-- Formulario de Reserva -->
        <div style="background: rgba(255,255,255,0.6); padding:25px; border-radius:12px; margin-bottom: 30px;">
            <h4 style="margin-top: 0; margin-bottom: 15px;">📅 Reservar Nueva Cita</h4>
            <form method="POST" autocomplete="off">
                <div style="display: flex; flex-wrap: wrap; gap: 20px; align-items: flex-end;">
                    <div style="flex: 1; min-width: 200px;">
                        <label>Servicio:</label>
                        <select name="servicio" required>
                            <option value="Arreglo y perfilado de barba (15€)">Arreglo y perfilado de barba (15€)</option>
                            <option value="Corte de Pelo (8€)">Corte de Pelo (8€)</option>
                            <option value="Mechas mas Corte (25€)">Mechas mas Corte (25€)</option>
                            <option value="Depilacion Cejas (4€)">Depilacion Cejas (4€)</option>
                        </select>
                    </div>
                    <div style="flex: 1; min-width: 150px;">
                        <label>Día:</label>
                        <input type="date" name="dia" min="<?php echo $hoy; ?>" required>
                    </div>
                    <div style="flex: 1; min-width: 100px;">
                        <label>Hora:</label>
                        <select name="hora" required>
                            <?php 
                                $horas = ["09:00", "09:30", "10:00", "10:30", "11:00", "11:30", "12:00", "12:30", "16:00", "16:30", "17:00", "17:30"];
                                foreach($horas as $h) echo "<option value='$h'>$h</option>";
                            ?>
                        </select>
                    </div>
                    <div style="flex: 1; min-width: 200px;">
                        <button type="submit" name="btn_reservar" class="btn-success">Confirmar Reserva</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tabla de Citas -->
        <h4 style="margin-bottom: 15px;">📋 Mis Citas Programadas</h4>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Servicio</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Estado</th>
                        <th style="text-align:center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista as $fila): ?>
                    <?php 
                        $estado_clase = 'status-pendiente';
                        if ($fila['estado'] == 'Confirmada') $estado_clase = 'status-confirmada';
                        if ($fila['estado'] == 'Cancelada') $estado_clase = 'status-cancelada';
                    ?>
                    <tr>
                        <td><b><?= htmlspecialchars($fila['servicio']) ?></b></td>
                        <td><?= htmlspecialchars($fila['fecha']) ?></td>
                        <td><?= htmlspecialchars($fila['hora']) ?></td>
                        <td>
                            <span class="badge <?= $estado_clase ?>">
                                <?= htmlspecialchars($fila['estado'] ?: 'Pendiente') ?>
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <?php if ($fila['estado'] !== 'Cancelada'): ?>
                                <a href="panel.php?eliminar_id=<?= $fila['id'] ?>" onclick="return confirm('¿Estás seguro de que quieres cancelar esta cita?')" class="btn-danger" style="font-size: 0.75rem; padding: 5px 10px; color: white; text-decoration: none; border-radius: 4px;">Cancelar</a>
                            <?php else: ?>
                                <span style="color: #666; font-size: 0.8rem;">---</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($lista)): ?>
                    <tr><td colspan="5" style="text-align:center; padding: 30px; color: #666;">Aún no tienes ninguna cita reservada.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
