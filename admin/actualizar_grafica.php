<?php
// Verificar si el usuario es administrador
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Acceso no autorizado']);
    exit();
}

// Ruta al script de Python
$python_script = __DIR__ . '/grafica_materias.py';
$output = [];
$return_var = 0;

// Ejecutar el script de Python
$command = 'python "' . $python_script . '"';
exec($command, $output, $return_var);

// Verificar si se ejecutó correctamente
if ($return_var === 0) {
    // Verificar si se generó el archivo de la gráfica
    if (file_exists(__DIR__ . '/grafica_materias.png')) {
        echo json_encode(['success' => true, 'message' => 'Gráfica generada correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo generar la gráfica']);
    }
} else {
    // Mostrar el error devuelto por Python
    $error = implode("\n", $output);
    echo json_encode(['success' => false, 'message' => 'Error al ejecutar el script: ' . $error]);
}
?>
