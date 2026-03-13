<?php
// aqui inicio sesion y verifico que el usuario este logueado
session_start();
require_once '../auth/verificar_sesion.php';
require_once '../auth/registrar_bitacora.php';


$conexion = new mysqli("localhost", "root", "edereder", "clinica_db");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];

$sql = $conexion->prepare(
    "UPDATE Especialidades SET NombreEspecialidad = ?, Descripcion = ? WHERE IdEspecialidad = ?"
);

if (!$sql) {
    die("Error en prepare: " . $conexion->error);
}

$sql->bind_param("ssi", $nombre, $descripcion, $id);

if ($sql->execute()) {
    // registro en bitacora
    registrarBitacora($_SESSION['usuario_id'], 'Editar especialidad', 'Especialidades');
    
      header("Location: ../../Especialidades.php?ok=2");
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
