<?php 
// esto le dice al navegador que voy a enviar json
header('Content-Type: application/json');

// aqui me conecto a la base de datos
$conexion = new mysqli("localhost", "root", "edereder", "clinica_db");
if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}

// aqui cuento el total de expedientes
$sql_total = "SELECT COUNT(*) as total FROM Expediente_Clinico";
$resultado_total = $conexion->query($sql_total);
$total = $resultado_total->fetch_assoc()['total'];

// aqui cuento los expedientes de este mes
$sql_mes = "SELECT COUNT(*) as total FROM Expediente_Clinico 
            WHERE MONTH(FechaConsulta) = MONTH(CURRENT_DATE()) 
            AND YEAR(FechaConsulta) = YEAR(CURRENT_DATE())";
$resultado_mes = $conexion->query($sql_mes);
$total_mes = $resultado_mes->fetch_assoc()['total'];

// aqui cuento los expedientes de hoy
$sql_hoy = "SELECT COUNT(*) as total FROM Expediente_Clinico 
            WHERE DATE(FechaConsulta) = CURRENT_DATE()";
$resultado_hoy = $conexion->query($sql_hoy);
$total_hoy = $resultado_hoy->fetch_assoc()['total'];

// aqui armo el json con las estadisticas
$estadisticas = [
    'total_expedientes' => $total,
    'expedientes_mes' => $total_mes,
    'expedientes_hoy' => $total_hoy
];

echo json_encode($estadisticas);

$conexion->close();
?>
