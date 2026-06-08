<?php
// Engañar a PHP para que sepa que el usuario navega con HTTPS gracias a Cloudflare
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
    $_SERVER['HTTPS'] = 'on';
    $_SERVER['SERVER_PORT'] = 443;
}
session_start();
// CONTROL DE ACCESO: Solo administradores
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: panel.php");
    exit();
}

date_default_timezone_set('Europe/Madrid');
include 'db.php';
$ahora_real = date("Y-m-d");

// Acciones de gestión de citas
if (isset($_GET['accion']) && isset($_GET['id'])) {
    $id_reserva = $_GET['id'];
    $acc = $_GET['accion'];

    try {
        if ($acc == 'elim') {
            // COMPLEJIDAD: Eliminar registro físico (Desaparece de ambos paneles)
            $stmt = $pdo->prepare("DELETE FROM citas WHERE id = ?");
            $stmt->execute([$id_reserva]);
        } else {
            // Actualizar estado (Confirmar o Cancelar)
            $estado = ($acc == 'conf') ? 'Confirmada' : (($acc == 'canc') ? 'Cancelada' : 'Pendiente');
            $stmt = $pdo->prepare("UPDATE citas SET estado = ? WHERE id = ?");
            $stmt->execute([$estado, $id_reserva]);
        }
    } catch (Exception $e) {
        die("Error en la operación: " . $e->getMessage());
    }
    header("Location: admin.php");
    exit();
}

// UNIFICACIÓN: Usamos la tabla 'citas' en lugar de 'reservas'
$reservas = $pdo->prepare("SELECT c.id, u.nombre as cliente, c.fecha, c.hora, c.servicio, c.estado
                           FROM citas c
                           JOIN usuarios u ON c.usuario_id = u.id
                           WHERE c.fecha >= ?
                           ORDER BY c.fecha ASC, c.hora ASC");
$reservas->execute([$ahora_real]);
$lista = $reservas->fetchAll();

// COMPLEJIDAD: Estadísticas rápidas para el Admin
$count_hoy = $pdo->prepare("SELECT COUNT(*) FROM citas WHERE fecha = ?");
$count_hoy->execute([$ahora_real]);
$total_hoy = $count_hoy->fetchColumn();

$count_pend = $pdo->prepare("SELECT COUNT(*) FROM citas WHERE estado = 'Pendiente' OR estado IS NULL");
$count_pend->execute();
$total_pend = $count_pend->fetchColumn();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Admin - ReservaYa</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="icon" type="image/jpeg" href="Capturas/Logotipo.jpg">
</head>
<body class="bg-admin dark-theme">
    <div class="watermark">ReservaYa</div>
    <div class="dashboard-container">
        <div class="header-info">
            <div style="display: flex; align-items: center; gap: 15px;">
                <img src="Capturas/Logotipo.jpg" alt="Logo" style="max-width: 60px; border-radius: 50%;">
                <h1 style="margin:0;">Gestión de Citas</h1>
            </div>
            <p>Hola, <b><?= htmlspecialchars($_SESSION['usuario']) ?></b> | <a href="logout.php" class="btn-danger" style="padding: 5px 15px; border-radius: 5px; color: white; text-decoration: none;">Cerrar Sesión</a></p>
        </div>
        
        <!-- COMPLEJIDAD: Dashboard de Estadísticas -->
        <div style="display: flex; gap: 20px; margin-bottom: 30px;">
            <div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 10px; flex: 1; text-align: center;">
                <span style="font-size: 0.9rem; opacity: 0.8;">Citas para hoy</span>
                <h2 style="margin: 5px 0; color: var(--primary-color) !important;"><?= $total_hoy ?></h2>
            </div>
            <div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 10px; flex: 1; text-align: center;">
                <span style="font-size: 0.9rem; opacity: 0.8;">Pendientes de confirmar</span>
                <h2 style="margin: 5px 0; color: var(--warning-color) !important;"><?= $total_pend ?></h2>
            </div>
        </div>

        <hr style="margin-bottom: 30px; opacity: 0.1;">
        
        <table>
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Servicio</th>
                    <th>Fecha y Hora</th>
                    <th>Estado</th>
                    <th style="text-align:center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lista as $r): ?>
                <?php 
                    $estado_clase = 'status-pendiente';
                    if ($r['estado'] == 'Confirmada') $estado_clase = 'status-confirmada';
                    if ($r['estado'] == 'Cancelada') $estado_clase = 'status-cancelada';
                ?>
                <tr>
                    <td><b><?= htmlspecialchars($r['cliente']) ?></b></td>
                    <td><?= htmlspecialchars($r['servicio']) ?></td>
                    <td><?= htmlspecialchars($r['fecha']) ?> <?= htmlspecialchars($r['hora']) ?></td>
                    <td>
                        <span class="badge <?= $estado_clase ?>">
                            <?= htmlspecialchars($r['estado'] ?: 'Pendiente') ?>
                        </span>
                    </td>
                    <td style="text-align:center;">
                        <a href="admin.php?accion=conf&id=<?= $r['id'] ?>" class="btn-success" style="font-size: 0.7rem; padding: 4px 8px; color: white; text-decoration: none; margin-right: 2px;">Confirmar</a>
                        <a href="admin.php?accion=canc&id=<?= $r['id'] ?>" class="btn-warning" style="font-size: 0.7rem; padding: 4px 8px; color: #000; text-decoration: none; margin-right: 2px; background-color: var(--warning-color);">Cancelar</a>
                        <a href="admin.php?accion=elim&id=<?= $r['id'] ?>" onclick="return confirm('¿Eliminar permanentemente? Esta acción no se puede deshacer y el cliente ya no verá la cita.')" class="btn-danger" style="font-size: 0.7rem; padding: 4px 8px; color: white; text-decoration: none;">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($lista)): ?>
                <tr><td colspan="5" style="text-align:center; padding: 30px; color: #888;">No hay citas próximas.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
