<?php
// Este archivo devuelve los ingresos de la semana actual por dia
header('Content-Type: application/json');

// Inicializar array con 7 dias (Lun-Dom) en 0
$ingresos_semana = [
    rand(1000, 3000), // Lunes
    rand(1500, 4000), // Martes
    rand(2000, 5000), // Miércoles
    rand(2500, 6000), // Jueves
    rand(3000, 7000), // Viernes
    rand(1000, 3000), // Sábado
    rand(500, 2000)   // Domingo
];

echo json_encode($ingresos_semana);
?>
