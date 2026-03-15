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

// aqui obtengo el id que me enviaron
$id = $_POST['id'];

// JR: obtengo los datos ANTES de preparar el DELETE
$datos_anteriores = obtener_datos_anteriores($conexion, 'Usuarios_Sistema', 'IdUsuario', $id);

// aqui preparo la consulta para eliminar
$sql = $conexion->prepare(
    "DELETE FROM Usuarios_Sistema WHERE IdUsuario = ?"
);

if (!$sql) {
    die("Error en prepare: " . $conexion->error);
}

// aqui pongo el id en la consulta
$sql->bind_param("i", $id);

// aqui ejecuto la consulta y redirijo
if ($sql->execute()) {
    // JR: registro en bitacora con datos anteriores
    registrar_bitacora(
        $_SESSION['id_usuario'], 
        'Eliminar', 
        'Usuarios', 
        'Eliminó usuario: ' . ($datos_anteriores ? $datos_anteriores['Usuario'] : 'ID ' . $id),
        $id,
        $datos_anteriores,
        null
    );
    
    header("Location: /Dashboard Medico/Usuarios.php?ok=3");
    $sql->close();
    $conexion->close();
    exit;
} else {
    header("Location: /Dashboard Medico/Usuarios.php?ok=0");
    $sql->close();
    $conexion->close();
    exit;
}
?>
