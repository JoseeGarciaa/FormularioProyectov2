<?php

session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../views/login.php");
    exit();
}

require_once '../config/database.php';

// Obtener estadísticas
try {
    $total_estudiantes = $conn->query("SELECT COUNT(*) as total FROM Inscripciones")->fetch_assoc()['total'];
    $total_masculino = $conn->query("SELECT COUNT(*) as total FROM Inscripciones WHERE genero = 'Masculino'")->fetch_assoc()['total'];
    $total_femenino = $conn->query("SELECT COUNT(*) as total FROM Inscripciones WHERE genero = 'Femenino'")->fetch_assoc()['total'];
    $porcentaje_hombres = $total_estudiantes > 0 ? round(($total_masculino / $total_estudiantes) * 100) : 0;
    $porcentaje_mujeres = $total_estudiantes > 0 ? round(($total_femenino / $total_estudiantes) * 100) : 0;
    
    // Obtener el mes actual para mostrar inscripciones del mes
    $mes_actual = date('Y-m-01');
    $inscripciones_mes = $conn->query("SELECT COUNT(*) as total FROM Inscripciones WHERE fecha >= '$mes_actual'")->fetch_assoc()['total'];
    
} catch (Exception $e) {
    $error = "Error al cargar estadísticas: " . $e->getMessage();
}

// Traer inscripciones con nombre del usuario y materias
$sql = "SELECT u.nombre, u.apellido, i.edad, i.genero, i.numero_celular, i.semestre, i.jornada,
               i.Materia1, i.Materia2, i.Materia3, i.Materia4, i.Materia5, i.Materia6, i.Materia7,
               DATE_FORMAT(i.fecha_registro, '%d/%m/%Y %H:%i') as fecha_formateada
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <style>


        :root {
            --primary-color: #001f87;
            --secondary-color: #1a237e;
            --accent-color: #4a90e2;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --light-color: #f8f9fa;
        }
        
        body {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            min-height: 100vh;
            color: #333;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .dashboard-container {
            max-width: 98%;
            margin: 1rem auto;
            padding: 1rem;
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--accent-color);
            height: 100%;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        .stat-card {
            padding: 1.5rem;
            border-radius: 12px;
            color: white;
            position: relative;
            overflow: hidden;
            height: 100%;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }
        
        .stat-card i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }
        
        .stat-card h3 {
            font-size: 2.2rem;
            font-weight: 700;
            margin: 0.5rem 0;
            position: relative;
            z-index: 1;
        }
        
        .stat-card p {
            margin: 0;
            opacity: 0.9;
            font-size: 0.95rem;
            position: relative;
            z-index: 1;
        }
        
        .progress {
            height: 8px;
            border-radius: 4px;
            background-color: rgba(255, 255, 255, 0.2);
            margin-top: 1rem;
            overflow: visible;
        }
        
        .progress-bar {
            background-color: white;
            position: relative;
            border-radius: 4px;
        }
        
        .progress-bar::after {
            content: attr(aria-valuenow) '%';
            position: absolute;
            right: -30px;
            top: -25px;
            font-size: 0.8rem;
            color: white;
            font-weight: 600;
        }
        
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            padding: 1.5rem;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 1rem 1.25rem;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }
        
        .table tbody tr {
            transition: all 0.2s ease;
        }
        
        .table tbody tr:hover {
            background-color: rgba(74, 144, 226, 0.05);
        }
        
        .table td {
            padding: 1rem 1.25rem;
            vertical-align: middle;
            border-color: #f1f3f9;
        }
        
        .badge {
            padding: 0.5em 0.8em;
            font-weight: 500;
            border-radius: 50px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge-masculino {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        
        .badge-femenino {
            background-color: #fce4ec;
            color: #c2185b;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1.5rem;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .header h1 {
            color: white;
            font-weight: 700;
            margin: 0;
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .header h1 i {
            background: rgba(255, 255, 255, 0.15);
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .btn-logout {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            box-shadow: 0 4px 15px rgba(255, 77, 77, 0.3);
        }
        
        .btn-logout:hover {
            background-color: #ff3333;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 77, 77, 0.4);
            color: white;
        }
        
        .card-title {
            font-size: 1rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .materias-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .materia-badge {
            background-color: #e9ecef;
            color: #495057;
            padding: 0.3rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 500;
            white-space: nowrap;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            margin: 0 0.15rem;
            border: 1px solid #dee2e6;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: var(--primary-color);
            color: white !important;
            border-color: var(--primary-color);
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #e9ecef;
            color: #333 !important;
            border: 1px solid #dee2e6;
        }
        
        .dataTables_wrapper .dataTables_length select {
            border-radius: 6px;
            padding: 0.25rem 1.75rem 0.25rem 0.5rem;
            border: 1px solid #ced4da;
        }
        
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 6px;
            padding: 0.25rem 0.5rem;
            border: 1px solid #ced4da;
            margin-left: 0.5rem;
        }
        
        @media (max-width: 992px) {
            .dashboard-container {
                padding: 0.75rem;
            }
            
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
                padding-bottom: 1rem;
            }
            
            .header h1 {
                font-size: 1.5rem;
            }
            
            .btn-logout {
                width: 100%;
                justify-content: center;
            }
            
            .table-responsive {
                padding: 0.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="header bg-primary rounded-3 p-4">
            <h1 class="text-white">
                <i class="bi bi-speedometer2"></i>
                Panel de Administración
            </h1>
            <a href="../auth/logout.php" class="btn btn-logout">
                <i class="bi bi-box-arrow-right"></i> Cerrar sesión
            </a>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <!-- Estadísticas -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card bg-primary">
                    <i class="bi bi-people"></i>
                    <h3><?= number_format($total_estudiantes) ?></h3>
                    <p>Total Estudiantes</p>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="stat-card bg-success">
                    <i class="bi bi-gender-male"></i>
                    <h3><?= number_format($total_masculino) ?></h3>
                    <p>Estudiantes Hombres</p>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: <?= $porcentaje_hombres ?>%" aria-valuenow="<?= $porcentaje_hombres ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="stat-card bg-warning">
                    <i class="bi bi-gender-female"></i>
                    <h3><?= number_format($total_femenino) ?></h3>
                    <p>Estudiantes Mujeres</p>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: <?= $porcentaje_mujeres ?>%" aria-valuenow="<?= $porcentaje_mujeres ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="stat-card bg-info">
                    <i class="bi bi-calendar-month"></i>
                    <h3><?= number_format($inscripciones_mes) ?></h3>
                    <p>Inscripciones este mes</p>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: <?= min(100, ($inscripciones_mes / max(1, $total_estudiantes)) * 100) ?>%" 
                             aria-valuenow="<?= min(100, ($inscripciones_mes / max(1, $total_estudiantes)) * 100) ?>" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Inscripciones -->
        <div class="card">
            <div class="card-header bg-white border-0">
                <h5 class="card-title mb-0">
                    <i class="bi bi-table me-2"></i>Últimas Inscripciones
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="inscripcionesTable" class="table table-hover align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>Estudiante</th>
                                <th>Contacto</th>
                                <th>Semestre</th>
                                <th>Género</th>
                                <th>Jornada</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar me-3">
                                                    <i class="bi bi-person-circle fs-3 text-muted"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0"><?= htmlspecialchars($row['nombre'] . ' ' . $row['apellido']) ?></h6>
                                                    <small class="text-muted"><?= $row['edad'] ?> años</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <i class="bi bi-telephone me-2 text-muted"></i>
                                            <?= htmlspecialchars($row['numero_celular']) ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary"><?= $row['semestre'] ?>°</span>
                                        </td>
                                        <td>
                                            <?php if ($row['genero'] === 'Masculino'): ?>
                                                <span class="badge badge-masculino">
                                                    <i class="bi bi-gender-male me-1"></i> <?= $row['genero'] ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-femenino">
                                                    <i class="bi bi-gender-female me-1"></i> <?= $row['genero'] ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                <i class="bi bi-clock me-1"></i> <?= $row['jornada'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?= $row['fecha_formateada'] ?? 'N/A' ?></small>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                            No hay inscripciones registradas
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        // Inicializar DataTable
        $(document).ready(function() {
            $('#inscripcionesTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
                },
                order: [[5, 'desc']], // Ordenar por fecha por defecto
                responsive: true,
                dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                     "<'row'<'col-sm-12'tr>>" +
                     "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                columnDefs: [
                    { orderable: false, targets: [6] } // Deshabilitar ordenación en columna de acciones
                ]
            });

            // Inicializar tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Actualizar la hora cada minuto
            function updateTime() {
                const now = new Date();
                const options = { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                };
                document.getElementById('current-time').textContent = now.toLocaleDateString('es-ES', options);
            }
            
            // Actualizar la hora cada minuto
            updateTime();
            setInterval(updateTime, 60000);
        });
    </script>
</body>
</html>
      </div>
      <div class="col-md-4">
        <div class="stat-card" style="background: linear-gradient(45deg, #ff6b6b, #ff8e8e);">
          <i class="bi bi-gender-male"></i>
          <h3><?= $total_masculino ?></h3>
          <p>Hombres</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-card" style="background: linear-gradient(45deg, #6c5ce7, #a29bfe);">
          <i class="bi bi-gender-female"></i>
          <h3><?= $total_femenino ?></h3>
          <p>Mujeres</p>
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
