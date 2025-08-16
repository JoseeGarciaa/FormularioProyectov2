<?php
// Verificar si el usuario es administrador
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Acceso no autorizado']);
    exit();
}

require_once '../config/database.php';

try {
    // Obtener total de inscripciones
    $total_inscripciones_query = "SELECT COUNT(*) as total FROM Inscripciones";
    $total_inscripciones_result = $conn->query($total_inscripciones_query);
    $total_inscripciones = $total_inscripciones_result->fetch_assoc()['total'];
    
    // Contar cuántas veces se inscribió cada materia
    $materias_count = [];
    
    // Para cada columna de materia
    for ($i = 1; $i <= 7; $i++) {
        $sql = "SELECT Materia{$i} as materia, COUNT(*) as cantidad
                FROM Inscripciones 
                WHERE Materia{$i} IS NOT NULL 
                  AND Materia{$i} != '' 
                  AND Materia{$i} != 'NULL'
                  AND TRIM(Materia{$i}) != ''
                GROUP BY Materia{$i}";
        
        $result = $conn->query($sql);
        
        while ($row = $result->fetch_assoc()) {
            $materia = trim($row['materia']);
            $cantidad = (int)$row['cantidad'];
            
            if (isset($materias_count[$materia])) {
                // Sumar todas las inscripciones de esta materia
                $materias_count[$materia] += $cantidad;
            } else {
                $materias_count[$materia] = $cantidad;
            }
        }
    }
    

    // Obtener los nombres de las materias
    $materias_nombres = [];
    $materias_query = $conn->query("SELECT id, nombre FROM Materias");
    while ($m = $materias_query->fetch_assoc()) {
        $materias_nombres[$m['id']] = $m['nombre'];
    }

    // Ordenar por cantidad descendente
    arsort($materias_count);

    // Tomar solo las top 10 para mejor visualización
    $top_materias = array_slice($materias_count, 0, 10, true);

    // Preparar datos para Chart.js
    $labels = array_map(function($id) use ($materias_nombres) {
        return isset($materias_nombres[$id]) ? $materias_nombres[$id] : $id;
    }, array_keys($top_materias));
    $data = array_values($top_materias);

    // Acortar nombres muy largos para mejor visualización
    $labels_cortos = array_map(function($label) {
        return strlen($label) > 25 ? substr($label, 0, 22) . '...' : $label;
    }, $labels);

    $response = [
        'success' => true,
        'data' => [
            'labels' => $labels_cortos,
            'datasets' => [
                [
                    'label' => 'Inscripciones por Materia',
                    'data' => $data,
                    'backgroundColor' => [
                        '#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FECA57',
                        '#FF9FF3', '#54A0FF', '#5F27CD', '#00D2D3', '#FF9F43'
                    ],
                    'borderColor' => [
                        '#FF5252', '#26A69A', '#2196F3', '#66BB6A', '#FFC107',
                        '#E91E63', '#2196F3', '#673AB7', '#00BCD4', '#FF9800'
                    ],
                    'borderWidth' => 2
                ]
            ]
        ],
        'stats' => [
            'total_inscripciones' => $total_inscripciones,
            'total_materias' => count($materias_count),
            'promedio' => count($materias_count) > 0 ? round(array_sum($materias_count) / count($materias_count), 1) : 0
        ]
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
    
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false, 
        'message' => 'Error al obtener datos: ' . $e->getMessage()
    ]);
}
?>
