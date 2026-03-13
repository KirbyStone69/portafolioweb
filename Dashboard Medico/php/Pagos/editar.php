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

// aqui obtengo los datos del formulario
$id_pago = $_POST['id_pago'];
$monto = $_POST['monto'];
$metodo_pago = $_POST['metodo_pago'];
$referencia = $_POST['referencia'];
$estatus_pago = $_POST['estatus_pago'];

// aqui preparo la consulta para actualizar
$sql = $conexion->prepare(
    "UPDATE Control_Pagos SET Monto = ?, MetodoPago = ?, Referencia = ?, EstatusPago = ? WHERE IdPago = ?"
);

if (!$sql) {
    die("Error en prepare: " . $conexion->error);
}

// aqui pongo los valores en la consulta
$sql->bind_param("dsssi", $monto, $metodo_pago, $referencia, $estatus_pago, $id_pago);

// aqui ejecuto y redirijo
if ($sql->execute()) {
    // registro en bitacora
    registrarBitacora($_SESSION['usuario_id'], 'Editar pago', 'Pagos');
    
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
