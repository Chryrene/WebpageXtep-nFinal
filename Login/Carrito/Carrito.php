<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$total = 0;

if (!isset($_SESSION['carrito']) || count($_SESSION['carrito']) == 0) {
    if (basename($_SERVER['PHP_SELF']) != 'Carrito.php') {
        $_SESSION['error'] = 'Tu carrito está vacío. Agrega algunos productos antes de procesar el pedido.';
        header('Location: ver_carrito.php');
        exit;
    } else {
        $error = 'Tu carrito está vacío. Agrega algunos productos antes de continuar.';
    }
}

foreach ($_SESSION['carrito'] as $indice => $producto) {
    if (isset($producto['precio']) || isset($producto['preciob']) || isset($producto['preciop'])) {
        $precio = 0;
        $nombre = '';
        if (isset($producto['precio'])) {
            $precio = floatval(preg_replace('/[^0-9.]+/', '', $producto['precio']));
            $nombre = htmlspecialchars($producto['nombre']);
        } elseif (isset($producto['preciob'])) {
            $precio = floatval(preg_replace('/[^0-9.]+/', '', $producto['preciob']));
            $nombre = htmlspecialchars($producto['nombreb']);
        } elseif (isset($producto['preciop'])) {
            $precio = floatval(preg_replace('/[^0-9.]+/', '', $producto['preciop']));
            $nombre = htmlspecialchars($producto['nombrep']);
        }
        $subtotal = $precio * $producto['cantidad'];
        $total += $subtotal;
    } else {
        $_SESSION['error'] = 'Hay un error con uno de los productos en tu carrito.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Carrito</title>
    <link rel="stylesheet" href="CSS/stylec.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Carrito</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/login/Cliente/Cliente.php">Volver atrás</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h2 class="text-black">Carrito de Compras</h2>
    <?php if(isset($error)): ?>
        <div class="alert alert-warning">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
 


    <table class="table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['carrito'] as $indice => $producto): ?>
                <?php
                if (isset($producto['precio']) || isset($producto['preciob']) || isset($producto['preciop'])) {
                    $precio = 0;
                    $nombre = '';
                    if (isset($producto['precio'])) {
                        $precio = floatval(preg_replace('/[^0-9.]+/', '', $producto['precio']));
                        $nombre = htmlspecialchars($producto['nombre']);
                    } elseif (isset($producto['preciob'])) {
                        $precio = floatval(preg_replace('/[^0-9.]+/', '', $producto['preciob']));
                        $nombre = htmlspecialchars($producto['nombreb']);
                    } elseif (isset($producto['preciop'])) {
                        $precio = floatval(preg_replace('/[^0-9.]+/', '', $producto['preciop']));
                        $nombre = htmlspecialchars($producto['nombrep']);
                    }
                    $subtotal = $precio * $producto['cantidad'];
                    ?>
                    <tr>
                        <td><?php echo $nombre; ?></td>
                        <td>$<?php echo number_format($precio, 2); ?></td>
                        <td><?php echo $producto['cantidad']; ?></td>
                        <td>$<?php echo number_format($subtotal, 2); ?></td>
                    </tr>
                    <?php
                } else {
                    echo "<tr><td colspan='4'>Error: Producto no identificado</td></tr>";
                }
                ?>
            <?php endforeach; ?>
            <tr>
                <td colspan="3" class="text-right"><strong>Total</strong></td>
                <td>$<?php echo number_format($total, 2); ?></td>
            </tr>
        </tbody>
    </table>


    <h3 class="text-white">Ingresar datos</h3>
    <form action="/login/Carrito/Procesar_carrito.php" method="post">
    <input type="hidden" name="total" value="<?php echo htmlspecialchars($total); ?>">
    <div class="mb-3">
        <label for="nombre" class="form-label text-black">Nombre completo</label>
        <input type="text" class="form-control" id="nombre" name="nombre" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label text-black">Correo electrónico</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
        <label for="telefono" class="form-label text-black">Teléfono</label>
        <input type="text" class="form-control" id="telefono" name="telefono" required>
    </div>
    <div class="mb-3">
        <label for="rfc" class="form-label text-black">RFC</label>
        <input type="text" class="form-control" id="rfc" name="rfc" required>
    </div>
    <div class="mb-3">
        <label for="direccion" class="form-label text-black">Dirección</label>
        <input type="text" class="form-control" id="direccion" name="direccion" required>
    </div>
    <button type="submit" class="btn btn-primary">Realizar pedido</button>
</form>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>