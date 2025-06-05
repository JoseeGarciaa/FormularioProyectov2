<?php
session_start();

if (isset($_SESSION['nombre'])) {
    header("Location: views/bienvenida.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulario de Inscripción - Universidad Santiago de Cali</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <style>
    :root {
      --primary-color: #001f87;
      --secondary-color: #630000;
      --accent-color: #ffc107;
    }
    
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      min-height: 100vh;
      margin: 0;
      padding: 0;
      color: #333;
    }

    .wrapper {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    .banner-container {
      position: relative;
      overflow: hidden;
      border-bottom: 5px solid var(--accent-color);
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .banner-img {
      width: 100%;
      height: auto;
      display: block;
      transition: transform 0.5s ease;
    }

    .banner-img:hover {
      transform: scale(1.02);
    }

    .content-wrapper {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 2rem 0;
    }

    .welcome-card {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      max-width: 800px;
      margin: 0 auto;
      padding: 2.5rem;
      text-align: center;
      transform: translateY(0);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .welcome-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
    }

    h1 {
      color: var(--primary-color);
      font-size: 2.2rem;
      font-weight: 700;
      margin-bottom: 1.5rem;
      position: relative;
      padding-bottom: 1rem;
    }

    h1::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 4px;
      background: var(--accent-color);
      border-radius: 2px;
    }

    .lead-text {
      color: #555;
      font-size: 1.1rem;
      line-height: 1.7;
      margin-bottom: 2rem;
    }

    .btn-container {
      display: flex;
      gap: 1.5rem;
      justify-content: center;
      flex-wrap: wrap;
      margin-top: 2rem;
    }

    .btn {
      padding: 0.8rem 2rem;
      border-radius: 50px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      border: none;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .btn-login {
      background: var(--primary-color);
      color: white;
    }

    .btn-register {
      background: var(--accent-color);
      color: #000;
    }

    .btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 7px 20px rgba(0, 0, 0, 0.15);
    }

    .btn-login:hover {
      background: #001566;
      color: white;
    }

    .btn-register:hover {
      background: #e6ac00;
      color: #000;
    }

    .features {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1.5rem;
      margin-top: 2.5rem;
    }

    .feature-item {
      background: rgba(255, 255, 255, 0.8);
      padding: 1.5rem;
      border-radius: 10px;
      text-align: center;
      transition: transform 0.3s ease;
    }

    .feature-item:hover {
      transform: translateY(-5px);
    }

    .feature-icon {
      font-size: 2.5rem;
      color: var(--primary-color);
      margin-bottom: 1rem;
    }

    footer {
      background: rgba(0, 0, 0, 0.1);
      color: white;
      text-align: center;
      padding: 1.5rem 0;
      margin-top: auto;
    }

    @media (max-width: 768px) {
      .welcome-card {
        margin: 1rem;
        padding: 1.5rem;
      }
      
      h1 {
        font-size: 1.8rem;
      }
      
      .btn {
        width: 100%;
        margin-bottom: 0.5rem;
      }
      
      .btn-container {
        flex-direction: column;
        gap: 1rem;
      }
    }
  </style>
</head>

<body>
  <div class="wrapper">
    <div class="banner-container">
      <img src="assets/images/Banner-Universidad-Santiago-de-Cali-USC-1.png" alt="Banner USC" class="banner-img">
    </div>
    
    <div class="content-wrapper">
      <div class="container">
        <div class="welcome-card">
          <h1>Bienvenido a la Plataforma USC</h1>
          <p class="lead-text">
            Accede a nuestro sistema de gestión académica para realizar tus inscripciones anticipadas para el proximo semestre.
          </p>
          
          <div class="btn-container">
            <a href="views/login.php" class="btn btn-login">
              <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
            </a>
            <a href="views/register.php" class="btn btn-register">
              <i class="bi bi-person-plus"></i> Registrarse
            </a>
          </div>
          
          <div class="features">
            <div class="feature-item">
              <div class="feature-icon">
                <i class="bi bi-book"></i>
              </div>
              <h4>Inscripciones</h4>
              <p>Realiza tus inscripciones de manera fácil y rápida</p>
            </div>
            <div class="feature-item">
              <div class="feature-icon">
                <i class="bi bi-calendar-check"></i>
              </div>
              <h4>Horarios</h4>
              <p>Selecciona tus horarios</p>
            </div>
            <div class="feature-item">
              <div class="feature-icon">
                <i class="bi bi-graph-up"></i>
              </div>
              <h4>Seguimiento</h4>
              <p>Mantén un registro de tu progreso académico</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <footer>
      <div class="container">
        <p class="mb-0">© <?= date('Y') ?> Universidad Santiago de Cali - Todos los derechos reservados</p>
      </div>
    </footer>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
