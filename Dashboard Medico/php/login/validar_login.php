<?php
// aqui inicio la sesion
session_start();

// aqui me conecto a la base de datos
$conexion = new mysqli("localhost", "root", "edereder", "clinica_db");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// aqui recibo los datos del formulario
$usuario = $_POST['usuario'];
$contrasena = $_POST['password'];

// aqui busco el usuario en la base de datos
$sql_usuario = $conexion->prepare("SELECT IdUsuario, Usuario, Rol, IdMedico, IdPaciente, NombreCompleto, Activo FROM Usuarios_Sistema WHERE Usuario = ? AND Contrasena = ?");
$sql_usuario->bind_param("ss", $usuario, $contrasena);
$sql_usuario->execute();
$resultado = $sql_usuario->get_result();

// aqui verifico si encontro el usuario
if ($resultado->num_rows === 1) {
    $datos_usuario = $resultado->fetch_assoc();
    
    // aqui verifico si el usuario esta activo
    if ($datos_usuario['Activo'] == 1) {
        // aqui guardo los datos en la sesion
        $_SESSION['id_usuario'] = $datos_usuario['IdUsuario'];
        $_SESSION['nombre_usuario'] = $datos_usuario['Usuario'];
        $_SESSION['nombre_completo'] = $datos_usuario['NombreCompleto'];
        $_SESSION['rol_usuario'] = $datos_usuario['Rol'];
        $_SESSION['id_medico'] = $datos_usuario['IdMedico'];
        $_SESSION['id_paciente'] = $datos_usuario['IdPaciente'];
        $_SESSION['sesion_iniciada'] = true;
        $_SESSION['ultimo_acceso'] = time();
        
        // aqui actualizo el ultimo acceso
        $sql_actualizar = $conexion->prepare("UPDATE Usuarios_Sistema SET UltimoAcceso = NOW() WHERE IdUsuario = ?");
        $sql_actualizar->bind_param("i", $datos_usuario['IdUsuario']);
        $sql_actualizar->execute();
        
        // aqui registro en la bitacora el inicio de sesion
        require_once 'registrar_bitacora.php';
        registrar_bitacora(
            $datos_usuario['IdUsuario'],
            'Login',
            'Sistema',
            'Usuario ' . $datos_usuario['Usuario'] . ' (' . $datos_usuario['Rol'] . ') inició sesión',
            null,
            null,
            array(
                'usuario' => $datos_usuario['Usuario'],
                'rol' => $datos_usuario['Rol'],
                'nombre' => $datos_usuario['NombreCompleto']
            )
        );
        
        // aqui redirijo segun el rol
        if ($datos_usuario['Rol'] === 'Paciente') {
            // los pacientes van directo a la agenda
            header("Location: /practica-9/agenda.php");
        } else {
            // el resto del staff va al dashboard
            header("Location: /practica-9/Dashboard.php");
        }
        exit;
    } else {
        // usuario inactivo
        header("Location: /practica-9/index.php?error=1");
        exit;
    }
} else {
    // usuario o contraseña incorrectos
    header("Location: /practica-9/index.php?error=1");
    exit;
}

$sql_usuario->close();
$conexion->close();
?>
