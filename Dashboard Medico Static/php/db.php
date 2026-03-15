<?php
// Archivo de conexion a la base de datos falso
require_once $_SERVER['DOCUMENT_ROOT'] . '/Dashboard Medico Static/php/mock_db.php';
$conn = new MockMysqli();

// Verificar conexion
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(['error' => 'Error de conexión a la base de datos']));
}

// Establecer charset
$conn->set_charset("utf8mb4");
?>
