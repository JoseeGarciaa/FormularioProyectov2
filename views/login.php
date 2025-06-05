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
      justify-content: center;
    }

    .login-container {
      max-width: 450px;
      margin: 2rem auto;
      background: rgba(255, 255, 255, 0.95);
      border-radius: 15px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
      padding: 2.5rem;
      transform: translateY(0);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .login-container:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
    }

    .login-header {
      text-align: center;
      margin-bottom: 2rem;
    }

    .login-header img {
      max-width: 150px;
      margin-bottom: 1.5rem;
    }

    .login-header h2 {
      color: var(--primary-color);
      font-weight: 700;
      margin-bottom: 0.5rem;
      position: relative;
      padding-bottom: 1rem;
    }

    .login-header h2::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 60px;
      height: 3px;
      background: var(--accent-color);
      border-radius: 2px;
    }

    .form-control {
      height: 48px;
      border-radius: 8px;
      border: 1px solid #ddd;
      padding: 0.75rem 1rem;
      margin-bottom: 1.25rem;
      font-size: 1rem;
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.25rem rgba(0, 31, 135, 0.25);
    }

    .btn-login {
      background: var(--primary-color);
      color: white;
      padding: 0.75rem;
      border: none;
      border-radius: 8px;
      font-size: 1.1rem;
      font-weight: 600;
      width: 100%;
      margin-top: 0.5rem;
      transition: all 0.3s ease;
    }

    .btn-login:hover {
      background: #001566;
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
      color: #001566;
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

    @keyframes fadeOut {
      0% { opacity: 1; }
      80% { opacity: 1; }
      100% { opacity: 0; display: none; }
    }


    @media (max-width: 768px) {
      .login-container {
        margin: 1rem;
        padding: 1.5rem;
      }
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <?php if (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
    <div class="toast error">
      ❌ Correo o contraseña incorrectos.
    </div>
    <?php endif; ?>

    <div class="login-container">
      <div class="login-header">
        <img src="../assets/images/logo-usc.png" alt="Logo USC">
        <h2>Iniciar Sesión</h2>
      </div>
      
      <form action="../auth/login.php" method="POST">
        <div class="mb-3">
          <input type="email" class="form-control" name="correo" placeholder="Correo electrónico" required>
        </div>
        <div class="mb-3">
          <input type="password" class="form-control" name="contrasena" placeholder="Contraseña" required>
        </div>
        <button type="submit" class="btn btn-login">
          Iniciar Sesión
        </button>
      </form>
      
      <div class="register-link">
        ¿No tienes una cuenta? <a href="register.php">Regístrate aquí</a>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
