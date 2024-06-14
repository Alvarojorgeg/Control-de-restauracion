<?php
session_start();

if (!isset($_SESSION['dni'])) {
    header("Location: index.php");
    exit();
}

include 'conexion.php';

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

$dni = $_SESSION['dni'];
$sql = "select * from empleados where dni = '$dni'";
$resultado = $conn->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    $empleado = $resultado->fetch_assoc();
} else {
    header("Location: index.php");
    exit();
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

<!-- Menu de navegación -->

<nav>
    <div class="cuadradoderecha">
        <a href="miperfil.php"><div class="menuimg"><img src="./img/logowhite.png" width="100px" height="100px"></div></a>
        <div class="menu"><a href="sistema.php">Sistema</a></div>
        <div class="menu"><a href="reservas.php">Reservas</a></div>
        <div class="menuselec"><a href="configuracion.php">Configuracion</a></div>
        <div class="menu"><a href="miperfil.php">Mi perfil</a></div>
        <a href="miperfil.php"><div class="nav_miperfil">
            <div class="nav_miperfil2">
                <img class="nav_perfil-imagen" src="./img/miperfil/<?php echo ($_SESSION['trabajando'] == 'Si') ? $empleado['categoria'].'on.png' : $empleado['categoria'].'off.png'; ?>">
            </div>
            <div class="nav_miperfil3">
                <div class="nav_miperfil4"><?php echo $empleado['nombre'] . ' ' . $empleado['apellidos']; ?></div>
                <div class="nav_miperfil4"><?php echo $empleado['categoria']; ?></div>
            </div>
        </div></a>
    </div>
</nav>

<!-- Final del menu de navegación -->

<div class="base">
<img src="./img/volver.png" class="base_cerrarpes" alt="Cerrar" onclick="window.history.back()">

    <div class="config_base2">
        <div class="config_base4"><h1 class="mesa_h1">FACTURAS</h1></div>
        <div class="config_base5">
            <div class="config_base3conbarra">
                <?php
                $sql_facturas = "SELECT * FROM factura";
                $resultado_facturas = $conn->query($sql_facturas);

                if ($resultado_facturas && $resultado_facturas->num_rows > 0) {
                    echo '<table class="facturas-tabla">';
                    echo '<tr><th>Código Ticket</th><th>Fecha</th><th>Total</th><th>Número de Mesa</th></tr>';
                    while($factura = $resultado_facturas->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $factura['codigo_ticket'] . '</td>';
                        echo '<td>' . $factura['fecha'] . '</td>';
                        echo '<td>' . $factura['total'] . ' €</td>';
                        echo '<td>' . $factura['numero_mesa'] . '</td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                } else {
                    echo '<p>No hay facturas disponibles.</p>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>

<?php
$conn->close();
?>
