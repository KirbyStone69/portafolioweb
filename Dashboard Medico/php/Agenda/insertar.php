<?php
// aqui inicio sesion y verifico que el usuario este logueado
session_start();
require_once '../auth/verificar_sesion.php';
require_once '../auth/registrar_bitacora.php';

// Archivo para crear una nueva cita
header('Content-Type: application/json');

// Conexion a la base de datos
$conexion = new mysqli("localhost", "root", "edereder", "clinica_db");

// Control de error de conexion
if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}

// Recibir datos del formulario
$idPaciente = $_POST['idPaciente'] ?? null;
$idMedico = $_POST['idMedico'] ?? null;
$fechaCita = $_POST['fechaCita'] ?? null;
$motivoConsulta = $_POST['motivoConsulta'] ?? '';
$estadoCita = $_POST['estadoCita'] ?? 'Programada';
$observaciones = $_POST['observaciones'] ?? '';

// Validar campos obligatorios
if (empty($idPaciente) || empty($idMedico) || empty($fechaCita)) {
    http_response_code(400);
    echo json_encode(['error' => 'Faltan datos obligatorios']);
    exit;
}

// Validar que el paciente existe
$sqlPaciente = "SELECT IdPaciente FROM Control_Pacientes WHERE IdPaciente = ? AND Estatus = 1";
$stmtPaciente = $conexion->prepare($sqlPaciente);
$stmtPaciente->bind_param("i", $idPaciente);
$stmtPaciente->execute();
$resultPaciente = $stmtPaciente->get_result();
if ($resultPaciente->num_rows == 0) {
    http_response_code(400);
    echo json_encode(['error' => 'El paciente no existe o está inactivo']);
    exit;
}

// Validar que el medico existe
$sqlMedico = "SELECT IdMedico FROM Control_Medicos WHERE IdMedico = ? AND Estatus = 1";
$stmtMedico = $conexion->prepare($sqlMedico);
$stmtMedico->bind_param("i", $idMedico);
$stmtMedico->execute();
$resultMedico = $stmtMedico->get_result();
if ($resultMedico->num_rows == 0) {
    http_response_code(400);
    echo json_encode(['error' => 'El médico no existe o está inactivo']);
    exit;
}

// aqui valido que la fecha no sea pasada
$fechaCitaObj = new DateTime($fechaCita);
$hoy = new DateTime();
if ($fechaCitaObj < $hoy) {
    http_response_code(400);
    echo json_encode(['error' => 'No se pueden agendar citas en fechas pasadas']);
    exit;
}

// aqui valido que no haya otra cita para el mismo medico a la misma hora
$sqlDuplicada = "SELECT IdCita FROM Control_Agenda 
                 WHERE IdMedico = ? 
                 AND FechaCita = ? 
                 AND EstadoCita != 'Cancelada'";
$stmtDuplicada = $conexion->prepare($sqlDuplicada);
$stmtDuplicada->bind_param("is", $idMedico, $fechaCita);
$stmtDuplicada->execute();
$resultDuplicada = $stmtDuplicada->get_result();
if ($resultDuplicada->num_rows > 0) {
    http_response_code(400);
    echo json_encode(['error' => 'El médico ya tiene una cita agendada a esa hora']);
    exit;
}


// Insertar la cita
$sql = "INSERT INTO Control_Agenda (IdPaciente, IdMedico, FechaCita, MotivoConsulta, EstadoCita, Observaciones) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("iissss", $idPaciente, $idMedico, $fechaCita, $motivoConsulta, $estadoCita, $observaciones);

if ($stmt->execute()) {
    // registro en bitacora
    registrarBitacora($_SESSION['usuario_id'], 'Agendar cita', 'Agenda');
    
    echo json_encode([
        'success' => true,
        'message' => 'Cita creada correctamente',
        'id' => $conexion->insert_id
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Error al crear cita']);
}

$conexion->close();
?>
