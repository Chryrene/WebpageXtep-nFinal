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
    // Asegúrate de que el total se recibe correctamente
    $total = isset($_POST['total']) ? (float)$_POST['total'] : $total;
    $folio = uniqid(); 
    $estatus = 'Pendiente'; 

    $query = "INSERT INTO pedidos (folio, total, estatus) VALUES ('$folio', '$total', '$estatus')";

    if (mysqli_query($conex, $query)) {
        $_SESSION['mensaje_pedido'] = "Pedido guardado con éxito. Folio: " . $folio;
        header('Location: /login/Carrito/mostrar_mensaje.php'); 
        exit;
    } else {
        die("Error al guardar el pedido: " . mysqli_error($conex));
    }
}
?>
