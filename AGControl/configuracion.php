<?php
session_start();

if (!isset($_SESSION['dni'])) {
    header("Location: index.php");
    exit();
}

include 'conexion.php';

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$conn) {
    die(mysqli_connect_error());
}

$dni = mysqli_real_escape_string($conn, $_SESSION['dni']);
$sql = "SELECT * FROM empleados WHERE dni = '$dni'";
$resultado = mysqli_query($conn, $sql);

if ($resultado && mysqli_num_rows($resultado) > 0) {
    $empleado = mysqli_fetch_assoc($resultado);
} else {
    header("Location: index.php");
    exit();
}

// Verificar si la categoría del empleado es "Gerente"
if ($empleado['categoria'] !== 'Gerente') {
    echo "<script>
            alert('No puedes acceder a esta web, solo puede acceder el Gerente');
            window.location.href = 'miperfil.php';
          </script>";
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
        <a href="miperfil.php"><div class="menuimg"><img src="./img/logowhite.png" width="100px" height="100px" alt=""></div></a>
        <div class="menu"><a href="sistema.php">Sistema</a></div>
        <div class="menu"><a href="reservas.php">Reservas</a></div>
        <div class="menuselec"><a href="configuracion.php">Configuracion</a></div>
        <div class="menu"><a href="miperfil.php">Mi perfil</a></div>
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

    <div class="config_base2">
        <div class="config_base4"><h1 class="mesa_h1">MENU DE CONFIGURACIÓN</h1></div>
        <div class="config_base5">
            <div class="config_base3">
                <a href="diaslibres.php"><div class="config_bmenu">
                    <img src="./img/c2.png" width="150px">
                    <h2 class="config_p">Días libres</h2>
                </div></a>
                <a href="contabilidad.php"><div class="config_bmenu">
                    <img src="./img/c1.png" width="150px">
                    <h2 class="config_p">Contabilidad</h2>
                </div></a>
                <a href="empleados.php"><div class="config_bmenu">
                    <img src="./img/c4.png" width="150px">
                    <h2 class="config_p">Empleados</h2>
                </div></a>
                <a href="menu.php"><div class="config_bmenu">
                    <img src="./img/c3.png" width="150px">
                    <h2 class="config_p">Menu</h2>
                </div></a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
