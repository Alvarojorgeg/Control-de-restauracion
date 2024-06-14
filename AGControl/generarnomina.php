<?php
session_start();

if (!isset($_SESSION['dni'])) {
    header("Location: index.php");
    exit();
}

require_once __DIR__ . '/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$fecha_hoy = date("d/m/Y");

$datos_empleado = [
    'dni' => $_POST['dni'],
    'nombre' => $_POST['nombre'],
    'apellidos' => $_POST['apellidos'],
    'fechanac' => $_POST['fechanac'],
    'direccion' => $_POST['direccion'],
    'correo' => $_POST['correo'],
    'telefono' => $_POST['telefono'],
    'horario' => $_POST['horario'],
    'nacionalidad' => $_POST['nacionalidad'],
    'inicio_contrato' => $_POST['inicio_contrato'],
    'categoria' => $_POST['categoria'],
    'nomina' => $_POST['nomina'],
    'salario_base' => $_POST['salario_base'],
    'irpf' => $_POST['irpf'],
    'otros_descuentos' => $_POST['otros_descuentos'],
    'fecha_pago' => $_POST['fecha_pago'],
    'nombreempresa' => $_POST['nombreempresa'],
    'horas' => $_POST['horas'],
    'total' => ($_POST['salario_base'] + (15 * $_POST['horas'])) - ($_POST['salario_base'] * ($_POST['irpf'] / 100) + $_POST['otros_descuentos'])
];

// Paso el contenido del HTML al PDF

$html_content = "
<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>Nomina de $fecha_hoy</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .center {
            text-align: center;
        }
        .bold {
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            padding: 7px;
            border: 0.1em solid grey;
            text-align: left;
        }
        th {
            background-color: #EEEEEE;
        }
        .red {
            color: #ff0000;
        }
    </style>
</head>
<body>
    <p class='center bold'>Nomina de {$datos_empleado['nombreempresa']}</p>
    <p class='center'>Fecha de creación: {$fecha_hoy}</p>
    <hr />
    <p>&nbsp;</p>
    <table>
        <tbody>
            <tr>
                <th>DNI:</th>
                <td>{$datos_empleado['dni']}</td>
                <th>Teléfono:</th>
                <td>{$datos_empleado['telefono']}</td>
            </tr>
            <tr>
                <th>Nombre:</th>
                <td>{$datos_empleado['nombre']}</td>
                <th>Nacionalidad:</th>
                <td>{$datos_empleado['nacionalidad']}</td>
            </tr>
            <tr>
                <th>Apellidos:</th>
                <td>{$datos_empleado['apellidos']}</td>
                <th>Inicio del contrato:</th>
                <td>{$datos_empleado['inicio_contrato']}</td>
            </tr>
            <tr>
                <th>Fecha de nacimiento:</th>
                <td>{$datos_empleado['fechanac']}</td>
                <th>Categoría:</th>
                <td>{$datos_empleado['categoria']}</td>
            </tr>
            <tr>
                <th>Dirección:</th>
                <td>{$datos_empleado['direccion']}</td>
                <th>Correo electrónico:</th>
                <td>{$datos_empleado['correo']}</td>
            </tr>
        </tbody>
    </table>
    <p>&nbsp;</p>
    <hr />
    <p>&nbsp;</p>
    <table>
        <tbody>
            <tr>
                <th>Número de Nómina:</th>
                <td>{$datos_empleado['nomina']}</td>
            </tr>
            <tr>
                <th>Salario base:</th>
                <td>{$datos_empleado['salario_base']} €</td>
            </tr>
            <tr>
                <th>IRPF:</th>
                <td>{$datos_empleado['irpf']} %</td>
            </tr>
            <tr>
                <th>Otros descuentos:</th>
                <td>{$datos_empleado['otros_descuentos']} €</td>
            </tr>
            <tr>
                <th>Fecha de pago:</th>
                <td>{$datos_empleado['fecha_pago']}</td>
            </tr>
            <tr>
                <th>Número de horas:</th>
                <td>{$datos_empleado['horas']} h</td>
            </tr>
            <tr>
                <th class='red'>Total:</th>
                <td class='red'>{$datos_empleado['total']} €</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
";

// dompdf

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html_content);
$dompdf->render();

$nombre_archivo = "{$datos_empleado['dni']}.pdf";

file_put_contents('./nominas/' . $nombre_archivo, $dompdf->output());

// Cuando nos genere el pdf, nos cierra la web con este script

echo "<script>
    window.close();
</script>";
?>
