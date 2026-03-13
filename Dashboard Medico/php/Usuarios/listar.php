<?php
// esto le dice al navegador que voy a enviar json
header('Content-Type: application/json');

// aqui me conecto a la base de datos
$conexion = new mysqli("localhost", "root", "edereder", "clinica_db");
if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}

// aqui verifico si la tabla Control_Medicos existe
$tabla_existe = $conexion->query("SHOW TABLES LIKE 'Control_Medicos'");

if ($tabla_existe && $tabla_existe->num_rows > 0) {
    // aqui hago el select con join si la tabla existe
    $sql = "SELECT 
        u.IdUsuario, 
        u.Usuario, 
        u.Rol, 
        u.IdMedico, 
        u.NombreCompleto,
        u.Telefono,
        u.CorreoElectronico,
        u.Activo,
        u.UltimoAcceso,
        m.NombreCompleto as NombreMedico
    FROM Usuarios_Sistema u
    LEFT JOIN Control_Medicos m ON u.IdMedico = m.IdMedico
    ORDER BY u.IdUsuario DESC";
} else {
    // si no existe la tabla, hago el select sin join
    $sql = "SELECT 
        IdUsuario, 
        Usuario, 
        Rol, 
        IdMedico, 
        NombreCompleto,
        Telefono,
        CorreoElectronico,
        Activo,
        UltimoAcceso,
        NULL as NombreMedico
    FROM Usuarios_Sistema
    ORDER BY IdUsuario DESC";
}

$respuesta = $conexion->query($sql);

// aqui verifico si la consulta funciono
if ($respuesta) {
    $array = [];
    // aqui meto cada fila en el array
    while ($linea = $respuesta->fetch_assoc()) {
        // aqui obtengo los medicos asignados si es recepcionista
        if ($linea['Rol'] == 'Recepcionista') {
            $id_usuario = $linea['IdUsuario'];
            $sql_medicos = "SELECT 
                rm.IdMedico, 
                m.NombreCompleto 
            FROM Recepcionista_Medico rm
            INNER JOIN Control_Medicos m ON rm.IdMedico = m.IdMedico
            WHERE rm.IdRecepcionista = $id_usuario";
            
            $resultado_medicos = $conexion->query($sql_medicos);
            $medicos_asignados = [];
            
            if ($resultado_medicos) {
                while ($medico = $resultado_medicos->fetch_assoc()) {
                    $medicos_asignados[] = $medico;
                }
            }
            
            $linea['MedicosAsignados'] = $medicos_asignados;
        }
        
        $array[] = $linea;
    }
    echo json_encode($array);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Error al consultar tabla']);
}

$conexion->close();
