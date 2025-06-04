<?php
session_start();
if (!isset($_SESSION['nombre'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bienvenido - USC</title>
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
      background: linear-gradient(90deg, #001f87, #630000);
      margin: 0;
      padding: 0;
    }

    h1 {
      color: #ffffff;
      font-size: 32px;
      background-color: rgb(0 0 0 / 0%);
      padding: 15px 30px;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 1.2);
      width: fit-content;
      margin: 20px auto 0;
      text-align: center;
      animation: fadeIn 1s ease;
    }

    .subtitulo {
      color: #ffffff;
      text-align: center;
      margin-top: 10px;
      font-size: 20px;
      animation: fadeInUp 1s ease;
    }

    .botones {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin: 30px auto;
      animation: fadeInUp 1s ease-out;
    }

    .btn {
      background-color: #ffffff22;
      color: white;
      text-decoration: none;
      padding: 12px 25px;
      border-radius: 10px;
      font-size: 16px;
      font-weight: bold;
      border: 2px solid #ffffff55;
      transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
    }

    .btn:hover {
      background-color: #ffffff55;
      color: #000;
      transform: translateY(-3px);
      box-shadow: 0 4px 10px rgba(255, 255, 255, 0.4);
    }
  </style>
</head>

<body>

  <img src="../assets/images/Banner-Universidad-Santiago-de-Cali-USC-1.png" alt="Banner USC" class="banner-img">

  <h1>Bienvenido a la Plataforma USC</h1>
  <div class="subtitulo">Hola, <strong><?php echo $_SESSION['nombre']; ?></strong> 👋</div>

  <div class="botones">
    <a href="indexform.php" class="btn">Inscribir Materias</a>
    <a href="../auth/logout.php" class="btn">Cerrar Sesión</a>
  </div>

</body>
</html>
