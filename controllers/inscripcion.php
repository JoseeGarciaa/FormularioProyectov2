<?php
session_start();
include __DIR__ . '/../config/database.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../views/login.html");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$edad = $_POST['edad'];
$genero = $_POST['genero'];
$numero_celular = $_POST['numero_celular'];
$programa_id = $_POST['programa_id'];
$semestre = $_POST['semestre'];
$jornada = $_POST['jornada'];
$fecha = date('Y-m-d'); // Se genera automáticamente

// Validar si ya existe una inscripción para ese usuario y semestre
$verificar_sql = "SELECT id FROM Inscripciones WHERE usuario_id = ? AND semestre = ?";
$stmt = $conn->prepare($verificar_sql);
$stmt->bind_param("ii", $usuario_id, $semestre);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "Ya existe una inscripción para este semestre.";
    exit();
}
$stmt->close();

// Insertar inscripción
$insert_sql = "INSERT INTO Inscripciones (usuario_id, edad, genero, numero_celular, programa_id, semestre, jornada, fecha)
               VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($insert_sql);
$stmt->bind_param("iisssiss", $usuario_id, $edad, $genero, $numero_celular, $programa_id, $semestre, $jornada, $fecha);
$stmt->execute();
$inscripcion_id = $stmt->insert_id;
$stmt->close();

// Insertar materias si están definidas
for ($i = 1; $i <= 7; $i++) {
    $campo = "materia$i";
    if (!empty($_POST[$campo])) {
        $materia_nombre = $_POST[$campo];

        // Buscar el ID real de la materia según nombre y programa
        $materia_stmt = $conn->prepare("SELECT id FROM Materias WHERE nombre = ? AND programa_id = ?");
        $materia_stmt->bind_param("si", $materia_nombre, $programa_id);
        $materia_stmt->execute();
        $materia_stmt->bind_result($materia_id);
        $materia_stmt->fetch();
        $materia_stmt->close();

        if (!empty($materia_id)) {
            $insert_materia = $conn->prepare("INSERT INTO MateriasInscritas (inscripcion_id, materia_id) VALUES (?, ?)");
            $insert_materia->bind_param("ii", $inscripcion_id, $materia_id);
            $insert_materia->execute();
            $insert_materia->close();
        }
    }
}

echo "Inscripción realizada correctamente.";
?>
