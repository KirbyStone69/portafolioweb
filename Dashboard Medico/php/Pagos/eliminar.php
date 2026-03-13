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

// aqui obtengo el id que me enviaron
$id_pago = $_POST['id_pago'];

// aqui preparo la consulta para eliminar
$sql = $conexion->prepare("DELETE FROM Gestor_Pagos WHERE IdPago = ?");

if (!$sql) {
    die("Error en prepare: " . $conexion->error);
}

// aqui pongo el valor en la consulta
$sql->bind_param("i", $id_pago);

// aqui ejecuto y redirijo
if ($sql->execute()) {
    // registro en bitacora
    registrarBitacora($_SESSION['usuario_id'], 'Eliminar pago', 'Pagos');
    
    header("Location: ../../Pagos.php?ok=1");
    $sql->close();
    $conexion->close();
    exit;
} else {
    header("Location: ../../Pagos.php?ok=0");
    $sql->close();
    $conexion->close();
    exit;
}
?>
