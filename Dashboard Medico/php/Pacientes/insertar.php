<?php
// aqui inicio sesion y verifico que el usuario este logueado
session_start();
require_once '../auth/verificar_sesion.php';
require_once '../auth/registrar_bitacora.php';


$conexion = new mysqli("localhost", "root", "edereder", "clinica_db");
if ($conexion->connect_error) {
    die("Error de conexion: " . $conexion->connect_error);
}

// recibe los datos del formulario
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

// prepara la consulta
$sql = $conexion->prepare(
    "INSERT INTO Control_Pacientes (NombreCompleto, CURP, FechaNacimiento, Sexo, Telefono, CorreoElectronico, Direccion, ContactoEmergencia, TelefonoEmergencia, Alergias, AntecedentesMedicos) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
);

if (!$sql) {
    die("Error en prepare: " . $conexion->error);
}

// asigna los parametros
$sql->bind_param("sssssssssss", $nombreCompleto, $curp, $fechaNacimiento, $sexo, $telefono, $correo, $direccion, $contactoEmergencia, $telefonoEmergencia, $alergias, $antecedentes);

// ejecuta la consulta
if ($sql->execute()) {
    // registro en bitacora
    registrarBitacora($_SESSION['usuario_id'], 'Insertar paciente', 'Pacientes');
    
      header("Location: ../../Pacientes.php?ok=1");
      $sql->close();
      $conexion->close();
  exit;
} else {
      header("Location: ../../Pacientes.php?ok=0");
      $sql->close();
      $conexion->close();
      exit;
}
?>
