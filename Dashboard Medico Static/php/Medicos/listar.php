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

// aqui hago el select con join para traer el nombre de la especialidad
$sql = "SELECT 
    m.IdMedico, 
    m.NombreCompleto, 
    m.CedulaProfesional, 
    m.EspecialidadId, 
    m.Telefono,
    m.CorreoElectronico,
    m.HorarioAtencion,
    m.FechaIngreso,
    m.Estatus,
    e.NombreEspecialidad
FROM Control_Medicos m
LEFT JOIN Especialidades e ON m.EspecialidadId = e.IdEspecialidad
ORDER BY m.IdMedico DESC";

$respuesta = $conexion->query($sql);

// aqui verifico si la consulta funciono
if ($respuesta) {
    $array = [];
    // aqui meto cada fila en el array
    while ($linea = $respuesta->fetch_assoc()) {
        $array[] = $linea;
    }
    echo json_encode($array);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Error al consultar tabla']);
}

$conexion->close();
