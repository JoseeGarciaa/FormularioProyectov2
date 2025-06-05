<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar Sesión - Universidad Santiago de Cali</title>
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


    .form-card {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      max-width: 450px;
      margin: 2rem auto;
      padding: 2.5rem;
      transform: translateY(0);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .form-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
    }

    h1 {
      color: var(--primary-color);
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 1.5rem;
      text-align: center;
      position: relative;
      padding-bottom: 1rem;
    }

    h1::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 60px;
      height: 4px;
      background: var(--accent-color);
      border-radius: 2px;
    }

    .form-label {
      font-weight: 600;
      color: #444;
      margin-bottom: 0.5rem;
    }

    .form-control {
      border: 2px solid #e1e1e1;
      border-radius: 8px;
      padding: 0.75rem 1rem;
      margin-bottom: 1.25rem;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.25rem rgba(0, 31, 135, 0.25);
    }

    .btn-primary {
      background-color: var(--primary-color);
      border: none;
      padding: 0.75rem 2rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      transition: all 0.3s ease;
      width: 100%;
      margin-top: 0.5rem;
    }

    .btn-primary:hover {
      background-color: #001566;
      transform: translateY(-2px);
    }

    .register-link {
      text-align: center;
      margin-top: 1.5rem;
      color: #666;
    }

    .register-link a {
      color: var(--primary-color);
      font-weight: 600;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .register-link a:hover {
      color: var(--secondary-color);
      text-decoration: underline;
    }

    .toast {
      position: fixed;
      top: 20px;
      right: 20px;
      padding: 15px 25px;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      color: white;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      z-index: 1000;
      opacity: 0.95;
      animation: fadeOut 4s forwards;
      display: flex;
      align-items: center;
      gap: 10px;
      background-color: #dc3545;
      border-left: 5px solid #bd2130;
    }

    .toast i {
      font-size: 1.25rem;
    }

    @keyframes fadeOut {
      0% { opacity: 1; }
      80% { opacity: 1; }
      100% { opacity: 0; display: none; }
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="banner-container">
      <img src="../assets/images/Banner-Universidad-Santiago-de-Cali-USC-1.png" alt="Banner USC" class="banner-img">
    </div>
    <div class="content-wrapper">
      <div class="form-card">
        <h1>Iniciar Sesión</h1>
        
        <?php if (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
        <div class="toast">
         
          <span>Correo o contraseña incorrectos.</span>
        </div>
        <?php endif; ?>
        
        <form action="../auth/login.php" method="POST" class="needs-validation" novalidate>
          <div class="mb-3">
            <label for="correo" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="correo" name="correo" required>
            <div class="invalid-feedback">
              Por favor ingresa tu correo electrónico.
            </div>
          </div>
          
          <div class="mb-4">
            <label for="contrasena" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="contrasena" name="contrasena" required>
            <div class="invalid-feedback">
              Por favor ingresa tu contraseña.
            </div>
          </div>
          
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
          </button>
          
          <div class="register-link">
            ¿No tienes una cuenta? <a href="register.php">Regístrate aquí</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Validación de formulario
    (function () {
      'use strict'
      
      var forms = document.querySelectorAll('.needs-validation')
      
      Array.prototype.slice.call(forms)
        .forEach(function (form) {
          form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
              event.preventDefault()
              event.stopPropagation()
            }

            form.classList.add('was-validated')
          }, false)
        })
    })()
  </script>
</body>
</html>
