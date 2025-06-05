<?php 
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
  header("Location: ../views/login.php");
  exit();
}

require_once '../config/database.php';

// Obtener lista de programas (ejemplo, ajusta según tu base de datos)
$programas = [
  ['id' => 1, 'nombre' => 'Ingeniería de Sistemas', 'codigo' => 'IS', 'duracion' => '10 Semestres', 'estado' => 'Activo'],
  ['id' => 2, 'nombre' => 'Administración de Empresas', 'codigo' => 'AE', 'duracion' => '8 Semestres', 'estado' => 'Activo'],
  ['id' => 3, 'nombre' => 'Psicología', 'codigo' => 'PSI', 'duracion' => '9 Semestres', 'estado' => 'Activo'],
  // Agrega más programas según sea necesario
]; 
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
          <button class="btn btn-success">
            <i class="bi bi-plus-lg"></i> Nuevo Programa
          </button>
        </div>
        
        <div class="row">
          <?php foreach($programas as $programa): ?>
            <div class="col-12 mb-3">
              <div class="programa-card">
                <div class="programa-header">
                  <div class="programa-info">
                    <div class="programa-codigo"><?= htmlspecialchars($programa['codigo']) ?></div>
                    <h3 class="programa-title"><?= htmlspecialchars($programa['nombre']) ?></h3>
                    <div class="programa-duracion">
                      <i class="bi bi-calendar-week"></i> <?= $programa['duracion'] ?>
                    </div>
                    <span class="programa-estado estado-<?= strtolower($programa['estado']) === 'activo' ? 'activo' : 'inactivo' ?>">
                      <i class="bi <?= strtolower($programa['estado']) === 'activo' ? 'bi-check-circle' : 'bi-x-circle' ?>"></i>
                      <?= $programa['estado'] ?>
                    </span>
                  </div>
                  <div class="programa-actions">
                    <button class="btn btn-outline-primary btn-icon" title="Editar">
                      <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-outline-danger btn-icon" title="Eliminar">
                      <i class="bi bi-trash"></i>
                    </button>
                    <button class="btn btn-primary btn-icon" title="Ver detalles">
                      <i class="bi bi-arrow-right"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
