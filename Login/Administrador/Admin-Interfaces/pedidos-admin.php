<?php
// Conexión a la base de datos
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'haciendaxtepen';

$conex = new mysqli($host, $username, $password, $database);

if ($conex->connect_error) {
    die("Connection failed: " . $conex->connect_error);
}

// Actualizar el estado del pedido a 'Pagado'
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_pedido'])) {
    $idPedido = $_POST['id_pedido'];
    $query = "UPDATE pedidos SET estatus = 'Pagado' WHERE id_pedido = $idPedido";
    $conex->query($query);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Pedidos</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Dashboard Menú</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/login/Administrador/Admin.php">Volver atrás</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-4">
    <h2>Historial de Pedidos</h2>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Folio</th>
                <th>Total</th>
                <th>Estatus</th>
                <th>Pagar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM pedidos";
            $result = $conex->query($query);
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id_pedido']}</td>
                        <td>{$row['folio']}</td>
                        <td>{$row['total']}</td>
                        <td>{$row['estatus']}</td>
                        <td>";
                if ($row['estatus'] !== 'Pagado') {
                    echo "<button class='btn btn-primary' data-toggle='modal' data-target='#paymentModal' data-id='{$row['id_pedido']}'>Pagar</button>";
                }
                echo "</td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</div>
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Pago del Pedido</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="id_pedido" id="id_pedido">
                    
                  
                    <div class="form-group">
                        <label for="numeroTarjeta">Número de Tarjeta</label>
                        <input type="text" class="form-control" id="numeroTarjeta" name="numeroTarjeta" required>
                    </div>

                    <div class="form-group">
                        <label for="fechaExpiracion">Fecha de Expiración</label>
                        <input type="text" class="form-control" id="fechaExpiracion" name="fechaExpiracion" placeholder="MM/AA" required>
                    </div>

                
                    <div class="form-group">
                        <label for="ccv">CCV</label>
                        <input type="text" class="form-control" id="ccv" name="ccv" required>
                    </div>

              
                    <div class="form-group">
                        <label for="nombreCliente">Nombre del Cliente</label>
                        <input type="text" class="form-control" id="nombreCliente" name="nombreCliente" required>
                    </div>

               
                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Pagar</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
    <script>
        $('#paymentModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var idPedido = button.data('id');
            var modal = $(this);
            modal.find('.modal-body #id_pedido').val(idPedido);
        });
    </script>
</body>
</html>