<?php
// Incluir la conexion a la base de datos
include_once '../db.php';

// Recibir datos del formulario
$idCita = $_POST['idCita'];
$idPaciente = $_POST['idPaciente'];
$idMedico = $_POST['idMedico'];
$fechaCita = $_POST['fechaCita'];
$motivoConsulta = $_POST['motivoConsulta'];
$estadoCita = $_POST['estadoCita'];
$observaciones = $_POST['observaciones'];

// Validar datos obligatorios
if (empty($idCita) || empty($idPaciente) || empty($idMedico) || empty($fechaCita) || empty($estadoCita)) {
    echo json_encode(array('success' => false, 'error' => 'Faltan datos obligatorios'));
    exit;
}

// Verificar que no haya conflictos de horario con el medico
$sqlVerificar = "SELECT COUNT(*) as total FROM Control_Agenda 
                 WHERE IdMedico = ? 
                 AND FechaCita = ? 
                 AND EstadoCita != 'Cancelada'
                 AND IdCita != ?";
$stmtVerificar = $conn->prepare($sqlVerificar);
$stmtVerificar->bind_param("isi", $idMedico, $fechaCita, $idCita);
$stmtVerificar->execute();
$resultVerificar = $stmtVerificar->get_result();
$verificacion = $resultVerificar->fetch_assoc();

if ($verificacion['total'] > 0) {
    echo json_encode(array('success' => false, 'error' => 'El medico ya tiene otra cita agendada a esa hora'));
    exit;
}

// Preparar la consulta SQL
$sql = "UPDATE Control_Agenda 
        SET IdPaciente = ?, IdMedico = ?, FechaCita = ?, MotivoConsulta = ?, EstadoCita = ?, Observaciones = ?
        WHERE IdCita = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iissssi", $idPaciente, $idMedico, $fechaCita, $motivoConsulta, $estadoCita, $observaciones, $idCita);

// Ejecutar la consulta
if ($stmt->execute()) {
    echo json_encode(array('success' => true, 'message' => 'Cita actualizada exitosamente'));
} else {
    echo json_encode(array('success' => false, 'error' => 'Error al actualizar la cita: ' . $conn->error));
}

// Cerrar conexion
$stmt->close();
$conn->close();
?>
