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
$password = $_POST['password'];

// aqui busco el usuario en la base de datos
$sql = $conexion->prepare("SELECT IdUsuario, Usuario, Rol, IdMedico, NombreCompleto, Activo FROM Usuarios_Sistema WHERE Usuario = ? AND Contrasena = ?");
$sql->bind_param("ss", $usuario, $password);
$sql->execute();
$resultado = $sql->get_result();

// aqui verifico si encontro el usuario
if ($resultado->num_rows === 1) {
    $user = $resultado->fetch_assoc();
    
    // aqui verifico si el usuario esta activo
    if ($user['Activo'] == 1) {
        // aqui guardo los datos en la sesion
        $_SESSION['usuario_id'] = $user['IdUsuario'];
        $_SESSION['usuario_nombre'] = $user['Usuario'];
        $_SESSION['usuario_nombre_completo'] = $user['NombreCompleto'];
        $_SESSION['usuario_rol'] = $user['Rol'];
        $_SESSION['usuario_medico_id'] = $user['IdMedico'];
        $_SESSION['sesion_iniciada'] = true;
        
        // aqui actualizo el ultimo acceso
        $sqlUpdate = $conexion->prepare("UPDATE Usuarios_Sistema SET UltimoAcceso = NOW() WHERE IdUsuario = ?");
        $sqlUpdate->bind_param("i", $user['IdUsuario']);
        $sqlUpdate->execute();
        
        // aqui registro en la bitacora el inicio de sesion
        $sqlBitacora = $conexion->prepare("INSERT INTO Bitacora_Acceso (IdUsuario, AccionRealizada, Modulo) VALUES (?, 'Inicio de sesión', 'Login')");
        $sqlBitacora->bind_param("i", $user['IdUsuario']);
        $sqlBitacora->execute();
        
        // aqui redirijo al dashboard
        header("Location: ../../Dashboard.php");
        exit;
    } else {
        // usuario inactivo
        header("Location: ../../login.php?error=1");
        exit;
    }
} else {
    // usuario o contraseña incorrectos
    header("Location: ../../login.php?error=1");
    exit;
}

$sql->close();
$conexion->close();
?>
