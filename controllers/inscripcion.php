<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../views/login.html");
    exit();
}

include '../config/conexion.php';

$usuario_id = $_SESSION['usuario_id'];
$edad = $_POST['edad'];
$genero = $_POST['genero'];
$numero_celular = $_POST['numero_celular'];
$programa_id = $_POST['programa_id'];
$semestre = $_POST['semestre'];
$jornada = $_POST['jornada'];
$fecha = $_POST['fecha'];

$materias = [];
for ($i = 1; $i <= 7; $i++) {
    $materias[] = isset($_POST["materia$i"]) ? $_POST["materia$i"] : null;
}

// Validar si ya existe inscripción para ese usuario y semestre
$query = "SELECT id FROM Inscripciones WHERE usuario_id = ? AND semestre = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $usuario_id, $semestre);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "<script>alert('Ya has realizado una inscripción para este semestre.'); window.location.href = '../views/bienvenida.php';</script>";
    exit();
}
$stmt->close();

// Insertar nueva inscripción
$query = "INSERT INTO Inscripciones 
(usuario_id, edad, genero, numero_celular, programa_id, semestre, jornada, fecha, 
 Materia1, Materia2, Materia3, Materia4, Materia5, Materia6, Materia7) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($query);
$stmt->bind_param(
    "iississsssssss",
    $usuario_id,
    $edad,
    $genero,
    $numero_celular,
    $programa_id,
    $semestre,
    $jornada,
    $fecha,
    $materias[0],
    $materias[1],
    $materias[2],
    $materias[3],
    $materias[4],
    $materias[5],
    $materias[6]
);

if ($stmt->execute()) {
    echo "<script>alert('Inscripción exitosa'); window.location.href = '../views/bienvenida.php';</script>";
} else {
    echo "Error al inscribir: " . $stmt->error;
}

$stmt->close();
$conn->close();
