<?php
// esto le dice al navegador que voy a enviar json
header('Content-Type: application/json');

// aqui me conecto a la base de datos
require_once __DIR__ . "/../mock_db.php";
$conexion = new MockMysqli();
if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}

// aqui calculo las estadisticas
$estadisticas = [];

// total de reportes
$sql_total = "SELECT COUNT(*) as total FROM Reportes";
$resultado = $conexion->query($sql_total);
$estadisticas['total_reportes'] = $resultado->fetch_assoc()['total'];

// reportes medicos
$sql_medicos = "SELECT COUNT(*) as total FROM Reportes WHERE TipoReporte = 'Medico'";
$resultado = $conexion->query($sql_medicos);
$estadisticas['reportes_medicos'] = $resultado->fetch_assoc()['total'];

// reportes financieros
$sql_financieros = "SELECT COUNT(*) as total FROM Reportes WHERE TipoReporte = 'Financiero'";
$resultado = $conexion->query($sql_financieros);
$estadisticas['reportes_financieros'] = $resultado->fetch_assoc()['total'];

// reportes de citas
$sql_citas = "SELECT COUNT(*) as total FROM Reportes WHERE TipoReporte = 'Citas'";
$resultado = $conexion->query($sql_citas);
$estadisticas['reportes_citas'] = $resultado->fetch_assoc()['total'];

echo json_encode($estadisticas);

$conexion->close();
?>
