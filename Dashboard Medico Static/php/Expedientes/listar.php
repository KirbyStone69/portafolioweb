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

// aqui hago el select con joins para traer nombres de paciente y medico
$sql = "SELECT 
    e.IdExpediente, 
    e.FechaConsulta,
    e.Sintomas,
    e.Diagnostico,
    e.Tratamiento,
    e.RecetaMedica,
    e.NotasAdicionales,
    e.ProximaCita,
    p.NombreCompleto AS NombrePaciente,
    p.CURP,
    m.NombreCompleto AS NombreMedico,
    m.CedulaProfesional,
    esp.NombreEspecialidad
FROM Expediente_Clinico e
INNER JOIN Control_Pacientes p ON e.IdPaciente = p.IdPaciente
INNER JOIN Control_Medicos m ON e.IdMedico = m.IdMedico
LEFT JOIN Especialidades esp ON m.EspecialidadId = esp.IdEspecialidad
ORDER BY e.FechaConsulta DESC";

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
?>
