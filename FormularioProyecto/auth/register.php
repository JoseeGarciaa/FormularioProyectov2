<?php
// Parámetros de conexión desde variables de entorno
$servername = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
$dbname = getenv('DB_NAME');
$port = getenv('DB_PORT');

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se recibió POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conn, $_POST['apellido']);
    $numeroIdentificacion = mysqli_real_escape_string($conn, $_POST['numeroIdentificacion']);
    $correo = mysqli_real_escape_string($conn, $_POST['correo']);
    $contrasena = mysqli_real_escape_string($conn, $_POST['contrasena']);
    $confirmarContrasena = mysqli_real_escape_string($conn, $_POST['confirmarContrasena']);

    // Verificar que las contraseñas coincidan
    if ($contrasena !== $confirmarContrasena) {
        echo "<div style='color:red; text-align:center;'>Las contraseñas no coinciden.</div>";
    } else {
        // Encriptar contraseña
        $hashContrasena = password_hash($contrasena, PASSWORD_DEFAULT);

        // Insertar en tabla Usuarios
        $sql = "
            INSERT INTO Usuarios (nombre, apellido, numeroIdentificacion, correo, contrasena)
            VALUES ('$nombre', '$apellido', '$numeroIdentificacion', '$correo', '$hashContrasena')
        ";

        if ($conn->query($sql) === TRUE) {
            echo "<div style='color:green; text-align:center;'>¡Usuario registrado con éxito!</div>";
        } else {
            echo "<div style='color:red; text-align:center;'>Error: " . $conn->error . "</div>";
        }
    }

    $conn->close();
}
?>
