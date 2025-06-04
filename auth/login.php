<?php
session_start();

// Conexión
$servername = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
$dbname = getenv('DB_NAME');
$port = getenv('DB_PORT');

$conn = new mysqli($servername, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = mysqli_real_escape_string($conn, $_POST['correo']);
    $contrasena = mysqli_real_escape_string($conn, $_POST['contrasena']);

    $sql = "SELECT * FROM Usuarios WHERE correo='$correo'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();
        // Verificar contraseña
        if (password_verify($contrasena, $usuario['contrasena'])) {
            // Guardar datos en sesión
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['apellido'] = $usuario['apellido'];
            $_SESSION['numeroIdentificacion'] = $usuario['numeroIdentificacion'];

            header("Location: indexform.html");
            exit();
        } else {
            echo "<div style='color:red; text-align:center;'>Contraseña incorrecta.</div>";
        }
    } else {
        echo "<div style='color:red; text-align:center;'>Correo no encontrado.</div>";
    }

    $conn->close();
}
?>
