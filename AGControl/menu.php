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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['eliminar_comida'])) {
        $cod_comida = mysqli_real_escape_string($conn, $_POST['eliminar_comida']);
        $delete_query = "delete from comida where cod_comida = '$cod_comida'";
        if (mysqli_query($conn, $delete_query)) {
            header("Location: {$_SERVER['PHP_SELF']}");
            exit();
        } else {
        }
    } elseif (!empty($_POST['nombre']) && !empty($_POST['categoria']) && !empty($_POST['precio']) && !empty($_POST['ingredientes'])) {
        $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
        $categoria = mysqli_real_escape_string($conn, $_POST['categoria']);
        $precio = mysqli_real_escape_string($conn, $_POST['precio']);
        $ingredientes = mysqli_real_escape_string($conn, $_POST['ingredientes']);

        $ruta_imagen = '';
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $nombre_imagen = $_FILES['imagen']['name'];
            $ruta_imagen = 'menu/' . $nombre_imagen;
            move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_imagen);
        }

        $insert_query = "insert into comida (nombre, categoria, precio, ingredientes, imagen) values ('$nombre', '$categoria', '$precio', '$ingredientes', '$ruta_imagen')";

        if (mysqli_query($conn, $insert_query)) {
            header("Location: {$_SERVER['PHP_SELF']}");
            exit();
        } else {
            $_SESSION['mensaje'] = mysqli_error($conn);
        }
    }
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
        <div class="menuimg"><img src="./img/logowhite.png" width="100px" height="100px" alt=""></div>
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
        <div class="mesa_h1"><h1>AÑADIR PLATO</h1></div>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required><br>
            <label for="categoria">Categoría:</label>
            <select id="categoria" name="categoria" required>
                <option value="Entrante">Entrante</option>
                <option value="Plato principal">Plato principal</option>
                <option value="Postre">Postre</option>
                <option value="Bebida">Bebida</option>
            </select><br>
            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio" step="0.01" required><br>
            <label for="ingredientes">Ingredientes:</label>
            <input type="text" id="ingredientes" name="ingredientes" required><br>
            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" name="imagen" accept="image/*" required><br>
            <br><input type="submit" class="boton" value="Añadir">
        </form>
    </div>

    <div class="empleado_menu3">
        <div class="mesa_h1"><h1>CARTA</h1></div>
        <table>
            <tr>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Ingredientes</th>
                <th>Imagen</th>
                <th>Acción</th>
            </tr>
            <?php
            $conexion = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
            $instruccion = "select * from comida";
            $consulta = mysqli_query($conexion, $instruccion) or die("Fallo en la consulta");
            if (mysqli_num_rows($consulta) > 0) {
                while ($resultado = mysqli_fetch_assoc($consulta)) {
                    echo "<tr>";
                    echo "<td>" . $resultado['nombre'] . "</td>";
                    echo "<td>" . $resultado['categoria'] . "</td>";
                    echo "<td>" . $resultado['precio'] . " €</td>";
                    echo "<td>" . $resultado['ingredientes'] . "</td>";
                    echo "<td><img src='" . $resultado['imagen'] . "' width='50px'></td>";
                    echo "<td>
                            <form method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' onsubmit='return confirm(\"¿Estás seguro de que deseas eliminar este plato?\")'>
                                <input type='hidden' name='eliminar_comida' value='" . $resultado['cod_comida'] . "'>
                                <button class='boton_borrarpequeño' type='submit'>Eliminar</button>
                            </form>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No hay platos en la carta.</td></tr>";
            }
            mysqli_close($conexion);
            ?>
        </table>
    </div>
</div>
</body>
</html>
