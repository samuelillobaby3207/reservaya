<?php
include 'db.php';
try {
    $new_email = 'admin@reservayasamuel.es';
    $stmt = $pdo->prepare("UPDATE usuarios SET email = ? WHERE rol = 'admin'");
    $stmt->execute([$new_email]);
    if ($stmt->rowCount() > 0) {
        echo "Admin email updated successfully to $new_email\n";
    } else {
        echo "No admin user found or email already set to $new_email\n";
    }
} catch (Exception $e) {
    echo "Error updating admin email: " . $e->getMessage() . "\n";
}
unlink(__FILE__);
?>
