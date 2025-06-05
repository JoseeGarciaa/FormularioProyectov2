<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
  header("Location: ../views/login.php");
  exit();
}

require_once '../config/database.php';

// Obtener lista de programas desde la base de datos
$programas_query = $conn->query("SELECT * FROM Programas ORDER BY nombre");
$programas = [];
while ($row = $programas_query->fetch_assoc()) {
    $programas[] = $row;
}

// Procesar formulario de agregar/editar programa
if (isset($_POST['agregar_programa']) || isset($_POST['editar_programa'])) {
    $nombre = trim($_POST['nombre']);
    $es_edicion = isset($_POST['editar_programa']);
    $programa_id = $es_edicion ? $_POST['programa_id'] : null;
    
    if (empty($nombre)) {
        $error = "El nombre del programa es obligatorio";
    } else {
        if ($es_edicion) {
            $stmt = $conn->prepare("UPDATE Programas SET nombre = ? WHERE id = ?");
            $stmt->bind_param("si", $nombre, $programa_id);
            $mensaje_exito = "Programa actualizado exitosamente";
        } else {
            $stmt = $conn->prepare("INSERT INTO Programas (nombre) VALUES (?)");
            $stmt->bind_param("s", $nombre);
            $mensaje_exito = "Programa agregado exitosamente";
        }
        
        if ($stmt->execute()) {
            header("Location: programas.php?success=1&edit=" . ($es_edicion ? '1' : '0'));
            exit();
        } else {
            $error = "Error al " . ($es_edicion ? 'actualizar' : 'agregar') . " el programa: " . $conn->error;
        }
    }
}

// Obtener datos del programa para editar
$programa_editar = null;
if (isset($_GET['editar'])) {
    $id_editar = intval($_GET['editar']);
    $result = $conn->query("SELECT * FROM Programas WHERE id = $id_editar");
    if ($result->num_rows > 0) {
        $programa_editar = $result->fetch_assoc();
    } else {
        header("Location: programas.php?error=Programa no encontrado");
        exit();
    }
}

// Procesar eliminación de programa
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $sql = "DELETE FROM Programas WHERE id = $id";
    if ($conn->query($sql)) {
        header("Location: programas.php?deleted=1");
        exit();
    } else {
        $error = "No se pudo eliminar el programa porque tiene materias asociadas.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión de Programas - Panel de Administrador</title>
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
    
    .btn-back {
      background-color: #6c757d;
      color: white;
      border: none;
      padding: 0.5rem 1rem;
      border-radius: 50px;
      font-weight: 500;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      text-decoration: none;
      width: 100%;
      margin-bottom: 0.5rem;
    }
    
    @media (min-width: 768px) {
      .btn-back {
        width: auto;
        padding: 0.5rem 1.5rem;
        margin-bottom: 0;
      }
    }
    
    .btn-back:hover {
      background-color: #5a6268;
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }
    
    .btn-success {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.5rem 1.5rem;
      border-radius: 50px;
      font-weight: 500;
    }
    
    .programa-card {
      padding: 1.5rem;
      margin-bottom: 1rem;
      border-radius: 10px;
      background: white;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      transition: all 0.3s ease;
    }
    
    .programa-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .programa-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 1rem;
    }
    
    .programa-info {
      flex: 1;
    }
    
    .programa-title {
      font-size: 1.25rem;
      font-weight: 600;
      margin: 0 0 0.5rem 0;
      color: var(--primary-color);
    }
    
    .programa-codigo {
      display: inline-block;
      background: #e9ecef;
      padding: 0.25rem 0.75rem;
      border-radius: 50px;
      font-size: 0.8rem;
      font-weight: 500;
      margin-bottom: 0.5rem;
    }
    
    .programa-duracion {
      font-size: 0.9rem;
      color: #6c757d;
      margin-bottom: 0.5rem;
    }
    
    .programa-estado {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.25rem 0.75rem;
      border-radius: 50px;
      font-size: 0.8rem;
      font-weight: 500;
    }
    
    .estado-activo {
      background: #d1e7dd;
      color: #0f5132;
    }
    
    .estado-inactivo {
      background: #f8d7da;
      color: #842029;
    }
    
    .programa-actions {
      display: flex;
      gap: 0.5rem;
    }
    
    .btn-icon {
      width: 36px;
      height: 36px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      padding: 0;
    }
    
    @media (max-width: 768px) {
      .dashboard-container {
        padding: 1rem;
      }
      
      .programa-header {
        flex-direction: column;
        gap: 1rem;
      }
      
      .programa-actions {
        width: 100%;
        justify-content: flex-end;
      }
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
    
    .table {
      margin-bottom: 0;
      min-width: 600px;
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
      
      .table-responsive {
        border-radius: 0;
        margin: 0 -0.75rem;
        width: calc(100% + 1.5rem);
      }
      
      .modal-dialog {
        margin: 0.5rem;
      }
    }
    
    /* Ajustes para tablets pequeñas */
    @media (min-width: 576px) and (max-width: 767.98px) {
      .btn {
        padding: 0.5rem 1rem;
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
      <h1><i class="bi bi-collection me-2"></i> Gestión de Programas</h1>
      <div class="d-flex gap-2">
        <a href="dashboard.php" class="btn btn-back">
          <i class="bi bi-arrow-left"></i> Volver al Dashboard
        </a>
      </div>
    </div>
    
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h5 class="card-title mb-0">Listado de Programas Académicos</h5>
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#programaModal">
            <i class="bi bi-plus-lg"></i> Nuevo Programa
          </button>
        </div>
        
        <?php if (isset($_GET['success'])): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            Programa <?= isset($_GET['edit']) && $_GET['edit'] ? 'actualizado' : 'agregado' ?> exitosamente
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_GET['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['deleted'])): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            Programa eliminado exitosamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>
        
        <div class="row">
          <?php if (count($programas) > 0): ?>
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Nombre del Programa</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($programas as $programa): ?>
                    <tr>
                      <td><?= htmlspecialchars($programa['id']) ?></td>
                      <td><?= htmlspecialchars($programa['nombre']) ?></td>
                      <td>
                        <a href="materias.php?programa_id=<?= $programa['id'] ?>" class="btn btn-sm btn-primary" title="Ver materias">
                          <i class="bi bi-book"></i> Materias
                        </a>
                        <a href="programas.php?editar=<?= $programa['id'] ?>" class="btn btn-sm btn-outline-primary" title="Editar">
                          <i class="bi bi-pencil"></i>
                        </a>
                        <a href="?eliminar=<?= $programa['id'] ?>" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Está seguro de eliminar este programa?')">
                          <i class="bi bi-trash"></i>
                        </a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <div class="text-center py-4">
              <p class="text-muted">No hay programas registrados.</p>
            </div>
          <?php endif; ?>
          
          <!-- Modal Programa -->
          <div class="modal fade" id="programaModal" tabindex="-1" aria-labelledby="programaModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="programaModalLabel"><?= $programa_editar ? 'Editar' : 'Nuevo' ?> Programa</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="">
                  <?php if ($programa_editar): ?>
                    <input type="hidden" name="programa_id" value="<?= $programa_editar['id'] ?>">
                  <?php endif; ?>
                  <div class="modal-body">
                    <div class="mb-3">
                      <label for="nombre" class="form-label">Nombre del Programa</label>
                      <input type="text" class="form-control" id="nombre" name="nombre" 
                             value="<?= $programa_editar ? htmlspecialchars($programa_editar['nombre']) : '' ?>" required>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="<?= $programa_editar ? 'editar_programa' : 'agregar_programa' ?>" class="btn btn-primary">
                      <?= $programa_editar ? 'Actualizar' : 'Guardar' ?>
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Mostrar automáticamente el modal si estamos en modo edición
<?php if ($programa_editar): ?>
document.addEventListener('DOMContentLoaded', function() {
    var myModal = new bootstrap.Modal(document.getElementById('programaModal'));
    myModal.show();
});
<?php endif; ?>

// Limpiar el formulario al cerrar el modal si no estamos editando
document.getElementById('programaModal').addEventListener('hidden.bs.modal', function () {
    if (!<?= $programa_editar ? 'true' : 'false' ?>) {
        this.querySelector('form').reset();
    } else {
        // Si estábamos editando, redirigir para limpiar la URL
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});
</script>
</body>
</html>
