<?php
// aqui inicio sesion y verifico que el usuario este logueado
session_start();
require_once '../login/verificar_sesion.php';
require_once '../login/registrar_bitacora.php';

// aqui me conecto a la base de datos
require_once __DIR__ . "/../mock_db.php";
$conexion = new MockMysqli();
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// aqui obtengo el id que me enviaron
$id_pago = $_POST['id_pago'];

// JR: obtengo los datos ANTES de preparar el DELETE
$datos_anteriores = obtener_datos_anteriores($conexion, 'Gestor_Pagos', 'IdPago', $id_pago);

// aqui preparo la consulta para eliminar
$sql = $conexion->prepare("DELETE FROM Gestor_Pagos WHERE IdPago = ?");

if (!$sql) {
    die("Error en prepare: " . $conexion->error);
}

// aqui pongo el valor en la consulta
$sql->bind_param("i", $id_pago);

// aqui ejecuto y redirijo
if ($sql->execute()) {
    // JR: registro en bitacora con datos anteriores
    registrar_bitacora(
        $_SESSION['id_usuario'],
        'Eliminar',
        'Pagos',
        'Eliminó pago #' . $id_pago . ($datos_anteriores ? ' - Monto: $' . $datos_anteriores['Monto'] : ''),
        $id_pago,
        $datos_anteriores,
        null
    );
    
    header("Location: /Dashboard Medico Static/Pagos.php?ok=1");
    $sql->close();
    $conexion->close();
    exit;
} else {
    header("Location: /Dashboard Medico Static/Pagos.php?ok=0");
    $sql->close();
    $conexion->close();
    exit;
}
?>
