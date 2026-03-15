<?php
// aqui inicio la sesion
session_start();

// aqui registro el logout en bitacora ANTES de destruir la sesion
if (isset($_SESSION['id_usuario'])) {
    require_once 'registrar_bitacora.php';
    registrar_bitacora(
        $_SESSION['id_usuario'],
        'Logout',
        'Sistema',
        'Usuario ' . $_SESSION['nombre_usuario'] . ' cerró sesión'
    );
}

// aqui destruyo todas las variables de sesion
session_unset();

// aqui cierro la sesion
session_destroy();

// aqui redirijo al login del Dashboard Médico
header("Location: /Dashboard Medico/index.php");
exit;
?>
