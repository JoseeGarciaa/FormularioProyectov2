<?php
session_start();
require_once '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = mysqli_real_escape_string($conn, $_POST['correo']);
    $contrasena = mysqli_real_escape_string($conn, $_POST['contrasena']);

    $sql = "SELECT * FROM Usuarios WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $usuario = $result->fetch_assoc();

        if (password_verify($contrasena, $usuario['contrasena'])) {
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['rol'] = $usuario['rol'];

            // Redirección según el rol
            if ($usuario['rol'] === 'admin') {
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: ../views/bienvenida.php");
            }
            exit();
        }
    }

    header("Location: ../views/login.php?status=error");
    exit();
}
?>
