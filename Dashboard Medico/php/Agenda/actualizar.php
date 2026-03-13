<?php
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

if ($stmt->execute()) {
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
