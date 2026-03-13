<?php
header('Content-Type: application/json');

$conexion = new mysqli("localhost", "root", "edereder", "clinica_db");
// control de error de conexion
if ($conexion->connect_error) {
  http_response_code(500);
  echo json_encode(['error' => 'Error de conexion']);
  exit;
}

$sql = "SELECT IdPaciente, NombreCompleto, CURP, Telefono, CorreoElectronico, FechaRegistro, Estatus FROM Control_Pacientes ORDER BY FechaRegistro DESC";
// ejecuta la consulta
$respuesta = $conexion->query($sql);

// si hay resultado
if ($respuesta) {
  $array = [];
  // mientras haya filas las carga en el array
  while ($linea = $respuesta->fetch_assoc()) {
    $array[] = $linea;
  }
  echo json_encode($array);
} else {
  http_response_code(500);
  echo json_encode(['error' => 'Error al consultar tabla']);
}

$conexion->close();
?>
