
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar Sesión - USC</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(90deg, #001f87, #630000);
      color: white;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 400px;
      margin: 50px auto;
      background: rgba(0,0,0,0.6);
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 0 10px rgba(0,0,0,1.2);
    }
    h2 {
      text-align: center;
      margin-bottom: 25px;
    }
    label {
      display: block;
      margin: 10px 0 5px;
    }
    input[type="email"], input[type="password"] {
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 8px;
      margin-bottom: 15px;
      box-sizing: border-box;
    }
    input[type="submit"] {
      background-color: #221559;
      color: white;
      padding: 12px;
      border: none;
      border-radius: 8px;
      width: 100%;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s;
    }
    input[type="submit"]:hover {
      background-color: #3e16d1;
    }
    .link {
      text-align: center;
      margin-top: 15px;
    }
    .link a {
      color: #66aaff;
      text-decoration: none;
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
.toast.error { background-color: #dc3545; }

@keyframes fadeOut {
  0% { opacity: 1; }
  80% { opacity: 1; }
  100% { opacity: 0; display: none; }
}

    
  </style>
</head>
<body>

  <?php if (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
  <div class="toast error">
    ❌ Correo o contraseña incorrectos.
  </div>
<?php endif; ?>

  <div class="container">
    <h2>Iniciar Sesión</h2>
    <form action="../auth/login.php" method="POST">
      <label>Correo:</label>
      <input type="email" name="correo" required>
      <label>Contraseña:</label>
      <input type="password" name="contrasena" required>
      <input type="submit" value="Iniciar Sesión">
    </form>
    <div class="link">
      ¿No tienes cuenta? <a href="register.html">Regístrate</a>
    </div>
  </div>
</body>
</html>
