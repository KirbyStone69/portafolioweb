<?php
// Incluir la conexion a la base de datos
include_once '../db.php';

// Consulta para obtener todos los pacientes activos
$sql = "SELECT IdPaciente, NombreCompleto 
        FROM Control_Pacientes 
        WHERE Estatus = 1
        ORDER BY NombreCompleto";

$result = $conn->query($sql);

$pacientes = array();

// Crear array con los pacientes
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pacientes[] = $row;
    }
}

// Cerrar conexion
$conn->close();

// Devolver JSON
header('Content-Type: application/json');
echo json_encode($pacientes);
?>
