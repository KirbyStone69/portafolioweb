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
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];

$sql = $conexion->prepare(
    "UPDATE Especialidades SET NombreEspecialidad = ?, Descripcion = ? WHERE IdEspecialidad = ?"
);

if (!$sql) {
    die("Error en prepare: " . $conexion->error);
}

$sql->bind_param("ssi", $nombre, $descripcion, $id);

// JR: obtengo datos anteriores antes de actualizar
$datos_anteriores = obtener_datos_anteriores($conexion, 'Especialidades', 'IdEspecialidad', $id);

if ($sql->execute()) {
    // JR: registro en bitacora con datos completos
    registrar_bitacora(
        $_SESSION['id_usuario'],
        'Editar',
        'Especialidades',
        'Editó especialidad #' . $id . ': ' . $nombre,
        $id,
        $datos_anteriores,
        array(
            'NombreEspecialidad' => $nombre,
            'Descripcion' => $descripcion
        )
    );
    
      header("Location: /Dashboard Medico/Especialidades.php?ok=2");
      $sql->close();
      $conexion->close();
  exit;
} else {
      header("Location: /Dashboard Medico/Especialidades.php?ok=0");
      $sql->close();
      $conexion->close();
      exit;
}
?>
