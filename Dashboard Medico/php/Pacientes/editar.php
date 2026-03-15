<?php
// aqui inicio sesion y verifico que el usuario este logueado
session_start();
require_once '../login/verificar_sesion.php';
require_once '../login/registrar_bitacora.php';


$conexion = new mysqli("localhost", "root", "edereder", "clinica_db");
if ($conexion->connect_error) {
    die("Error de conexion: " . $conexion->connect_error);
}

// recibe los datos del formulario
$id = $_POST['id'];
$nombreCompleto = $_POST['nombre_completo'];
$curp = $_POST['curp'];
$fechaNacimiento = $_POST['fecha_nacimiento'];
$sexo = $_POST['sexo'];
$telefono = $_POST['telefono'];
$correo = $_POST['correo'];
$direccion = $_POST['direccion'];
$contactoEmergencia = $_POST['contacto_emergencia'];
$telefonoEmergencia = $_POST['telefono_emergencia'];
$alergias = $_POST['alergias'];
$antecedentes = $_POST['antecedentes'];

// prepara la consulta de actualizacion
$sql = $conexion->prepare(
    "UPDATE Control_Pacientes SET NombreCompleto=?, CURP=?, FechaNacimiento=?, Sexo=?, Telefono=?, CorreoElectronico=?, Direccion=?, ContactoEmergencia=?, TelefonoEmergencia=?, Alergias=?, AntecedentesMedicos=? WHERE IdPaciente=?"
);

if (!$sql) {
    die("Error en prepare: " . $conexion->error);
}

// asigna los parametros
$sql->bind_param("sssssssssssi", $nombreCompleto, $curp, $fechaNacimiento, $sexo, $telefono, $correo, $direccion, $contactoEmergencia, $telefonoEmergencia, $alergias, $antecedentes, $id);

// aqui obtengo los datos anteriores antes de actualizar
$datos_anteriores = obtener_datos_anteriores($conexion, 'Control_Pacientes', 'IdPaciente', $id);

// ejecuta la consulta
if ($sql->execute()) {
    // registro en bitacora
    registrar_bitacora(
        $_SESSION['id_usuario'],
        'Editar',
        'Pacientes',
        'Editó paciente: ' . $nombreCompleto,
        $id,
        $datos_anteriores,
        array(
            'NombreCompleto' => $nombreCompleto,
            'CURP' => $curp,
            'FechaNacimiento' => $fechaNacimiento,
            'Sexo' => $sexo,
            'Telefono' => $telefono,
            'CorreoElectronico' => $correo
        )
    );
    
    header("Location: /practica-9/Pacientes.php?ok=2");
    $sql->close();
    $conexion->close();
    exit;
} else {
    header("Location: /practica-9/Pacientes.php?ok=0");
    $sql->close();
    $conexion->close();
    exit;
}
?>
