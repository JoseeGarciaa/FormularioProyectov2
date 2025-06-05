<?php 
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
  header("Location: ../views/login.php");
  exit();
}

require_once '../config/database.php';

// Obtener estadísticas
$total_estudiantes = $conn->query("SELECT COUNT(*) as total FROM Inscripciones")->fetch_assoc()['total'];
$total_masculino = $conn->query("SELECT COUNT(*) as total FROM Inscripciones WHERE genero = 'Masculino'")->fetch_assoc()['total'];
$total_femenino = $conn->query("SELECT COUNT(*) as total FROM Inscripciones WHERE genero = 'Femenino'")->fetch_assoc()['total'];

// Traer inscripciones con nombre del usuario y materias
$sql = "SELECT u.nombre, u.apellido, i.edad, i.genero, i.numero_celular, i.semestre, i.jornada,
               i.Materia1, i.Materia2, i.Materia3, i.Materia4, i.Materia5, i.Materia6, i.Materia7
        FROM Inscripciones i
        JOIN Usuarios u ON i.usuario_id = u.id
        ORDER BY i.id DESC";
$result = $conn->query($sql); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Administrador - USC</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <style>
    :root {
      --primary-color: #001f87;
      --secondary-color: #630000;
      --accent-color: #4a90e2;
    }
    
    body {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      min-height: 100vh;
      color: #333;
    }
    
    .dashboard-container {
      width: 100%;
      max-width: 1400px;
      margin: 1rem auto;
      padding: 0.75rem;
    }
    
    @media (min-width: 768px) {
      .dashboard-container {
        padding: 1.5rem;
      }
    }
    
    .card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      margin-bottom: 1.5rem;
    }
    
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }
    
    .stat-card {
      text-align: center;
      padding: 1.5rem 0.75rem;
      color: white;
      border-radius: 12px;
      background: linear-gradient(45deg,rgb(255, 136, 0),rgb(255, 170, 72));
      height: 100%;
      min-height: 160px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      transition: all 0.3s ease;
    }
    
    @media (min-width: 768px) {
      .stat-card {
        padding: 1.5rem;
        margin-bottom: 0;
      }
    }
    
    .stat-card i {
      font-size: 2rem;
      margin-bottom: 0.75rem;
    }
    
    @media (min-width: 768px) {
      .stat-card i {
        font-size: 2.5rem;
        margin-bottom: 1rem;
      }
    }
    
    .stat-card h3 {
      font-size: 1.5rem;
      font-weight: 700;
      margin: 0.5rem 0;
    }
    
    @media (min-width: 768px) {
      .stat-card h3 {
        font-size: 2rem;
      }
    }
    
    .stat-card p {
      margin: 0;
      opacity: 0.9;
    }
    
    .gender-stats {
      display: flex;
      justify-content: space-around;
      margin-top: 1rem;
    }
    
    .gender-stat {
      text-align: center;
    }
    
    .gender-stat i {
      font-size: 1.5rem;
      display: block;
      margin-bottom: 0.5rem;
    }
    
    .table-responsive {
      background: white;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
      margin: 0 -0.75rem;
      width: calc(100% + 1.5rem);
    }
    
    @media (min-width: 768px) {
      .table-responsive {
        margin: 0;
        width: 100%;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      }
    }
    
    .table {
      margin-bottom: 0;
    }
    
    .table {
      margin-bottom: 0;
      min-width: 600px;
    }
    
    .table thead th {
      background-color: var(--primary-color);
      color: white;
      border: none;
      padding: 0.75rem;
      font-weight: 500;
      white-space: nowrap;
    }
    
    .table td {
      padding: 0.75rem;
      vertical-align: middle;
    }
    
    @media (min-width: 768px) {
      .table thead th {
        padding: 1rem;
      }
      
      .table td {
        padding: 1rem;
      }
    }
    
    .table tbody tr:hover {
      background-color: rgba(74, 144, 226, 0.05);
    }
    
    .materias-cell {
      max-width: 200px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    
    @media (min-width: 992px) {
      .materias-cell {
        max-width: 300px;
      }
    }
    
    .header {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      margin-bottom: 1.5rem;
      gap: 1rem;
    }
    
    @media (min-width: 768px) {
      .header {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
      }
    }
    
    .header h1 {
      color: white;
      font-weight: 700;
      margin: 0;
    }
    
    .btn-logout {
      background-color: #ff4d4d;
      color: white;
      border: none;
      padding: 0.5rem 1rem;
      border-radius: 50px;
      font-weight: 500;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.3rem;
      white-space: nowrap;
      width: 100%;
    }
    
    @media (min-width: 768px) {
      .btn-logout {
        width: auto;
        padding: 0.5rem 1.5rem;
      }
    }
    
    .btn-primary, .btn-success {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.3rem;
      white-space: nowrap;
      padding: 0.5rem 1rem;
      border-radius: 50px;
      font-weight: 500;
      transition: all 0.3s ease;
      text-align: center;
      width: 100%;
      margin-bottom: 0.5rem;
    }
    
    @media (min-width: 768px) {
      .btn-primary, .btn-success {
        width: auto;
        padding: 0.5rem 1.5rem;
        margin-bottom: 0;
      }
    }
    
    .btn-logout:hover {
      background-color: #ff3333;
      transform: translateY(-2px);
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }
    
    /* Ajustes para móviles */
    @media (max-width: 767.98px) {
      .header h1 {
        font-size: 1.5rem;
      }
      
      .btn {
        font-size: 0.9rem;
        padding: 0.4rem 0.8rem;
      }
      
      .stat-card {
        margin-bottom: 1rem;
      }
      
      .table-responsive {
        border-radius: 0;
        margin: 0 -0.75rem;
        width: calc(100% + 1.5rem);
      }
    }
    
    /* Ajustes para tablets pequeñas */
    @media (min-width: 576px) and (max-width: 767.98px) {
      .stat-card {
        padding: 1rem;
      }
    }
    
    /* Ajustes para tablets */
    @media (min-width: 768px) and (max-width: 991.98px) {
      .dashboard-container {
        padding: 1.25rem;
      }
    }
  </style>
</head>
<body>
  <div class="dashboard-container">
    <div class="header">
      <h1><i class="bi bi-speedometer2 me-2"></i> Panel de Administración</h1>
      <div class="d-flex gap-2">
        <a href="materias.php" class="btn btn-primary">
          <i class="bi bi-book me-1"></i> Materias
        </a>
        <a href="programas.php" class="btn btn-success">
          <i class="bi bi-collection me-1"></i> Programas
        </a>
        <a href="../auth/logout.php" class="btn btn-logout">
          <i class="bi bi-box-arrow-right me-1"></i> Cerrar sesión
        </a>
      </div>
    </div>
    
    <div class="row g-3 mb-4">
      <div class="col-12 col-sm-6 col-md-3">
        <div class="stat-card">
          <i class="bi bi-people"></i>
          <h3><?= $total_estudiantes ?></h3>
          <p>Total Estudiantes</p>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <div class="stat-card" style="background: linear-gradient(45deg,rgb(107, 122, 255),rgb(142, 153, 255));">
          <i class="bi bi-gender-male"></i>
          <h3><?= $total_masculino ?></h3>
          <p>Hombres</p>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <div class="stat-card" style="background: linear-gradient(45deg,rgb(231, 92, 219),rgb(254, 155, 241));">
          <i class="bi bi-gender-female"></i>
          <h3><?= $total_femenino ?></h3>
          <p>Mujeres</p>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <div class="stat-card" style="background: linear-gradient(45deg,rgb(176, 182, 181),rgb(236, 236, 236));">
          <i class="bi bi-people-fill"></i>
          <h3><?= $total_estudiantes - $total_masculino - $total_femenino ?></h3>
          <p>Otro</p>
        </div>
      </div>
    </div>
    
    <div class="card">
      <div class="card-body">
        <h5 class="card-title mb-4">
          <i class="bi bi-list-check me-2"></i> Lista de Inscripciones
        </h5>
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Edad</th>
                <th>Género</th>
                <th>Celular</th>
                <th>Semestre</th>
                <th>Jornada</th>
                <th>Materias</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              $count = 1;
              while ($row = $result->fetch_assoc()): 
                $materias = array_filter([
                  $row['Materia1'], $row['Materia2'], $row['Materia3'],
                  $row['Materia4'], $row['Materia5'], $row['Materia6'], $row['Materia7']
                ]);
              ?>
                <tr>
                  <td><?= $count++ ?></td>
                  <td><strong><?= htmlspecialchars($row['nombre'] . ' ' . $row['apellido']) ?></strong></td>
                  <td><?= $row['edad'] ?></td>
                  <td>
                    <span class="badge rounded-pill" style="background-color: <?= $row['genero'] === 'Masculino' ? '#4a90e2' : '#6c5ce7' ?>">
                      <?= $row['genero'] ?>
                    </span>
                  </td>
                  <td><?= $row['numero_celular'] ?></td>
                  <td>Semestre <?= $row['semestre'] ?></td>
                  <td>
                    <span class="badge bg-<?= $row['jornada'] === 'Diurna' ? 'success' : 'info' ?>">
                      <?= $row['jornada'] ?>
                    </span>
                  </td>
                  <td class="materias-cell" title="<?= htmlspecialchars(implode(', ', $materias)) ?>">
                    <?= implode(', ', $materias) ?>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Agregar tooltips de Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl, {
        trigger: 'hover',
        placement: 'top'
      });
    });
  </script>
</body>
</html>
