<?php

// Obtener parámetros de conexión desde variables de entorno
$servername = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
$dbname = getenv('DB_NAME');
$port = getenv('DB_PORT');

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se recibió una solicitud POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos enviados a través de POST
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conn, $_POST['apellido']);
    $numeroIdentificacion = mysqli_real_escape_string($conn, $_POST['numeroIdentificacion']);
    $edad = mysqli_real_escape_string($conn, $_POST['edad']);
    $genero = mysqli_real_escape_string($conn, $_POST['genero']);
    $correo = mysqli_real_escape_string($conn, $_POST['correo']);
    $numeroCelular = mysqli_real_escape_string($conn, $_POST['numeroCelular']);
    $nombrePrograma = mysqli_real_escape_string($conn, $_POST['nombrePrograma']);
    $semestre = mysqli_real_escape_string($conn, $_POST['semestre']);
    $jornada = mysqli_real_escape_string($conn, $_POST['jornada']);
    $materia1 = mysqli_real_escape_string($conn, $_POST['materia1']);
    $materia2 = mysqli_real_escape_string($conn, $_POST['materia2']);
    $materia3 = mysqli_real_escape_string($conn, $_POST['materia3']);
    $materia4 = mysqli_real_escape_string($conn, $_POST['materia4']);
    $materia5 = mysqli_real_escape_string($conn, $_POST['materia5']);
    $materia6 = mysqli_real_escape_string($conn, $_POST['materia6']);
    $materia7 = mysqli_real_escape_string($conn, $_POST['materia7']);
    $fecha = mysqli_real_escape_string($conn, $_POST['fecha']);

    // Consulta SQL para insertar los datos
    $sql = "
        INSERT INTO FormularioInscripcion (
            Nombre, Apellido, NumeroIdentificacion, Edad, Genero, Correo, NumeroCelular, 
            NombrePrograma, Semestre, Jornada, Materia1, Materia2, Materia3, Materia4, 
            Materia5, Materia6, Materia7, Fecha
        ) 
        VALUES (
            '$nombre', '$apellido', '$numeroIdentificacion', '$edad', '$genero', '$correo', 
            '$numeroCelular', '$nombrePrograma', '$semestre', '$jornada', '$materia1', 
            '$materia2', '$materia3', '$materia4', '$materia5', '$materia6', '$materia7', '$fecha'
        )
    ";

    // Ejecutar la consulta SQL y verificar si fue exitosa
    if ($conn->query($sql) === TRUE) {
        echo "<div class='success-message'>¡Nuevo registro creado con éxito!</div>";
    } else {
        echo "<div class='error-message'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }

    // Cerrar la conexión
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Inscripción</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 50%;
            margin: 50px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group label {
            display: block;
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
        }

        .input-group input,
        .input-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            color: #333;
        }

        .input-group input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            border: none;
        }

        .input-group input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error-message {
            color: #ff6347;
            background-color: #f8d7da;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 20px;
        }

        .success-message {
            color: #28a745;
            background-color: #d4edda;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 20px;
        }

        .input-group input,
        .input-group select {
            transition: border 0.3s ease;
        }

        .input-group input:focus,
        .input-group select:focus {
            border-color: #4CAF50;
            outline: none;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Formulario de Inscripción</h2>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="input-group">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>

        <div class="input-group">
            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" required>
        </div>

        <div class="input-group">
            <label for="numeroIdentificacion">Número de Identificación:</label>
            <input type="text" id="numeroIdentificacion" name="numeroIdentificacion" required>
        </div>

        <div class="input-group">
            <label for="edad">Edad:</label>
            <input type="number" id="edad" name="edad" required>
        </div>

        <div class="input-group">
            <label for="genero">Género:</label>
            <select id="genero" name="genero" required>
                <option value="Masculino">Masculino</option>
                <option value="Femenino">Femenino</option>
                <option value="Otro">Otro</option>
            </select>
        </div>

        <div class="input-group">
            <label for="correo">Correo Electrónico:</label>
            <input type="email" id="correo" name="correo" required>
        </div>

        <div class="input-group">
            <label for="numeroCelular">Número de Celular:</label>
            <input type="text" id="numeroCelular" name="numeroCelular" required>
        </div>

        <div class="input-group">
            <label for="nombrePrograma">Nombre del Programa:</label>
            <input type="text" id="nombrePrograma" name="nombrePrograma" required>
        </div>

        <div class="input-group">
            <label for="semestre">Semestre:</label>
            <input type="number" id="semestre" name="semestre" required>
        </div>

        <div class="input-group">
            <label for="jornada">Jornada:</label>
            <select id="jornada" name="jornada" required>
                <option value="Mañana">Mañana</option>
                <option value="Tarde">Tarde</option>
                <option value="Noche">Noche</option>
            </select>
        </div>

        <div class="input-group">
            <label for="materia1">Materia 1:</label>
            <input type="text" id="materia1" name="materia1" required>
        </div>

        <div class="input-group">
            <label for="materia2">Materia 2:</label>
            <input type="text" id="materia2" name="materia2" required>
        </div>

        <div class="input-group">
            <label for="materia3">Materia 3:</label>
            <input type="text" id="materia3" name="materia3" required>
        </div>

        <div class="input-group">
            <label for="materia4">Materia 4:</label>
            <input type="text" id="materia4" name="materia4" required>
        </div>

        <div class="input-group">
            <label for="materia5">Materia 5:</label>
            <input type="text" id="materia5" name="materia5" required>
        </div>

        <div class="input-group">
            <label for="materia6">Materia 6:</label>
            <input type="text" id="materia6" name="materia6" required>
        </div>

        <div class="input-group">
            <label for="materia7">Materia 7:</label>
            <input type="text" id="materia7" name="materia7" required>
        </div>

        <div class="input-group">
            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha" required>
        </div>

        <div class="input-group">
            <input type="submit" value="Registrar">
        </div>
    </form>
</div>

</body>
</html>
