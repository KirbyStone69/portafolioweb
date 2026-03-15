<?php
// Archivo para obtener todas las citas del calendario
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// Habilitar reporte de errores para debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // No mostrar errores en output
ini_set('log_errors', 1);

// Conexion a la base de datos
require_once __DIR__ . "/../mock_db.php";
$conexion = new MockMysqli();
// Control de error de conexion
if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión: ' . $conexion->connect_error]);
    exit;
}

// Consulta para traer todas las citas con datos de paciente, medico y especialidad
$sql = "SELECT 
            ca.IdCita,
            ca.FechaCita,
            ca.MotivoConsulta,
            ca.EstadoCita,
            ca.Observaciones,
            cp.NombreCompleto as NombrePaciente,
            cm.NombreCompleto as NombreMedico,
            e.NombreEspecialidad,
            ca.IdPaciente,
            ca.IdMedico
        FROM Control_Agenda ca
        INNER JOIN Control_Pacientes cp ON ca.IdPaciente = cp.IdPaciente
        INNER JOIN Control_Medicos cm ON ca.IdMedico = cm.IdMedico
        LEFT JOIN Especialidades e ON cm.EspecialidadId = e.IdEspecialidad
        ORDER BY ca.FechaCita DESC";

$resultado = $conexion->query($sql);

// Verificar si la consulta fue exitosa
if (!$resultado) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en consulta: ' . $conexion->error]);
    $conexion->close();
    exit;
}

// Formatear para FullCalendar
$eventos = [];
if ($resultado->num_rows > 0) {
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
                'nombreEspecialidad' => $cita['NombreEspecialidad'],
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
