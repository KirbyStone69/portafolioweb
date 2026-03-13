<?php
// aqui inicio la sesion
session_start();

// aqui me conecto a la base de datos para registrar en bitacora
if (isset($_SESSION['usuario_id'])) {
    $conexion = new mysqli("localhost", "root", "edereder", "clinica_db");
    if (!$conexion->connect_error) {
        // aqui registro en la bitacora el cierre de sesion
        $usuario_id = $_SESSION['usuario_id'];
        $sqlBitacora = $conexion->prepare("INSERT INTO Bitacora_Acceso (IdUsuario, AccionRealizada, Modulo) VALUES (?, 'Cierre de sesión', 'Logout')");
        $sqlBitacora->bind_param("i", $usuario_id);
        $sqlBitacora->execute();
        $conexion->close();
    }
}

// aqui destruyo todas las variables de sesion
session_unset();

// aqui destruyo la sesion
session_destroy();

// aqui redirijo al login
header("Location: ../../login.php");
exit;
?>
