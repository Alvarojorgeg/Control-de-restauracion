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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['insertar_empleado'])) {
    $conexion = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    $dni = mysqli_real_escape_string($conexion, $_POST['dni']);
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $apellidos = mysqli_real_escape_string($conexion, $_POST['apellidos']);
    $fechanac = mysqli_real_escape_string($conexion, $_POST['fechanac']);
    $direccion = mysqli_real_escape_string($conexion, $_POST['direccion']);
    $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
    $telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
    $horario = mysqli_real_escape_string($conexion, $_POST['horario']);
    $nacionalidad = mysqli_real_escape_string($conexion, $_POST['nacionalidad']);
    $inicio_contrato = mysqli_real_escape_string($conexion, $_POST['inicio_contrato']);
    $categoria = mysqli_real_escape_string($conexion, $_POST['categoria']);
    $pin = mysqli_real_escape_string($conexion, $_POST['pin']); 

    $insert_query = "insert into Empleados (dni, nombre, apellidos, fechanac, direccion, correo, telefono, horario, nacionalidad, inicio_contrato, categoria, trabajando, pin) VALUES ('$dni', '$nombre', '$apellidos', '$fechanac', '$direccion', '$correo', '$telefono', '$horario', '$nacionalidad', '$inicio_contrato', '$categoria', 'No', '$pin')";
    
    if (mysqli_query($conexion, $insert_query)) {
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        $_SESSION['mensaje'] = mysqli_error($conexion);
    }

    mysqli_close($conexion);
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
        <div class="mesa_h1"><h1>AÑADIR</h1></div>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="dni">DNI:</label>
            <input type="text" id="dni" name="dni" required><br>
            <label for="pin">PIN:</label>
            <input type="password" id="pin" name="pin" required><br>
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required><br>
            <label for="apellidos">Apellidos:</label>
            <input type="text" id="apellidos" name="apellidos" required><br>
            <label for="fechanac">Fecha de nacimiento:</label>
            <input type="date" id="fechanac" name="fechanac" required><br>
            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" required><br>
            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" required><br>
            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" required><br>
            <label for="horario">Horario:</label>
            <select id="horario" name="horario" required>
                <option value="Mañana">Mañana</option>
                <option value="Tarde">Tarde</option>
                <option value="Noche">Noche</option>
            </select><br>
            <label for="nacionalidad">Nacionalidad:</label>
            <input type="text" id="nacionalidad" name="nacionalidad" required><br>
            <label for="inicio_contrato">Inicio de contrato:</label>
            <input type="date" id="inicio_contrato" name="inicio_contrato" required><br>
            <label for="categoria">Puesto:</label>
            <select id="categoria" name="categoria" required>
                <option value="Gerente">Gerente</option>
                <option value="Jefe de cocina">Jefe de cocina</option>
                <option value="Cocinero">Cocinero</option>
                <option value="Metre">Metre</option>
                <option value="Camarero">Camarero</option>
                <option value="Limpieza">Limpieza</option>
            </select><br>
            <div class="empleado_botones">
                <input type="submit" class="boton" value="Añadir Empleado" name="insertar_empleado">
                <input type="reset" class="boton_borrar" value="Borrar datos">
            </div>
        </form>
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
