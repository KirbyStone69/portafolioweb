<?php
// aqui inicio sesion y verifico que el usuario este logueado
session_start();
require_once '../login/verificar_sesion.php';
require_once '../login/registrar_bitacora.php';


$conexion = new mysqli("localhost", "root", "edereder", "clinica_db");
if ($conexion->connect_error) {
    die("Error de conexion: " . $conexion->connect_error);
}

// recibe el id del paciente a eliminar
$id = $_POST['id'];

// JR: obtengo los datos ANTES de preparar el DELETE
$datos_anteriores = obtener_datos_anteriores($conexion, 'Control_Pacientes', 'IdPaciente', $id);

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
    registrar_bitacora(
        $_SESSION['id_usuario'],
        'Eliminar',
        'Pacientes',
        'Eliminó paciente: ' . ($datos_anteriores ? $datos_anteriores['NombreCompleto'] : 'ID ' . $id),
        $id,
        $datos_anteriores,
        null
    );
    
    header("Location: /practica-9/Pacientes.php?ok=3");
    $sql->close();
    $conexion->close();
    exit;
} else {
    header("Location: /practica-9/Pacientes.php?ok=0");
    $sql->close();
    $conexion->close();
    exit;
}
?>
