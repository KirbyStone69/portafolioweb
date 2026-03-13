<?php
// Incluir la conexion a la base de datos
include_once '../db.php';

// Consulta para obtener todos los medicos activos con su especialidad
$sql = "SELECT 
    m.IdMedico,
    m.NombreCompleto,
    e.NombreEspecialidad as NombreEspecialidad
FROM Control_Medicos m
LEFT JOIN Especialidades e ON m.EspecialidadId = e.IdEspecialidad
WHERE m.Estatus = 1
ORDER BY m.NombreCompleto";

$result = $conn->query($sql);

$medicos = array();

// Crear array con los medicos
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $medicos[] = $row;
    }
}

// Cerrar conexion
$conn->close();

// Devolver JSON
header('Content-Type: application/json');
echo json_encode($medicos);
?>
