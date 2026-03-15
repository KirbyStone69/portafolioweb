<?php
// aqui inicio la sesion si no esta iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// aqui verifico si el usuario esta logueado
if (!isset($_SESSION['sesion_iniciada']) || $_SESSION['sesion_iniciada'] !== true) {
    // si no esta logueado lo mando al login
    header("Location: /Dashboard Medico/index.php?error=2");
    exit;
}

// aqui opcional puedo verificar el tiempo de inactividad (30 minutos)
$tiempo_inactividad = 1800; // 30 minutos en segundos
if (isset($_SESSION['ultimo_acceso'])) {
    $tiempo_transcurrido = time() - $_SESSION['ultimo_acceso'];
    if ($tiempo_transcurrido > $tiempo_inactividad) {
        // sesion expirada por inactividad
        session_unset();
        session_destroy();
        header("Location: /Dashboard Medico/index.php?error=2");
        exit;
    }
}

// aqui actualizo el tiempo del ultimo acceso
$_SESSION['ultimo_acceso'] = time();
?>
