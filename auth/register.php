<?php
require_once '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conn, $_POST['apellido']);
    $identificacion = mysqli_real_escape_string($conn, $_POST['numero_identificacion']);
    $correo = mysqli_real_escape_string($conn, $_POST['correo']);
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

    // Verificar si ya existe el correo o el número de identificación
    $verificar = $conn->prepare("SELECT id FROM Usuarios WHERE correo = ? OR numero_identificacion = ?");
    $verificar->bind_param("ss", $correo, $identificacion);
    $verificar->execute();
    $verificar->store_result();

    if ($verificar->num_rows > 0) {
        // Verificar cuál de los dos ya existe
        $verificar_correo = $conn->prepare("SELECT id FROM Usuarios WHERE correo = ?");
        $verificar_correo->bind_param("s", $correo);
        $verificar_correo->execute();
        $verificar_correo->store_result();
        
        $verificar_identificacion = $conn->prepare("SELECT id FROM Usuarios WHERE numero_identificacion = ?");
        $verificar_identificacion->bind_param("s", $identificacion);
        $verificar_identificacion->execute();
        $verificar_identificacion->store_result();
        
        if ($verificar_correo->num_rows > 0 && $verificar_identificacion->num_rows > 0) {
            // Ambos ya existen
            header("Location: ../views/register.php?status=ambos_existen");
        } elseif ($verificar_correo->num_rows > 0) {
            // Solo el correo ya existe
            header("Location: ../views/register.php?status=correo_existe");
        } else {
            // Solo la identificación ya existe
            header("Location: ../views/register.php?status=identificacion_existe");
        }
        exit();
    }

    $verificar->close();

    $sql = "INSERT INTO Usuarios (nombre, apellido, numero_identificacion, correo, contrasena)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nombre, $apellido, $identificacion, $correo, $contrasena);
    
    if ($stmt->execute()) {
        header("Location: ../views/register.php?status=exito");
        exit();
    } else {
        header("Location: ../views/register.php?status=error");
        exit();
    }
}
?>
