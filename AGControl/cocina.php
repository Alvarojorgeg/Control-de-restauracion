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

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero_mesa = intval($_POST['numero_mesa']);

    $sql = "update pedido set estado = 'Listo' where numero_mesa = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $numero_mesa);

    if ($stmt->execute()) {
    } else {
        $mensaje = $stmt->error;
    }

    $stmt->close();
}

$sql = "select p.numero_mesa, c.nombre, sum(p.cantidad) as cantidad
        from pedido p
        join comida c on p.comida_id = c.cod_comida
        where p.estado = 'Cocinando'
        group by p.numero_mesa, c.nombre";
$resultado = $conn->query($sql);

$pedidos = [];

if ($resultado && $resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $pedidos[$row['numero_mesa']][] = $row;
    }
}

$conn->close();
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
            <div class="menuselec"><a href="sistema.php">Sistema</a></div>
            <div class="menu"><a href="reservas.php">Reservas</a></div>
            <div class="menu"><a href="configuracion.php">Configuracion</a></div>
            <div class="menu"><a href="miperfil.php">Mi perfil</a></div>
        </div>
        <a href="miperfil.php"><div class="nav_miperfil">
            <div class="nav_miperfil2">
                <img class="nav_perfil-imagen" src="./img/miperfil/<?php echo ($_SESSION['trabajando'] == 'Si') ? $empleado['categoria'].'on.png' : $empleado['categoria'].'off.png'; ?>" alt="">
            </div>
            <div class="nav_miperfil3">
                <div class="nav_miperfil4"><?php echo $empleado['nombre'] . ' ' . $empleado['apellidos']; ?></div>
                <div class="nav_miperfil4"><?php echo $empleado['categoria']; ?></div>
            </div>
        </div></a>
    </nav>
    <div class="base">
    <img src="./img/volver.png" class="base_cerrarpes" alt="Cerrar" onclick="window.history.back()">
        <div class="cocina_base">
            <div class="cocina_menu">
                <div class="cocina_menu_base">
                    <?php if (!empty($pedidos)): ?>
                        <?php foreach ($pedidos as $mesa => $pedido): ?>
                            <div class="cocina_mesa">
                                <h2>Mesa <?php echo htmlspecialchars($mesa); ?></h2>
                                <ul>
                                    <?php foreach ($pedido as $plato): ?>
                                        <li><?php echo htmlspecialchars($plato['nombre']); ?> - Cantidad: <?php echo htmlspecialchars($plato['cantidad']); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <form method="POST" action="">
                                    <input type="hidden" name="numero_mesa" value="<?php echo htmlspecialchars($mesa); ?>">
                                    <button type="submit" class="cocina_boton_listo">Listo</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No hay pedidos pendientes.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php if (isset($mensaje)): ?>
            <p><?php echo htmlspecialchars($mensaje); ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
