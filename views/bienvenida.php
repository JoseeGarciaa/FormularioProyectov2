<?php
session_start();
if (!isset($_SESSION['nombre'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bienvenido - USC</title>
  
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
  
  <style>
    :root {
      --usc-blue: #001f87;
      --usc-red: #630000;
      --primary-gradient: linear-gradient(135deg, var(--usc-blue), var(--usc-red));
      --glass-bg: rgba(255, 255, 255, 0.1);
      --glass-border: rgba(255, 255, 255, 0.2);
    }
    
    body {
      background: var(--primary-gradient);
      min-height: 100vh;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      overflow-x: hidden;
    }
    
    /* Partículas de fondo animadas */
    .particles {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: 1;
    }
    
    .particle {
      position: absolute;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      animation: float 6s ease-in-out infinite;
    }
    
    .particle:nth-child(1) { width: 10px; height: 10px; left: 10%; animation-delay: 0s; }
    .particle:nth-child(2) { width: 15px; height: 15px; left: 20%; animation-delay: 1s; }
    .particle:nth-child(3) { width: 8px; height: 8px; left: 30%; animation-delay: 2s; }
    .particle:nth-child(4) { width: 12px; height: 12px; left: 40%; animation-delay: 3s; }
    .particle:nth-child(5) { width: 6px; height: 6px; left: 50%; animation-delay: 4s; }
    .particle:nth-child(6) { width: 14px; height: 14px; left: 60%; animation-delay: 5s; }
    .particle:nth-child(7) { width: 9px; height: 9px; left: 70%; animation-delay: 0.5s; }
    .particle:nth-child(8) { width: 11px; height: 11px; left: 80%; animation-delay: 1.5s; }
    .particle:nth-child(9) { width: 7px; height: 7px; left: 90%; animation-delay: 2.5s; }
    
    @keyframes float {
      0%, 100% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
      10% { opacity: 1; }
      90% { opacity: 1; }
      100% { transform: translateY(-10vh) rotate(360deg); opacity: 0; }
    }
    
    .banner-container {
      background: var(--glass-bg);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid var(--glass-border);
      position: relative;
      z-index: 2;
    }
    
    .banner-img {
      width: 100%;
      height: auto;
      max-height: 120px;
      object-fit: contain;
      animation: slideDown 1s ease-out;
    }
    
    .main-container {
      position: relative;
      z-index: 2;
      min-height: calc(100vh - 120px);
      display: flex;
      align-items: center;
      padding: 2rem 0;
    }
    
    .welcome-card {
      background: var(--glass-bg);
      backdrop-filter: blur(20px);
      border: 1px solid var(--glass-border);
      border-radius: 25px;
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
      overflow: hidden;
      animation: scaleIn 0.8s ease-out;
      position: relative;
    }
    
    .welcome-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(45deg, 
        rgba(255, 255, 255, 0.1) 0%, 
        transparent 50%, 
        rgba(255, 255, 255, 0.1) 100%);
      pointer-events: none;
    }
    
    .welcome-header {
      background: linear-gradient(135deg, 
        rgba(255, 255, 255, 0.2), 
        rgba(255, 255, 255, 0.1));
      padding: 3rem 2rem 2rem;
      text-align: center;
      position: relative;
      z-index: 1;
    }
    
    .welcome-title {
      color: white;
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 1rem;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
      animation: fadeInUp 1s ease-out 0.3s both;
    }
    
    .welcome-subtitle {
      color: rgba(255, 255, 255, 0.9);
      font-size: 1.3rem;
      font-weight: 400;
      margin-bottom: 0;
      animation: fadeInUp 1s ease-out 0.5s both;
    }
    
    .user-name {
      color: #FFD700;
      font-weight: 600;
      text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
    }
    
    .welcome-body {
      padding: 2rem;
      position: relative;
      z-index: 1;
    }
    
    .action-cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2rem;
    }
    
    .action-card {
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 15px;
      padding: 1.5rem;
      text-align: center;
      transition: all 0.3s ease;
      animation: fadeInUp 1s ease-out 0.7s both;
      position: relative;
      overflow: hidden;
    }
    
    .action-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, 
        transparent, 
        rgba(255, 255, 255, 0.2), 
        transparent);
      transition: left 0.5s ease;
    }
    
    .action-card:hover::before {
      left: 100%;
    }
    
    .action-card:hover {
      transform: translateY(-5px);
      background: rgba(255, 255, 255, 0.15);
      border-color: rgba(255, 255, 255, 0.3);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
    }
    
    .action-icon {
      font-size: 2.5rem;
      color: #FFD700;
      margin-bottom: 1rem;
      display: block;
    }
    
    .action-title {
      color: white;
      font-size: 1.1rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
    }
    
    .action-description {
      color: rgba(255, 255, 255, 0.8);
      font-size: 0.9rem;
      margin-bottom: 1.5rem;
    }
    
    .btn-action {
      background: linear-gradient(135deg, var(--usc-blue), var(--usc-red));
      border: none;
      border-radius: 25px;
      color: white;
      padding: 0.75rem 1.5rem;
      font-weight: 600;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    
    .btn-action::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(255, 255, 255, 0.2);
      transform: translateX(-100%);
      transition: transform 0.3s ease;
    }
    
    .btn-action:hover::before {
      transform: translateX(0);
    }
    
    .btn-action:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
      color: white;
    }
    
    .stats-section {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 15px;
      padding: 1.5rem;
      margin-top: 1rem;
      animation: fadeInUp 1s ease-out 0.9s both;
    }
    
    .stats-title {
      color: white;
      font-size: 1.1rem;
      font-weight: 600;
      margin-bottom: 1rem;
      text-align: center;
    }
    
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
      gap: 1rem;
      text-align: center;
    }
    
    .stat-item {
      color: white;
    }
    
    .stat-number {
      font-size: 1.5rem;
      font-weight: 700;
      color: #FFD700;
      display: block;
    }
    
    .stat-label {
      font-size: 0.8rem;
      opacity: 0.8;
    }
    
    @keyframes slideDown {
      from { transform: translateY(-100%); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
    
    @keyframes scaleIn {
      from { transform: scale(0.8); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
    }
    
    @keyframes fadeInUp {
      from { transform: translateY(30px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
    
    @media (max-width: 768px) {
      .welcome-title {
        font-size: 2rem;
      }
      
      .welcome-subtitle {
        font-size: 1.1rem;
      }
      
      .action-cards {
        grid-template-columns: 1fr;
      }
      
      .welcome-header {
        padding: 2rem 1rem 1.5rem;
      }
      
      .welcome-body {
        padding: 1.5rem;
      }
    }
  </style>
</head>
<body>
  <!-- Partículas de fondo animadas -->
  <div class="particles">
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
  </div>

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
        <div class="col-lg-10 col-xl-8">
          
          <!-- Tarjeta de Bienvenida -->
          <div class="welcome-card">
            
            <!-- Header de Bienvenida -->
            <div class="welcome-header">
              <h1 class="welcome-title">
                <i class="bi bi-house-heart me-3"></i>
                Bienvenido a USC
              </h1>
              <p class="welcome-subtitle">
                Hola, <span class="user-name"><?php echo htmlspecialchars($_SESSION['nombre']); ?></span> 👋
                <br>
                <small>Gestiona tu proceso de inscripción académica</small>
              </p>
            </div>

            <!-- Cuerpo de la Bienvenida -->
            <div class="welcome-body">
              
              <!-- Tarjetas de Acciones -->
              <div class="action-cards">
                
                <!-- Inscribir Materias -->
                <div class="action-card">
                  <i class="bi bi-journal-bookmark action-icon"></i>
                  <h3 class="action-title">Inscribir Materias</h3>
                  <p class="action-description">
                    Realiza tu inscripción anticipada para el próximo semestre académico
                  </p>
                  <a href="indexform.php" class="btn-action">
                    <i class="bi bi-pencil-square"></i>
                    Comenzar Inscripción
                  </a>
                </div>

                <!-- Perfil de Usuario -->
                <div class="action-card">
                  <i class="bi bi-person-circle action-icon"></i>
                  <h3 class="action-title">Mi Perfil</h3>
                  <p class="action-description">
                    Consulta y actualiza tu información personal y académica
                  </p>
                  <a href="#" class="btn-action" onclick="mostrarProximamente()">
                    <i class="bi bi-gear"></i>
                    Ver Perfil
                  </a>
                </div>

                <!-- Historial -->
                <div class="action-card">
                  <i class="bi bi-clock-history action-icon"></i>
                  <h3 class="action-title">Historial</h3>
                  <p class="action-description">
                    Revisa tus inscripciones anteriores y el progreso académico
                  </p>
                  <a href="#" class="btn-action" onclick="mostrarProximamente()">
                    <i class="bi bi-list-ul"></i>
                    Ver Historial
                  </a>
                </div>

                <!-- Soporte -->
                <div class="action-card">
                  <i class="bi bi-headset action-icon"></i>
                  <h3 class="action-title">Soporte</h3>
                  <p class="action-description">
                    Obtén ayuda y resuelve tus dudas sobre el proceso de inscripción
                  </p>
                  <a href="#" class="btn-action" onclick="mostrarSoporte()">
                    <i class="bi bi-chat-dots"></i>
                    Contactar
                  </a>
                </div>

              </div>

              <!-- Sección de Estadísticas -->
              <div class="stats-section">
                <h4 class="stats-title">
                  <i class="bi bi-graph-up me-2"></i>
                  Estado del Sistema
                </h4>
                <div class="stats-grid">
                  <div class="stat-item">
                    <span class="stat-number" id="totalProgramas">-</span>
                    <div class="stat-label">Programas Disponibles</div>
                  </div>
                  <div class="stat-item">
                    <span class="stat-number" id="totalMaterias">-</span>
                    <div class="stat-label">Materias Activas</div>
                  </div>
                  <div class="stat-item">
                    <span class="stat-number" id="inscripcionesHoy">-</span>
                    <div class="stat-label">Inscripciones Hoy</div>
                  </div>
                  <div class="stat-item">
                    <span class="stat-number"><?php echo date('H:i'); ?></span>
                    <div class="stat-label">Hora Actual</div>
                  </div>
                </div>
              </div>

              <!-- Botón de Cerrar Sesión -->
              <div class="text-center mt-4">
                <a href="../auth/logout.php" class="btn btn-outline-light btn-lg" onclick="return confirmarCerrarSesion()">
                  <i class="bi bi-box-arrow-right me-2"></i>
                  Cerrar Sesión
                </a>
              </div>
              
            </div>
          </div>
          
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- JavaScript personalizado -->
  <script>
    // Cargar estadísticas del sistema
    function cargarEstadisticas() {
      // Simular carga de estadísticas (puedes conectar con PHP real)
      setTimeout(() => {
        document.getElementById('totalProgramas').textContent = '12';
        document.getElementById('totalMaterias').textContent = '156';
        document.getElementById('inscripcionesHoy').textContent = '24';
      }, 1000);
    }
    
    // Mostrar mensaje de próximamente
    function mostrarProximamente() {
      alert('🚀 Funcionalidad próximamente\n\nEsta característica estará disponible en futuras actualizaciones.');
    }
    
    // Mostrar información de soporte
    function mostrarSoporte() {
      alert('📞 Soporte Técnico\n\nPara asistencia contacta:\n\n📧 Email: soporte@usc.edu.co\n📱 Teléfono: (2) 518-3000\n🕰️ Horario: Lunes a Viernes 8:00 AM - 6:00 PM');
    }
    
    // Confirmar cerrar sesión
    function confirmarCerrarSesion() {
      return confirm('¿Estás seguro de que deseas cerrar sesión?');
    }
    
    // Actualizar hora cada minuto
    function actualizarHora() {
      const ahora = new Date();
      const hora = ahora.toLocaleTimeString('es-CO', { 
        hour: '2-digit', 
        minute: '2-digit' 
      });
      const horaElement = document.querySelector('.stat-number:last-child');
      if (horaElement) {
        horaElement.textContent = hora;
      }
    }
    
    // Inicializar cuando carga la página
    document.addEventListener('DOMContentLoaded', function() {
      cargarEstadisticas();
      
      // Actualizar hora cada minuto
      setInterval(actualizarHora, 60000);
      
      // Efecto de bienvenida personalizado
      setTimeout(() => {
        const userName = '<?php echo addslashes($_SESSION['nombre']); ?>';
        console.log(`🎉 Bienvenido ${userName} a la plataforma USC!`);
      }, 2000);
    });
  </script>
  
</body>
</html>
