<?php
// aqui inicio sesion y verifico que el usuario este logueado
session_start();
require_once '../auth/verificar_sesion.php';
require_once '../auth/registrar_bitacora.php';

// Incluir la conexion a la base de datos
include_once '../db.php';

// Recibir datos del formulario
$idPaciente = $_POST['idPaciente'];
$idMedico = $_POST['idMedico'];
$fechaCita = $_POST['fechaCita'];
$motivoConsulta = $_POST['motivoConsulta'];
$estadoCita = $_POST['estadoCita'];
$observaciones = $_POST['observaciones'];

// Validar datos obligatorios
if (empty($idPaciente) || empty($idMedico) || empty($fechaCita) || empty($estadoCita)) {
    echo json_encode(array('success' => false, 'error' => 'Faltan datos obligatorios'));
    exit;
}

// Validar que la fecha no sea pasada
$fechaCitaTimestamp = strtotime($fechaCita);
$fechaActual = time();

if ($fechaCitaTimestamp < $fechaActual) {
    echo json_encode(array('success' => false, 'error' => 'No se pueden agendar citas en fechas pasadas'));
    exit;
}

// Validar que no sea fin de semana
$diaSemana = date('N', $fechaCitaTimestamp);
if ($diaSemana > 5) {
    echo json_encode(array('success' => false, 'error' => 'No se pueden agendar citas los fines de semana'));
    exit;
}

// Verificar que no haya conflictos de horario con el medico
$sqlVerificar = "SELECT COUNT(*) as total FROM Control_Agenda 
                 WHERE IdMedico = ? 
                 AND FechaCita = ? 
                 AND EstadoCita != 'Cancelada'";
$stmtVerificar = $conn->prepare($sqlVerificar);
$stmtVerificar->bind_param("is", $idMedico, $fechaCita);
$stmtVerificar->execute();
    // registro en bitacora
    registrarBitacora($_SESSION['usuario_id'], 'Agendar cita', 'Agenda');
    
$resultVerificar = $stmtVerificar->get_result();
$verificacion = $resultVerificar->fetch_assoc();

if ($verificacion['total'] > 0) {
    echo json_encode(array('success' => false, 'error' => 'El medico ya tiene una cita agendada a esa hora'));
    exit;
}

// Verificar horarios del medico
$sqlHorario = "SELECT HorarioAtencion FROM Control_Medicos WHERE IdMedico = ?";
$stmtHorario = $conn->prepare($sqlHorario);
$stmtHorario->bind_param("i", $idMedico);
$stmtHorario->execute();
$resultHorario = $stmtHorario->get_result();
$medico = $resultHorario->fetch_assoc();

if ($medico && $medico['HorarioAtencion']) {
    $horarios = json_decode($medico['HorarioAtencion'], true);
    $diasSemana = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes'];
    $diaActual = $diasSemana[$diaSemana - 1];
    
    if (!isset($horarios[$diaActual]) || !$horarios[$diaActual]['activo']) {
        echo json_encode(array('success' => false, 'error' => 'El medico no atiende los ' . $diaActual));
        exit;
    }
}

// Preparar la consulta SQL
$sql = "INSERT INTO Control_Agenda (IdPaciente, IdMedico, FechaCita, MotivoConsulta, EstadoCita, Observaciones) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iissss", $idPaciente, $idMedico, $fechaCita, $motivoConsulta, $estadoCita, $observaciones);

// Ejecutar la consulta
if ($stmt->execute()) {
    // registro en bitacora
    registrarBitacora($_SESSION['usuario_id'], 'Agendar cita', 'Agenda');
    
    echo json_encode(array('success' => true, 'message' => 'Cita creada exitosamente'));
} else {
    echo json_encode(array('success' => false, 'error' => 'Error al crear la cita: ' . $conn->error));
}

// Cerrar conexion
$stmt->close();
$conn->close();
?>
