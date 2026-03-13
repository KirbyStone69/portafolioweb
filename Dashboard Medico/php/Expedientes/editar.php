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

// aqui recibo los datos del formulario
$id = $_POST['id'];
$id_paciente = $_POST['id_paciente'];
$id_medico = $_POST['id_medico'];
$fecha_consulta = $_POST['fecha_consulta'];
$sintomas = $_POST['sintomas'];
$diagnostico = $_POST['diagnostico'];
$tratamiento = $_POST['tratamiento'];
$receta = $_POST['receta'];
$notas = $_POST['notas'];
$proxima_cita = $_POST['proxima_cita'];

// aqui preparo la consulta para actualizar
$sql = $conexion->prepare(
    "UPDATE Expediente_Clinico SET IdPaciente=?, IdMedico=?, FechaConsulta=?, Sintomas=?, Diagnostico=?, Tratamiento=?, RecetaMedica=?, NotasAdicionales=?, ProximaCita=? WHERE IdExpediente=?"
);

if (!$sql) {
    die("Error en prepare: " . $conexion->error);
}

// aqui verifico si proxima cita esta vacia
if (empty($proxima_cita)) {
    $proxima_cita = null;
}

// aqui asigno los parametros
$sql->bind_param("iisssssssi", $id_paciente, $id_medico, $fecha_consulta, $sintomas, $diagnostico, $tratamiento, $receta, $notas, $proxima_cita, $id);

// aqui ejecuto la actualizacion
if ($sql->execute()) {
    // registro en bitacora
    registrarBitacora($_SESSION['usuario_id'], 'Editar expediente', 'Expedientes');
    
    header("Location: ../../Expedientes.php?ok=2");
    $sql->close();
    $conexion->close();
    exit;
} else {
    header("Location: ../../Expedientes.php?ok=0");
    $sql->close();
    $conexion->close();
    exit;
}
?>
