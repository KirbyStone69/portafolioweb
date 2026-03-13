<?php 
// aqui inicio sesion y verifico que este logueado
session_start();
require_once '../auth/verificar_sesion.php';
require_once '../auth/registrar_bitacora.php';

$conexion = new mysqli("localhost", "root", "edereder", "clinica_db");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// aqui recibo los datos
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];

// aqui preparo la consulta
$sql = $conexion->prepare("INSERT INTO Especialidades (NombreEspecialidad, Descripcion) VALUES (?, ?)");

if (!$sql) {
    die("Error en prepare: " . $conexion->error);
}

$sql->bind_param("ss", $nombre, $descripcion);

// aqui ejecuto y registro en bitacora
if ($sql->execute()) {
    // registro en bitacora
    registrarBitacora($_SESSION['usuario_id'], 'Insertar especialidad: ' . $nombre, 'Especialidades');
    
    header("Location: ../../Especialidades.php?ok=1");
    $sql->close();
    $conexion->close();
    exit;
} else {
    header("Location: ../../Especialidades.php?ok=0");
    $sql->close();
    $conexion->close();
    exit;
}
?>
