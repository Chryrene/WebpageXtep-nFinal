<?php
if (isset($_POST['usuario']) && isset($_POST['contraseña'])) {
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];

    session_start();

    $conexion = mysqli_connect("localhost", "root", "", "haciendaxtepen");

    if (!$conexion) {
        die("Error al conectar a la base de datos: " . mysqli_connect_error());
    }

    // Evitar inyecciones SQL
    $usuario = mysqli_real_escape_string($conexion, $usuario);
    $contraseña = mysqli_real_escape_string($conexion, $contraseña);

    // Consulta para verificar el usuario y la contraseña directamente
    $consulta = "SELECT roles FROM usuarios WHERE usuario='$usuario' AND contraseña='$contraseña'";
    $resultado = mysqli_query($conexion, $consulta);

    if (!$resultado) {
        die("Error en la consulta: " . mysqli_error($conexion));
    }

    $fila = mysqli_fetch_assoc($resultado);

    if ($fila) {
        $_SESSION['usuario'] = $usuario;
        $_SESSION['roles'] = $fila['roles'];

        // Redirigir según el rol
        switch ($fila['roles']) {
            case 'admin': 
                header("location: /login/Administrador/Admin.php");
                break;

            case 'cliente': 
                header("location: /login/Cliente/Cliente.php");
                break;

            default:
                echo "Rol no reconocido";
                break;
        }
    } else {
        echo "Usuario o contraseña incorrectos, favor de volver a ingresar sus credenciales";
    }

    mysqli_free_result($resultado);
    mysqli_close($conexion);
}
?>