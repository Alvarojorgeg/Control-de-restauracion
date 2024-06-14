<?php
session_start();

include 'conexion.php';

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

$sql = "select * from empleados";
$result = mysqli_query($conn, $sql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pin = $_POST['pin'];
    $dni = $_POST['dni'];

    $sql = "select pin from empleados where dni='$dni'";
    $resultado = mysqli_query($conn, $sql);

    if (mysqli_num_rows($resultado) > 0) {
        $row = mysqli_fetch_assoc($resultado);
        if ($row['pin'] == $pin) {
            $_SESSION['dni'] = $dni;
            header("Location: miperfil.php");
            exit();
        } else {
            echo '<script>alert("El PIN es incorrecto. Por favor ponlo de nuevo.");</script>';
        }
    } else {
        echo '<script>alert("Usuario no encontrado.");</script>';
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

<div class="selec_base">
    <div class="selec_cuadrado">
        <div class="selec_caja1">
            <div class="selec_logo"><img src="./img/logowhite.png" alt="" width="90px"></div>
        </div>
        <div class="selec_caja1">
            <div class="selec_titulo"><h1>Selecciona tu usuario</h1></div>
        </div>
        <div class="selec_centrar">
            <div class="select_caja2">
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="selec_cajacen">';
                        echo '<div class="selec_cajaus">';
                        echo '<div class="selec_name"><b>Nombre:&nbsp;</b> ' . $row["nombre"] . '</div>';
                        echo '<div class="selec_name"><b>Puesto:&nbsp;</b> ' . $row["categoria"] . '</div>';
                        echo '<div class="selec_name"><b>Horario:&nbsp;</b> ' . $row["horario"] . '</div>';
                        echo '<form action="" method="post">';
                        echo '<div class="selec_name"><input type="password" name="pin" placeholder="PIN"></div>';
                        echo '<input type="hidden" name="dni" value="' . $row["dni"] . '">';
                        echo '<div class="selec_name2"><input type="submit" class="selec_acceder" value="Acceder"></div>';
                        echo '</form>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "No se encontraron usuarios.";
                }
                ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
