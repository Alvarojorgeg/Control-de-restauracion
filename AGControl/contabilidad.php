<?php
session_start();

if (!isset($_SESSION['dni'])) {
    header("Location: index.php");
    exit();
}

include 'conexion.php';

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

$dni = $_SESSION['dni'];
$sql = "select * from empleados where dni = '$dni'";
$resultado = mysqli_query($conn, $sql);

if ($resultado && mysqli_num_rows($resultado) > 0) {
    $empleado = mysqli_fetch_assoc($resultado);
} else {
    header("Location: index.php");
    exit();
}

mysqli_close($conn);
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
        <a href="miperfil.php"><div class="menuimg"><img src="./img/logowhite.png" width="100px" height="100px"></div></a>
        <div class="menu"><a href="sistema.php">Sistema</a></div>
        <div class="menu"><a href="reservas.php">Reservas</a></div>
        <div class="menuselec"><a href="configuracion.php">Configuracion</a></div>
        <div class="menu"><a href="miperfil.php">Mi perfil</a></div>
        <a href="miperfil.php">
            <div class="nav_miperfil">
                <div class="nav_miperfil2">
                    <img class="nav_perfil-imagen" src="./img/miperfil/<?php echo ($_SESSION['trabajando'] == 'Si') ? $empleado['categoria'].'on.png' : $empleado['categoria'].'off.png'; ?>">
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

    <div class="config_base2">
        <div class="config_base4"><h1 class="mesa_h1">MENU DE CONTABILIDAD</h1></div>
        <div class="config_base5">
            <div class="config_base3">
                <a href="nominas.php"><div class="config_bmenu">
                    <img src="./img/c5.png" width="150px">
                    <h2 class="config_p">Nóminas</h2>
                </div></a>
                <a href="facturas.php"><div class="config_bmenu">
                    <img src="./img/c6.png" width="150px">
                    <h2 class="config_p">Facturas</h2>
                </div></a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
