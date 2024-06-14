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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cambiar_estado'])) {
    $id = $_POST['id'];
    $fecha = $_POST['fecha'];
    $nuevo_estado = $_POST['nuevo_estado'];
    $update_sql = "update dia_libre set concedido_sino = '$nuevo_estado' where dni = '$id' and fecha = '$fecha'";
    mysqli_query($conn, $update_sql);
    header("Location: " . $_SERVER['PHP_SELF']);
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
    <script>
        function cambiarEstado(form) {
            form.submit();
        }
    </script>
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

    <div class="reservas_menu2">
        <div class="mesa_h1"><h1>GESTIÓN DÍAS LIBRES</h1></div>
        <table>
            <tr>
                <th>DNI</th>
                <th>Nombre y apellidos</th>
                <th>Fecha</th>
                <th>Concedido</th>
                <th>Acción</th>
            </tr>
            <?php
            $instruccion = "select dia_libre.dni, empleados.nombre, empleados.apellidos, dia_libre.fecha, dia_libre.concedido_sino 
                            from dia_libre 
                            join empleados on dia_libre.dni = empleados.dni 
                            order by fecha";
            $consulta = mysqli_query($conn, $instruccion);
            if (mysqli_num_rows($consulta) > 0) {
                while ($resultado = mysqli_fetch_assoc($consulta)) {
                    echo "<tr>";
                    echo "<td>" . $resultado['dni'] . "</td>";
                    echo "<td>" . $resultado['nombre'] . "&nbsp" . $resultado['apellidos'] . "</td>";
                    echo "<td>" . $resultado['fecha'] . "</td>";
                    echo "<td>" . $resultado['concedido_sino'] . "</td>";
                    echo "<td>
                            <form method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>
                                <input type='hidden' name='id' value='" . $resultado['dni'] . "'>
                                <input type='hidden' name='fecha' value='" . $resultado['fecha'] . "'>
                                <input type='hidden' name='cambiar_estado' value='1'>
                                <select name='nuevo_estado' class='diaslibres_nuevo_estado' onchange='cambiarEstado(this.form)'>
                                    <option value='Si'" . ($resultado['concedido_sino'] == 'Si' ? " selected" : "") . ">Si</option>
                                    <option value='No'" . ($resultado['concedido_sino'] == 'No' ? " selected" : "") . ">No</option>
                                </select>
                            </form>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Los empleados no han pedido días.</td></tr>";
            }
            mysqli_close($conn);
            ?>
        </table>
    </div>
</div>

</body>
</html>
