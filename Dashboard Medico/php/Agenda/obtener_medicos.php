<?php
// Archivo para obtener lista de medicos activos
header('Content-Type: application/json');

// Conexion a la base de datos
$conexion = new mysqli("localhost", "root", "edereder", "clinica_db");

// Control de error de conexion
if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}

// Obtener todos los medicos activos con su especialidad
$sql = "SELECT 
            cm.IdMedico, 
            cm.NombreCompleto,
            e.NombreEspecialidad
        FROM Control_Medicos cm
        LEFT JOIN Especialidades e ON cm.EspecialidadId = e.IdEspecialidad
        WHERE cm.Estatus = 1 
        ORDER BY cm.NombreCompleto ASC";

$resultado = $conexion->query($sql);

if ($resultado) {
    $medicos = [];
    while ($fila = $resultado->fetch_assoc()) {
        $medicos[] = $fila;
    }
    echo json_encode($medicos);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener médicos']);
}

$conexion->close();
?>
