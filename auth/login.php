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

    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();
        if (password_verify($contrasena, $usuario['contrasena'])) {
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['usuario_id'] = $usuario['id'];
            header("Location: ../views/bienvenida.php");
            exit();
        } else {
            header("Location: ../views/login.html?status=error");
            
            exit();
        }
    } else {
        header("Location: ../views/login.html?status=error"); 
        
        exit();
    }
    $stmt->close();
    $conn->close();
}
?>
