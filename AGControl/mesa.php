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
    header("Location: error.php");
    exit();
}

// AJAX

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'obtener_pedidos') {
    header('Content-Type: application/json');
    $numero_mesa = intval($_GET['mesa']);
    
    $stmt = $conn->prepare("select p.id, c.nombre, p.cantidad, c.precio from pedido p JOIN comida c ON p.comida_id = c.cod_comida where p.numero_mesa = ?");
    if (!$stmt) {
        echo json_encode(['error' => $conn->error]);
        exit();
    }
    $stmt->bind_param("i", $numero_mesa);
    $stmt->execute();
    $result = $stmt->get_result();
    $pedidos = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    echo json_encode($pedidos);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'eliminarPedido') {
    header('Content-Type: application/json');
    $idPedido = intval($_POST['id']);
    
    $stmt = $conn->prepare("delete from pedido where id = ?");
        if (!$stmt) {
        echo json_encode(['error' => $conn->error]);
        exit();
    }
    $stmt->bind_param("i", $idPedido);
    $stmt->execute();
    $stmt->close();
    
    echo json_encode(['success' => true]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] != 'GET' || !isset($_GET['action'])) {
    $stmt = $conn->prepare("select num_mesas, config_mesas from configuracion_mesas limit 1");
    if (!$stmt) {
        die($conn->error);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $num_mesas = $row['num_mesas'];
        $config_mesas = json_decode($row['config_mesas'], true);
    } else {
        $num_mesas = 1;
        $config_mesas = array_fill(1, 6, 0);
    }

    $stmt = $conn->prepare("select categoria, count(*) as count from empleados where trabajando = 'si' group by categoria");
    if (!$stmt) {
        die($conn->error);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $camareros_trabajando = 0;
    $cocineros_trabajando = 0;
    $total_trabajando = 0;

    while ($row = $result->fetch_assoc()) {
        if ($row['categoria'] == 'Camarero') {
            $camareros_trabajando = $row['count'];
        } elseif ($row['categoria'] == 'Cocinero') {
            $cocineros_trabajando = $row['count'];
        }
        $total_trabajando += $row['count'];
    }

    $stmt->close();

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['generar'])) {
        $num_mesas = isset($_POST['num_mesas']) ? intval($_POST['num_mesas']) : 0;

        if ($num_mesas > 0 && $num_mesas <= 6) {
            unset($_SESSION['config_mesas']);
            $_SESSION['config_mesas'] = array();

            for ($i = 1; $i <= $num_mesas; $i++) {
                $personas = isset($_POST["personas_mesa_$i"]) ? intval($_POST["personas_mesa_$i"]) : 0;
                $_SESSION['config_mesas'][$i] = $personas;
            }

            $config_mesas_json = json_encode($_SESSION['config_mesas']);

            $stmt = $conn->prepare("update configuracion_mesas set num_mesas=?, config_mesas=?");
            if (!$stmt) {
                die($conn->error);
            }
            $stmt->bind_param("is", $num_mesas, $config_mesas_json);
            $stmt->execute();
            $stmt->close();

            echo '<script>
                if (!sessionStorage.getItem("reloaded")) {
                    sessionStorage.setItem("reloaded", "true");
                    setTimeout(function() { window.location.reload(); }, 2000);
                }
            </script>';
        } else {
            echo "<p>El número de mesas debe estar entre 1 y 6.</p>";
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['crearPedido'])) {
        $numero_mesa = $_POST['mesa_seleccionada'];

        if (isset($_POST['platos']) && is_array($_POST['platos']) && count($_POST['platos']) > 0) {
            foreach ($_POST['platos'] as $comida_id => $cantidad) {
                if ($cantidad > 0) {
                    $stmt = $conn->prepare("insert into pedido (numero_mesa, comida_id, estado, cantidad) values (?, ?, 'Cocinando', ?)");
                    if (!$stmt) {
                        die($conn->error);
                    }
                    $stmt->bind_param("iii", $numero_mesa, $comida_id, $cantidad);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        } else {
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cobrar'])) {
        $numero_mesa = $_POST['mesa_seleccionada'];

        $stmt = $conn->prepare("select sum(cantidad * precio) as total from pedido p join comida c on p.comida_id = c.cod_comida where numero_mesa = ?");
        if (!$stmt) {
            die($conn->error);
        }
        $stmt->bind_param("i", $numero_mesa);
        $stmt->execute();
        $result = $stmt->get_result();
        $total = $result->fetch_assoc()['total'];
        $stmt->close();

        if ($total > 0) {
            $codigo_ticket = uniqid('ticket_');

            $stmt = $conn->prepare("insert into factura (codigo_ticket, total, numero_mesa) values (?, ?, ?)");
            if (!$stmt) {
                die($conn->error);
            }
            $stmt->bind_param("sdi", $codigo_ticket, $total, $numero_mesa);
            $stmt->execute();
            $stmt->close();

            $stmt = $conn->prepare("delete from pedido where numero_mesa = ?");
            if (!$stmt) {
                die($conn->error);
            }
            $stmt->bind_param("i", $numero_mesa);
            $stmt->execute();
            $stmt->close();
        } else {
        }
    }

    $stmt = $conn->prepare("select cod_comida, nombre, precio from comida");
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $platos = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $estados_pedidos = [];
    $stmt = $conn->prepare("select numero_mesa, estado from pedido");
    if (!$stmt) {
        die($conn->error);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $numero_mesa = $row['numero_mesa'];
        $estado = $row['estado'];
        if (!isset($estados_pedidos[$numero_mesa])) {
            $estados_pedidos[$numero_mesa] = [];
        }
        $estados_pedidos[$numero_mesa][] = $estado;
    }
    $stmt->close();
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
            <div class="menuimg"><img src="./img/logowhite.png" width="100px" height="100px" alt=""></div>
            <div class="menuselec"><a href="sistema.php">Sistema</a></div>
            <div class="menu"><a href="reservas.php">Reservas</a></div>
            <div class="menu"><a href="configuracion.php">Configuracion</a></div>
            <div class="menu"><a href="miperfil.php">Mi perfil</a></div>
        </div><a href="miperfil.php"><div class="nav_miperfil">
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

        <div class="mesa_menu2">
            <div class="mesa_h1"><h1>MESAS</h1></div>

            <?php
            for ($i = 1; $i <= $num_mesas; $i++) {
                $personas = $config_mesas[$i] ?? 0;
                
                if ($personas > 0) {
                    $imagen_mesa = "./img/{$personas}.png";
                    $estado_pedido = isset($estados_pedidos[$i]) ? $estados_pedidos[$i][0] : 'No atendido';
                    echo "<div class='mesa-contenedor'>";
                    echo "<img class='mesa' data-numero-mesa='$i' width='250px' src='{$imagen_mesa}' alt='Mesa {$i}' />";
                    echo "<span>{$estado_pedido}</span>";
                    echo "</div>";
                }
            }
            ?>
        </div>

        <div class="mesa_menu3">
            <div class="mesa_h1"><h1>AJUSTES</h1></div>

            <form id="ajustesForm" method="post">
                <label for="num_mesas">Número de mesas:</label>
                <select name="num_mesas" id="num_mesas" required>
                    <?php
                    for ($i = 1; $i <= 6; $i++) {
                        echo "<option value='$i' " . ($i == $num_mesas ? "selected" : "") . ">$i</option>";
                    }
                    ?>
                </select>
                <br><br>

                <?php
                for ($i = 1; $i <= 6; $i++) {
                    echo "<label for='personas_mesa_$i'>Personas en mesa $i:</label>";
                    echo "<input type='number' name='personas_mesa_$i' id='personas_mesa_$i' min='0' value='" . ($config_mesas[$i] ?? 0) . "' required><br>";
                }
                ?>

                <input type="submit" id="generarBtn" name="generar" value="Generar">
            </form>
            <div>
                <br><br><br>
                <p><b>Camareros disponibles: </b> <?php echo $camareros_trabajando; ?></p>
                <p><b>Cocineros disponibles: </b><?php echo $cocineros_trabajando; ?></p>
                <p><b>Trabajadores disponibles: </b><?php echo $total_trabajando; ?></p>
            </div>
        </div>
    </div>

    <div id="menuEmergente" class="menu-emergente">
        <form method="post">
            <input type="hidden" id="mesaSeleccionada" name="mesa_seleccionada">
            <h2>Mesa <span id="numeroMesaSeleccionada"></span></h2>
            <h3>Selecciona platos:</h3>
            <?php foreach ($platos as $plato): ?>
                <input type="number" name="platos[<?php echo $plato['cod_comida']; ?>]" min="0" value="0">
                <label><?php echo $plato['nombre']; ?> <br> $<?php echo $plato['precio']; ?></label><br>
            <?php endforeach; ?>
            <input type="submit" name="crearPedido" value="Enviar Pedido">
            <input type="submit" name="cobrar" value="Cobrar">
        </form>

        <div class="platos-pedidos">
            <h3>Platos pedidos en esta mesa:</h3>
            <ul id="listaPlatosPedidos"></ul>
        </div>
    </div>

    <script>
document.querySelectorAll('.mesa').forEach(mesa => {
    mesa.addEventListener('click', () => {
        const numeroMesa = mesa.getAttribute('data-numero-mesa');
        document.getElementById('mesaSeleccionada').value = numeroMesa;
        document.getElementById('numeroMesaSeleccionada').textContent = numeroMesa;
        document.getElementById('menuEmergente').style.display = 'block';

        fetch(`<?php echo $_SERVER['PHP_SELF']; ?>?action=obtener_pedidos&mesa=${numeroMesa}`)
            .then(response => response.json())
            .then(data => {
                const listaPlatosPedidos = document.getElementById('listaPlatosPedidos');
                listaPlatosPedidos.innerHTML = '';
                if (data.error) {
                    listaPlatosPedidos.innerHTML = `<li>${data.error}</li>`;
                } else if (data.length > 0) {
                    data.forEach(pedido => {
                        const listItem = document.createElement('li');
                        listItem.innerHTML = `${pedido.nombre} (${pedido.cantidad}) - $${pedido.precio} <img src='./img/cerrarpestana.png' width='20px' height='20px' class='cerrar-plato' data-id='${pedido.id}' />`;
                        listaPlatosPedidos.appendChild(listItem);
                    });
                } else {
                    listaPlatosPedidos.innerHTML = '<li>No hay platos pedidos para esta mesa.</li>';
                }
            })
            .catch(error => {
                const listaPlatosPedidos = document.getElementById('listaPlatosPedidos');
                listaPlatosPedidos.innerHTML = '<li>Error al obtener los pedidos.</li>';
            });
    });
});

document.getElementById('generarBtn').addEventListener('click', function() {
    const form = document.getElementById('ajustesForm');
    form.submit();
});

document.addEventListener('click', function(event) {
    const menuEmergente = document.getElementById('menuEmergente');
    const mesas = document.querySelectorAll('.mesa');

    if (!menuEmergente.contains(event.target) && ![...mesas].includes(event.target)) {
        menuEmergente.style.display = 'none';
    }
});

document.addEventListener('click', function(event) {
    if (event.target.classList.contains('cerrar-plato')) {
        const idPedido = event.target.getAttribute('data-id');

        fetch('<?php echo $_SERVER['PHP_SELF']; ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `action=eliminarPedido&id=${idPedido}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                event.target.parentElement.remove();
            } else {
                alert('Error al eliminar el plato: ' + data.error);
            }
        })
        .catch(error => {
            alert('Error al eliminar el plato.');
        });
    }
});
    </script>
</body>
</html>
