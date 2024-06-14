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
$resultado = mysqli_query($conn, $sql);

if ($resultado && mysqli_num_rows($resultado) > 0) {
    $empleado = mysqli_fetch_assoc($resultado);
} else {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conexion = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    
    if (isset($_POST['eliminar_dia_libre'])) {
        $fecha = mysqli_real_escape_string($conexion, $_POST['eliminar_dia_libre']);
        $delete_query = "delete from dia_libre where dni = '$dni' and fecha = '$fecha'";
        $_SESSION['mensaje'] = mysqli_query($conexion, $delete_query) ? "Día libre eliminado correctamente" : "Error al eliminar el día libre";
    } else {
        $fecha = mysqli_real_escape_string($conexion, $_POST['fecha']);
        $concedido_sino = 'No';
        
        $insert_query = "insert into dia_libre (dni, fecha, concedido_sino) values ('$dni', '$fecha', '$concedido_sino')";
        if (mysqli_query($conexion, $insert_query)) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $_SESSION['mensaje'] = mysqli_error($conexion);
        }
    }
    
    mysqli_close($conexion);
}

$hoy = date('Y-m-d');
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
        <div class="menu"><a href="configuracion.php">Configuración</a></div>
        <div class="menuselec"><a href="miperfil.php">Mi perfil</a></div>
        <a href="miperfil.php"><div class="nav_miperfil">
            <div class="nav_miperfil2">
                <img class="nav_perfil-imagen" src="./img/miperfil/<?php echo ($_SESSION['trabajando'] == 'Si') ? $empleado['categoria'].'on.png' : $empleado['categoria'].'off.png'; ?>" alt="">
            </div>
            <div class="nav_miperfil3">
                <div class="nav_miperfil4"><?php echo $empleado['nombre'] . ' ' . $empleado['apellidos']; ?></div>
                <div class="nav_miperfil4"><?php echo $empleado['categoria']; ?></div>
            </div>
        </div></a>
    </div>
</nav>

<div class="base">
<img src="./img/volver.png" class="base_cerrarpes" alt="Cerrar" onclick="window.history.back()">

    <div class="reservas_menu1">
        <div class="mesa_h1"><h1>AÑADIR DÍA LIBRE</h1></div>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha" value="<?php echo $hoy ?>" required min="<?php echo date('Y-m-d'); ?>"><br><br>
            <input type="submit" class="boton" value="Solicitar día libre">
        </form>
    </div>

    <div class="reservas_menu2">
        <div class="mesa_h1"><h1>MIS DÍAS LIBRES</h1></div>
        <table>
            <tr>
                <th>Fecha</th>
                <th>Concedido</th>
                <th>Acción</th>
            </tr>
            <?php
            $conexion = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
            $instruccion = "SELECT * FROM dia_libre WHERE dni = '$dni' ORDER BY fecha";
            $consulta = mysqli_query($conexion, $instruccion);
            if (mysqli_num_rows($consulta) > 0) {
                while ($resultado = mysqli_fetch_assoc($consulta)) {
                    echo "<tr>";
                    echo "<td>" . $resultado['fecha'] . "</td>";
                    echo "<td>" . $resultado['concedido_sino'] . "</td>";
                    echo "<td><form method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' onsubmit='return confirm(\"¿Estás seguro de que deseas eliminar este día libre?\")'><input type='hidden' name='eliminar_dia_libre' value='" . $resultado['fecha'] . "'><input class='boton_borrarpequeño' type='submit' value='Eliminar'></form></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No hay días libres disponibles.</td></tr>";
            }
            mysqli_close($conexion);
            ?>
        </table>
    </div>
</div>

</body>
</html>
