<?php
// Este archivo devuelve la distribucion de metodos de pago
header('Content-Type: application/json');

// Conexion a la base de datos
require_once __DIR__ . "/../mock_db.php";
$conexion = new MockMysqli();
if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}

// Obtener conteo por metodo de pago
$sql = "SELECT 
    MetodoPago,
    COUNT(*) as cantidad
FROM Gestor_Pagos
WHERE EstatusPago = 'Pagado'
GROUP BY MetodoPago
ORDER BY cantidad DESC";

$resultado = $conexion->query($sql);

$labels = [];
$valores = [];

// Si hay resultados, llenar arrays
if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $labels[] = $fila['MetodoPago'];
        $valores[] = intval($fila['cantidad']);
    }
} else {
    // Si no hay datos, devolver valores por defecto
    $labels = ['Efectivo', 'Tarjeta', 'Transferencia'];
    $valores = [0, 0, 0];
}

// Preparar respuesta
$respuesta = [
    'labels' => $labels,
    'valores' => $valores
];

echo json_encode($respuesta);

$conexion->close();
?>
