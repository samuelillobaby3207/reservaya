<?php
// --- CONFIGURACIÓN ---
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'db.php';

// Fecha mínima (Hoy)
$hoy = date("Y-m-d");

// Usuario
$usu = isset($_GET['nombre']) ? $_GET['nombre'] : "Admin";
$id_u = isset($_GET['id']) ? $_GET['id'] : 1;

// --- GUARDAR (INSERT) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dia = $_POST['dia'];
    $hora = $_POST['hora'];
    $serv = $_POST['servicio'];
    
    // Unimos Día + Hora para que MySQL lo entienda (Formato: YYYY-MM-DD HH:MM:SS)
    $fecha_final = $dia . ' ' . $hora . ':00';
    
    try {
        $sql = "INSERT INTO reservas (usuario_id, servicio_id, fecha_hora, estado) VALUES (?, ?, ?, 'CONFIRMADA')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_u, $serv, $fecha_final]);
    } catch (Exception $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}

// --- LEER DATOS ---
$sql2 = "SELECT r.id, r.fecha_hora, r.estado, u.nombre as cliente, s.nombre as nom_serv 
         FROM reservas r 
         LEFT JOIN usuarios u ON r.usuario_id = u.id 
         LEFT JOIN servicios s ON r.servicio_id = s.id 
         ORDER BY r.fecha_hora DESC";
$res = $pdo->query($sql2);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Panel Gestión</title>
</head>
<body style="font-family: Arial; padding: 20px; background-color: #eee;">

    <div style="background: white; border: 1px solid #999; padding: 20px; max-width: 900px; margin: 0 auto;">
        
        <h3>Panel ReservaYa</h3>
        <p>Hola, <b><?php echo $usu; ?></b> | <a href="index.php">Salir</a></p>
        <hr>

        <div style="background:#ddd; padding:15px; border:1px solid #aaa;">
            <h4>+ Nueva Cita</h4>
            <form method="POST">
                <label>Servicio:</label>
                <select name="servicio" style="padding:5px;">
                    <option value="1">Corte Básico</option>
                    <option value="2">Barba</option>
                    <option value="3">Tinte</option>
                </select>
                
                <br><br>

                <label>Día:</label>
                <input type="date" name="dia" min="<?php echo $hoy; ?>" required style="padding:5px;">

                <label style="margin-left:15px;">Hora:</label>
                <select name="hora" style="padding:5px;">
                    <option>09:00</option>
                    <option>09:15</option>
                    <option>09:30</option>
                    <option>09:45</option>
                    <option>10:00</option>
                    <option>10:15</option>
                    <option>10:30</option>
                    <option>10:45</option>
                    <option>11:00</option>
                    <option>11:15</option>
                    <option>11:30</option>
                    <option>12:00</option>
                    <option>16:00</option>
                    <option>16:15</option>
                    <option>16:30</option>
                    <option>17:00</option>
                </select>
                
                <button type="submit" style="margin-left:20px; padding: 5px 15px; background: #28a745; color: white; border: none; cursor: pointer;">Confirmar Reserva</button>
            </form>
        </div>

        <hr>

        <h4>Agenda (Base de Datos)</h4>
        <table border="1" width="100%" cellpadding="5" style="border-collapse: collapse;">
            <tr style="background-color: #444; color: white;">
                <th>ID</th>
                <th>Cliente</th>
                <th>Servicio</th>
                <th>Fecha y Hora</th>
                <th>Estado</th>
            </tr>
            
            <?php 
            if ($res) {
                while ($fila = $res->fetch(PDO::FETCH_ASSOC)) {
                    $nom_cli = $fila['cliente'] ? $fila['cliente'] : "Desconocido";
                    $nom_ser = $fila['nom_serv'] ? $fila['nom_serv'] : "Servicio Borrado";
                    $fecha_bonita = date("d/m/Y H:i", strtotime($fila['fecha_hora']));

                    echo "<tr>";
                    echo "<td>#" . $fila['id'] . "</td>";
                    echo "<td><b>" . $nom_cli . "</b></td>"; 
                    echo "<td>" . $nom_ser . "</td>";
                    echo "<td>" . $fecha_bonita . "</td>";
                    echo "<td>" . $fila['estado'] . "</td>";
                    echo "</tr>";
                }
            }
            ?>
        </table>
    </div>
</body>
</html>
