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

// aqui hago el select de medicos activos
$sql = "SELECT IdMedico, NombreCompleto, CedulaProfesional FROM Control_Medicos WHERE Estatus = 1 ORDER BY NombreCompleto";

$respuesta = $conexion->query($sql);

// aqui verifico si funciono
if ($respuesta) {
    $array = [];
    while ($linea = $respuesta->fetch_assoc()) {
        $array[] = $linea;
    }
    echo json_encode($array);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Error al consultar médicos']);
}

$conexion->close();
?>
