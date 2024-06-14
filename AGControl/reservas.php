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
    
    if (isset($_POST['eliminar_reserva'])) {
        $cod_mesareservada = mysqli_real_escape_string($conexion, $_POST['eliminar_reserva']);
        $delete_query = "delete from mesareservada where cod_mesareservada = '$cod_mesareservada'";
        $_SESSION['mensaje'] = mysqli_query($conexion, $delete_query) ? "Reserva eliminada correctamente" : "Error al eliminar la reserva";
    } else {
        $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
        $apellidos = mysqli_real_escape_string($conexion, $_POST['apellidos']);
        $intolerancias = mysqli_real_escape_string($conexion, $_POST['intolerancias']);
        $fecha = mysqli_real_escape_string($conexion, $_POST['fecha']);
        $personas = mysqli_real_escape_string($conexion, $_POST['personas']);
        $hora = mysqli_real_escape_string($conexion, $_POST['hora']);
        
        $insert_query = "insert into mesareservada (nombre, apellidos, intolerancias, fecha, personas, hora) values ('$nombre', '$apellidos', '$intolerancias', '$fecha', '$personas', '$hora')";
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
        <div class="menuselec"><a href="reservas.php">Reservas</a></div>
        <div class="menu"><a href="configuracion.php">Configuracion</a></div>
        <div class="menu"><a href="miperfil.php">Mi perfil</a></div>
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
        <div class="mesa_h1"><h1>AÑADIR</h1></div>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required><br>
            <label for="apellidos">Apellidos:</label>
            <input type="text" id="apellidos" name="apellidos" required><br>
            <label for="intolerancias">Intolerancias:</label>
            <input type="text" id="intolerancias" name="intolerancias" required><br>
            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha" value="<?php echo $hoy?>" required min="<?php echo date('Y-m-d'); ?>"><br>
            <label for="personas">Personas:</label>
            <select id="personas" name="personas" required>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
            </select><br>
            <label for="hora">Hora:</label>
            <select id="hora" name="hora" required>
                <option value="13:00">13:00</option>
                <option value="13:30">13:30</option>
                <option value="14:00">14:00</option>
                <option value="14:30">14:30</option>
                <option value="15:00">15:00</option>
                <option value="20:00">20:00</option>
                <option value="20:30">20:30</option>
                <option value="21:00">21:00</option>
                <option value="21:30">21:30</option>
                <option value="22:00">22:00</option>
            </select>
            <br><br><input type="submit" class="boton" value="Añadir reserva">
        </form>
    </div>

    <div class="reservas_menu2">
        <div class="mesa_h1"><h1>CONSULTAR</h1></div>
        <table>
            <tr>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Intolerancias</th>
                <th>Fecha</th>
                <th>Personas</th>
                <th>Hora</th>
                <th>Acción</th>
            </tr>
            <?php
            $conexion = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
            $instruccion = "select * from mesareservada order by fecha";
            $consulta = mysqli_query($conexion, $instruccion);
            if (mysqli_num_rows($consulta) > 0) {
                while ($resultado = mysqli_fetch_assoc($consulta)) {
                    echo "<tr>";
                    echo "<td>" . $resultado['nombre'] . "</td>";
                    echo "<td>" . $resultado['apellidos'] . "</td>";
                    echo "<td>" . $resultado['intolerancias'] . "</td>";
                    echo "<td>" . $resultado['fecha'] . "</td>";
                    echo "<td>" . $resultado['personas'] . "</td>";
                    echo "<td>" . $resultado['hora'] . "</td>";
                    echo "<td><form method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' onsubmit='return confirm(\"¿Estás seguro de que deseas eliminar esta reserva?\")'><input type='hidden' name='eliminar_reserva' value='" . $resultado['cod_mesareservada'] . "'><input class='boton_borrarpequeño' type='submit' value='Eliminar'></form></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No hay reservas disponibles.</td></tr>";
            }
            mysqli_close($conexion);
            ?>
        </table>
    </div>
</div>

</body>
</html>
