<?php
// aqui inicio sesion y verifico que el usuario este logueado
session_start();
require_once '../auth/verificar_sesion.php';
require_once '../auth/registrar_bitacora.php';

// Incluir la conexion a la base de datos
include_once '../db.php';

// Recibir el ID de la cita
$idCita = $_POST['idCita'];

// Validar que se recibio el ID
if (empty($idCita)) {
    echo json_encode(array('success' => false, 'error' => 'No se recibió el ID de la cita'));
    exit;
}

// Preparar la consulta SQL para eliminar
$sql = "DELETE FROM Control_Agenda WHERE IdCita = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idCita);

// Ejecutar la consulta
if ($stmt->execute()) {
    // registro en bitacora
    registrarBitacora($_SESSION['usuario_id'], 'Eliminar cita', 'Agenda');
    
    echo json_encode(array('success' => true, 'message' => 'Cita eliminada exitosamente'));
} else {
    echo json_encode(array('success' => false, 'error' => 'Error al eliminar la cita: ' . $conn->error));
}

// Cerrar conexion
$stmt->close();
$conn->close();
?>
