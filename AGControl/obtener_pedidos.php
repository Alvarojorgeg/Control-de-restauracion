<?php
include 'conexion.php';

$mesa = intval($_GET['mesa']);

if (!$conn) {
    die(json_encode(['error' => 'Conexión fallida: ' . mysqli_connect_error()]));
}

$stmt = $conn->prepare("select c.nombre, c.precio, p.cantidad 
                        from comida c 
                        inner join pedido p on c.cod_comida = p.comida_id 
                        where p.numero_mesa = ? and p.estado = 'cocinando'");
if (!$stmt) {
    die(json_encode(['error' => $conn->error]));
}

$stmt->bind_param("i", $mesa);

if (!$stmt->execute()) {
    die(json_encode(['error' => $stmt->error]));
}

$result = $stmt->get_result();
if (!$result) {
    die(json_encode(['error' => $stmt->error]));
}

$pedidos = [];
while ($row = $result->fetch_assoc()) {
    $pedidos[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($pedidos);
?>
