<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro de Usuario - USC</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #001f87;
      --secondary-color: #630000;
      --accent-color: #ffc107;
    }
    
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      min-height: 100vh;
      margin: 0;
      padding: 0;
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
   body {
    font-family: Arial, sans-serif;
    background: linear-gradient(90deg, #001f87, #630000);
    color: #001f87;
    margin: 0;
    padding: 0;
}
    .container {
    max-width: 400px;
    margin: 50px auto;
    background: rgb(255 255 255);
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 1.2);
}
    h2 {
      text-align: center;
      margin-bottom: 25px;
    }
    label {
      display: block;
      margin: 10px 0 5px;
    }
    input[type="text"], input[type="email"], input[type="password"] {
    width: 100%;
    padding: 10px;
    background: rgb(233 233 233);
    border: none;
    border-radius: 8px;
    margin-bottom: 15px;
    box-sizing: border-box;
}
input[type="submit"] {
    background-color: #001f87;
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

.toast.exito { background-color: #28a745; }
.toast.correo_existe { background-color: #ffc107; color: #000; }
.toast.identificacion_existe { background-color: #ffc107; color: #000; }
.toast.ambos_existen { background-color: #dc3545; }
.toast.error { background-color: #dc3545; }

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

      <?php if (isset($_GET['status'])): ?>
        <div class="toast <?= htmlspecialchars($_GET['status']) ?>">
          <?php
            switch ($_GET['status']) {
              case 'exito':
                echo '✅ Registro exitoso. Ya puedes iniciar sesión.';
                break;
              case 'correo_existe':
                echo '⚠️ El correo electrónico ya está registrado.';
                break;
              case 'identificacion_existe':
                echo '⚠️ El número de identificación ya está registrado.';
                break;
              case 'ambos_existen':
                echo '❌ El correo y el número de identificación ya están registrados.';
                break;
              case 'error':
                echo '❌ Error al registrar. Intenta de nuevo.';
            }
          ?>
        </div>
      <?php endif; ?>

      <div class="container">
        <h2>Registro de Usuario</h2>
        <form action="../auth/register.php" method="POST">
          <label>Nombre:</label>
          <input type="text" name="nombre" required>
          <label>Apellido:</label>
          <input type="text" name="apellido" required>
          <label>Número de Identificación:</label>
          <input type="text" name="numero_identificacion" required>
          <label>Correo:</label>
          <input type="email" name="correo" required>
          <label>Contraseña:</label>
          <input type="password" name="contrasena" required>
          <input type="submit" value="Registrarse">
        </form>
        <div class="link">
          ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
