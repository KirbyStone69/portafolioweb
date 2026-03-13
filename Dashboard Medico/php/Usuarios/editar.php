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
$id = $_POST['id'];
$usuario = $_POST['usuario'];
$rol = $_POST['rol'];
$medico_id = $_POST['medico_id'] != '' ? $_POST['medico_id'] : NULL;
$activo = isset($_POST['activo']) ? 1 : 0;
$nombre_completo = $_POST['nombre_completo'];
$telefono = $_POST['telefono'];
$correo = $_POST['correo'];

// aqui obtengo los medicos asignados si es recepcionista
$medicos_asignados = isset($_POST['medicos_asignados']) ? $_POST['medicos_asignados'] : array();

// aqui verifico si me enviaron una nueva contraseña
if (isset($_POST['password']) && $_POST['password'] != '') {
    $password = $_POST['password'];
    
    // aqui actualizo con la nueva contraseña
    $sql = $conexion->prepare(
        "UPDATE Usuarios_Sistema SET Usuario = ?, Contrasena = ?, Rol = ?, IdMedico = ?, NombreCompleto = ?, Telefono = ?, CorreoElectronico = ?, Activo = ? WHERE IdUsuario = ?"
    );
    $sql->bind_param("ssissssii", $usuario, $password, $rol, $medico_id, $nombre_completo, $telefono, $correo, $activo, $id);
} else {
    // aqui actualizo sin cambiar la contraseña
    $sql = $conexion->prepare(
        "UPDATE Usuarios_Sistema SET Usuario = ?, Rol = ?, IdMedico = ?, NombreCompleto = ?, Telefono = ?, CorreoElectronico = ?, Activo = ? WHERE IdUsuario = ?"
    );
    $sql->bind_param("ssisssii", $usuario, $rol, $medico_id, $nombre_completo, $telefono, $correo, $activo, $id);
}

if (!$sql) {
    die("Error en prepare: " . $conexion->error);
}

// aqui ejecuto la consulta
if ($sql->execute()) {
    // registro en bitacora
    registrarBitacora($_SESSION['usuario_id'], 'Editar usuario', 'Usuarios');
    
    
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
    
    header("Location: ../../Usuarios.php?ok=2");
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
