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

$consulta = "SELECT * FROM banquetes";
$resultado = mysqli_query($conex, $consulta);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['btnActualizar'])) {
        if (isset($_POST['Nombreb'], $_POST['Descripb'], $_POST['Preciob'])) {
            $id_banquete = $_POST['id_banquete'];
            $nuevo_nombreb = $_POST['Nombreb'];
            $nueva_descripb = $_POST['Descripb'];
            $nuevo_preciob = $_POST['Preciob'];

            $actualizar_consulta = $conex->prepare("UPDATE banquetes SET Nombreb = ?, Descripb = ?, Preciob = ? WHERE id_banquetes = ?");
            $actualizar_consulta->bind_param("ssdi", $nuevo_nombreb, $nueva_descripb, $nuevo_preciob, $id_banquete);

            if ($actualizar_consulta->execute()) {
              $_SESSION['mensaje'] = 'Banquete actualizado con éxito';
              header("Location: $_SERVER[PHP_SELF]");
              exit();
          } else {
              $_SESSION['mensaje'] = 'Error al actualizar el banquete: ' . $conex->error;
          }
        }
    } elseif (isset($_POST['btnAgregar'])) {
        if (isset($_POST['Nombreb'], $_POST['Descripb'], $_POST['Preciob'])) {
            $nombreb = $_POST['Nombreb'];
            $descripb = $_POST['Descripb'];
            $preciob = $_POST['Preciob'];

            $agregar_consulta = $conex->prepare("INSERT INTO banquetes (Nombreb, Descripb, Preciob) VALUES (?, ?, ?)");
            $agregar_consulta->bind_param("ssd", $nombreb, $descripb, $preciob);

            if ($agregar_consulta->execute()) {
                $_SESSION['mensaje'] = 'Banquete agregado con éxito';
                header("Location: $_SERVER[PHP_SELF]");
                exit();
            } else {
                $_SESSION['mensaje'] = 'Error al agregar el banquete: ' . $conex->error;
            }
        }
    }
}
   if (isset($_POST['btnEliminar'])) {
        $id_banquete = $_POST['id_banquete'];

        $eliminar_consulta = $conex->prepare("DELETE FROM banquetes WHERE id_banquetes = ?");
        $eliminar_consulta->bind_param("i", $id_banquete);

        if ($eliminar_consulta->execute()) {
            $_SESSION['mensaje'] = 'Banquete eliminado con éxito';
        } else {
            $_SESSION['mensaje'] = 'Error al eliminar el banquete: ' . $conex->error;
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
    <title>Actualizar Banquete</title>
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
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#agregarModal">Añadir Nuevo Banquete</button>
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
    <h2>Banquetes</h2>
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
        <?php while ($banquete = mysqli_fetch_assoc($resultado)): ?>
            <tr>
                <td><?php echo htmlspecialchars($banquete['Nombreb']); ?></td>
                <td><?php echo htmlspecialchars($banquete['Descripb']); ?></td>
                <td><?php echo htmlspecialchars($banquete['Preciob']); ?></td>
                <td>
                    
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#actualizarModal<?php echo $banquete['id_banquetes']; ?>">Actualizar</button>
                    <form method="post" style="display: inline-block;">
                <input type="hidden" name="id_banquete" value="<?php echo $banquete['id_banquetes']; ?>">
                <button type="submit" class="btn btn-danger" name="btnEliminar">Eliminar</button>
            </form>
                </td>
            </tr>

  
            <div class="modal fade" id="actualizarModal<?php echo $banquete['id_banquetes']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Actualizar Banquete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="post">
                                <input type="hidden" name="id_banquete" value="<?php echo htmlspecialchars($banquete['id_banquetes']); ?>">
                                <div class="mb-3">
                                    <label for="nuevo_nombreb" class="form-label">Nuevo Nombre</label>
                                    <input type="text" class="form-control" id="nuevo_nombreb" name="Nombreb" value="<?php echo htmlspecialchars($banquete['Nombreb']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="nueva_descripb" class="form-label">Nueva Descripción</label>
                                    <textarea class="form-control" id="nueva_descripb" name="Descripb"><?php echo htmlspecialchars($banquete['Descripb']); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="nuevo_preciob" class="form-label">Nuevo Precio</label>
                                    <input type="text" class="form-control" id="nuevo_preciob" name="Preciob" value="<?php echo htmlspecialchars($banquete['Preciob']); ?>">
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
                <h5 class="modal-title" id="agregarModalLabel">Agregar Nuevo Banquete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-slabel="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="mb-3">
                        <label for="nombreb" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombreb" name="Nombreb" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripb" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripb" name="Descripb"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="preciob" class="form-label">Precio</label>
                        <input type="text" class="form-control" id="preciob" name="Preciob" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="btnAgregar">Agregar Banquete</button>
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