<?php 
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
  header("Location: ../views/login.php");
  exit();
}

require_once '../config/database.php';

// Obtener lista de materias (ejemplo, ajusta según tu base de datos)
$materias_query = $conn->query("SELECT DISTINCT Materia1 as materia FROM Inscripciones WHERE Materia1 IS NOT NULL UNION 
                                SELECT DISTINCT Materia2 FROM Inscripciones WHERE Materia2 IS NOT NULL UNION
                                SELECT DISTINCT Materia3 FROM Inscripciones WHERE Materia3 IS NOT NULL UNION
                                SELECT DISTINCT Materia4 FROM Inscripciones WHERE Materia4 IS NOT NULL UNION
                                SELECT DISTINCT Materia5 FROM Inscripciones WHERE Materia5 IS NOT NULL UNION
                                SELECT DISTINCT Materia6 FROM Inscripciones WHERE Materia6 IS NOT NULL UNION
                                SELECT DISTINCT Materia7 FROM Inscripciones WHERE Materia7 IS NOT NULL
                                ORDER BY materia");

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
          <h5 class="card-title mb-0">Listado de Materias</h5>
          <button class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Nueva Materia
          </button>
        </div>
        
        <div class="row">
          <?php while($materia = $materias_query->fetch_assoc()): ?>
            <div class="col-md-6 col-lg-4 mb-3">
              <div class="materia-card">
                <div class="materia-header">
                  <h3 class="materia-title"><?= htmlspecialchars($materia['materia']) ?></h3>
                  <div class="btn-group">
                    <button class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                  </div>
                </div>
                <div class="materia-stats">
                  <div class="stat-item">
                    <div class="stat-value">25</div>
                    <div class="stat-label">Estudiantes</div>
                  </div>
                  <div class="stat-item">
                    <div class="stat-value">3</div>
                    <div class="stat-label">Grupos</div>
                  </div>
                  <div class="stat-item">
                    <div class="stat-value">4.5</div>
                    <div class="stat-label">Promedio</div>
                  </div>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
