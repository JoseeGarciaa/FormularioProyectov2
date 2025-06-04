<?php
session_start();
if (isset($_SESSION['nombre'])) {
    header("Location: views/bienvenida.php");
}
exit();
?>
