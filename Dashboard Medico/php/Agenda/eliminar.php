<?php
// aqui inicio sesion y verifico que el usuario este logueado
session_start();
require_once '../login/verificar_sesion.php';
require_once '../login/registrar_bitacora.php';

// Archivo para eliminar una cita
header('Content-Type: application/json');

// Conexion a la base de datos
$conexion = new mysqli("localhost", "root", "edereder", "clinica_db");

// Control de error de conexion
if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}

// Recibir el ID de la cita
$idCita = $_POST['idCita'] ?? null;

if (empty($idCita)) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de cita no proporcionado']);
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

// JR: aqui obtengo los datos antes de eliminar para guardar en bitacora
$datos_anteriores = $resultCheck->fetch_assoc(); // Fetch the data before deletion

// Eliminar la cita
$sql = "DELETE FROM Control_Agenda WHERE IdCita = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idCita);

if ($stmt->execute()) {
    // JR: registro en bitacora con los datos de lo que se elimino
    registrar_bitacora(
        $_SESSION['id_usuario'],
        'Eliminar',
        'Agenda',
        'Eliminó cita #' . $idCita . ($datos_anteriores ? ' - Paciente ID: ' . $datos_anteriores['IdPaciente'] : ''),
        $idCita,
        $datos_anteriores,
        null
    );

    echo json_encode([
        'success' => true,
        'message' => 'Cita eliminada correctamente'
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Error al eliminar cita']);
}

$conexion->close();
?>
