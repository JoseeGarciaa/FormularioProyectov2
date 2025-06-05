<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Usuario - USC</title>
  <style>
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
  <script>
    function validarTerminos() {
      const terminos = document.getElementById('terminos');
      if (!terminos.checked) {
        alert('Debes aceptar los términos y condiciones para continuar');
        return false;
      }
      return true;
    }

    function mostrarTerminos() {
      const mensaje = '🚨 TÉRMINOS Y CONDICIONES DE USO 🚨\n\n' +
                    '🔒 Tus datos personales son importantes para nosotros. Al aceptar, confirmas que:\n\n' +
                    '• La Universidad Santiago de Cali utilizará esta información exclusivamente para fines académicos y administrativos.\n' +
                    '• Tus datos estarán protegidos según la normativa de protección de datos vigente.\n' +
                    '• Podrás ejercer tus derechos ARCO (Acceso, Rectificación, Cancelación y Oposición) cuando lo desees.\n\n' +
                    'Al hacer clic en "Aceptar", reconoces haber leído y estar de acuerdo con estos términos.';
      
      alert(mensaje);
    }
  </script>
</head>
<body>

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
          break;
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
      
      <div style="margin: 15px 0;">
        <label style="display: flex; align-items: flex-start; cursor: pointer;">
          <input type="checkbox" name="terminos" id="terminos" required style="width: auto; margin-right: 10px; margin-top: 3px;">
          <span>Acepto los <a href="#" onclick="mostrarTerminos(); return false;" style="color: #001f87; text-decoration: underline;">términos y condiciones</a> de uso</span>
        </label>
      </div>
      
      <input type="submit" id="btnRegistrar" value="Registrarse" onclick="return validarTerminos();">
    </form>
    <div class="link">
      ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>
    </div>
  </div>
</body>
</html>
