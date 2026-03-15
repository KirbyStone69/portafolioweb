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

// total general de pagos pagados
$sql_total = "SELECT COALESCE(SUM(Monto), 0) as total FROM Gestor_Pagos WHERE EstatusPago = 'Pagado'";
$resultado = $conexion->query($sql_total);
$estadisticas['total_general'] = $resultado->fetch_assoc()['total'];

// ingresos del mes actual
$sql_mes = "SELECT COALESCE(SUM(Monto), 0) as total FROM Gestor_Pagos 
            WHERE EstatusPago = 'Pagado' 
            AND MONTH(FechaPago) = MONTH(CURRENT_DATE()) 
            AND YEAR(FechaPago) = YEAR(CURRENT_DATE())";
$resultado = $conexion->query($sql_mes);
$estadisticas['mes'] = $resultado->fetch_assoc()['total'];

// ingresos de la semana
$sql_semana = "SELECT COALESCE(SUM(Monto), 0) as total FROM Gestor_Pagos 
               WHERE EstatusPago = 'Pagado' 
               AND YEARWEEK(FechaPago, 1) = YEARWEEK(CURRENT_DATE(), 1)";
$resultado = $conexion->query($sql_semana);
$estadisticas['semana'] = $resultado->fetch_assoc()['total'];

// ingresos de hoy
$sql_hoy = "SELECT COALESCE(SUM(Monto), 0) as total FROM Gestor_Pagos 
            WHERE EstatusPago = 'Pagado' 
            AND DATE(FechaPago) = CURRENT_DATE()";
$resultado = $conexion->query($sql_hoy);
$estadisticas['hoy'] = $resultado->fetch_assoc()['total'];

echo json_encode($estadisticas);

$conexion->close();
