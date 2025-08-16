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

// Configuración de paginación
$registros_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Obtener el total de registros
$total_registros = $conn->query("SELECT COUNT(*) as total FROM Inscripciones")->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);


// Obtener todos los nombres de materias en un array asociativo
$materias_nombres = [];
$materias_query = $conn->query("SELECT id, nombre FROM Materias");
while ($m = $materias_query->fetch_assoc()) {
  $materias_nombres[$m['id']] = $m['nombre'];
}

// Traer inscripciones con nombre del usuario y materias con paginación
$sql = "SELECT u.nombre, u.apellido, i.edad, i.genero, i.numero_celular, i.semestre, i.jornada,
         i.Materia1, i.Materia2, i.Materia3, i.Materia4, i.Materia5, i.Materia6, i.Materia7
    FROM Inscripciones i
    JOIN Usuarios u ON i.usuario_id = u.id
    ORDER BY i.id DESC
    LIMIT $offset, $registros_por_pagina";
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
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    
    /* Estilos para la sección de gráfica */
    #graficaContainer {
      min-height: 200px;
      position: relative;
    }
    
    #graficaContainer img {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    #graficaContainer img:hover {
      transform: scale(1.02);
      box-shadow: 0 8px 16px rgba(0,0,0,0.15) !important;
    }
    
    .spinner-border {
      width: 3rem;
      height: 3rem;
    }
    
    #loadingGrafica p {
      color: #6c757d;
      font-weight: 500;
    }
    
    /* Responsive para la gráfica */
    @media (max-width: 768px) {
      #graficaContainer img {
        border-radius: 4px !important;
      }
    }
    
    /* Animación para los botones */
    .btn-success:hover, .btn-primary:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.15);
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
    
    <!-- Sección de Gráfica Interactiva de Materias -->
    <div class="card mb-4">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h5 class="card-title mb-0">
            <i class="bi bi-bar-chart me-2"></i> Materias Más Inscritas
          </h5>
          <button id="actualizarGraficaInteractiva" class="btn btn-primary btn-sm">
            <i class="bi bi-arrow-clockwise me-1"></i> Actualizar
          </button>
        </div>
        
        <!-- Estadísticas -->
        <div class="row mb-4" id="estadisticasGrafica">
          <div class="col-md-4">
            <div class="text-center p-3 bg-light rounded">
              <h6 class="text-muted mb-1">Total Inscripciones</h6>
              <h4 class="mb-0 text-primary" id="totalInscripciones">-</h4>
            </div>
          </div>
          <div class="col-md-4">
            <div class="text-center p-3 bg-light rounded">
              <h6 class="text-muted mb-1">Materias Únicas</h6>
              <h4 class="mb-0 text-success" id="totalMaterias">-</h4>
            </div>
          </div>
          <div class="col-md-4">
            <div class="text-center p-3 bg-light rounded">
              <h6 class="text-muted mb-1">Promedio por Materia</h6>
              <h4 class="mb-0 text-info" id="promedioMaterias">-</h4>
            </div>
          </div>
        </div>
        
        <div id="graficaInteractivaContainer">
          <div id="loadingGraficaInteractiva" class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Cargando gráfica...</span>
            </div>
            <p class="mt-2">Cargando datos de materias...</p>
          </div>
          
          <div id="graficaInteractivaContent" class="d-none" style="height: 400px;">
            <canvas id="graficaMaterias"></canvas>
          </div>
          
          <div id="errorGraficaInteractiva" class="alert alert-danger d-none">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <span id="errorMessageInteractiva">Error al cargar la gráfica</span>
          </div>
        </div>
      </div>
    </div>
    
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h5 class="card-title mb-0">
            <i class="bi bi-list-check me-2"></i> Lista de Inscripciones
          </h5>
          <div class="text-muted">
            Mostrando <?= ($offset + 1) ?>-<?= min($offset + $registros_por_pagina, $total_registros) ?> de <?= $total_registros ?> registros
          </div>
        </div>
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
                $materias_ids = array_filter([
                  $row['Materia1'], $row['Materia2'], $row['Materia3'],
                  $row['Materia4'], $row['Materia5'], $row['Materia6'], $row['Materia7']
                ]);
                $materias = [];
                foreach ($materias_ids as $id) {
                  if (isset($materias_nombres[$id])) {
                    $materias[] = $materias_nombres[$id];
                  }
                }
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
        
        <!-- Paginación -->
        <?php if ($total_paginas > 1): ?>
        <nav aria-label="Navegación de páginas" class="mt-4">
          <ul class="pagination justify-content-center">
            <!-- Botón Anterior -->
            <li class="page-item <?= $pagina_actual <= 1 ? 'disabled' : '' ?>">
              <a class="page-link" href="?pagina=<?= $pagina_actual - 1 ?>" aria-label="Anterior">
                <span aria-hidden="true">&laquo;</span>
              </a>
            </li>
            
            <!-- Números de página -->
            <?php
            $pagina_inicio = max(1, $pagina_actual - 2);
            $pagina_fin = min($total_paginas, $pagina_actual + 2);
            
            // Mostrar primera página si no está en el rango
            if ($pagina_inicio > 1) {
                echo '<li class="page-item"><a class="page-link" href="?pagina=1">1</a></li>';
                if ($pagina_inicio > 2) {
                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
            }
            
            // Mostrar páginas en el rango actual
            for ($i = $pagina_inicio; $i <= $pagina_fin; $i++):
            ?>
                <li class="page-item <?= $i == $pagina_actual ? 'active' : '' ?>">
                    <a class="page-link" href="?pagina=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            
            <!-- Mostrar última página si no está en el rango -->
            <?php if ($pagina_fin < $total_paginas): ?>
                <?php if ($pagina_fin < $total_paginas - 1): ?>
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                <?php endif; ?>
                <li class="page-item"><a class="page-link" href="?pagina=<?= $total_paginas ?>"><?= $total_paginas ?></a></li>
            <?php endif; ?>
            
            <!-- Botón Siguiente -->
            <li class="page-item <?= $pagina_actual >= $total_paginas ? 'disabled' : '' ?>">
              <a class="page-link" href="?pagina=<?= $pagina_actual + 1 ?>" aria-label="Siguiente">
                <span aria-hidden="true">&raquo;</span>
              </a>
            </li>
          </ul>
        </nav>
        <?php endif; ?>
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
    
    // Variables para la gráfica interactiva
    let graficaChart = null;
    
    // Función para cargar datos de la gráfica
    function cargarGraficaInteractiva() {
      const loadingDiv = document.getElementById('loadingGraficaInteractiva');
      const contentDiv = document.getElementById('graficaInteractivaContent');
      const errorDiv = document.getElementById('errorGraficaInteractiva');
      const button = document.getElementById('actualizarGraficaInteractiva');
      
      // Mostrar loading
      loadingDiv.classList.remove('d-none');
      contentDiv.classList.add('d-none');
      errorDiv.classList.add('d-none');
      button.disabled = true;
      
      // Realizar petición AJAX
      fetch('obtener_datos_grafica.php')
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Actualizar estadísticas
            document.getElementById('totalInscripciones').textContent = data.stats.total_inscripciones;
            document.getElementById('totalMaterias').textContent = data.stats.total_materias;
            document.getElementById('promedioMaterias').textContent = data.stats.promedio;
            
            // Destruir gráfica anterior si existe
            if (graficaChart) {
              graficaChart.destroy();
            }
            
            // Crear nueva gráfica
            const ctx = document.getElementById('graficaMaterias').getContext('2d');
            graficaChart = new Chart(ctx, {
              type: 'bar',
              data: data.data,
              options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                  title: {
                    display: true,
                    text: 'Top 10 Materias Más Inscritas',
                    font: {
                      size: 16,
                      weight: 'bold'
                    }
                  },
                  legend: {
                    display: false
                  },
                  tooltip: {
                    callbacks: {
                      label: function(context) {
                        return context.parsed.y + ' inscripciones';
                      }
                    }
                  }
                },
                scales: {
                  y: {
                    beginAtZero: true,
                    title: {
                      display: true,
                      text: 'Número de Inscripciones'
                    },
                    ticks: {
                      stepSize: 1
                    }
                  },
                  x: {
                    title: {
                      display: true,
                      text: 'Materias'
                    },
                    ticks: {
                      maxRotation: 45,
                      minRotation: 45
                    }
                  }
                },
                animation: {
                  duration: 1000,
                  easing: 'easeInOutQuart'
                }
              }
            });
            
            // Mostrar contenido
            contentDiv.classList.remove('d-none');
            
          } else {
            // Mostrar error
            document.getElementById('errorMessageInteractiva').textContent = data.message;
            errorDiv.classList.remove('d-none');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          document.getElementById('errorMessageInteractiva').textContent = 'Error de conexión al cargar los datos';
          errorDiv.classList.remove('d-none');
        })
        .finally(() => {
          // Ocultar loading y restaurar botón
          loadingDiv.classList.add('d-none');
          button.disabled = false;
        });
    }
    
    // Manejar actualización de gráfica interactiva
    document.getElementById('actualizarGraficaInteractiva').addEventListener('click', cargarGraficaInteractiva);
    
    // Cargar gráfica al iniciar la página
    document.addEventListener('DOMContentLoaded', function() {
      cargarGraficaInteractiva();
    });
  </script>
</body>
</html>
