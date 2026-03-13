<?php
// esto le dice al navegador que voy a enviar json
header('Content-Type: application/json');

// aqui me conecto a la base de datos
$conexion = new mysqli("localhost", "root", "edereder", "clinica_db");
if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}

// aqui traigo solo las citas que no tienen pago asociado
$sql = "SELECT 
    c.IdCita,
    c.IdPaciente,
    c.IdMedico,
    c.FechaCita,
    c.MotivoConsulta,
    p.NombreCompleto AS NombrePaciente,
    p.CURP,
    m.NombreCompleto AS NombreMedico
FROM Control_Agenda c
INNER JOIN Control_Pacientes p ON c.IdPaciente = p.IdPaciente
INNER JOIN Control_Medicos m ON c.IdMedico = m.IdMedico
WHERE c.EstadoCita = 'Completada'
AND c.IdCita NOT IN (SELECT IdCita FROM Gestor_Pagos)
ORDER BY c.FechaCita DESC";

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
    echo json_encode(['error' => 'Error al consultar citas']);
}

$conexion->close();
