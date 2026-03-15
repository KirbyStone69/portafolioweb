<?php
// aqui inicio sesion y verifico que el usuario este logueado
session_start();
require_once '../login/verificar_sesion.php';
require_once '../login/registrar_bitacora.php';

// aqui me conecto a la base de datos
$conexion = new mysqli("localhost", "root", "edereder", "clinica_db");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// aqui obtengo los datos que me enviaron del formulario
$id = $_POST['id'];
$usuario = $_POST['usuario'];
$rol = $_POST['rol'];
$medico_id = $_POST['medico_id'] != '' ? $_POST['medico_id'] : NULL;
$paciente_id = ($rol == 'Paciente' && isset($_POST['paciente_id']) && $_POST['paciente_id'] != '') ? $_POST['paciente_id'] : NULL;
$nombre_completo = $_POST['nombre_completo'];
$telefono = $_POST['telefono'];
$correo = $_POST['correo'];
$activo = isset($_POST['activo']) ? 1 : 0;

// aqui obtengo los medicos asignados si es recepcionista
$medicos_asignados = isset($_POST['medicos_asignados']) ? $_POST['medicos_asignados'] : array();

// aqui verifico si enviaron nueva contraseña
$password = isset($_POST['password']) && $_POST['password'] != '' ? $_POST['password'] : NULL;

// aqui valido que el usuario no exista (excepto el actual)
$sql_check = $conexion->prepare("SELECT IdUsuario FROM Usuarios_Sistema WHERE Usuario = ? AND IdUsuario != ?");
$sql_check->bind_param("si", $usuario, $id);
$sql_check->execute();
if ($sql_check->get_result()->num_rows > 0) {
    header("Location: /Dashboard Medico/Usuarios.php?ok=0&error=" . urlencode("El usuario ya existe"));
    $sql_check->close();
    $conexion->close();
    exit;
}
$sql_check->close();

// aqui preparo la consulta para actualizar
if ($password) {
    // si hay nueva contraseña, la actualizo tambien
    $sql = $conexion->prepare(
        "UPDATE Usuarios_Sistema SET Usuario=?, Contrasena=?, Rol=?, IdMedico=?, IdPaciente=?, NombreCompleto=?, Telefono=?, CorreoElectronico=?, Activo=? WHERE IdUsuario=?"
    );
    $sql->bind_param("sssiisssii", $usuario, $password, $rol, $medico_id, $paciente_id, $nombre_completo, $telefono, $correo, $activo, $id);
} else {
    // si no hay nueva contraseña, no la actualizo
    $sql = $conexion->prepare(
        "UPDATE Usuarios_Sistema SET Usuario=?, Rol=?, IdMedico=?, IdPaciente=?, NombreCompleto=?, Telefono=?, CorreoElectronico=?, Activo=? WHERE IdUsuario=?"
    );
    $sql->bind_param("ssiisssii", $usuario, $rol, $medico_id, $paciente_id, $nombre_completo, $telefono, $correo, $activo, $id);
}

if (!$sql) {
    die("Error en prepare: " . $conexion->error);
}

// JR: aqui obtengo los datos anteriores antes de actualizar
$datos_anteriores = obtener_datos_anteriores($conexion, 'Usuarios_Sistema', 'IdUsuario', $id);

// aqui ejecuto la consulta
if ($sql->execute()) {
    // JR: registro en bitacora con datos completos
    registrar_bitacora(
        $_SESSION['id_usuario'], 
        'Editar', 
        'Usuarios', 
        'Editó usuario: ' . $usuario . ' - Rol: ' . $rol,
        $id,
        $datos_anteriores,
        array(
            'Usuario' => $usuario,
            'Rol' => $rol,
            'NombreCompleto' => $nombre_completo,
            'Telefono' => $telefono,
            'CorreoElectronico' => $correo,
            'Activo' => $activo
        )
    );
    
    // aqui actualizo las asignaciones si es recepcionista
    if ($rol == 'Recepcionista') {
        // primero borro las asignaciones anteriores
        $sql_delete = $conexion->prepare("DELETE FROM Recepcionista_Medico WHERE IdRecepcionista = ?");
        $sql_delete->bind_param("i", $id);
        $sql_delete->execute();
        $sql_delete->close();
        
        // ahora inserto las nuevas asignaciones
        if (count($medicos_asignados) > 0) {
            $sql_asignacion = $conexion->prepare(
                "INSERT INTO Recepcionista_Medico (IdRecepcionista, IdMedico) VALUES (?, ?)"
            );
            
            foreach ($medicos_asignados as $id_medico) {
                $sql_asignacion->bind_param("ii", $id, $id_medico);
                $sql_asignacion->execute();
            }
            $sql_asignacion->close();
        }
    }
    
    header("Location: /Dashboard Medico/Usuarios.php?ok=2");
    $sql->close();
    $conexion->close();
    exit;
} else {
    header("Location: /Dashboard Medico/Usuarios.php?ok=0");
    $sql->close();
    $conexion->close();
    exit;
}
?>
