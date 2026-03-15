<?php
// Archivo para obtener lista de pacientes activos
header('Content-Type: application/json');

// Conexion a la base de datos
require_once __DIR__ . "/../mock_db.php";
$conexion = new MockMysqli();
// Control de error de conexion
if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}

// Obtener todos los pacientes activos
$sql = "SELECT IdPaciente, NombreCompleto 
        FROM Control_Pacientes 
        WHERE Estatus = 1 
        ORDER BY NombreCompleto ASC";

$resultado = $conexion->query($sql);

if ($resultado) {
    $pacientes = [];
    while ($fila = $resultado->fetch_assoc()) {
        $pacientes[] = $fila;
    }
    echo json_encode($pacientes);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener pacientes']);
}

$conexion->close();
?>
