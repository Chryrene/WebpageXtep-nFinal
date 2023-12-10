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

$_SESSION['mensaje'] = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['btnAccion'])) {
        $nombre_producto = mysqli_real_escape_string($conex, $_POST['nombreb']);
        $descrip_producto = mysqli_real_escape_string($conex, $_POST['descripb']);
        $precio_producto = mysqli_real_escape_string($conex, $_POST['preciob']); // Corrección aquí

        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        $producto_encontrado = array_search($nombre_producto, array_column($_SESSION['carrito'], 'nombreb'));
        if ($producto_encontrado === false) {
            $_SESSION['carrito'][] = [
                'nombreb' => $nombre_producto,
                'descripb' => $descrip_producto,
                'preciob' => $precio_producto,
                'cantidad' => 1
            ];
            $_SESSION['mensaje'] = 'Producto añadido al carrito';
        } else {
            $_SESSION['carrito'][$producto_encontrado]['cantidad']++; 
            $_SESSION['mensaje'] = 'Cantidad actualizada en el carrito';
        }
    }

    if (isset($_POST['btnAccion']) && $_POST['btnAccion'] == 'elimina') {
        $nombre_producto = mysqli_real_escape_string($conex, $_POST['nombreb']);
        foreach ($_SESSION['carrito'] as $indice => $producto) {
            if ($producto['nombreb'] == $nombre_producto) {
                unset($_SESSION['carrito'][$indice]);
                $_SESSION['carrito'] = array_values($_SESSION['carrito']);
                $_SESSION['mensaje'] = 'Producto eliminado del carrito';
                break;
            }
        }
    }
}

;

$consulta = "SELECT id_banquetes, Nombreb, Descripb, Preciob FROM banquetes";
$resultado = mysqli_query($conex, $consulta);
$total_items = count($_SESSION['carrito'] ?? []);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banquetes</title>
    <link rel="stylesheet" href="CSS/stylec.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>


<nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Banquetes</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/login/Cliente/Cliente.php">Volver atrás</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/login/Cliente/Cliente.php">Carrito<span class="badge bg-secondary"><?php echo $total_items; ?></span></a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<?php if(!empty($_SESSION['mensaje'])): ?>
    <div class="alert alert-success">
        <?php echo $_SESSION['mensaje']; ?>
    </div>
<?php endif; ?>

<div class="container mt-4">
    <div class="row">
        <?php while($banquete = mysqli_fetch_assoc($resultado)): ?>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $banquete['Nombreb']; ?></h5>
                        <p class="card-text"><?php echo $banquete['Descripb']; ?></p>
                        <p class="card-text">$<?php echo $banquete['Preciob']; ?></p>
                        <form action="" method="post">
                            <input type="hidden" name="nombreb" value="<?php echo $banquete['Nombreb']; ?>">
                            <input type="hidden" name="descripb" value="<?php echo $banquete['Descripb']; ?>">
                            <input type="hidden" name="preciob" value="<?php echo $banquete['Preciob']; ?>">
                            <button class="btn btn-primary" name="btnAccion" value="agrega" type="submit">Añadir al carrito</button>
                            <button class="btn btn-danger" name="btnAccion" value="elimina" type="submit">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var mensaje = document.getElementById('mensaje');
        if (mensaje) {
            setTimeout(function() {
                mensaje.style.display = 'none';
            }, 2000); // Oculta el mensaje después de 2 segundos
        }
    });
</script>
</body>
</html>