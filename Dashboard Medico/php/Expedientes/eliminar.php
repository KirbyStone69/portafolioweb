<?php
// aqui inicio sesion y verifico que el usuario este logueado
session_start();
require_once '../auth/verificar_sesion.php';
require_once '../auth/registrar_bitacora.php';

// aqui me conecto a la base de datos
$conexion = new mysqli("localhost", "root", "edereder", "clinica_db");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// aqui recibo el id del expediente a eliminar
$id = $_POST['id'];

// aqui preparo la consulta para eliminar
$sql = $conexion->prepare("DELETE FROM Expediente_Clinico WHERE IdExpediente=?");

if (!$sql) {
    die("Error en prepare: " . $conexion->error);
}

// aqui asigno el parametro
$sql->bind_param("i", $id);

// aqui ejecuto la eliminacion
if ($sql->execute()) {
    // registro en bitacora
    registrarBitacora($_SESSION['usuario_id'], 'Eliminar expediente', 'Expedientes');
    
    header("Location: ../../Expedientes.php?ok=3");
    $sql->close();
    $conexion->close();
    exit;
} else {
    header("Location: ../../Expedientes.php?ok=0");
    $sql->close();
    $conexion->close();
    exit;
}
?>
