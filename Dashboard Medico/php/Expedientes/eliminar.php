<?php
// aqui inicio sesion y verifico que el usuario este logueado
session_start();
require_once '../login/verificar_sesion.php';
require_once '../login/registrar_bitacora.php';

// aqui me conecto a la base de datos
$conexion = new mysqli("localhost", "root", "edereder", "clinica_db");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// aqui recibo el id del expediente a eliminar
$id = $_POST['id'];

// JR: obtengo los datos ANTES de preparar el DELETE
$datos_anteriores = obtener_datos_anteriores($conexion, 'Expediente_Clinico', 'IdExpediente', $id);

// aqui preparo la consulta para eliminar
$sql = $conexion->prepare("DELETE FROM Expediente_Clinico WHERE IdExpediente=?");

if (!$sql) {
    die("Error en prepare: " . $conexion->error);
}

// aqui asigno el parametro
$sql->bind_param("i", $id);

// aqui ejecuto la eliminacion
if ($sql->execute()) {
    // JR: registro en bitacora con datos anteriores
    registrar_bitacora(
        $_SESSION['id_usuario'],
        'Eliminar',
        'Expedientes',
        'Eliminó expediente clínico #' . $id . ($datos_anteriores ? ' - Paciente ID: ' . $datos_anteriores['IdPaciente'] : ''),
        $id,
        $datos_anteriores,
        null
    );
    
    header("Location: /practica-9/Expedientes.php?ok=3");
    $sql->close();
    $conexion->close();
    exit;
} else {
    header("Location: /practica-9/Expedientes.php?ok=0");
    $sql->close();
    $conexion->close();
    exit;
}
?>
