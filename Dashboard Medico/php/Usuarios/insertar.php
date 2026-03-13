<?php
// aqui inicio sesion y verifico que el usuario este logueado
session_start();
require_once '../auth/verificar_sesion.php';
require_once '../auth/registrar_bitacora.php';

// aqui me conecto a la base de datos
$conexion = new mysqli("localhost", "root", "edereder", "clinica_db");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// aqui obtengo los datos que me enviaron del formulario
$usuario = $_POST['usuario'];
$password = $_POST['password'];
$rol = $_POST['rol'];
$medico_id = $_POST['medico_id'] != '' ? $_POST['medico_id'] : NULL;
$activo = isset($_POST['activo']) ? 1 : 0;
$nombre_completo = $_POST['nombre_completo'];
$telefono = $_POST['telefono'];
$correo = $_POST['correo'];

$medicos_asignados = isset($_POST['medicos_asignados']) ? $_POST['medicos_asignados'] : array();

// aqui preparo la consulta para insertar
$sql = $conexion->prepare(
    "INSERT INTO Usuarios_Sistema (Usuario, Contrasena, Rol, IdMedico, NombreCompleto, Telefono, CorreoElectronico, Activo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
);

if (!$sql) {
    die("Error en prepare: " . $conexion->error);
}

// aqui pongo los valores en la consulta
$sql->bind_param("sssisssi", $usuario, $password, $rol, $medico_id, $nombre_completo, $telefono, $correo, $activo);

// aqui ejecuto la consulta
if ($sql->execute()) {
    // registro en bitacora
    registrarBitacora($_SESSION['usuario_id'], 'Insertar usuario', 'Usuarios');
    
    $nuevo_id = $conexion->insert_id;
    
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
    
    header("Location: ../../Usuarios.php?ok=1");
    $sql->close();
    $conexion->close();
    exit;
} else {
    header("Location: ../../Usuarios.php?ok=0");
    $sql->close();
    $conexion->close();
    exit;
}
?>
