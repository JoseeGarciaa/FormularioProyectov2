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
$fecha = date('Y-m-d'); // se genera automáticamente

// Validar si ya existe una inscripción para ese usuario y semestre
$verificar_sql = "SELECT id FROM Inscripciones WHERE usuario_id = ? AND semestre = ?";
$stmt = $conn->prepare($verificar_sql);
$stmt->bind_param("ii", $usuario_id, $semestre);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "❌ Ya existe una inscripción para este semestre.";
    exit();
}
$stmt->close();

// Recoger materias del formulario
$materias = [];
for ($i = 1; $i <= 7; $i++) {
    $campo = "materia$i";
    $materias[$i] = !empty($_POST[$campo]) ? $_POST[$campo] : null;
}

// Insertar todos los datos en Inscripciones
$insert_sql = "INSERT INTO Inscripciones (
    usuario_id, edad, genero, numero_celular, programa_id, semestre, jornada, fecha,
    materia1, materia2, materia3, materia4, materia5, materia6, materia7
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($insert_sql);
$stmt->bind_param(
    "iisssisssssssss",
    $usuario_id, $edad, $genero, $numero_celular, $programa_id,
    $semestre, $jornada, $fecha,
    $materias[1], $materias[2], $materias[3], $materias[4],
    $materias[5], $materias[6], $materias[7]
);

if ($stmt->execute()) {
    echo "✅ Inscripción realizada correctamente.";
} else {
    echo "❌ Error al inscribir: " . $stmt->error;
}
$stmt->close();
?>
