<?php
// aqui inicio sesion y verifico que el usuario este logueado
session_start();
require_once '../login/verificar_sesion.php';
require_once '../login/registrar_bitacora.php';

// aqui me conecto a la base de datos
require_once __DIR__ . "/../mock_db.php";
$conexion = new MockMysqli();
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// aqui obtengo los datos que me enviaron del formulario
$usuario = $_POST['usuario'];
$password = $_POST['password'];
$rol = $_POST['rol'];
$medico_id = $_POST['medico_id'] != '' ? $_POST['medico_id'] : NULL;
$paciente_id = ($rol == 'Paciente' && isset($_POST['paciente_id']) && $_POST['paciente_id'] != '') ? $_POST['paciente_id'] : NULL;
$activo = isset($_POST['activo']) ? 1 : 0;
$nombre_completo = $_POST['nombre_completo'];
$telefono = $_POST['telefono'];
$correo = $_POST['correo'];

$medicos_asignados = isset($_POST['medicos_asignados']) ? $_POST['medicos_asignados'] : array();

// aqui valido que el usuario no exista
$sql_check = $conexion->prepare("SELECT IdUsuario FROM Usuarios_Sistema WHERE Usuario = ?");
$sql_check->bind_param("s", $usuario);
$sql_check->execute();
if ($sql_check->get_result()->num_rows > 0) {
    header("Location: /Dashboard Medico/Usuarios.php?ok=0&error=" . urlencode("El usuario ya existe"));
    $sql_check->close();
    $conexion->close();
    exit;
}
$sql_check->close();

// aqui preparo la consulta para insertar
$sql = $conexion->prepare(
    "INSERT INTO Usuarios_Sistema (Usuario, Contrasena, Rol, IdMedico, IdPaciente, NombreCompleto, Telefono, CorreoElectronico, Activo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
);

if (!$sql) {
    die("Error en prepare: " . $conexion->error);
}

// aqui pongo los valores en la consulta
$sql->bind_param("sssiisssi", $usuario, $password, $rol, $medico_id, $paciente_id, $nombre_completo, $telefono, $correo, $activo);

// aqui ejecuto la consulta
if ($sql->execute()) {
    $nuevo_id = $conexion->insert_id;
    
    // JR: registro en bitacora con datos completos
    registrar_bitacora(
        $_SESSION['id_usuario'], 
        'Insertar', 
        'Usuarios', 
        'Insertó usuario: ' . $usuario . ' - Rol: ' . $rol,
        $nuevo_id,
        null,
        array(
            'Usuario' => $usuario,
            'Rol' => $rol,
            'NombreCompleto' => $nombre_completo,
            'Telefono' => $telefono,
            'CorreoElectronico' => $correo,
            'Activo' => $activo
        )
    );
    
    // aqui inserto las asignaciones si es recepcionista
    if ($rol == 'Recepcionista' && count($medicos_asignados) > 0) {
        $sql_asignacion = $conexion->prepare(
            "INSERT INTO Recepcionista_Medico (IdRecepcionista, IdMedico) VALUES (?, ?)"
        );
        
        foreach ($medicos_asignados as $id_medico) {
            $sql_asignacion->bind_param("ii", $nuevo_id, $id_medico);
            $sql_asignacion->execute();
        }
        $sql_asignacion->close();
    }
    
    header("Location: /Dashboard Medico/Usuarios.php?ok=1");
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
