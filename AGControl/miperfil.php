<?php
session_start();

if (!isset($_SESSION['dni'])) {
    header("Location: index.php");
    exit();
}

include 'conexion.php';

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$dni = $_SESSION['dni'];
$sql = "select * from empleados where dni = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 's', $dni);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if ($resultado && mysqli_num_rows($resultado) > 0) {
    $empleado = mysqli_fetch_assoc($resultado);
} else {
    echo '<script>alert("Usuario no encontrado."); window.location.href = "error.php";</script>';
    exit();
}

if (isset($_POST['fichar'])) {
    $nuevo_estado = ($empleado['trabajando'] == 'Si') ? 'No' : 'Si';
    $sql_update = "update empleados set trabajando = ? where dni = ?";
    $stmt_update = mysqli_prepare($conn, $sql_update);
    mysqli_stmt_bind_param($stmt_update, 'ss', $nuevo_estado, $dni);
    if (mysqli_stmt_execute($stmt_update)) {
        $empleado['trabajando'] = $nuevo_estado;
        $_SESSION['trabajando'] = $nuevo_estado;
    } else {
        echo mysqli_error($conn);
    }
}

if (isset($_POST['cerrar_sesion'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION['trabajando'])) {
    $_SESSION['trabajando'] = $empleado['trabajando'];
}

if (isset($_POST['descargar_nomina'])) {
    $filepath = __DIR__ . "/nominas/$dni.pdf";
    if (file_exists($filepath)) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit();
    } else {
        echo '<script>alert("No se ha encontrado ninguna nómina.");</script>';
    }
}

if (isset($_POST['antiguo_pin']) && isset($_POST['nuevo_pin']) && isset($_POST['confirmar_pin'])) {
    $antiguo_pin = mysqli_real_escape_string($conn, $_POST['antiguo_pin']);
    $nuevo_pin = mysqli_real_escape_string($conn, $_POST['nuevo_pin']);
    $confirmar_pin = mysqli_real_escape_string($conn, $_POST['confirmar_pin']);

    if ($antiguo_pin === $empleado['pin']) {
        if ($nuevo_pin === $confirmar_pin) {
            $sql_update_pin = "update empleados set pin = ? where dni = ?";
            $stmt_update_pin = mysqli_prepare($conn, $sql_update_pin);
            mysqli_stmt_bind_param($stmt_update_pin, 'ss', $nuevo_pin, $dni);
            if (mysqli_stmt_execute($stmt_update_pin)) {
                echo '<script>alert("PIN actualizado correctamente");</script>';
            } else {
                echo '<script>alert("Error al actualizar el PIN: ' . mysqli_error($conn) . '");</script>';
            }
        } else {
            echo '<script>alert("Los PINs no coinciden.");</script>';
        }
    } else {
        echo '<script>alert("El antiguo PIN no es correcto.");</script>';
    }
}

mysqli_close($conn);

function calcular_edad($fecha_nacimiento) {
    $fecha_actual = date("Y-m-d");
    $edad = date_diff(date_create($fecha_nacimiento), date_create($fecha_actual));
    return $edad->format('%y años');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <title>AG CONTROL</title>
</head>
<body>

<nav>
    <div class="cuadradoderecha">
        <a href="miperfil.php"><div class="menuimg"><img src="./img/logowhite.png" width="100px" height="100px" alt=""></div></a>
        <div class="menu"><a href="sistema.php">Sistema</a></div>
        <div class="menu"><a href="reservas.php">Reservas</a></div>
        <div class="menu"><a href="configuracion.php">Configuracion</a></div>
        <div class="menuselec"><a href="miperfil.php">Mi perfil</a></div>
        <a href="miperfil.php">
            <div class="nav_miperfil">
                <div class="nav_miperfil2">
                    <img class="nav_perfil-imagen" src="./img/miperfil/<?php echo ($_SESSION['trabajando'] == 'Si') ? $empleado['categoria'].'on.png' : $empleado['categoria'].'off.png'; ?>" alt="">
                </div>
                <div class="nav_miperfil3">
                    <div class="nav_miperfil4"><?php echo $empleado['nombre'] . ' ' . $empleado['apellidos']; ?></div>
                    <div class="nav_miperfil4"><?php echo $empleado['categoria']; ?></div>
                </div>
            </div>
        </a>
    </div>
</nav>

<div class="base">
    <img src="./img/volver.png" class="base_cerrarpes" alt="Cerrar" onclick="window.history.back()">
    <div class="miperfil_base">
        <div class="mesa_h1"><h1>MI PERFIL</h1></div>
        <div class="miperfil_base2">
            <div class="miperfil_base3">
                <img src="./img/miperfil/<?php echo ($_SESSION['trabajando'] == 'Si') ? $empleado['categoria'].'on.png' : $empleado['categoria'].'off.png'; ?>" width="200px" alt=""><br>
                <p class="miperfil_h1"><?php echo $empleado['nombre'] . ' ' . $empleado['apellidos']; ?></p>
                <p class="miperfil_h1"><?php echo $empleado['categoria']; ?></p>
                <div class="miperfil_espaciobotones">
                    <form method="POST">
                        <input type="submit" class="miperfil_boton" name="fichar" value="<?php echo ($_SESSION['trabajando'] == 'Si') ? 'Salir del puesto' : 'Fichar'; ?>">
                    </form>
                    <form method="POST">
                        <input type="submit" class="miperfil_boton" name="descargar_nomina" value="Descargar mi nómina">
                    </form>
                    <form method="POST">
                        <input type="submit" class="miperfil_boton" name="cerrar_sesion" value="Cerrar sesión">
                    </form>
                    <a href="pedirdia.php"><input type="submit" class="miperfil_boton" value="Pedir día libre"></a>
                </div>
            </div>
            <div class="miperfil_base3">
                <p>Edad: <?php echo isset($empleado['fechanac']) ? calcular_edad($empleado['fechanac']) : ''; ?></p>
                <p>DNI: <?php echo $empleado['dni']; ?></p>
                <p>Fecha de nacimiento: <?php echo $empleado['fechanac']; ?></p>
                <p>Dirección: <?php echo $empleado['direccion']; ?></p>
                <p>Correo: <?php echo $empleado['correo']; ?></p>
                <p>Teléfono: <?php echo $empleado['telefono']; ?></p>
                <p>Horario: <?php echo $empleado['horario']; ?></p>
                <p>Nacionalidad: <?php echo $empleado['nacionalidad']; ?></p>
                <p>Inicio del contrato: <?php echo $empleado['inicio_contrato']; ?></p>
                <br>
                <form method="POST">
                    <label for="antiguo_pin">Antiguo PIN:</label><br>
                    <input type="password" id="antiguo_pin" name="antiguo_pin" autocomplete="current-password" required><br>
                    <label for="nuevo_pin">Nuevo PIN:</label><br>
                    <input type="password" id="nuevo_pin" name="nuevo_pin" autocomplete="new-password" required><br>
                    <label for="confirmar_pin">Confirmar nuevo PIN:</label><br>
                    <input type="password" id="confirmar_pin" name="confirmar_pin" autocomplete="new-password" required>
                    <br><br><input type="submit" class="boton" value="Cambiar PIN">
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
