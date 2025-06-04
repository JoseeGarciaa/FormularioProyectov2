<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
  header("Location: ../views/login.html");
  exit();
}

require_once '../config/database.php';

// Traer inscripciones con nombre del usuario y materias
$sql = "SELECT u.nombre, u.apellido, i.edad, i.genero, i.numero_celular, i.semestre, i.jornada,
               i.Materia1, i.Materia2, i.Materia3, i.Materia4, i.Materia5, i.Materia6, i.Materia7
        FROM Inscripciones i
        JOIN Usuarios u ON i.usuario_id = u.id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Administrador - USC</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(90deg, #001f87, #630000);
      color: white;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 95%;
      margin: 30px auto;
      background: rgba(0, 0, 0, 0.6);
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 0 15px rgba(0,0,0,1.2);
    }
    h2 {
      text-align: center;
      margin-bottom: 25px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background-color: white;
      color: black;
      border-radius: 10px;
      overflow: hidden;
    }
    th, td {
      padding: 10px;
      border: 1px solid #ccc;
      text-align: center;
    }
    th {
      background-color: #001f87;
      color: white;
    }
    tr:nth-child(even) {
      background-color: #f2f2f2;
    }
    .logout {
      text-align: center;
      margin-top: 20px;
    }
    .logout a {
      color: #66aaff;
      text-decoration: none;
      font-weight: bold;
    }
    .logout a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Panel de Administrador - Inscripciones</h2>
    <table>
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Apellido</th>
          <th>Edad</th>
          <th>Género</th>
          <th>Celular</th>
          <th>Semestre</th>
          <th>Jornada</th>
          <th>Materias</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['nombre']) ?></td>
            <td><?= htmlspecialchars($row['apellido']) ?></td>
            <td><?= $row['edad'] ?></td>
            <td><?= $row['genero'] ?></td>
            <td><?= $row['numero_celular'] ?></td>
            <td><?= $row['semestre'] ?></td>
            <td><?= $row['jornada'] ?></td>
            <td>
              <?= implode(", ", array_filter([
                $row['Materia1'], $row['Materia2'], $row['Materia3'],
                $row['Materia4'], $row['Materia5'], $row['Materia6'], $row['Materia7']
              ])) ?>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    <div class="logout">
      <p><a href="../auth/logout.php">Cerrar sesión</a></p>
    </div>
  </div>
</body>
</html>
