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

$consulta = "SELECT * FROM promos";
$resultado = mysqli_query($conex, $consulta);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['btnActualizar'])) {
        $id_prom = $_POST['id_prom'];
        $nombrep = $_POST['nombrep'];
        $descripp = $_POST['descripp'];
        $preciop = $_POST['preciop'];

        $actualizar_consulta = $conex->prepare("UPDATE promos SET nombrep = ?, descripp = ?, preciop = ? WHERE id_prom = ?");
        $actualizar_consulta->bind_param("ssdi", $nombrep, $descripp, $preciop, $id_prom);

        if ($actualizar_consulta->execute()) {
            $_SESSION['mensaje'] = 'Promoción actualizada con éxito';
        } else {
            $_SESSION['mensaje'] = 'Error al actualizar la promoción: ' . $conex->error;
        }
    } elseif (isset($_POST['btnAgregar'])) {
        $nombrep = $_POST['nombrep'];
        $descripp = $_POST['descripp'];
        $preciop = $_POST['preciop'];

        $agregar_consulta = $conex->prepare("INSERT INTO promos (nombrep, descripp, preciop) VALUES (?, ?, ?)");
        $agregar_consulta->bind_param("ssd", $nombrep, $descripp, $preciop);

        if ($agregar_consulta->execute()) {
            $_SESSION['mensaje'] = 'Promoción agregada con éxito';
        } else {
            $_SESSION['mensaje'] = 'Error al agregar la promoción: ' . $conex->error;
        }
    } elseif (isset($_POST['btnEliminar'])) {
        $id_prom = $_POST['id_prom'];

        $eliminar_consulta = $conex->prepare("DELETE FROM promos WHERE id_prom = ?");
        $eliminar_consulta->bind_param("i", $id_prom);

        if ($eliminar_consulta->execute()) {
            $_SESSION['mensaje'] = 'Promoción eliminada con éxito';
        } else {
            $_SESSION['mensaje'] = 'Error al eliminar la promoción: ' . $conex->error;
        }
    }
    header("Location: $_SERVER[PHP_SELF]");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Administrador/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Administrar Promociones</title>
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
                <li class="nav-item">
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#agregarModal">Añadir Nueva Promoción</button>
                </li>
            </ul>
        </div>
    </div>
</nav>
<?php if(isset($_SESSION['mensaje'])): ?>
        <div id="mensaje" class="alert alert-info">
            <?php echo $_SESSION['mensaje']; ?>
            <?php unset($_SESSION['mensaje']); ?>
        </div>
    <?php endif; ?>

<div class="container mt-4">
    <h2>Promociones</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($promo = mysqli_fetch_assoc($resultado)): ?>
            <tr>
                <td><?php echo htmlspecialchars($promo['nombrep']); ?></td>
                <td><?php echo htmlspecialchars($promo['descripp']); ?></td>
                <td><?php echo htmlspecialchars($promo['preciop']); ?></td>
                <td>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#actualizarModal<?php echo $promo['id_prom']; ?>">Actualizar</button>
                    <form method="post" style="display: inline-block;">
                        <input type="hidden" name="id_prom" value="<?php echo $promo['id_prom']; ?>">
                        <button type="submit" class="btn btn-danger" name="btnEliminar">Eliminar</button>
                    </form>
                </td>
            </tr>
            <div class="modal fade" id="actualizarModal<?php echo $promo['id_prom']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Actualizar Promoción</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="post">
                                <input type="hidden" name="id_prom" value="<?php echo htmlspecialchars($promo['id_prom']); ?>">
                                <div class="mb-3">
                                    <label for="nombrep" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombrep" name="nombrep" value="<?php echo htmlspecialchars($promo['nombrep']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="descripp" class="form-label">Descripción</label>
                                    <textarea class="form-control" id="descripp" name="descripp"><?php echo htmlspecialchars($promo['descripp']); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="preciop" class="form-label">Precio</label>
                                    <input type="text" class="form-control" id="preciop" name="preciop" value="<?php echo htmlspecialchars($promo['preciop']); ?>">
                                </div>
                                <button type="submit" class="btn btn-primary" name="btnActualizar">Guardar Cambios</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>


<div class="modal fade" id="agregarModal" tabindex="-1" role="dialog" aria-labelledby="agregarModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregarModalLabel">Agregar Nueva Promoción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-slabel="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="mb-3">
                        <label for="nombrep" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombrep" name="nombrep" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripp" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripp" name="descripp"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="preciop" class="form-label">Precio</label>
                        <input type="text" class="form-control" id="preciop" name="preciop" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="btnAgregar">Agregar Promoción</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var mensaje = document.getElementById('mensaje');
        if (mensaje) {
            setTimeout(function() {
                mensaje.style.display = 'none';
            }, 2000); // Oculta el mensaje después de 5 segundos
        }
    });
    </script>
</body>
</html>
