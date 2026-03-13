<?php
// Archivo de conexion a la base de datos
$conn = new mysqli("localhost", "root", "edereder", "clinica_db");

// Verificar conexion
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(['error' => 'Error de conexión a la base de datos']));
}

// Establecer charset
$conn->set_charset("utf8mb4");
?>
