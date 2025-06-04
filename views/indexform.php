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
  <title>Formulario de Inscripción</title>
  <style>
    .banner-img {
      display: block;
      width: 100%;
      height: auto;
      object-fit: contain;
      animation: fadeIn 1s ease;
    }
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #001f87, #630000);
      margin: 0;
      padding: 0;
    }
    h1 {
      color: #ffffff;
      font-size: 32px;
      background-color: transparent;
      padding: 15px 30px;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 1.2);
      width: fit-content;
      margin: 20px auto 0;
      text-align: center;
      animation: fadeIn 1s ease;
    }
    form {
      max-width: 600px;
      margin: 30px auto;
      background: transparent;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 1.2);
      animation: fadeInUp 0.8s ease-out;
    }
    label {
      display: block;
      margin-bottom: 8px;
      color: #ffffff;
      font-weight: bold;
    }
    input[type="text"], input[type="number"], input[type="email"],
    input[type="date"], select {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border: 1px solid #cce0ff;
      border-radius: 8px;
      background-color: #f9fcff;
      transition: all 0.3s ease;
    }
    input[type="text"]:focus, input[type="number"]:focus,
    input[type="email"]:focus, input[type="date"]:focus,
    select:focus {
      border-color: #66aaff;
      box-shadow: 0 0 5px rgba(102, 170, 255, 0.6);
      outline: none;
    }
    input[type="submit"] {
      background-color: #00000087;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      width: 100%;
      font-size: 16px;
      transition: background-color 0.3s ease, transform 0.2s ease;
    }
    input[type="submit"]:hover {
      background-color: #005bb5;
      transform: scale(1.05);
    }
    select option {
      transition: background-color 0.3s ease;
    }
    select option:hover {
      background-color: #e6f3ff;
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

    .toast.ok { background-color: #28a745; }
    .toast.exists { background-color: #ffc107; color: #000; }
    .toast.error { background-color: #dc3545; }

    @keyframes fadeOut {
      0% { opacity: 1; }
      80% { opacity: 1; }
      100% { opacity: 0; display: none; }
    }
      
  </style>
 
   
  <script>
    function cargarMaterias(programaId) {
      if (!programaId) return;
      fetch(`../controllers/materias.php?programa_id=${programaId}`)
        .then(response => response.json())
        .then(data => {
          for (let i = 1; i <= 7; i++) {
            const materiaSelect = document.getElementById('materia' + i);
            materiaSelect.innerHTML = '<option value="">Seleccione una materia</option>';
            data.materias.forEach(materia => {
              const option = document.createElement('option');
              option.value = materia;
              option.text = materia;
              materiaSelect.appendChild(option);
            });
          }
        });
    }
  </script>
</head>
<body>
  <img src="../assets/images/Banner-Universidad-Santiago-de-Cali-USC-1.png" alt="Banner USC" class="banner-img">

<?php if (isset($_GET['status'])): ?>
  <div class="toast <?= htmlspecialchars($_GET['status']) ?>">
    <?php
      switch ($_GET['status']) {
        case 'ok':
          echo '✅ Inscripción realizada correctamente.';
          break;
        case 'exists':
          echo '⚠️ Ya existe una inscripción para este semestre.';
          break;
        case 'error':
          echo '❌ Ocurrió un error al inscribir.';
          break;
      }
    ?>
  </div>
<?php endif; ?>

    
  <h1>Formulario de Inscripción USC</h1>
  <form method="POST" action="../controllers/inscripcion.php">
    <label>Edad:</label>
    <input type="number" name="edad" required>

    <label>Género:</label>
    <select name="genero" required>
      <option value="">Seleccione</option>
      <option value="Masculino">Masculino</option>
      <option value="Femenino">Femenino</option>
      <option value="Otro">Otro</option>
    </select>

    <label>Número Celular:</label>
    <input type="text" name="numero_celular" required>

    <label>Programa Académico:</label>
    <select name="programa_id" onchange="cargarMaterias(this.value)" required>
      <option value="">Seleccione un programa</option>
      <?php foreach ($programas as $p): ?>
        <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
      <?php endforeach; ?>
    </select>

    <label>Semestre:</label>
    <input type="number" name="semestre" min="1" max="12" required>

    <label>Jornada:</label>
    <select name="jornada" required>
      <option value="">Seleccione</option>
      <option value="Diurna">Diurna</option>
      <option value="Nocturna">Nocturna</option>
      <option value="Mixta">Mixta</option>
    </select>

    <?php for ($i = 1; $i <= 7; $i++): ?>
      <label for="materia<?= $i ?>">Materia <?= $i ?>:</label>
      <select name="materia<?= $i ?>" id="materia<?= $i ?>">
        <option value="">Seleccione una materia</option>
      </select>
    <?php endfor; ?>

    <input type="submit" value="Inscribirse">
  </form>
</body>
</html>
