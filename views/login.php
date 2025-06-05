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
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-container {
      width: 100%;
      max-width: 420px;
      padding: 2rem;
    }

    .login-card {
      background: rgba(255, 255, 255, 0.98);
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      padding: 2.5rem;
      transform: translateY(0);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .login-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
    }

    .logo-container {
      text-align: center;
      margin-bottom: 1.5rem;
    }

    .logo-img {
      max-width: 180px;
      height: auto;
      margin-bottom: 1rem;
    }

    .form-title {
      color: var(--primary-color);
      font-size: 1.8rem;
      font-weight: 700;
      text-align: center;
      margin-bottom: 1.5rem;
      position: relative;
      padding-bottom: 1rem;
    }

    .form-title::after {
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

    .form-floating {
      margin-bottom: 1.25rem;
    }

    .form-floating > label {
      color: #6c757d;
    }

    .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.25rem rgba(0, 31, 135, 0.25);
    }

    .btn-login {
      background: var(--primary-color);
      border: none;
      padding: 0.8rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      transition: all 0.3s ease;
      margin-top: 0.5rem;
    }

    .btn-login:hover {
      background: #001566;
      transform: translateY(-2px);
    }

    .register-link {
      text-align: center;
      margin-top: 1.5rem;
      color: #6c757d;
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
      font-weight: bold;
      color: white;
      box-shadow: 0 0 10px rgba(0,0,0,0.5);
      z-index: 1000;
      opacity: 0.95;
      animation: fadeOut 4s forwards;
    }
    
    .toast.error { 
      background-color: #dc3545;
    }
    
    .toast.success {
      background-color: #28a745;
    }
    
    @keyframes fadeOut {
      0% { opacity: 1; }
      80% { opacity: 1; }
      100% { opacity: 0; display: none; }
    }

    @media (max-width: 576px) {
      .login-container {
        padding: 1.5rem;
      }
      
      .login-card {
        padding: 1.5rem;
      }
      
      .form-title {
        font-size: 1.5rem;
      }
    }
  </style>
</head>
<body>
  <?php if (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
  <div class="toast error">
    ❌ Correo o contraseña incorrectos.
  </div>
  <?php endif; ?>
  
  <?php if (isset($_GET['registro']) && $_GET['registro'] === 'exitoso'): ?>
  <div class="toast success">
    ✅ Registro exitoso. Ya puedes iniciar sesión.
  </div>
  <?php endif; ?>

  <div class="login-container">
    <div class="login-card">
      <div class="logo-container">
        <img src="../assets/images/logo-usc.png" alt="Logo USC" class="logo-img">
      </div>
      
      <h1 class="form-title">Iniciar Sesión</h1>
      
      <form action="../auth/login.php" method="POST" class="needs-validation" novalidate>
        <div class="form-floating mb-3">
          <input type="email" class="form-control" id="correo" name="correo" placeholder="nombre@ejemplo.com" required>
          <label for="correo">Correo electrónico</label>
          <div class="invalid-feedback">
            Por favor ingresa un correo válido.
          </div>
        </div>
        
        <div class="form-floating mb-3">
          <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Contraseña" required>
          <label for="contrasena">Contraseña</label>
          <div class="invalid-feedback">
            Por favor ingresa tu contraseña.
          </div>
        </div>
        
        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary btn-login">
            <i class="bi bi-box-arrow-in-right me-2"></i> Iniciar Sesión
          </button>
        </div>
      </form>
      
      <div class="register-link">
        ¿No tienes una cuenta? <a href="register.php">Regístrate aquí</a>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Validación de formulario
    (function () {
      'use strict'
      
      const forms = document.querySelectorAll('.needs-validation')
      
      Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
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
