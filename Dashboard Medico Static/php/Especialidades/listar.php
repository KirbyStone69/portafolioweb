  <?php
  header('Content-Type: application/json');

  require_once __DIR__ . "/../mock_db.php";
$conexion = new MockMysqli();
//control de error de conexion utilizando el codigo 500
  if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión']);
    exit;
  }

  $sql = "SELECT IdEspecialidad, NombreEspecialidad, Descripcion FROM Especialidades";
  // esto es la funcion de la base que ejecuta sql, aqui obtuvo los datos con select y lo carga en $respuesta
  $respuesta = $conexion->query($sql);

  //si respuesta es diferente de null
  if ($respuesta) {
    $array = [];
    //mientras haya lineas en la tabla (filas) se estaran cargando las lineas en el array 
    while ($linea = $respuesta->fetch_assoc()) {
      $array[] = $linea;
    }
    echo json_encode($array);
  } else {
    http_response_code(500);
    echo json_encode(['error' => 'Error al consultar tabla']);
  }

  $conexion->close();