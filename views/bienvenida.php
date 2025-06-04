<?php
session_start();

// Si no hay sesión activa, redirigir al login.html dentro de views
if (!isset($_SESSION['nombre'])) {
    header("Location: login.html");
    exit();
}
?>

<!-- Aquí puedes poner el contenido HTML que quieras mostrar cuando haya sesión activa -->
<h1>Bienvenido, <?php echo $_SESSION['nombre']; ?> 👋</h1>
<p><a href="../auth/logout.php">Cerrar sesión</a></p>
