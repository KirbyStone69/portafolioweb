<?php
// Este archivo devuelve la distribucion de metodos de pago falsa para la grafica del Dashboard
header('Content-Type: application/json');

$respuesta = [
    'labels' => ['Efectivo', 'Tarjeta', 'Transferencia', 'Aseguradora'],
    'valores' => [rand(20, 50), rand(30, 80), rand(15, 40), rand(5, 15)]
];

echo json_encode($respuesta);
?>
