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

// aqui obtengo los datos que me enviaron del formulario
$id = $_POST['id'];
$descripcion = $_POST['descripcion'];
$costo = $_POST['costo'];
$especialidad_id = $_POST['especialidad_id'];
$estatus = isset($_POST['estatus']) ? 1 : 0;

// aqui preparo la consulta para actualizar
$sql = $conexion->prepare(
    "UPDATE Gestor_Tarifas SET DescripcionServicio = ?, CostoBase = ?, EspecialidadId = ?, Estatus = ? WHERE IdTarifa = ?"
);

if (!$sql) {
    die("Error en prepare: " . $conexion->error);
}

// aqui pongo los valores en la consulta
$sql->bind_param("sdiii", $descripcion, $costo, $especialidad_id, $estatus, $id);

// aqui ejecuto la consulta y redirijo
if ($sql->execute()) {
    // registro en bitacora
    registrarBitacora($_SESSION['usuario_id'], 'Editar tarifa', 'Tarifas');
    
    header("Location: ../../Tarifas.php?ok=2");
    $sql->close();
    $conexion->close();
    exit;
} else {
    header("Location: ../../Tarifas.php?ok=0");
    $sql->close();
    $conexion->close();
    exit;
}
?>
