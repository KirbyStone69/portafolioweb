<?php
// aqui inicio sesion y verifico que el usuario este logueado
session_start();
require_once '../login/verificar_sesion.php';
require_once '../login/registrar_bitacora.php';


$conexion = new mysqli("localhost", "root", "edereder", "clinica_db");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$id = $_POST['id'];

// JR: obtengo los datos ANTES de preparar el DELETE
$datos_anteriores = obtener_datos_anteriores($conexion, 'Especialidades', 'IdEspecialidad', $id);

$sql = $conexion->prepare(
    "DELETE FROM Especialidades WHERE IdEspecialidad = ?"
);

if (!$sql) {
    die("Error en prepare: " . $conexion->error);
}

$sql->bind_param("i", $id);
if ($sql->execute()) {
    // JR: registro en bitacora con datos anteriores
    registrar_bitacora(
        $_SESSION['id_usuario'], 
        'Eliminar', 
        'Especialidades', 
        'Eliminó especialidad: ' . ($datos_anteriores ? $datos_anteriores['NombreEspecialidad'] : 'ID ' . $id),
        $id,
        $datos_anteriores,
        null
    );
    
      header("Location: /practica-9/Especialidades.php?ok=3");
      $sql->close();
      $conexion->close();
  exit;
} else {
      header("Location: /practica-9/Especialidades.php?ok=0");
      $sql->close();
      $conexion->close();
      exit;
}
?>
