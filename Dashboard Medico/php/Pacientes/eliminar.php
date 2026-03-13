<?php
// aqui inicio sesion y verifico que el usuario este logueado
session_start();
require_once '../auth/verificar_sesion.php';
require_once '../auth/registrar_bitacora.php';


$conexion = new mysqli("localhost", "root", "edereder", "clinica_db");
if ($conexion->connect_error) {
    die("Error de conexion: " . $conexion->connect_error);
}

// recibe el id del paciente a eliminar
$id = $_POST['id'];

// prepara la consulta de eliminacion
$sql = $conexion->prepare("DELETE FROM Control_Pacientes WHERE IdPaciente=?");

if (!$sql) {
    die("Error en prepare: " . $conexion->error);
}

// asigna el parametro
$sql->bind_param("i", $id);

// ejecuta la consulta
if ($sql->execute()) {
    // registro en bitacora
    registrarBitacora($_SESSION['usuario_id'], 'Eliminar paciente', 'Pacientes');
    
    header("Location: ../../Pacientes.php?ok=3");
    $sql->close();
    $conexion->close();
    exit;
} else {
    header("Location: ../../Pacientes.php?ok=0");
    $sql->close();
    $conexion->close();
    exit;
}
?>
