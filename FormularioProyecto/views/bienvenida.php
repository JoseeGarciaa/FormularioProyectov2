
<?php
session_start();

// Si no hay sesión activa, redirigir al login
if (!isset($_SESSION['nombre'])) {
    header("Location: login.php");
    exit();
}
?>
