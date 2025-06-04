<?php
if (!isset($_GET['programa_id'])) {
    echo json_encode(['error' => 'Falta programa_id']);
    exit();
}

include __DIR__ . '/../config/database.php';

$programa_id = intval($_GET['programa_id']);
$query = "SELECT nombre FROM Materias WHERE programa_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $programa_id);
$stmt->execute();
$result = $stmt->get_result();

$materias = [];
while ($row = $result->fetch_assoc()) {
    $materias[] = $row['nombre'];
}

echo json_encode(['materias' => $materias]);
