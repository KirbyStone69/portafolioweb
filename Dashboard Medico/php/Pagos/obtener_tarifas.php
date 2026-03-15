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

// aqui hago el select para traer todas las tarifas activas
$sql = "SELECT 
    t.IdTarifa, 
    t.DescripcionServicio,
    t.CostoBase,
    e.NombreEspecialidad
FROM Gestor_Tarifas t
LEFT JOIN Especialidades e ON t.EspecialidadId = e.IdEspecialidad
WHERE t.Estatus = 1
ORDER BY t.DescripcionServicio";

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
    echo json_encode(['error' => 'Error al consultar tarifas']);
}

$conexion->close();
?>
