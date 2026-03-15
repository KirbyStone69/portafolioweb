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
$id = $_POST['id'];

// JR: obtengo los datos ANTES de preparar el DELETE
$datos_anteriores = obtener_datos_anteriores($conexion, 'Control_Medicos', 'IdMedico', $id);

// aqui preparo la consulta para eliminar
$sql = $conexion->prepare(
    "DELETE FROM Control_Medicos WHERE IdMedico = ?"
);

if (!$sql) {
    die("Error en prepare: " . $conexion->error);
}

// aqui pongo el id en la consulta
$sql->bind_param("i", $id);

// aqui ejecuto la consulta y redirizo
if ($sql->execute()) {
    // JR: registro en bitacora con datos anteriores
    registrar_bitacora(
        $_SESSION['id_usuario'], 
        'Eliminar', 
        'Medicos', 
        'Eliminó médico: ' . ($datos_anteriores ? $datos_anteriores['NombreCompleto'] : 'ID ' . $id),
        $id,
        $datos_anteriores,
        null
    );
    
    header("Location: /Dashboard Medico/Medicos.php?ok=3");
    $sql->close();
    $conexion->close();
    exit;
} else {
    header("Location: /Dashboard Medico/Medicos.php?ok=0");
    $sql->close();
    $conexion->close();
    exit;
}
?>
