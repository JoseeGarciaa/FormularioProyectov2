<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Administrador - USC</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #1a1a2e, #16213e);
      color: white;
    }
    header {
      background: #0f3460;
      padding: 20px;
      text-align: center;
      font-size: 24px;
      font-weight: bold;
      box-shadow: 0 4px 10px rgba(0,0,0,0.5);
    }
    .container {
      max-width: 1200px;
      margin: 40px auto;
      padding: 20px;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.4);
    }
    h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #f7c59f;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 30px;
    }
    th, td {
      padding: 12px 15px;
      border-bottom: 1px solid #ddd;
      text-align: left;
      color: white;
    }
    th {
      background-color: #1f4068;
    }
    tr:hover {
      background-color: rgba(255, 255, 255, 0.1);
    }
    .btn {
      padding: 8px 16px;
      background-color: #e94560;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }
    .btn:hover {
      background-color: #ff2e54;
    }
  </style>
</head>
<body>
  <header>Panel de Control - Administrador</header>
  <div class="container">
    <h2>Lista de Estudiantes Inscritos</h2>
    <table>
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Correo</th>
          <th>Programa</th>
          <th>Semestre</th>
          <th>Materias</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <!-- Aquí se cargarán dinámicamente los estudiantes -->
        <?php
        require_once '../config/database.php';
        $sql = "SELECT u.nombre, u.correo, i.semestre, p.nombre AS programa,
                       i.materia1, i.materia2, i.materia3, i.materia4, i.materia5, i.materia6, i.materia7, i.id
                FROM Inscripciones i
                JOIN Usuarios u ON i.usuario_id = u.id
                JOIN Programas p ON i.programa_id = p.id";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()):
          $materias = array_filter([
            $row['materia1'], $row['materia2'], $row['materia3'],
            $row['materia4'], $row['materia5'], $row['materia6'], $row['materia7']
          ]);
        ?>
        <tr>
          <td><?= htmlspecialchars($row['nombre']) ?></td>
          <td><?= htmlspecialchars($row['correo']) ?></td>
          <td><?= htmlspecialchars($row['programa']) ?></td>
          <td><?= htmlspecialchars($row['semestre']) ?></td>
          <td><?= implode(', ', $materias) ?></td>
          <td><a class="btn" href="eliminar_inscripcion.php?id=<?= $row['id'] ?>">Eliminar</a></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
