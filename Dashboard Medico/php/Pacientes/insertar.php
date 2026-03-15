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

// aqui valido que el CURP no exista (si se proporciono)
if (!empty($curp)) {
    $sql_check = $conexion->prepare("SELECT IdPaciente FROM Control_Pacientes WHERE CURP = ?");
    $sql_check->bind_param("s", $curp);
    $sql_check->execute();
    if ($sql_check->get_result()->num_rows > 0) {
        header("Location: /Dashboard Medico/Pacientes.php?ok=0&error=" . urlencode("El CURP ya existe"));
        $sql_check->close();
        $conexion->close();
        exit;
    }
    $sql_check->close();
}

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
    $id_nuevo = $conexion->insert_id;
    
    // registro en bitacora
    registrar_bitacora(
        $_SESSION['id_usuario'],
        'Insertar',
        'Pacientes',
        'Insertó paciente: ' . $nombreCompleto . ' (CURP: ' . $curp . ')',
        $id_nuevo,
        null,
        array(
            'NombreCompleto' => $nombreCompleto,
            'CURP' => $curp,
            'FechaNacimiento' => $fechaNacimiento,
            'Sexo' => $sexo,
            'Telefono' => $telefono,
            'CorreoElectronico' => $correo
        )
    );
    
      header("Location: /Dashboard Medico/Pacientes.php?ok=1");
      $sql->close();
      $conexion->close();
  exit;
} else {
      header("Location: /Dashboard Medico/Pacientes.php?ok=0");
      $sql->close();
      $conexion->close();
      exit;
}
?>
