<?php
// aqui inicio sesion para verificar si ya esta activa
session_start();

// aqui envio respuesta JSON
header('Content-Type: application/json');

// aqui verifico si hay sesion activa
if (isset($_SESSION['sesion_iniciada']) && $_SESSION['sesion_iniciada'] === true) {
    // hay sesion activa
    echo json_encode([
        'sesion_activa' => true,
        'usuario' => $_SESSION['nombre_usuario'],
        'rol' => $_SESSION['rol_usuario']
    ]);
} else {
    // no hay sesion activa
    echo json_encode([
        'sesion_activa' => false
    ]);
}
?>
