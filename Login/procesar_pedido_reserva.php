<?php
session_start();

// Conexión a la base de datos
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'haciendaxtepen';

$conex = mysqli_connect($host, $username, $password, $database);

if (!$conex) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fecha_seleccionada = isset($_POST['fecha']) ? mysqli_real_escape_string($conex, $_POST['fecha']) : null;
    $precio = 90000; // Precio predeterminado para la reserva de la hacienda
    $folio = uniqid(); // Genera un folio único
    $estatus = 'Pendiente'; // Estado inicial del pedido

    // Iniciar transacción
    mysqli_begin_transaction($conex);

    // Insertar la reserva en la tabla pedidos
    $queryPedidos = "INSERT INTO pedidos (folio, total, estatus) VALUES ('$folio', '$precio', '$estatus')";
    $insertPedidosOk = mysqli_query($conex, $queryPedidos);

    // Insertar la fecha en la tabla fechahacienda
    $queryFechahacienda = "INSERT INTO fechahacienda (fecha, precio) VALUES ('$fecha_seleccionada', '$precio')";
    $insertFechahaciendaOk = mysqli_query($conex, $queryFechahacienda);

    // Verificar si ambas inserciones fueron exitosas
    if ($insertPedidosOk && $insertFechahaciendaOk) {
        mysqli_commit($conex);
        $_SESSION['mensaje_pedido'] = "Reserva guardada con éxito. Folio: " . $folio;
        header('Location: /login/Mostrar_mensaje_reserva.php'); // Redirige a la página de confirmación
        exit;
    } else {
        mysqli_rollback($conex);
        die("Error al guardar la reserva: " . mysqli_error($conex));
    }
}
?>
