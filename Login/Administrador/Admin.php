<?php
session_start();

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'haciendaxtepen';

$conex = mysqli_connect($host, $username, $password, $database);

if (!$conex) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Administrador/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Dashboard del Administrador</title>

</head>
<body>
    <div class="container my-5">
    <h1 class="text-center mb-4">Bienvenido, <?php echo ($_SESSION['usuario']); ?></h1>
        <div class="row justify-content-center">
        <div class="d-grid gap-2 col-6 mx-auto">
          <a href="Admin-Interfaces/menu-admin.php" class="btn btn-dark" role="button">Actualizar catálogos de menú</a>
          <a href="Admin-Interfaces/banquetes-admin.php" class="btn btn-dark" role="button">Actualizar catálogos de banquetes</a>
          <a href="Admin-Interfaces/promociones-admin.php" class="btn btn-dark" role="button">Actualizar catálogos de promociones</a>
          <a href="Admin-Interfaces/hacienda-admin.php" class="btn btn-dark" role="button">Alquilar hacienda</a>
          <a href="Admin-Interfaces/pedidos-admin.php" class="btn btn-dark" role="button">Visualizar historial de pedidos</a>
          <a href="Admin-Interfaces/añadir-usuario.php" class="btn btn-dark" role="button">Añadir nuevo usuario</a>
         </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
