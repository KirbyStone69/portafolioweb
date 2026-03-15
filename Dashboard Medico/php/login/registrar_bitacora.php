<?php
/**
 * JR: Funcion para registrar acciones en la bitacora (historial de lo que hacen los usuarios)
 * JR: Esta funcion se llama desde TODOS los modulos cuando alguien inserta, edita o elimina algo
 * JR: Tambien se usa para registrar cuando alguien hace login o logout
 * 
 * @param int $id_usuario ID del usuario que realiza la accion (quien lo hizo)
 * @param string $tipo_accion Tipo: Login, Logout, Insertar, Editar, Eliminar, Ver (que hizo)
 * @param string $modulo Nombre del modulo: Pacientes, Medicos, Usuarios, Agenda, etc (donde lo hizo)
 * @param string $descripcion Descripcion detallada de la accion en texto (explicacion de lo que hizo)
 * @param int|null $id_registro ID del registro afectado - opcional (el ID del paciente, medico, cita, etc que se afecto)
 * @param array|null $datos_anteriores Datos antes de la modificacion - opcional, solo para Editar/Eliminar (como estaban los datos ANTES)
 * @param array|null $datos_nuevos Datos nuevos - opcional, solo para Insertar/Editar (como quedaron los datos DESPUES)
 */
function registrar_bitacora($id_usuario, $tipo_accion, $modulo, $descripcion = '', $id_registro = null, $datos_anteriores = null, $datos_nuevos = null) {
    // aqui me conecto a la base de datos
    $conexion = new mysqli("localhost", "root", "edereder", "clinica_db");
    
    if ($conexion->connect_error) {
        // si falla la conexion, no detengo el proceso principal
        error_log("Error al conectar a BD para bitacora: " . $conexion->connect_error);
        return false;
    }
    
    // aqui obtengo la IP del usuario
    $ip = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    
    // aqui convierto los arrays a JSON si existen
    $json_anteriores = $datos_anteriores ? json_encode($datos_anteriores, JSON_UNESCAPED_UNICODE) : null;
    $json_nuevos = $datos_nuevos ? json_encode($datos_nuevos, JSON_UNESCAPED_UNICODE) : null;
    
    // aqui preparo la consulta
    $sql = $conexion->prepare(
        "INSERT INTO Bitacora_Acceso (IdUsuario, TipoAccion, Modulo, DescripcionAccion, IdRegistroAfectado, DatosAnteriores, DatosNuevos, DireccionIP) 
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );
    
    if (!$sql) {
        error_log("Error al preparar consulta de bitacora: " . $conexion->error);
        $conexion->close();
        return false;
    }
    
    // aqui ejecuto la consulta
    $sql->bind_param("isssssss", $id_usuario, $tipo_accion, $modulo, $descripcion, $id_registro, $json_anteriores, $json_nuevos, $ip);
    $resultado = $sql->execute();
    
    if (!$resultado) {
        error_log("Error al insertar en bitacora: " . $sql->error);
    }
    
    $sql->close();
    $conexion->close();
    
    return $resultado;
}

/**
 * Funcion auxiliar para obtener datos de un registro antes de editarlo/eliminarlo
 * Util para guardar el estado anterior en la bitacora
 * 
 * @param mysqli $conexion Conexion a la base de datos
 * @param string $tabla Nombre de la tabla
 * @param string $campo_id Nombre del campo ID
 * @param int $id Valor del ID
 * @return array|null Array con los datos o null si no existe
 */
function obtener_datos_anteriores($conexion, $tabla, $campo_id, $id) {
    $sql = $conexion->prepare("SELECT * FROM $tabla WHERE $campo_id = ?");
    if (!$sql) {
        return null;
    }
    
    $sql->bind_param("i", $id);
    $sql->execute();
    $resultado = $sql->get_result();
    
    if ($resultado->num_rows > 0) {
        $datos = $resultado->fetch_assoc();
        $sql->close();
        return $datos;
    }
    
    $sql->close();
    return null;
}
?>
