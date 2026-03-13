<?php
// Conexion a la base de datos
include '../conexion.php';

// Obtener todas las citas para el calendario
$sql = "SELECT 
    c.IdCita,
    c.FechaHora,
    c.Motivo,
    c.Estado,
    c.Duracion,
    p.Nombre as NombrePaciente,
    p.Apellido as ApellidoPaciente,
    m.Nombre as NombreMedico,
    m.Apellido as ApellidoMedico,
    e.Nombre as Especialidad
FROM Citas c
INNER JOIN Pacientes p ON c.PacienteId = p.IdPaciente
INNER JOIN Control_Medicos m ON c.MedicoId = m.IdMedico
LEFT JOIN Especialidades e ON m.EspecialidadId = e.IdEspecialidad
ORDER BY c.FechaHora";

$resultado = $conn->query($sql);

$eventos = array();

if ($resultado->num_rows > 0) {
    while($fila = $resultado->fetch_assoc()) {
        // Crear objeto evento para FullCalendar
        $evento = array(
            'id' => $fila['IdCita'],
            'title' => $fila['NombrePaciente'] . ' ' . $fila['ApellidoPaciente'],
            'start' => $fila['FechaHora'],
            'end' => date('Y-m-d H:i:s', strtotime($fila['FechaHora'] . ' +' . $fila['Duracion'] . ' minutes')),
            'backgroundColor' => ($fila['Estado'] == 'Pendiente') ? '#007bff' : (($fila['Estado'] == 'Completada') ? '#28a745' : '#dc3545'),
            'borderColor' => ($fila['Estado'] == 'Pendiente') ? '#007bff' : (($fila['Estado'] == 'Completada') ? '#28a745' : '#dc3545'),
            'extendedProps' => array(
                'paciente' => $fila['NombrePaciente'] . ' ' . $fila['ApellidoPaciente'],
                'medico' => $fila['NombreMedico'] . ' ' . $fila['ApellidoMedico'],
                'especialidad' => $fila['Especialidad'],
                'motivo' => $fila['Motivo'],
                'estado' => $fila['Estado'],
                'duracion' => $fila['Duracion']
            )
        );
        
        array_push($eventos, $evento);
    }
}

// Devolver JSON
header('Content-Type: application/json');
echo json_encode($eventos);

$conn->close();
?>
