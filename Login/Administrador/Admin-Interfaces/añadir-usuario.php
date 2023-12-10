<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];
    $telefono = $_POST['telefono'];
    $rfc = $_POST['rfc'];
    $direccion = $_POST['direccion'];
    $correo = $_POST['correo'];

    $conexion = mysqli_connect("localhost", "root", "", "haciendaxtepen");
    if (!$conexion) {
        die("Error al conectar a la base de datos: " . mysqli_connect_error());
    }

 
    $usuario = mysqli_real_escape_string($conexion, $usuario);
    $contraseña = mysqli_real_escape_string($conexion, $contraseña);
    $telefono = mysqli_real_escape_string($conexion, $telefono);
    $rfc = mysqli_real_escape_string($conexion, $rfc);
    $direccion = mysqli_real_escape_string($conexion, $direccion);
    $correo = mysqli_real_escape_string($conexion, $correo);

    
    $contraseñaEncriptada = password_hash($contraseña, PASSWORD_DEFAULT);

    $rol = 'cliente';

    $consulta = "INSERT INTO usuarios (usuario, contraseña, teléfono, rfc, direccion, correo, rol) VALUES ('$usuario', '$contraseñaEncriptada', '$telefono', '$rfc', '$direccion', '$correo', '$rol')";
    if (mysqli_query($conexion, $consulta)) {
        echo "<div class='alert alert-success' role='alert'>Usuario registrado con éxito</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error al registrar el usuario: " . mysqli_error($conexion) . "</div>";
    }

    mysqli_close($conexion);
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Registro de Usuarios</title>

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Dashboard Usuarios</a>
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
    <div class="container">
        <h2 class="mt-5">Formulario de Registro de Usuarios</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="mt-4">
            <div class="form-group">
                <label for="usuario">Usuario:</label>
                <input type="text" class="form-control" name="usuario" required>
            </div>
            <div class="form-group">
                <label for="contraseña">Contraseña:</label>
                <input type="password" class="form-control" name="contraseña" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" class="form-control" name="telefono" required>
            </div>
            <div class="form-group">
                <label for="rfc">RFC:</label>
                <input type="text" class="form-control" name="rfc" required>
            </div>
            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" class="form-control" name="direccion" required>
            </div>
            <div class="form-group">
                <label for="correo">Correo:</label>
                <input type="email" class="form-control" name="correo" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrar</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
