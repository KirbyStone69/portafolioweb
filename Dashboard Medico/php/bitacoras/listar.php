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

// aqui hago el select con join para traer el nombre del usuario
$sql = "SELECT 
    b.IdBitacora, 
    b.IdUsuario,
    b.FechaAcceso,
    b.AccionRealizada,
    b.Modulo,
    u.NombreCompleto,
    u.Rol
FROM Bitacora_Acceso b
INNER JOIN Usuarios_Sistema u ON b.IdUsuario = u.IdUsuario
ORDER BY b.FechaAcceso DESC";

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
