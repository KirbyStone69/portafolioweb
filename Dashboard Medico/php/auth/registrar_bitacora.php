<?php
// Funcion helper para registrar acciones en la bitacora
// Uso: registrarBitacora($usuario_id, $accion, $modulo);

function registrarBitacora($usuario_id, $accion, $modulo) {
    // aqui me conecto a la base de datos
    $conexion = new mysqli("localhost", "root", "edereder", "clinica_db");
    
    if (!$conexion->connect_error) {
        // aqui inserto en la bitacora
        $sql = $conexion->prepare("INSERT INTO Bitacora_Acceso (IdUsuario, AccionRealizada, Modulo) VALUES (?, ?, ?)");
        $sql->bind_param("iss", $usuario_id, $accion, $modulo);
        $sql->execute();
        $sql->close();
        $conexion->close();
    }
}
?>
