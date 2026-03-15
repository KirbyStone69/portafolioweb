<?php
// aqui inicio sesion y verifico que el usuario este logueado
session_start();
require_once '../login/verificar_sesion.php';
require_once '../login/registrar_bitacora.php';

// Archivo para actualizar una cita existente
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
$idCita = $_POST['idCita'] ?? null;
$idPaciente = $_POST['idPaciente'] ?? null;
$idMedico = $_POST['idMedico'] ?? null;
$fechaCita = $_POST['fechaCita'] ?? null;
$motivoConsulta = $_POST['motivoConsulta'] ?? '';
$estadoCita = $_POST['estadoCita'] ?? 'Programada';
$observaciones = $_POST['observaciones'] ?? '';

// Validar campos obligatorios
if (empty($idCita) || empty($idPaciente) || empty($idMedico) || empty($fechaCita)) {
    http_response_code(400);
    echo json_encode(['error' => 'Faltan datos obligatorios']);
    exit;
}

// Verificar que la cita existe
$sqlCheck = "SELECT IdCita FROM Control_Agenda WHERE IdCita = ?";
$stmtCheck = $conexion->prepare($sqlCheck);
$stmtCheck->bind_param("i", $idCita);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();
if ($resultCheck->num_rows == 0) {
    http_response_code(404);
    echo json_encode(['error' => 'La cita no existe']);
    exit;
}

// Validar que el paciente existe y esta activo
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

// Validar que el medico existe y esta activo
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

// aqui valido que la fecha no sea pasada (excepto si ya esta completada o cancelada)
if ($estadoCita !== 'Completada' && $estadoCita !== 'Cancelada') {
    $fechaCitaObj = new DateTime($fechaCita);
    $hoy = new DateTime();
    if ($fechaCitaObj < $hoy) {
        http_response_code(400);
        echo json_encode(['error' => 'No se pueden agendar citas en fechas pasadas']);
        exit;
    }
}

// aqui valido que no haya otra cita para el mismo medico a la misma hora (excluyendo esta cita)
$sqlDuplicada = "SELECT IdCita FROM Control_Agenda 
                 WHERE IdMedico = ? 
                 AND FechaCita = ? 
                 AND EstadoCita != 'Cancelada'
                 AND IdCita != ?";
$stmtDuplicada = $conexion->prepare($sqlDuplicada);
$stmtDuplicada->bind_param("isi", $idMedico, $fechaCita, $idCita);
$stmtDuplicada->execute();
$resultDuplicada = $stmtDuplicada->get_result();
if ($resultDuplicada->num_rows > 0) {
    http_response_code(400);
    echo json_encode(['error' => 'El médico ya tiene una cita agendada a esa hora']);
    exit;
}

// Actualizar la cita
$sql = "UPDATE Control_Agenda 
        SET IdPaciente = ?, 
            IdMedico = ?, 
            FechaCita = ?, 
            MotivoConsulta = ?, 
            EstadoCita = ?, 
            Observaciones = ?
        WHERE IdCita = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("iissssi", $idPaciente, $idMedico, $fechaCita, $motivoConsulta, $estadoCita, $observaciones, $idCita);

// JR: aqui obtengo los datos anteriores antes de actualizar para la bitacora
$datos_anteriores = obtener_datos_anteriores($conexion, 'Control_Agenda', 'IdCita', $idCita);

if ($stmt->execute()) {
    // JR: registro en bitacora con datos completos - antes y despues
    registrar_bitacora(
        $_SESSION['id_usuario'], 
        'Editar', 
        'Agenda', 
        'Actualizó cita #' . $idCita . ' - Paciente ID: ' . $idPaciente . ', Médico ID: ' . $idMedico,
        $idCita,
        $datos_anteriores,
        array(
            'IdPaciente' => $idPaciente,
            'IdMedico' => $idMedico,
            'FechaCita' => $fechaCita,
            'MotivoConsulta' => $motivoConsulta,
            'EstadoCita' => $estadoCita,
            'Observaciones' => $observaciones
        )
    );
    
    echo json_encode([
        'success' => true,
        'message' => 'Cita actualizada correctamente'
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Error al actualizar cita']);
}

$conexion->close();
?>
