<?php
// Archivo de prueba para verificar conexión
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Conexion a la base de datos
require_once __DIR__ . "/../mock_db.php";
$conexion = new MockMysqli();
// Control de error de conexion
if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión: ' . $conexion->connect_error]);
    exit;
}

// Consulta simple
$sql = "SELECT COUNT(*) as total FROM Control_Agenda";
$resultado = $conexion->query($sql);

if ($resultado) {
    $row = $resultado->fetch_assoc();
    echo json_encode([
        'success' => true,
        'message' => 'Conexión exitosa',
        'total_citas' => $row['total']
    ]);
} else {
    echo json_encode([
        'error' => 'Error en consulta: ' . $conexion->error
    ]);
}

$conexion->close();
?>
