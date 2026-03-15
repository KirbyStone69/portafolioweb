<?php
// aqui no inicio sesion porque esto es para usuarios que NO estan logueados

// aqui me conecto a la base de datos
$conexion = new mysqli("localhost", "root", "edereder", "clinica_db");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// aqui recibo los datos del formulario
$nombre_completo = $_POST['nombre_completo'];
$curp = strtoupper($_POST['curp']); // aqui lo convierto a mayusculas
$fecha_nacimiento = $_POST['fecha_nacimiento'];
$sexo = $_POST['sexo'];
$telefono = $_POST['telefono'];
$correo = $_POST['correo'];
$direccion = isset($_POST['direccion']) ? $_POST['direccion'] : '';
$usuario = $_POST['usuario'];
$contrasena = $_POST['password'];
$contrasena_confirmar = $_POST['password_confirm'];

// aqui verifico que las contraseñas coincidan
if ($contrasena !== $contrasena_confirmar) {
    header("Location: /Dashboard Medico/registro.php?error=1");
    exit;
}

// aqui verifico si el usuario ya existe
$sql_verificar = $conexion->prepare("SELECT IdUsuario FROM Usuarios_Sistema WHERE Usuario = ?");
$sql_verificar->bind_param("s", $usuario);
$sql_verificar->execute();
$resultado_verificar = $sql_verificar->get_result();

if ($resultado_verificar->num_rows > 0) {
    // aqui significa que el usuario ya existe
    header("Location: /Dashboard Medico/registro.php?error=2");
    $sql_verificar->close();
    $conexion->close();
    exit;
}

// aqui verifico si el CURP ya existe en pacientes
$sql_verificar_curp = $conexion->prepare("SELECT IdPaciente FROM Control_Pacientes WHERE CURP = ?");
$sql_verificar_curp->bind_param("s", $curp);
$sql_verificar_curp->execute();
$resultado_verificar_curp = $sql_verificar_curp->get_result();

if ($resultado_verificar_curp->num_rows > 0) {
    // aqui significa que el CURP ya esta registrado
    header("Location: /Dashboard Medico/registro.php?error=2");
    $sql_verificar_curp->close();
    $conexion->close();
    exit;
}

// aqui inicio una transaccion para que si algo falla, todo se deshaga
$conexion->begin_transaction();

try {
    // aqui primero inserto el paciente en Control_Pacientes
    $sql_paciente = $conexion->prepare(
        "INSERT INTO Control_Pacientes (NombreCompleto, CURP, FechaNacimiento, Sexo, Telefono, CorreoElectronico, Direccion) 
         VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    $sql_paciente->bind_param("sssssss", $nombre_completo, $curp, $fecha_nacimiento, $sexo, $telefono, $correo, $direccion);
    $sql_paciente->execute();
    
    // aqui obtengo el ID del paciente que acabo de insertar
    $id_paciente = $conexion->insert_id;
    
    // JR: aqui ahora inserto el usuario en Usuarios_Sistema con rol Paciente
    $rol = "Paciente";
    $activo = 1; // aqui lo dejo activo de una vez
    $sql_usuario = $conexion->prepare(
        "INSERT INTO Usuarios_Sistema (Usuario, Contrasena, Rol, IdPaciente, NombreCompleto, Telefono, CorreoElectronico, Activo) 
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );
    // JR: CORREGIDO - antes tenia espacios "sssiss si", ahora es "sssisssi" (8 parametros sin espacios)
    // JR: Usuario(s), Contrasena(s), Rol(s), IdPaciente(i), NombreCompleto(s), Telefono(s), Correo(s), Activo(i)
    $sql_usuario->bind_param("sssisssi", $usuario, $contrasena, $rol, $id_paciente, $nombre_completo, $telefono, $correo, $activo);
    $sql_usuario->execute();
    $id_nuevo_usuario = $conexion->insert_id;
    
    // aqui si todo salio bien, confirmo la transaccion
    $conexion->commit();
    
    // JR: registro en bitacora el autoregistro del paciente
    require_once 'registrar_bitacora.php';
    registrar_bitacora(
        $id_nuevo_usuario,
        'Insertar',
        'Sistema',
        'Paciente se autoregistró: ' . $nombre_completo . ' (Usuario: ' . $usuario . ')',
        $id_paciente,
        null,
        array(
            'NombreCompleto' => $nombre_completo,
            'CURP' => $curp,
            'FechaNacimiento' => $fecha_nacimiento,
            'Sexo' => $sexo,
            'Telefono' => $telefono,
            'CorreoElectronico' => $correo,
            'Direccion' => $direccion,
            'Usuario' => $usuario,
            'Rol' => $rol
        )
    );
    
    // aqui redirijo con exito
    header("Location: /Dashboard Medico/registro.php?ok=1");
    
    $sql_paciente->close();
    $sql_usuario->close();
    $conexion->close();
    exit;
    
} catch (Exception $e) {
    // aqui si algo salio mal, deshago todo
    $conexion->rollback();
    header("Location: /Dashboard Medico/registro.php?error=3");
    $conexion->close();
    exit;
}
?>
