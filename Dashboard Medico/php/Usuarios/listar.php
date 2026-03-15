<?php
// esto le dice al navegador que voy a enviar json
header('Content-Type: application/json');

// aqui me conecto a la base de datos
require_once __DIR__ . "/../mock_db.php";
$conexion = new MockMysqli();
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
        u.IdPaciente, 
        u.NombreCompleto,
        u.Telefono,
        u.CorreoElectronico,
        u.Activo,
        u.UltimoAcceso,
        m.NombreCompleto as NombreMedico,
        p.NombreCompleto as NombrePaciente
    FROM Usuarios_Sistema u
    LEFT JOIN Control_Medicos m ON u.IdMedico = m.IdMedico
    LEFT JOIN Control_Pacientes p ON u.IdPaciente = p.IdPaciente
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
            
            // JR: uso prepared statement para evitar SQL injection
            $sql_medicos = $conexion->prepare("SELECT 
                rm.IdMedico, 
                m.NombreCompleto 
            FROM Recepcionista_Medico rm
            INNER JOIN Control_Medicos m ON rm.IdMedico = m.IdMedico
            WHERE rm.IdRecepcionista = ?");
            $sql_medicos->bind_param("i", $id_usuario);
            $sql_medicos->execute();
            $resultado_medicos = $sql_medicos->get_result();
            
            $medicos_asignados = [];
            
            if ($resultado_medicos) {
                while ($medico = $resultado_medicos->fetch_assoc()) {
                    $medicos_asignados[] = $medico;
                }
            }
            $sql_medicos->close(); // JR: cierro el prepared statement
            
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
