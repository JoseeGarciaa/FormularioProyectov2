<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
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
  <meta charset="UTF-8" />
  <title>Formulario de Inscripción</title>
  <script>
    function cargarMaterias(programaId) {
      if (!programaId) return;

      fetch(`../scripts/materias.php?programa_id=${programaId}`)
        .then(response => response.json())
        .then(data => {
          const materias = data.materias;
          for (let i = 1; i <= 7; i++) {
            const select = document.getElementById(`materia${i}`);
            select.innerHTML = '<option value="">Seleccione una materia</option>';
            materias.forEach(materia => {
              const option = document.createElement("option");
              option.value = materia;
              option.text = materia;
              select.appendChild(option);
            });
          }
        });
    }
  </script>
</head>
<body>
  <h2>Formulario de Inscripción</h2>
  <form action="../scripts/inscribir.php" method="POST">
    <label for="edad">Edad:</label>
    <input type="number" name="edad" required>

    <label for="genero">Género:</label>
    <select name="genero" required>
      <option value="">Seleccione</option>
      <option value="Masculino">Masculino</option>
      <option value="Femenino">Femenino</option>
      <option value="Otro">Otro</option>
    </select>

    <label for="numero_celular">Número de Celular:</label>
    <input type="text" name="numero_celular" required>

    <label for="programa_id">Programa Académico:</label>
    <select name="programa_id" onchange="cargarMaterias(this.value)" required>
      <option value="">Seleccione un programa</option>
      <?php foreach ($programas as $p): ?>
        <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
      <?php endforeach; ?>
    </select>

    <label for="semestre">Semestre:</label>
    <input type="number" name="semestre" min="1" max="12" required>

    <label for="jornada">Jornada:</label>
    <select name="jornada" required>
      <option value="">Seleccione</option>
      <option value="Diurna">Diurna</option>
      <option value="Nocturna">Nocturna</option>
      <option value="Mixta">Mixta</option>
    </select>

    <hr>
    <h3>Selecciona hasta 7 materias:</h3>
    <?php for ($i = 1; $i <= 7; $i++): ?>
      <label for="materia<?= $i ?>">Materia <?= $i ?>:</label>
      <select name="materia<?= $i ?>" id="materia<?= $i ?>">
        <option value="">Seleccione una materia</option>
      </select>
    <?php endfor; ?>

    <br><br>
    <input type="submit" value="Inscribirse">
  </form>
</body>
</html>
