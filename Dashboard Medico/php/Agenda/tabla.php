<?php
// Archivo para obtener todas las citas del calendario
header('Content-Type: application/json');

// Conexion a la base de datos
$conexion = new mysqli("localhost", "root", "edereder", "clinica_db");

// Control de error de conexion
if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}

// Consulta para traer todas las citas con datos de paciente y medico
$sql = "SELECT 
            ca.IdCita,
            ca.FechaCita,
            ca.MotivoConsulta,
            ca.EstadoCita,
            ca.Observaciones,
            cp.NombreCompleto as NombrePaciente,
            cm.NombreCompleto as NombreMedico,
            ca.IdPaciente,
            ca.IdMedico
        FROM Control_Agenda ca
        INNER JOIN Control_Pacientes cp ON ca.IdPaciente = cp.IdPaciente
        INNER JOIN Control_Medicos cm ON ca.IdMedico = cm.IdMedico
        ORDER BY ca.FechaCita DESC";

$resultado = $conexion->query($sql);

// Formatear para FullCalendar
$eventos = [];
if ($resultado) {
    while ($cita = $resultado->fetch_assoc()) {
        // Definir color segun estado
        $color = '#6c757d'; // gris por defecto
        if ($cita['EstadoCita'] == 'Programada') {
            $color = '#ffc107'; // amarillo
        } elseif ($cita['EstadoCita'] == 'Completada') {
            $color = '#28a745'; // verde
        } elseif ($cita['EstadoCita'] == 'Cancelada') {
            $color = '#dc3545'; // rojo
        }
        
        $eventos[] = [
            'id' => $cita['IdCita'],
            'title' => $cita['NombrePaciente'],
            'start' => $cita['FechaCita'],
            'backgroundColor' => $color,
            'borderColor' => $color,
            'extendedProps' => [
                'idPaciente' => $cita['IdPaciente'],
                'idMedico' => $cita['IdMedico'],
                'nombreMedico' => $cita['NombreMedico'],
                'estado' => $cita['EstadoCita'],
                'motivo' => $cita['MotivoConsulta'],
                'observaciones' => $cita['Observaciones']
            ]
        ];
    }
}

echo json_encode($eventos);
$conexion->close();
?>
