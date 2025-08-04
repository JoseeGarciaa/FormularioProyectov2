<?php
ob_start(); // Inicia el búfer de salida

session_start();
include __DIR__ . '/../config/database.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$programas = [];
$query = "SELECT id, nombre FROM Programas";
$result = $conn->query($query);
while ($row = $result->fetch_assoc()) {
    $programas[] = $row;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulario de Inscripción - USC</title>
  
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
  
  <style>
    :root {
      --usc-blue: #001f87;
      --usc-red: #630000;
      --primary-gradient: linear-gradient(135deg, var(--usc-blue), var(--usc-red));
    }
    
    body {
      background: var(--primary-gradient);
      min-height: 100vh;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .banner-container {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .banner-img {
      width: 100%;
      height: auto;
      max-height: 120px;
      object-fit: contain;
      animation: fadeIn 1s ease;
    }
    
    .main-container {
      padding: 2rem 0;
    }
    
    .form-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.3);
      border-radius: 20px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      animation: slideUp 0.8s ease-out;
    }
    
    .form-header {
      background: var(--primary-gradient);
      color: white;
      padding: 2rem;
      text-align: center;
      position: relative;
    }
    
    .form-header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
    }
    
    .form-header h1 {
      margin: 0;
      font-size: 2.2rem;
      font-weight: 600;
      position: relative;
      z-index: 1;
    }
    
    .form-header p {
      margin: 0.5rem 0 0 0;
      opacity: 0.9;
      font-size: 1.1rem;
      position: relative;
      z-index: 1;
    }
    
    .form-body {
      padding: 2.5rem;
    }
    
    .form-section {
      margin-bottom: 2rem;
    }
    
    .section-title {
      color: var(--usc-blue);
      font-weight: 600;
      font-size: 1.2rem;
      margin-bottom: 1.5rem;
      padding-bottom: 0.5rem;
      border-bottom: 2px solid #e9ecef;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .form-label {
      font-weight: 600;
      color: #495057;
      margin-bottom: 0.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .form-control, .form-select {
      border: 2px solid #e9ecef;
      border-radius: 10px;
      padding: 0.75rem 1rem;
      font-size: 1rem;
      transition: all 0.3s ease;
      background-color: #f8f9fa;
    }
    
    .form-control:focus, .form-select:focus {
      border-color: var(--usc-blue);
      box-shadow: 0 0 0 0.2rem rgba(0, 31, 135, 0.25);
      background-color: white;
    }
    
    .btn-submit {
      background: var(--primary-gradient);
      border: none;
      border-radius: 15px;
      padding: 1rem 2rem;
      font-size: 1.1rem;
      font-weight: 600;
      color: white;
      width: 100%;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    
    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      color: white;
    }
    
    .btn-submit:active {
      transform: translateY(0);
    }
    
    .materias-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 1rem;
    }
    
    .materia-item {
      background: #f8f9fa;
      border: 2px solid #e9ecef;
      border-radius: 10px;
      padding: 1rem;
      transition: all 0.3s ease;
    }
    
    .materia-item:hover {
      border-color: var(--usc-blue);
      background: white;
    }
    
    .alert-custom {
      border: none;
      border-radius: 15px;
      padding: 1rem 1.5rem;
      margin-bottom: 2rem;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .alert-success {
      background: linear-gradient(135deg, #d4edda, #c3e6cb);
      color: #155724;
    }
    
    .alert-warning {
      background: linear-gradient(135deg, #fff3cd, #ffeaa7);
      color: #856404;
    }
    
    .alert-danger {
      background: linear-gradient(135deg, #f8d7da, #f5c6cb);
      color: #721c24;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    
    @keyframes slideUp {
      from { 
        opacity: 0; 
        transform: translateY(30px); 
      }
      to { 
        opacity: 1; 
        transform: translateY(0); 
      }
    }
    
    .loading-spinner {
      display: none;
    }
    
    .form-control.is-invalid {
      border-color: #dc3545;
    }
    
    .form-control.is-valid {
      border-color: #28a745;
    }
    
    @media (max-width: 768px) {
      .form-body {
        padding: 1.5rem;
      }
      
      .materias-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
 
   
  <script>
    // Función para cargar materias
    function cargarMaterias(programaId) {
      if (!programaId) {
        // Limpiar todas las materias
        for (let i = 1; i <= 7; i++) {
          const materiaSelect = document.getElementById('materia' + i);
          materiaSelect.innerHTML = '<option value="">Primero seleccione un programa</option>';
          materiaSelect.disabled = true;
        }
        return;
      }
      
      // Mostrar loading en todas las materias
      for (let i = 1; i <= 7; i++) {
        const materiaSelect = document.getElementById('materia' + i);
        materiaSelect.innerHTML = '<option value="">Cargando materias...</option>';
        materiaSelect.disabled = true;
      }
      
      fetch(`../controllers/materias.php?programa_id=${programaId}`)
        .then(response => response.json())
        .then(data => {
          for (let i = 1; i <= 7; i++) {
            const materiaSelect = document.getElementById('materia' + i);
            materiaSelect.innerHTML = '<option value="">Seleccione una materia</option>';
            materiaSelect.disabled = false;
            
            data.materias.forEach(materia => {
              const option = document.createElement('option');
              option.value = materia;
              option.text = materia;
              materiaSelect.appendChild(option);
            });
          }
        })
        .catch(error => {
          console.error('Error cargando materias:', error);
          for (let i = 1; i <= 7; i++) {
            const materiaSelect = document.getElementById('materia' + i);
            materiaSelect.innerHTML = '<option value="">Error cargando materias</option>';
          }
        });
    }
    
    // Validación del formulario
    function validarFormulario() {
      const form = document.getElementById('inscripcionForm');
      const inputs = form.querySelectorAll('input[required], select[required]');
      let valido = true;
      
      inputs.forEach(input => {
        if (!input.value.trim()) {
          input.classList.add('is-invalid');
          valido = false;
        } else {
          input.classList.remove('is-invalid');
          input.classList.add('is-valid');
        }
      });
      
      return valido;
    }
    
    // Envío del formulario con loading
    function enviarFormulario(event) {
      event.preventDefault();
      
      if (!validarFormulario()) {
        mostrarAlerta('Por favor complete todos los campos obligatorios', 'danger');
        return;
      }
      
      const submitBtn = document.getElementById('submitBtn');
      const spinner = document.getElementById('loadingSpinner');
      const btnText = document.getElementById('btnText');
      
      // Mostrar loading
      submitBtn.disabled = true;
      spinner.style.display = 'inline-block';
      btnText.textContent = 'Procesando inscripción...';
      
      // Enviar formulario
      document.getElementById('inscripcionForm').submit();
    }
    
    // Mostrar alertas
    function mostrarAlerta(mensaje, tipo) {
      const alertContainer = document.getElementById('alertContainer');
      alertContainer.innerHTML = `
        <div class="alert alert-${tipo} alert-custom alert-dismissible fade show" role="alert">
          <i class="bi bi-${tipo === 'success' ? 'check-circle' : tipo === 'warning' ? 'exclamation-triangle' : 'x-circle'}"></i>
          ${mensaje}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      `;
    }
    
    // Inicializar cuando carga la página
    document.addEventListener('DOMContentLoaded', function() {
      // Inicializar materias
      cargarMaterias('');
      
      // Agregar eventos de validación en tiempo real
      const inputs = document.querySelectorAll('input, select');
      inputs.forEach(input => {
        input.addEventListener('blur', function() {
          if (this.hasAttribute('required')) {
            if (!this.value.trim()) {
              this.classList.add('is-invalid');
              this.classList.remove('is-valid');
            } else {
              this.classList.remove('is-invalid');
              this.classList.add('is-valid');
            }
          }
        });
      });
    });
  </script>
</head>
<body>
  <!-- Banner Superior -->
  <div class="banner-container">
    <div class="container-fluid p-0">
      <img src="../assets/images/Banner-Universidad-Santiago-de-Cali-USC-1.png" alt="Banner USC" class="banner-img">
    </div>
  </div>

  <!-- Contenedor Principal -->
  <div class="main-container">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
          
          <!-- Contenedor de Alertas -->
          <div id="alertContainer">
            <?php if (isset($_GET['status'])): ?>
              <div class="alert alert-<?= $_GET['status'] === 'ok' ? 'success' : ($_GET['status'] === 'exists' ? 'warning' : 'danger') ?> alert-custom alert-dismissible fade show" role="alert">
                <i class="bi bi-<?= $_GET['status'] === 'ok' ? 'check-circle' : ($_GET['status'] === 'exists' ? 'exclamation-triangle' : 'x-circle') ?>"></i>
                <?php
                  switch ($_GET['status']) {
                    case 'ok':
                      echo 'Inscripción realizada correctamente.';
                      break;
                    case 'exists':
                      echo 'Ya existe una inscripción para este semestre.';
                      break;
                    case 'error':
                      echo 'Ocurrió un error al procesar la inscripción.';
                      break;
                  }
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>
            <?php endif; ?>
          </div>

          <!-- Tarjeta del Formulario -->
          <div class="form-card">
            <!-- Header del Formulario -->
            <div class="form-header">
              <h1><i class="bi bi-clipboard-check me-2"></i>Formulario de Inscripción</h1>
              <p>Complete todos los campos para realizar su inscripción anticipada</p>
            </div>

            <!-- Cuerpo del Formulario -->
            <div class="form-body">
              <form id="inscripcionForm" method="POST" action="../controllers/inscripcion.php" onsubmit="enviarFormulario(event)">
                
                <!-- Sección: Información Personal -->
                <div class="form-section">
                  <h3 class="section-title">
                    <i class="bi bi-person-circle"></i>
                    Información Personal
                  </h3>
                  
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label class="form-label">
                        <i class="bi bi-calendar-event"></i>
                        Edad
                      </label>
                      <input type="number" class="form-control" name="edad" min="16" max="80" required placeholder="Ingrese su edad">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                      <label class="form-label">
                        <i class="bi bi-gender-ambiguous"></i>
                        Género
                      </label>
                      <select class="form-select" name="genero" required>
                        <option value="">Seleccione su género</option>
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                        <option value="Otro">Otro</option>
                      </select>
                    </div>
                  </div>
                  
                  <div class="mb-3">
                    <label class="form-label">
                      <i class="bi bi-phone"></i>
                      Número Celular
                    </label>
                    <input type="text" class="form-control" name="numero_celular" required placeholder="Ej: 3001234567" pattern="[0-9]{10}">
                    <div class="form-text">Ingrese un número de 10 dígitos sin espacios</div>
                  </div>
                </div>

                <!-- Sección: Información Académica -->
                <div class="form-section">
                  <h3 class="section-title">
                    <i class="bi bi-mortarboard"></i>
                    Información Académica
                  </h3>
                  
                  <div class="row">
                    <div class="col-md-8 mb-3">
                      <label class="form-label">
                        <i class="bi bi-book"></i>
                        Programa Académico
                      </label>
                      <select class="form-select" name="programa_id" onchange="cargarMaterias(this.value)" required>
                        <option value="">Seleccione un programa</option>
                        <?php foreach ($programas as $p): ?>
                          <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                      <label class="form-label">
                        <i class="bi bi-list-ol"></i>
                        Semestre
                      </label>
                      <select class="form-select" name="semestre" required>
                        <option value="">Semestre</option>
                        <?php for ($i = 1; $i <= 12; $i++): ?>
                          <option value="<?= $i ?>"><?= $i ?>° Semestre</option>
                        <?php endfor; ?>
                      </select>
                    </div>
                  </div>
                  
                  <div class="mb-3">
                    <label class="form-label">
                      <i class="bi bi-clock"></i>
                      Jornada
                    </label>
                    <select class="form-select" name="jornada" required>
                      <option value="">Seleccione una jornada</option>
                      <option value="Diurna">Diurna (Mañana y Tarde)</option>
                      <option value="Nocturna">Nocturna (Noche)</option>
                      <option value="Mixta">Mixta (Flexible)</option>
                    </select>
                  </div>
                </div>

                <!-- Sección: Materias -->
                <div class="form-section">
                  <h3 class="section-title">
                    <i class="bi bi-journal-bookmark"></i>
                    Selección de Materias
                  </h3>
                  <p class="text-muted mb-4">
                    <i class="bi bi-info-circle"></i>
                    Primero seleccione un programa académico para cargar las materias disponibles.
                  </p>
                  
                  <div class="materias-grid">
                    <?php for ($i = 1; $i <= 7; $i++): ?>
                      <div class="materia-item">
                        <label class="form-label">
                          <i class="bi bi-book-half"></i>
                          Materia <?= $i ?>
                        </label>
                        <select class="form-select" name="materia<?= $i ?>" id="materia<?= $i ?>" disabled>
                          <option value="">Primero seleccione un programa</option>
                        </select>
                      </div>
                    <?php endfor; ?>
                  </div>
                </div>

                <!-- Botón de Envío -->
                <div class="text-center mt-4">
                  <button type="submit" id="submitBtn" class="btn btn-submit">
                    <span id="loadingSpinner" class="spinner-border spinner-border-sm me-2 loading-spinner" role="status"></span>
                    <i class="bi bi-send me-2"></i>
                    <span id="btnText">Realizar Inscripción</span>
                  </button>
                </div>
                
              </form>
            </div>
          </div>
          
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
