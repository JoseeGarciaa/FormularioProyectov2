<?php
session_start();

// Verifica que el usuario sea admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../views/login.html");
    exit();
}

require_once '../config/database.php';

// Obtener todos los usuarios (estudiantes)
$sql = "SELECT u.nombre, u.apellido, u.numero_identificacion, u.correo,
               i.semestre, i.jornada, i.fecha,
               i.materia1, i.materia2, i.materia3, i.materia4, i.materia5, i.materia6, i.materia7
        FROM Usuarios u
        LEFT JOIN Inscripciones i ON u.id = i.usuario_id
        WHERE u.rol = 'estudiante'";

$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Administrador - USC</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f0f0;
      padding: 20px;
    }
    h1 {
      text-align: center;
      color: #333;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      background-color: white;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 8px;
      text-align: left;
    }
    th {
      background-color: #001f87;
      color: white;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    .logout {
      text-align: right;
      margin-bottom: 10px;
    }
    .logout a {
      color: #c00;
      text-decoration: none;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="logout">
    <a href="../auth/logout.php">Cerrar sesión</a>
  </div>
  <h1>Panel de Administrador - Inscripciones</h1>

  <table>
    <thead>
      <tr>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Identificación</th>
        <th>Correo</th>
        <th>Semestre</th>
        <th>Jornada</th>
        <th>Fecha</th>
        <th>Materias</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $resultado->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['nombre']) ?></td>
          <td><?= htmlspecialchars($row['apellido']) ?></td>
          <td><?= htmlspecialchars($row['numero_identificacion']) ?></td>
          <td><?= htmlspecialchars($row['correo']) ?></td>
          <td><?= htmlspecialchars($row['semestre']) ?></td>
          <td><?= htmlspecialchars($row['jornada']) ?></td>
          <td><?= htmlspecialchars($row['fecha']) ?></td>
          <td>
            <?php
              $materias = [];
              for ($i = 1; $i <= 7; $i++) {
                if (!empty($row['materia'.$i])) {
                  $materias[] = $row['materia'.$i];
                }
              }
              echo implode(', ', $materias);
            ?>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</body>
</html>
