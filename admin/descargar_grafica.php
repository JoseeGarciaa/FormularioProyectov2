<?php
// Verificar si el usuario es administrador
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../views/login.php");
    exit();
}

// Ruta del archivo de la gráfica
$archivo_grafica = __DIR__ . '/grafica_materias.png';

// Verificar si el archivo existe
if (!file_exists($archivo_grafica)) {
    // Si no existe, intentar generar la gráfica
    $python_script = __DIR__ . '/grafica_materias.py';
    $command = 'python "' . $python_script . '"';
    exec($command, $output, $return_var);
    
    // Verificar si se generó correctamente
    if ($return_var !== 0 || !file_exists($archivo_grafica)) {
        http_response_code(404);
        echo "Error: No se pudo generar o encontrar la gráfica de materias.";
        exit();
    }
}

// Configurar headers para descarga
$nombre_archivo = 'grafica_materias_' . date('Y-m-d_H-i-s') . '.png';

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $nombre_archivo . '"');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . filesize($archivo_grafica));
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Expires: 0');

// Limpiar cualquier salida previa
ob_clean();
flush();

// Enviar el archivo
readfile($archivo_grafica);
exit();
?>
