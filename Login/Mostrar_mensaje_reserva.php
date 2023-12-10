<?php
session_start();

$mensaje = "";
if (isset($_SESSION['mensaje_pedido'])) {
    $mensaje = $_SESSION['mensaje_pedido'];
    $_SESSION['carrito'] = [];
    unset($_SESSION['mensaje_pedido']);
} else {
    header('Location: /login/Carrito/Carrito.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Pedido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container text-center">
        <h1>Confirmación de Pedido</h1>
        <?php if ($mensaje): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>
        <a href="/login/Cliente/Cliente-Interfaces/pedidos-cliente.php" class="btn btn-primary">Proceder al pago</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>