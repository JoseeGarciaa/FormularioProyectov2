<?php
require_once '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conn, $_POST['apellido']);
    $identificacion = mysqli_real_escape_string($conn, $_POST['numero_identificacion']);
    $correo = mysqli_real_escape_string($conn, $_POST['correo']);
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO Usuarios (nombre, apellido, numero_identificacion, correo, contrasena)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nombre, $apellido, $identificacion, $correo, $contrasena);
    
    if ($stmt->execute()) {
        header("Location: ../views/login.html");
        exit();
    } else {
        echo "<p style='color:red; text-align:center;'>Error al registrar: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
}
?>
