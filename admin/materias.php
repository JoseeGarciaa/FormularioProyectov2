<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
  header("Location: ../views/login.php");
  exit();
}

require_once '../config/database.php';

// Obtener programas para el filtro
$programas = $conn->query("SELECT * FROM Programas ORDER BY nombre");

// Obtener el programa seleccionado (si hay alguno)
$programa_id = isset($_GET['programa_id']) ? intval($_GET['programa_id']) : 0;

// Construir la consulta de materias
$sql = "SELECT m.*, p.nombre as programa_nombre 
        FROM Materias m 
        JOIN Programas p ON m.programa_id = p.id";

if ($programa_id > 0) {
    $sql .= " WHERE m.programa_id = $programa_id";
}

$sql .= " ORDER BY p.nombre, m.nombre";

$materias_query = $conn->query($sql);

// Procesar formulario de agregar materia
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_materia'])) {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $programa_id = intval($_POST['programa_id']);
    
    $sql = "INSERT INTO Materias (nombre, programa_id) VALUES ('$nombre', $programa_id)";
    if ($conn->query($sql)) {
        header("Location: materias.php?success=1&programa_id=$programa_id");
        exit();
    } else {
        $error = "Error al agregar la materia: " . $conn->error;
    }
}

// Procesar eliminación de materia
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $sql = "DELETE FROM Materias WHERE id = $id";
    if ($conn->query($sql)) {
        header("Location: materias.php?deleted=1");
        exit();
    } else {
        $error = "No se pudo eliminar la materia porque está siendo utilizada en inscripciones.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión de Materias - Panel de Administrador</title>
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
      max-width: 95%;
      margin: 2rem auto;
      padding: 1.5rem;
    }
    
    .card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      margin-bottom: 1.5rem;
    }
    
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
      flex-wrap: wrap;
      gap: 1rem;
    }
    
    .header h1 {
      color: white;
      font-weight: 700;
      margin: 0;
    }
    
    .btn-back {
      background-color: #6c757d;
      color: white;
      border: none;
      padding: 0.5rem 1.5rem;
      border-radius: 50px;
      font-weight: 500;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      text-decoration: none;
    }
    
    .btn-back:hover {
      background-color: #5a6268;
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }
    
    .btn-primary {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.5rem 1.5rem;
      border-radius: 50px;
      font-weight: 500;
    }
    
    .materia-card {
      padding: 1.5rem;
      margin-bottom: 1rem;
      border-radius: 10px;
      background: white;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      transition: all 0.3s ease;
    }
    
    .materia-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .materia-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
    }
    
    .materia-title {
      font-size: 1.25rem;
      font-weight: 600;
      margin: 0;
    }
    
    .materia-stats {
      display: flex;
      gap: 1.5rem;
    }
    
    .stat-item {
      text-align: center;
    }
    
    .stat-value {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--primary-color);
    }
    
    .stat-label {
      font-size: 0.8rem;
      color: #6c757d;
    }
    
    @media (max-width: 768px) {
      .dashboard-container {
        padding: 1rem;
      }
      
      .materia-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
      }
      
      .materia-stats {
        width: 100%;
        justify-content: space-between;
      }
    }
  </style>
</head>
<body>
  <div class="dashboard-container">
    <div class="header">
      <h1><i class="bi bi-book me-2"></i> Gestión de Materias</h1>
      <div class="d-flex gap-2">
        <a href="dashboard.php" class="btn btn-back">
          <i class="bi bi-arrow-left"></i> Volver al Dashboard
        </a>
      </div>
    </div>
    
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h5 class="card-title mb-0">
            <?= $programa_id > 0 ? 'Materias del Programa: ' . $programas->fetch_assoc()['nombre'] : 'Todas las Materias' ?>
          </h5>
          <div class="d-flex gap-2">
            <div class="dropdown">
              <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownProgramas" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-funnel"></i> Filtrar por Programa
              </button>
              <ul class="dropdown-menu" aria-labelledby="dropdownProgramas">
                <li><a class="dropdown-item" href="materias.php">Todos los Programas</a></li>
                <?php 
                // Reiniciar el puntero del resultado para volver a usarlo
                $programas->data_seek(0);
                while ($prog = $programas->fetch_assoc()): 
                ?>
                  <li><a class="dropdown-item <?= $programa_id == $prog['id'] ? 'active' : '' ?>" 
                         href="materias.php?programa_id=<?= $prog['id'] ?>">
                    <?= htmlspecialchars($prog['nombre']) ?>
                  </a></li>
                <?php endwhile; ?>
              </ul>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevaMateriaModal">
              <i class="bi bi-plus-lg"></i> Nueva Materia
            </button>
          </div>
        </div>
        
        <?php if (isset($_GET['success'])): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            Materia <?= isset($_GET['edit']) ? 'actualizada' : 'agregada' ?> exitosamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['deleted'])): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            Materia eliminada exitosamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>
        
        <?php if ($materias_query->num_rows > 0): ?>
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nombre de la Materia</th>
                  <th>Programa</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php while($materia = $materias_query->fetch_assoc()): ?>
                  <tr>
                    <td><?= $materia['id'] ?></td>
                    <td><?= htmlspecialchars($materia['nombre']) ?></td>
                    <td><?= htmlspecialchars($materia['programa_nombre']) ?></td>
                    <td class="d-flex gap-1">
                      <a href="#" class="btn btn-sm btn-outline-secondary" title="Editar">
                        <i class="bi bi-pencil"></i>
                      </a>
                      <a href="?eliminar=<?= $materia['id'] ?>" class="btn btn-sm btn-outline-danger" 
                         title="Eliminar" 
                         onclick="return confirm('¿Está seguro de eliminar esta materia?')">
                        <i class="bi bi-trash"></i>
                      </a>
                    </td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <div class="text-center py-5">
            <div class="mb-3">
              <i class="bi bi-book text-muted" style="font-size: 3rem;"></i>
            </div>
            <h5 class="text-muted">
              <?= $programa_id > 0 ? 'No hay materias registradas para este programa.' : 'No hay materias registradas.' ?>
            </h5>
            <?php if ($programa_id === 0): ?>
              <p class="text-muted">Selecciona un programa para ver sus materias o crea una nueva materia.</p>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <!-- Modal Nueva Materia -->
        <div class="modal fade" id="nuevaMateriaModal" tabindex="-1" aria-labelledby="nuevaMateriaModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="nuevaMateriaModalLabel">Nueva Materia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form method="POST" action="">
                <div class="modal-body">
                  <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre de la Materia</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                  </div>
                  <div class="mb-3">
                    <label for="programa_id" class="form-label">Programa</label>
                    <select class="form-select" id="programa_id" name="programa_id" required>
                      <option value="">Seleccione un programa</option>
                      <?php 
                      // Reiniciar el puntero del resultado para volver a usarlo
                      $programas->data_seek(0);
                      while ($prog = $programas->fetch_assoc()): 
                      ?>
                        <option value="<?= $prog['id'] ?>" <?= $programa_id == $prog['id'] ? 'selected' : '' ?>>
                          <?= htmlspecialchars($prog['nombre']) ?>
                        </option>
                      <?php endwhile; ?>
                    </select>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                  <button type="submit" name="agregar_materia" class="btn btn-primary">Guardar</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
