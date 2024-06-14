<?php
session_start();

if (!isset($_SESSION['dni'])) {
    header("Location: index.php");
    exit();
}

include 'conexion.php';

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['dni']) && !empty($_POST['dni'])) {
    $dni_empleado = mysqli_real_escape_string($conn, $_POST['dni']);
    $query = "delete from empleados where dni = '$dni_empleado'";
    if (mysqli_query($conn, $query)) {
        exit();
    } else {
        echo mysqli_error($conn);
    }
}

$dni = $_SESSION['dni'];
$sql = "select * from empleados where dni = '$dni'";
$resultado = $conn->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    $empleado = $resultado->fetch_assoc();
} else {
    header("Location: error.php");
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
<nav>
    <div class="cuadradoderecha">
        <a href="miperfil.php"><div class="menuimg"><img src="./img/logowhite.png" width="100px" height="100px" alt=""></div></a>
        <div class="menu"><a href="sistema.php">Sistema</a></div>
        <div class="menu"><a href="reservas.php">Reservas</a></div>
        <div class="menuselec"><a href="configuracion.php">Configuracion</a></div>
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

    <div class="empleado_menu2">
        <div class="mesa_h1"><h1>EMPLEADO</h1></div>
        <?php
        if (isset($_GET['dni']) && !empty($_GET['dni'])) {
            $dni_empleado = $_GET['dni'];
            $query = "select * from empleados where dni = '$dni_empleado'";
            $resultado = mysqli_query($conn, $query);

            if (mysqli_num_rows($resultado) > 0) {
                $empleado = mysqli_fetch_assoc($resultado);
                echo "<div class='empleado_detalle'>";
                echo "<div class='detalle_izquierda'><p>DNI:</p></div><div class='detalle_derecha'><p>" . $empleado['dni'] . "</p></div>";
                echo "<div class='detalle_izquierda'><p>Nombre:</p></div><div class='detalle_derecha'><p>" . $empleado['nombre'] . "</p></div>";
                echo "<div class='detalle_izquierda'><p>Apellidos:</p></div><div class='detalle_derecha'><p>" . $empleado['apellidos'] . "</p></div>";
                echo "<div class='detalle_izquierda'><p>Fecha de Nacimiento:</p></div><div class='detalle_derecha'><p>" . $empleado['fechanac'] . "</p></div>";
                echo "<div class='detalle_izquierda'><p>Dirección:</p></div><div class='detalle_derecha'><p>" . $empleado['direccion'] . "</p></div>";
                echo "<div class='detalle_izquierda'><p>Correo:</p></div><div class='detalle_derecha'><p>" . $empleado['correo'] . "</p></div>";
                echo "<div class='detalle_izquierda'><p>Teléfono:</p></div><div class='detalle_derecha'><p>" . $empleado['telefono'] . "</p></div>";
                echo "<div class='detalle_izquierda'><p>Horario:</p></div><div class='detalle_derecha'><p>" . $empleado['horario'] . "</p></div>";
                echo "<div class='detalle_izquierda'><p>Nacionalidad:</p></div><div class='detalle_derecha'><p>" . $empleado['nacionalidad'] . "</p></div>";
                echo "<div class='detalle_izquierda'><p>Inicio de contrato:</p></div><div class='detalle_derecha'><p>" . $empleado['inicio_contrato'] . "</p></div>";
                echo "<div class='detalle_izquierda'><p>Puesto:</p></div><div class='detalle_derecha'><p>" . $empleado['categoria'] . "</p></div>";
                echo "<div class='detalle_izquierda'><p>Trabajando:</p></div><div class='detalle_derecha'><p>" . $empleado['trabajando'] . "</p></div>";
                echo "</div>";
            } else {
                echo "No existen empleados con ese DNI.";
            }
        } else {
            echo "El DNI no ha sido procesado.";
        }
        ?>
        <div class="empleado_botones">
            <a href="empleados.php"><button class="boton">Volver</button></a>
            <form action="" method="post" style="display:inline;">
                <input type="hidden" name="dni" value="<?php echo $dni_empleado; ?>">
                <button type="submit" class="boton">Borrar</button>
            </form><br><br>
            <a href="modificar_empleado.php?dni=<?php echo $dni_empleado; ?>"><button class="boton">Modificar</button></a>
            <a href="contabilidad.php"><button class="boton">Contabilidad</button></a>
        </div>
    </div>
    <div class="empleado_menu3">
        <div class="mesa_h1"><h1>LISTA DE EMPLEADOS</h1></div>
        <table>
            <tr>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Trabajando</th>
                <th>Puesto</th>
                <th>Acción</th>
            </tr>
            <?php
            $instruccion = "select * from empleados order by trabajando desc";
            $consulta = mysqli_query($conn, $instruccion) or die("Fallo en la consulta");
            if (mysqli_num_rows($consulta) > 0) {
                while ($resultado = mysqli_fetch_assoc($consulta)) {
                    echo "<tr>";
                    echo "<td>" . $resultado['dni'] . "</td>";
                    echo "<td>" . $resultado['nombre'] . "</td>";
                    echo "<td>" . $resultado['apellidos'] . "</td>";
                    $estado = $resultado['trabajando'];
                    $color = ($estado == "Si") ? "green" : "red";
                    echo "<td style='color: $color; font-weight: bold;'>$estado</td>";
                    echo "<td>" . $resultado['categoria'] . "</td>";
                    echo "<td><a href='detalles_empleados.php?dni=" . $resultado['dni'] . "'><button class='boton_pequeño'>Más Información</button></a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No hay empleados en esta Empresa.</td></tr>";
            }
            mysqli_close($conn);
            ?>
        </table>
    </div>
</div>
</body>
</html>
