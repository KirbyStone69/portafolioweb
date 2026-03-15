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

// aqui obtengo el id del pago
$id_pago = isset($_GET['id_pago']) ? intval($_GET['id_pago']) : 0;

if ($id_pago == 0) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de pago requerido']);
    exit;
}

// aqui hago el select para traer el desglose del pago
$sql = "SELECT 
    dp.IdDetalle,
    dp.IdTarifa,
    dp.Cantidad,
    dp.PrecioUnitario,
    dp.Subtotal,
    dp.Descripcion,
    t.DescripcionServicio
FROM Detalle_Pagos dp
JOIN Gestor_Tarifas t ON dp.IdTarifa = t.IdTarifa
WHERE dp.IdPago = ?
ORDER BY dp.IdDetalle";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_pago);
$stmt->execute();
$respuesta = $stmt->get_result();

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
    echo json_encode(['error' => 'Error al consultar detalles']);
}

$stmt->close();
$conexion->close();
?>
