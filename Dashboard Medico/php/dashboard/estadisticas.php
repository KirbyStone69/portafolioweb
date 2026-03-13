<?php
// Este archivo devuelve las estadisticas generales del dashboard
header('Content-Type: application/json');

// Conexion a la base de datos
$conexion = new mysqli("localhost", "root", "edereder", "clinica_db");
if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}

// Array para almacenar las estadisticas
$estadisticas = [];

// Total de pacientes activos
$sql = "SELECT COUNT(*) as total FROM Control_Pacientes WHERE Estatus = 1";
$resultado = $conexion->query($sql);
$estadisticas['pacientes_total'] = $resultado->fetch_assoc()['total'];

// Total de medicos activos
$sql = "SELECT COUNT(*) as total FROM Control_Medicos WHERE Estatus = 1";
$resultado = $conexion->query($sql);
$estadisticas['medicos_total'] = $resultado->fetch_assoc()['total'];

// Total de citas pendientes (programadas)
$sql = "SELECT COUNT(*) as total FROM Control_Agenda WHERE EstadoCita = 'Programada'";
$resultado = $conexion->query($sql);
$estadisticas['citas_pendientes'] = $resultado->fetch_assoc()['total'];

// Total de ingresos (pagos completados)
$sql = "SELECT COALESCE(SUM(Monto), 0) as total FROM Gestor_Pagos WHERE EstatusPago = 'Pagado'";
$resultado = $conexion->query($sql);
$estadisticas['ingresos_total'] = $resultado->fetch_assoc()['total'];

// Devolver JSON
echo json_encode($estadisticas);

$conexion->close();
?>
