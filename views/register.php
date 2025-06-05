<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro de Usuario - Universidad Santiago de Cali</title>
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
      padding: 2rem 0;
      display: flex;
      align-items: center;
    }

    .register-container {
      width: 100%;
      max-width: 600px;
      margin: 0 auto;
      padding: 0 1.5rem;
    }

    .register-card {
      background: rgba(255, 255, 255, 0.98);
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      padding: 2.5rem;
      transform: translateY(0);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .register-card:hover {
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
      width: 80px;
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
      box-shadow: 0 0 0 0.25rem rgba(0, 31, 135, 0.15);
    }

    .btn-register {
      background: var(--accent-color);
      color: #000;
      border: none;
      padding: 0.8rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      transition: all 0.3s ease;
      margin-top: 0.5rem;
    }

    .btn-register:hover {
      background: #e6ac00;
      transform: translateY(-2px);
      color: #000;
    }

    .login-link {
      text-align: center;
      margin-top: 1.5rem;
      color: #6c757d;
    }

    .login-link a {
      color: var(--primary-color);
      font-weight: 600;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .login-link a:hover {
      color: var(--secondary-color);
      text-decoration: underline;
    }

    .toast {
      position: fixed;
      top: 20px;
      right: 20px;
      padding: 1rem 1.5rem;
      border-radius: 8px;
      font-weight: 500;
      color: white;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
      z-index: 1000;
      opacity: 0.95;
      animation: fadeOut 4s forwards;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      max-width: 90%;
    }

    .toast i {
      font-size: 1.2rem;
      flex-shrink: 0;
    }

    .toast.ok { 
      background-color: #28a745;
      border-left: 4px solid #1e7e34;
    }

    .toast.exists { 
      background-color: #ffc107;
      border-left: 4px solid #d39e00;
      color: #000;
    }

    .toast.error { 
      background-color: #dc3545;
      border-left: 4px solid #a71d2a;
    }

    @keyframes fadeOut {
      0% { opacity: 1; transform: translateY(0); }
      80% { opacity: 1; transform: translateY(0); }
      100% { opacity: 0; transform: translateY(-20px); display: none; }
    }

    .password-requirements {
      font-size: 0.85rem;
      color: #6c757d;
      margin-top: -0.5rem;
      margin-bottom: 1rem;
    }

    .requirement {
      display: flex;
      align-items: center;
      margin-bottom: 0.25rem;
    }

    .requirement i {
      margin-right: 0.5rem;
      font-size: 0.7rem;
    }

    .requirement.valid {
      color: #28a745;
    }

    @media (max-width: 768px) {
      .register-container {
        padding: 0 1rem;
      }
      
      .register-card {
        padding: 1.5rem;
      }
      
      .form-title {
        font-size: 1.5rem;
      }
      
      .row > div {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
      }
    }
  </style>
</head>
<body>
  <?php 
  // Mostrar mensaje de error si existe
  if (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
  <div class="toast error">
    ❌ Error al registrar. Intenta de nuevo.
  </div>
  <?php endif; ?>
  
  <?php if (isset($_GET['status']) && $_GET['status'] === 'exists'): ?>
  <div class="toast error">
    ⚠️ El correo ya está registrado.
  </div>
  <?php endif; ?>
  
  <?php if (isset($_GET['registro']) && $_GET['registro'] === 'exitoso'): ?>
  <div class="toast success">
    ✅ Registro exitoso. Ya puedes iniciar sesión.
  </div>
  <?php endif; ?>

  <div class="register-container">
    <div class="register-card">
      <div class="logo-container">
        <img src="../assets/images/logo-usc.png" alt="Logo USC" class="logo-img">
      </div>
      
      <h1 class="form-title">Crear Cuenta</h1>
      
      <form id="registerForm" action="../auth/register.php" method="POST" class="needs-validation" novalidate>
        <div class="row">
          <div class="col-md-6">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required>
              <label for="nombre">Nombre</label>
              <div class="invalid-feedback">
                Por favor ingresa tu nombre.
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Apellido" required>
              <label for="apellido">Apellido</label>
              <div class="invalid-feedback">
                Por favor ingresa tu apellido.
              </div>
            </div>
          </div>
        </div>
        
        <div class="form-floating mb-3">
          <input type="text" class="form-control" id="numero_identificacion" name="numero_identificacion" placeholder="Número de Identificación" required>
          <label for="numero_identificacion">Número de Identificación</label>
          <div class="invalid-feedback">
            Por favor ingresa tu número de identificación.
          </div>
        </div>
        
        <div class="form-floating mb-3">
          <input type="email" class="form-control" id="correo" name="correo" placeholder="nombre@ejemplo.com" required>
          <label for="correo">Correo Electrónico</label>
          <div class="invalid-feedback">
            Por favor ingresa un correo válido.
          </div>
        </div>
        
        <div class="form-floating mb-2">
          <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Contraseña" required 
                 pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$">
          <label for="contrasena">Contraseña</label>
          <div class="invalid-feedback">
            La contraseña debe tener al menos 8 caracteres, incluyendo una mayúscula, una minúscula y un número.
          </div>
        </div>
        
        <div class="password-requirements">
          <p class="mb-2">La contraseña debe contener:</p>
          <div class="requirement" id="length">
            <i class="bi" id="length-icon"></i>
            <span>Mínimo 8 caracteres</span>
          </div>
          <div class="requirement" id="uppercase">
            <i class="bi" id="uppercase-icon"></i>
            <span>Al menos una letra mayúscula</span>
          </div>
          <div class="requirement" id="lowercase">
            <i class="bi" id="lowercase-icon"></i>
            <span>Al menos una letra minúscula</span>
          </div>
          <div class="requirement" id="number">
            <i class="bi" id="number-icon"></i>
            <span>Al menos un número</span>
          </div>
        </div>
        

        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-warning btn-register">
            <i class="bi bi-person-plus me-2"></i> Crear Cuenta
          </button>
        </div>
      </form>
      
      <div class="login-link">
        ¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Validación de formulario
    (function () {
      'use strict'
      
      // Validación de Bootstrap
      const forms = document.querySelectorAll('.needs-validation')
      
      // Validación personalizada de contraseña
      const password = document.getElementById('contrasena');
      const requirements = {
        length: { element: document.getElementById('length'), icon: document.getElementById('length-icon') },
        uppercase: { element: document.getElementById('uppercase'), icon: document.getElementById('uppercase-icon') },
        lowercase: { element: document.getElementById('lowercase'), icon: document.getElementById('lowercase-icon') },
        number: { element: document.getElementById('number'), icon: document.getElementById('number-icon') }
      };

      // Función para validar la contraseña
      function validatePassword() {
        const value = password.value;
        let isValid = true;

        // Validar longitud
        if (value.length >= 8) {
          setValid('length');
        } else {
          setInvalid('length');
          isValid = false;
        }

        // Validar mayúsculas
        if (/[A-Z]/.test(value)) {
          setValid('uppercase');
        } else {
          setInvalid('uppercase');
          isValid = false;
        }


        // Validar minúsculas
        if (/[a-z]/.test(value)) {
          setValid('lowercase');
        } else {
          setInvalid('lowercase');
          isValid = false;
        }


        // Validar números
        if (/[0-9]/.test(value)) {
          setValid('number');
        } else {
          setInvalid('number');
          isValid = false;
        }

        return isValid;
      }


      function setValid(type) {
        requirements[type].element.classList.add('valid');
        requirements[type].icon.className = 'bi bi-check-circle-fill';
      }

      function setInvalid(type) {
        requirements[type].element.classList.remove('valid');
        requirements[type].icon.className = 'bi bi-x-circle';
      }


      // Event listeners
      password.addEventListener('input', validatePassword);
      
      Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
          if (!form.checkValidity() || !validatePassword()) {
            event.preventDefault();
            event.stopPropagation();
          } else {
            // Mostrar mensaje de éxito antes de enviar el formulario
            const successToast = document.createElement('div');
            successToast.className = 'toast ok';
            successToast.innerHTML = '<i class="bi bi-check-circle"></i> Registro exitoso. Redirigiendo...';
            document.body.appendChild(successToast);
            
            // Ocultar el mensaje después de 3 segundos
            setTimeout(() => {
              successToast.style.display = 'none';
            }, 3000);
          }
          
          form.classList.add('was-validated');
        }, false);
      });
    })();
  </script>
</body>
</html>
