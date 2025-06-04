<?php
require_once '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conn, $_POST['apellido']);
    $identificacion = mysqli_real_escape_string($conn, $_POST['numero_identificacion']);
    $correo = mysqli_real_escape_string($conn, $_POST['correo']);
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

    // Verificar si ya existe ese correo
    $verificar = $conn->prepare("SELECT id FROM Usuarios WHERE correo = ?");
    $verificar->bind_param("s", $correo);
    $verificar->execute();
    $verificar->store_result();

    if ($verificar->num_rows > 0) {
        header("Location: ../views/register.html?status=exists");
        exit();
    }

    $verificar->close();

    $sql = "INSERT INTO Usuarios (nombre, apellido, numero_identificacion, correo, contrasena)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nombre, $apellido, $identificacion, $correo, $contrasena);
    
    if ($stmt->execute()) {
        header("Location: ../views/register.php?status=ok");
        exit();
    } else {
        header("Location: ../views/register.html?status=error");
        exit();
    }
}
?>
