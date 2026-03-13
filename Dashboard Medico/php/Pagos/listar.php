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

// aqui hago el select con joins para traer paciente, medico y cita
$sql = "SELECT 
    p.IdPago, 
    p.IdCita,
    p.IdPaciente,
    p.Monto, 
    p.MetodoPago,
    p.FechaPago,
    p.Referencia,
    p.EstatusPago,
    pac.NombreCompleto AS NombrePaciente,
    pac.CURP AS CURPPaciente,
    m.NombreCompleto AS NombreMedico,
    m.CedulaProfesional AS CedulaMedico,
    c.FechaCita,
    c.MotivoConsulta
FROM Gestor_Pagos p
INNER JOIN Control_Pacientes pac ON p.IdPaciente = pac.IdPaciente
INNER JOIN Control_Agenda c ON p.IdCita = c.IdCita
INNER JOIN Control_Medicos m ON c.IdMedico = m.IdMedico
ORDER BY p.FechaPago DESC";

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
