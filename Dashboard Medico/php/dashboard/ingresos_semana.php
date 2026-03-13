<?php
// Este archivo devuelve los ingresos de la semana actual por dia
header('Content-Type: application/json');

// Conexion a la base de datos
$conexion = new mysqli("localhost", "root", "edereder", "clinica_db");
if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}

// Obtener ingresos por dia de la semana actual
$sql = "SELECT 
    DAYOFWEEK(FechaPago) as dia,
    COALESCE(SUM(Monto), 0) as total
FROM Gestor_Pagos
WHERE EstatusPago = 'Pagado'
    AND YEARWEEK(FechaPago, 1) = YEARWEEK(CURRENT_DATE(), 1)
GROUP BY DAYOFWEEK(FechaPago)
ORDER BY dia";

$resultado = $conexion->query($sql);

// Inicializar array con 7 dias (Lun-Dom) en 0
$ingresos_semana = [0, 0, 0, 0, 0, 0, 0];

// Llenar con los datos reales
if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        // DAYOFWEEK devuelve 1=Domingo, 2=Lunes, ..., 7=Sabado
        // Convertimos a indice 0=Lunes, ..., 6=Domingo
        $dia_semana = $fila['dia'];
        if ($dia_semana == 1) { // Domingo
            $ingresos_semana[6] = floatval($fila['total']);
        } else { // Lunes a Sabado
            $ingresos_semana[$dia_semana - 2] = floatval($fila['total']);
        }
    }
}

// Preparar respuesta
$respuesta = [
    'dias' => ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
    'montos' => $ingresos_semana
];

echo json_encode($respuesta);

$conexion->close();
?>
