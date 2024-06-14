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

<!-- Final del menu de navegación -->

<div class="base">
<img src="./img/volver.png" class="base_cerrarpes" alt="Cerrar" onclick="window.history.back()">
    <div class="nomina_base">
        <div class="mesa_h1"><h1>NOMINA</h1></div>
        <div class="nomina_co3">
            
        <form id="nomina_form" action="generarnomina.php" method="POST" target="_blank">

            <div class="nomina_co1">
                <?php
                    include 'conexion.php';

                    $conexion = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

                    if (!$conexion) {
                        die("Error de conexión: " . mysqli_connect_error());
                    }

                    $query = "select dni, nombre, apellidos, fechanac, direccion, correo, telefono, horario, nacionalidad, inicio_contrato, categoria, trabajando, nomina from Empleados";
                    $result = mysqli_query($conexion, $query);

                    if (!$result) {
                        die('Error en la consulta: ' . mysqli_error($conexion));
                    }

                    if (mysqli_num_rows($result) > 0) {
                        echo '<label for="select">Empleado:</label><select id="select_empleado" name="select_empleado">';
                        echo '<option value="">Seleccionar empleado</option>';

                        while ($row = mysqli_fetch_assoc($result)) {
                            $empleadoData = htmlspecialchars(json_encode($row));

                            echo '<option value="' . $row['dni'] . '" data-empleado=\'' . $empleadoData . '\'>' . $row['nombre'] . ' ' . $row['apellidos'] . '</option>';
                        }

                        echo '</select>';
                    } else {
                        echo 'No existen empleados en esta Empresa.';
                    }

                    mysqli_free_result($result);
                    mysqli_close($conexion);
                ?>
                <br><br>
                <label for="dni">DNI:</label>
                <input type="text" id="dni" name="dni" required><br>

                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required><br>

                <label for="apellidos">Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos" required><br>

                <label for="fechanac">Fecha de nacimiento:</label>
                <input type="date" id="fechanac" name="fechanac" required><br>

                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion" required><br>

                <label for="correo">Correo electrónico:</label>
                <input type="email" id="correo" name="correo" required><br>

                <label for="telefono">Teléfono:</label>
                <input type="tel" id="telefono" name="telefono" required><br>

                <label for="horario">Horario:</label>
                <input type="text" id="horario" name="horario" required><br>

                <label for="nacionalidad">Nacionalidad:</label>
                <input type="text" id="nacionalidad" name="nacionalidad" required><br>

                </div>
                <div class="nomina_co2">

                <label for="inicio_contrato">Fecha de inicio de contrato:</label>
                <input type="date" id="inicio_contrato" name="inicio_contrato" required><br>

                <label for="categoria">Categoría:</label>
                <input type="text" id="categoria" name="categoria" required><br>

                <label for="nomina">Número de nómina:</label>
                <input type="text" id="nomina" name="nomina" required><br>

                <label for="salario_base">Salario base:</label>
                <input type="number" id="salario_base" name="salario_base" required><br>

                <label for="irpf">IRPF (%):</label>
                <input type="number" id="irpf" name="irpf" value="21" required><br>

                <label for="otros_descuentos">Otros descuentos:</label>
                <input type="number" id="otros_descuentos" name="otros_descuentos" value="0" required><br>

                <label for="fecha_pago">Fecha de pago:</label>
                <input type="date" id="fecha_pago" name="fecha_pago" required><br>

                <label for="horas">Horas extra(15€/h):</label>
                <input type="number" id="horas" name="horas" required><br>

                <label for="horas">Nombre de la empresa</label>
                <input type="text" value="AG CONTROL" id="nombreempresa" name="nombreempresa" required><br>

                <br><br><input type="submit" class="boton" value="Generar nomina">
                </div>
                </form>
            </div>
        </div>
        <div class="nomina_base2">
        <div class="mesa_h1"><h1>NOMINA ACTUAL</h1></div>
        <embed id="pdf-nominas" src="" type="application/pdf" width="540px" height="550px" />

        </div>
    </div>
    <script>
    document.getElementById('select_empleado').addEventListener('change', function() {
    var selectedOption = this.options[this.selectedIndex];
    var empleadoData = JSON.parse(selectedOption.getAttribute('data-empleado'));

    document.getElementById('dni').value = empleadoData.dni;
    document.getElementById('nombre').value = empleadoData.nombre;
    document.getElementById('apellidos').value = empleadoData.apellidos;
    document.getElementById('fechanac').value = empleadoData.fechanac;
    document.getElementById('direccion').value = empleadoData.direccion;
    document.getElementById('correo').value = empleadoData.correo;
    document.getElementById('telefono').value = empleadoData.telefono;
    document.getElementById('horario').value = empleadoData.horario;
    document.getElementById('nacionalidad').value = empleadoData.nacionalidad;
    document.getElementById('inicio_contrato').value = empleadoData.inicio_contrato;
    document.getElementById('categoria').value = empleadoData.categoria;
    document.getElementById('nomina').value = empleadoData.nomina;
    var rutaPDF = "./nominas/" + empleadoData.dni + ".pdf";
    document.getElementById('pdf-nominas').setAttribute('src', rutaPDF);
});
</script>

</body>
</html>
