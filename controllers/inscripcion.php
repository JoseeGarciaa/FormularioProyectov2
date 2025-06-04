<?php
session_start();
require_once '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_id = $_SESSION['usuario_id'];
    $edad = $_POST['edad'];
    $genero = $_POST['genero'];
    $numero_celular = $_POST['numero_celular'];
    $programa_id = $_POST['programa_id'];
    $semestre = $_POST['semestre'];
    $jornada = $_POST['jornada'];
    $fecha = date("Y-m-d");

    // Insertar inscripción
    $sql = "INSERT INTO Inscripciones (usuario_id, edad, genero, numero_celular, programa_id, semestre, jornada, fecha)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisssiss", $usuario_id, $edad, $genero, $numero_celular, $programa_id, $semestre, $jornada, $fecha);

    if ($stmt->execute()) {
        $inscripcion_id = $stmt->insert_id;
        // Guardar materias seleccionadas
        for ($i = 1; $i <= 7; $i++) {
            if (!empty($_POST["materia$i"])) {
                $materia_id = $_POST["materia$i"];
                $sql_materia = "INSERT INTO MateriasInscritas (inscripcion_id, materia_id) VALUES (?, ?)";
                $stmt_materia = $conn->prepare($sql_materia);
                $stmt_materia->bind_param("ii", $inscripcion_id, $materia_id);
                $stmt_materia->execute();
                $stmt_materia->close();
            }
        }
        echo "<p style='text-align:center; color:green;'>Inscripción realizada con éxito.</p>";
    } else {
        echo "<p style='text-align:center; color:red;'>Error al inscribir: " . $stmt->error . "</p>";
    }
    $stmt->close();
    $conn->close();
}
?>
