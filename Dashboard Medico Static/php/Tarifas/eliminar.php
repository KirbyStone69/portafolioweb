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

// aqui obtengo el id que me enviaron (god)
$id = $_POST['id'];

// aqui preparo la consulta para eliminar
$sql = $conexion->prepare(
    "DELETE FROM Gestor_Tarifas WHERE IdTarifa = ?"
);

if (!$sql) {
    die("Error en prepare: " . $conexion->error);
}

// aqui pongo el id en la consulta
$sql->bind_param("i", $id);

// JR: obtengo los datos antes de eliminar
$datos_anteriores = obtener_datos_anteriores($conexion, 'Gestor_Tarifas', 'IdTarifa', $id);

// aqui ejecuto la consulta y redirijo
if ($sql->execute()) {
    // JR: registro en bitacora con datos anteriores
    registrar_bitacora(
        $_SESSION['id_usuario'], 
        'Eliminar', 
        'Tarifas', 
        'Eliminó tarifa: ' . ($datos_anteriores ? $datos_anteriores['DescripcionServicio'] : 'ID ' . $id),
        $id,
        $datos_anteriores,
        null
    );
    
    header("Location: /Dashboard Medico Static/Tarifas.php?ok=3");
    $sql->close();
    $conexion->close();
    exit;
} else {
    header("Location: /Dashboard Medico Static/Tarifas.php?ok=0");
    $sql->close();
    $conexion->close();
    exit;
}
?>
