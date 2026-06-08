<?php
// Engañar a PHP para que sepa que el usuario navega con HTTPS gracias a Cloudflare
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
    $_SERVER['HTTPS'] = 'on';
    $_SERVER['SERVER_PORT'] = 443;
}
session_start();
session_unset();
session_destroy();
header("Location: index.php");
exit();
?>
