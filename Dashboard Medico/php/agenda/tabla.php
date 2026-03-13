<?php
// Incluir la conexion a la base de datos
include_once '../db.php';

// Array para almacenar los eventos del calendario
$eventos = array();

// Consulta para obtener todas las citas
$sql = "SELECT 
    c.IdCita,
    c.IdPaciente,
    c.IdMedico,
    c.FechaCita,
    c.MotivoConsulta,
    c.EstadoCita,
    c.Observaciones,
    CONCAT(p.NombreCompleto) as NombrePaciente,
    CONCAT(m.NombreCompleto) as NombreMedico,
    e.NombreEspecialidad as NombreEspecialidad
FROM Control_Agenda c
INNER JOIN Control_Pacientes p ON c.IdPaciente = p.IdPaciente
INNER JOIN Control_Medicos m ON c.IdMedico = m.IdMedico
LEFT JOIN Especialidades e ON m.EspecialidadId = e.IdEspecialidad
ORDER BY c.FechaCita";

$result = $conn->query($sql);

// Crear los eventos para FullCalendar
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Determinar color segun el estado
        $color = '';
        switch($row['EstadoCita']) {
            case 'Programada':
                $color = '#ffc107'; // Amarillo
                break;
            case 'Completada':
                $color = '#28a745'; // Verde
                break;
            case 'Cancelada':
                $color = '#dc3545'; // Rojo
                break;
            default:
                $color = '#007bff'; // Azul
        }
        
        // Crear evento
        $evento = array(
            'id' => $row['IdCita'],
            'title' => $row['NombrePaciente'],
            'start' => $row['FechaCita'],
            'backgroundColor' => $color,
            'borderColor' => $color,
            'extendedProps' => array(
                'idPaciente' => $row['IdPaciente'],
                'idMedico' => $row['IdMedico'],
                'nombreMedico' => $row['NombreMedico'],
                'nombreEspecialidad' => $row['NombreEspecialidad'],
                'motivo' => $row['MotivoConsulta'],
                'estado' => $row['EstadoCita'],
                'observaciones' => $row['Observaciones']
            )
        );
        
        $eventos[] = $evento;
    }
}

// Cerrar conexion
$conn->close();

// Devolver JSON
header('Content-Type: application/json');
echo json_encode($eventos);
?>
