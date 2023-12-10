<?php
// calendario.php
session_start();

// Conectar a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "haciendaxtepen");

if (!$conexion) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

// Autenticación del usuario 
if (isset($_POST['usuario']) && isset($_POST['contraseña'])) {
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $contraseña = mysqli_real_escape_string($conexion, $_POST['contraseña']);

    $consulta = "SELECT * FROM usuarios WHERE usuario='$usuario' AND contraseña='$contraseña'";
    $resultado = mysqli_query($conexion, $consulta);

    if (mysqli_num_rows($resultado) > 0) {
        $_SESSION['usuario'] = $usuario;
    } else {
        die("Usuario o contraseña incorrectos.");
    }
}

if (isset($_POST['action']) && $_POST['action'] == 'check_date') {
    $fecha_seleccionada = mysqli_real_escape_string($conexion, $_POST['fecha']);
    $stmt = $conexion->prepare("SELECT * FROM fechahacienda WHERE fecha = ?");
    $stmt->bind_param("s", $fecha_seleccionada);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode(['status' => 'unavailable']);
    } else {
        echo json_encode(['status' => 'available']);
    }
    $stmt->close();
    exit;
}

if (isset($_POST['action']) && $_POST['action'] == 'save_date') {
    $fecha_seleccionada = mysqli_real_escape_string($conexion, $_POST['fecha']);
    $precioh = 90000; // Precio predeterminado

    $stmt = $conexion->prepare("INSERT INTO fechahacienda (fecha, precioh) VALUES (?, ?)");
    $stmt->bind_param("si", $fecha_seleccionada, $precioh);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al insertar la reserva.']);
    }
    $stmt->close();
    exit;
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva de Fecha para la Hacienda</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        body {
            background-color: #f4f4f4;
            font-family: 'Arial', sans-serif;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn {
            border-radius: 5px;
        }
        .flatpickr-calendar {
            border-radius: 5px;
            font-family: 'Arial', sans-serif;
        }
        .flatpickr-day.today {
            background: #007bff;
            color: white;
        }
        .flatpickr-day.selected {
            background: #28a745;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Hacienda</a>
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
<div class="container mt-5">
    <h2 class="mb-4">Selecciona la Fecha para tu Evento en la Hacienda</h2>
    <div class="row">
        <div class="col-md-6">
            <form id="fechaForm">
                <div class="mb-3">
                    <label for="fecha" class="form-label">Fecha del Evento</label>
                    <input type="text" class="form-control" id="fecha" name="fecha" required>
                </div>
                <div class="mb-3">
                    <button type="button" id="comprobarDisponibilidad" class="btn btn-primary">Comprobar Disponibilidad</button>
                    <button type="button" id="realizarReserva" class="btn btn-success">Realizar Reserva</button>
                </div>
            </form>
            <form id="pagoForm" action="procesar_pedido_reserva.php" method="POST" style="display:none;">
                <input type="hidden" id="fechaReserva" name="fecha">
                <button type="submit" class="btn btn-info">Proceder al Pago</button>
            </form>
        </div>
    </div>
    <div id="resultado" class="alert" style="display: none;"></div>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<!-- jQuery (necesario para AJAX) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
$(document).ready(function() {
    flatpickr("#fecha", {
        minDate: "today",
        dateFormat: "Y-m-d",
        "locale": {
            "firstDayOfWeek": 1 // start week on Monday
        }
    });

    $('#comprobarDisponibilidad').click(function() {
        var fechaSeleccionada = $('#fecha').val();
        $.ajax({
            url: 'Comprobar_fecha.php', // El script PHP que manejará la solicitud
            type: 'POST',
            dataType: 'json',
            data: { action: 'check_date', fecha: fechaSeleccionada },
            success: function(response) {
                $('#resultado').show();
                if(response.status === 'available') {
                    $('#resultado').addClass('alert-success').removeClass('alert-danger').text('¡Fecha disponible! Puedes proceder con la reserva.');
                    $('#realizarReserva').show();
                } else {
                    $('#resultado').addClass('alert-danger').removeClass('alert-success').text('Fecha no disponible, por favor elige otra.');
                    $('#realizarReserva').hide();
                }
            },
            error: function() {
                $('#resultado').show().addClass('alert-danger').text('Hubo un error al comprobar la fecha.');
            }
        });
    });

    $('#realizarReserva').click(function() {
        var fechaSeleccionada = $('#fecha').val();
        
        $('#realizarReserva').hide();
        $('#fechaReserva').val(fechaSeleccionada); 
        $('#pagoForm').show(); 
    });
});
</script>

</body>
</html>