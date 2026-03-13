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
$id = $_POST['id'];

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
    // registro en bitacora
    registrarBitacora($_SESSION['usuario_id'], 'Eliminar usuario', 'Usuarios');
    
    header("Location: ../../Usuarios.php?ok=3");
    $sql->close();
    $conexion->close();
    exit;
} else {
    header("Location: ../../Usuarios.php?ok=0");
    $sql->close();
    $conexion->close();
    exit;
}
?>
