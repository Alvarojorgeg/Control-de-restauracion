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

    <div class="empleado_menu2">
        <div class="mesa_h1"><h1>EMPLEADO</h1></div>
        <?php
        if (isset($_GET['dni']) && !empty($_GET['dni'])) {
            $dni_empleado = $_GET['dni'];
            
            $conexion = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
            
            $query = "select * from empleados where dni = '$dni_empleado'";
            $resultado = mysqli_query($conexion, $query);
            
            if (mysqli_num_rows($resultado) > 0) {
                $empleado = mysqli_fetch_assoc($resultado);
            
                if (isset($_POST['modificar_empleado'])) {
                    $dni_nuevo = $_POST['dni'];
                    $nombre = $_POST['nombre'];
                    $apellidos = $_POST['apellidos'];
                    $fechanac = $_POST['fechanac'];
                    $direccion = $_POST['direccion'];
                    $correo = $_POST['correo'];
                    $telefono = $_POST['telefono'];
                    $horario = $_POST['horario'];
                    $nacionalidad = $_POST['nacionalidad'];
                    $inicio_contrato = $_POST['inicio_contrato'];
                    $categoria = $_POST['categoria'];
                    $trabajando = $_POST['trabajando'];
            
                    $query_eliminar = "delete from empleados where dni = '$dni_empleado'";
                    $resultado_eliminar = mysqli_query($conexion, $query_eliminar);
            
                    if ($resultado_eliminar) {
                        $query_insertar = "insert into Empleados (dni, nombre, apellidos, fechanac, direccion, correo, telefono, horario, nacionalidad, inicio_contrato, categoria, trabajando) values ('$dni_nuevo', '$nombre', '$apellidos', '$fechanac', '$direccion', '$correo', '$telefono', '$horario', '$nacionalidad', '$inicio_contrato', '$categoria', '$trabajando')";
                        $resultado_insertar = mysqli_query($conexion, $query_insertar);
            
                        if ($resultado_insertar) {
                            echo "Empleado modificado correctamente.";
                            header("Location: empleados.php");
                            exit();
                        } else {
                            echo mysqli_error($conexion);
                        }
                    } else {
                        echo mysqli_error($conexion);
                    }
                }
            
                echo "<form id='modificarempleado' action='".$_SERVER['PHP_SELF']."?dni=".$dni_empleado."' method='post'>";
                echo "<label for='dni'>DNI:</label><input type='text' name='dni' id='dni' value='" . $empleado['dni'] . "'><br>";
                echo "<label for='nombre'>Nombre:</label><input type='text' name='nombre' id='nombre' value='" . $empleado['nombre'] . "'><br>";
                echo "<label for='apellidos'>Apellidos:</label><input type='text' name='apellidos' id='apellidos' value='" . $empleado['apellidos'] . "'><br>";
                echo "<label for='fechanac'>Fecha de Nacimiento:</label><input type='date' name='fechanac' id='fechanac' value='" . $empleado['fechanac'] . "'><br>";
                echo "<label for='direccion'>Dirección:</label><input type='text' name='direccion' id='direccion' value='" . $empleado['direccion'] . "'><br>";
                echo "<label for='correo'>Correo:</label><input type='email' name='correo' id='correo' value='" . $empleado['correo'] . "'><br>";
                echo "<label for='telefono'>Teléfono:</label><input type='tel' name='telefono' id='telefono' value='" . $empleado['telefono'] . "'><br>";
                echo "<label for='horario'>Horario:</label><input type='text' name='horario' id='horario' value='" . $empleado['horario'] . "'><br>";
                echo "<label for='nacionalidad'>Nacionalidad:</label><input type='text' name='nacionalidad' id='nacionalidad' value='" . $empleado['nacionalidad'] . "'><br>";
                echo "<label for='inicio_contrato'>Inicio de contrato:</label><input type='date' name='inicio_contrato' id='inicio_contrato' value='" . $empleado['inicio_contrato'] . "'><br>";
                echo "<label for='categoria'>Puesto:</label><input type='text' name='categoria' id='categoria' value='" . $empleado['categoria'] . "'><br>";
                echo "<label for='trabajando'>Trabajando:</label><input type='text' name='trabajando' id='trabajando' value='" . $empleado['trabajando'] . "'><br>";
                echo "<br>";
                echo "<div style='text-align: center;'>";
                echo "<input type='submit' style='text-align: center;' name='modificar_empleado' value='Modificar' class='boton'>";
                echo "</div>";                
                echo "</form>";
            } else {
                echo "No se encontró ningún empleado con el DNI proporcionado.";
            }

            mysqli_close($conexion);
        } else {
            echo "El DNI no ha sido procesado.";
        }
        ?>
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
            $conexion = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
            $instruccion = "select * from Empleados order by trabajando desc";
            $consulta = mysqli_query($conexion, $instruccion);
            if (mysqli_num_rows($consulta) > 0) {
                while ($resultado = mysqli_fetch_assoc($consulta)) {
                    $color = $resultado['trabajando'] == "Si" ? "green" : "red";
                    echo "<tr>";
                    echo "<td>" . $resultado['dni'] . "</td>";
                    echo "<td>" . $resultado['nombre'] . "</td>";
                    echo "<td>" . $resultado['apellidos'] . "</td>";
                    echo "<td style='color: $color; font-weight: bold;'>" . $resultado['trabajando'] . "</td>";
                    echo "<td>" . $resultado['categoria'] . "</td>";
                    echo "<td><a href='detalles_empleados.php?dni=" . $resultado['dni'] . "'><button class='boton_pequeño'>Más Información</button></a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No hay empleados en esta Empresa.</td></tr>";
            }
            mysqli_close($conexion);
            ?>
        </table>
    </div>
</div>
</body>
</html>
