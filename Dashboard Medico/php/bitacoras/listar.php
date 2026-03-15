<?php
// esto le dice al navegador que voy a enviar json
header('Content-Type: application/json');

// aqui me conecto a la base de datos
$conexion = new mysqli("localhost", "root", "edereder", "clinica_db");
if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}

// JR: aqui hago el select con join para traer los datos de bitacora junto con el usuario
// JR: IMPORTANTE: antes decia FechaAcceso, FechaSalida y AccionRealizada pero esas columnas NO EXISTEN en la BD
// JR: Las columnas correctas son: FechaHora, TipoAccion, Modulo, etc
$sql = "SELECT 
    b.IdBitacora, 
    b.IdUsuario,
    b.TipoAccion,
    b.Modulo,
    b.DescripcionAccion,
    b.IdRegistroAfectado,
    b.DatosAnteriores,
    b.DatosNuevos,
    b.FechaHora,
    b.DireccionIP,
    u.NombreCompleto,
    u.Rol
FROM Bitacora_Acceso b
INNER JOIN Usuarios_Sistema u ON b.IdUsuario = u.IdUsuario
ORDER BY b.FechaHora DESC";

$respuesta = $conexion->query($sql);

// aqui verifico si la consulta funciono
if ($respuesta) {
    $array = [];
    // aqui meto cada fila en el array
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
