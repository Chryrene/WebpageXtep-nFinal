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

$consulta = "SELECT * FROM menu";
$resultado = mysqli_query($conex, $consulta);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['btnActualizar'])) {
        if (isset($_POST['nombre'], $_POST['descrip'], $_POST['precio'])) {
            $menu_id = $_POST['menu_id'];
            $nuevo_nombre = $_POST['nombre'];
            $nueva_descrip = $_POST['descrip'];
            $nuevo_precio = $_POST['precio'];

            $actualizar_consulta = $conex->prepare("UPDATE menu SET nombre = ?, descrip = ?, precio = ? WHERE menu_id = ?");
            $actualizar_consulta->bind_param("ssdi", $nuevo_nombre, $nueva_descrip, $nuevo_precio, $menu_id);

            if ($actualizar_consulta->execute()) {
                $_SESSION['mensaje'] = 'Menú actualizado con éxito';
                header("Location: $_SERVER[PHP_SELF]");
                exit();
            } else {
                $_SESSION['mensaje'] = 'Error al actualizar el menú: ' . $conex->error;
            }
        }
    } elseif (isset($_POST['btnAgregar'])) {
        if (isset($_POST['nombre'], $_POST['descrip'], $_POST['precio'])) {
            $nombre = $_POST['nombre'];
            $descrip = $_POST['descrip'];
            $precio = $_POST['precio'];

            $agregar_consulta = $conex->prepare("INSERT INTO menu (nombre, descrip, precio) VALUES (?, ?, ?)");
            $agregar_consulta->bind_param("ssd", $nombre, $descrip, $precio);

            if ($agregar_consulta->execute()) {
                $_SESSION['mensaje'] = 'Menú agregado con éxito';
                header("Location: $_SERVER[PHP_SELF]");
                exit();
            } else {
                $_SESSION['mensaje'] = 'Error al agregar el menú: ' . $conex->error;
            }
        }
    }
}
if (isset($_POST['btnEliminar'])) {
    $menu_id = $_POST['menu_id'];

    $eliminar_consulta = $conex->prepare("DELETE FROM menu WHERE menu_id = ?");
    $eliminar_consulta->bind_param("i", $menu_id);

    if ($eliminar_consulta->execute()) {
        $_SESSION['mensaje'] = 'Menú eliminado con éxito';
    } else {
        $_SESSION['mensaje'] = 'Error al eliminar el menú: ' . $conex->error;
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
    <title>Actualizar Menú</title>
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
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#agregarModal">Añadir Nuevo Menú</button>
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
    <h2>Menú</h2>
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
        <?php while ($menu = mysqli_fetch_assoc($resultado)): ?>
            <tr>
                <td><?php echo htmlspecialchars($menu['nombre']); ?></td>
                <td><?php echo htmlspecialchars($menu['descrip']); ?></td>
                <td><?php echo htmlspecialchars($menu['precio']); ?></td>
                <td>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#actualizarModal<?php echo $menu['menu_id']; ?>">Actualizar</button>
                    <form method="post" style="display: inline-block;">
                <input type="hidden" name="menu_id" value="<?php echo $menu['menu_id']; ?>">
                <button type="submit" class="btn btn-danger" name="btnEliminar">Eliminar</button>
            </form>
                </td>
            </tr>

            <div class="modal fade" id="actualizarModal<?php echo $menu['menu_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Actualizar Menú</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="post">
                                <input type="hidden" name="menu_id" value="<?php echo htmlspecialchars($menu['menu_id']); ?>">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($menu['nombre']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="descrip" class="form-label">Descripción</label>
                                    <textarea class="form-control" id="descrip" name="descrip"><?php echo htmlspecialchars($menu['descrip']); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="precio" class="form-label">Precio</label>
                                    <input type="text" class="form-control" id="precio" name="precio" value="<?php echo htmlspecialchars($menu['precio']); ?>">
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
                <h5 class="modal-title" id="agregarModalLabel">Agregar Nuevo Menú</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-slabel="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="descrip" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descrip" name="descrip"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio</label>
                        <input type="text" class="form-control" id="precio" name="precio" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="btnAgregar">Agregar Menú</button>
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
