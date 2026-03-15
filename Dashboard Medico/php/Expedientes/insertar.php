<?php 
// aqui inicio sesion y verifico que este logueado
session_start();
require_once '../login/verificar_sesion.php';
require_once '../login/registrar_bitacora.php';

// aqui me conecto a la base de datos
$conexion = new mysqli("localhost", "root", "edereder", "clinica_db");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// aqui recibo los datos del formulario
$id_paciente = $_POST['id_paciente'];
$id_medico = $_POST['id_medico'];
$fecha_consulta = $_POST['fecha_consulta'];
$sintomas = $_POST['sintomas'];
$diagnostico = $_POST['diagnostico'];
$tratamiento = $_POST['tratamiento'];
$receta = $_POST['receta'];
$notas = $_POST['notas'];
$proxima_cita = $_POST['proxima_cita'];
$id_cita = isset($_POST['id_cita']) ? $_POST['id_cita'] : null; // opcional

// aqui preparo la consulta para insertar con IdCita opcional
$sql = $conexion->prepare(
    "INSERT INTO Expediente_Clinico (IdPaciente, IdMedico, IdCita, FechaConsulta, Sintomas, Diagnostico, Tratamiento, RecetaMedica, NotasAdicionales, ProximaCita) 
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
);

if (!$sql) {
    die("Error en prepare: " . $conexion->error);
}

// aqui asigno los parametros - la proxima cita y id_cita pueden ser vacias
if (empty($proxima_cita)) {
    $proxima_cita = null;
}
if (empty($id_cita)) {
    $id_cita = null;
}

// aqui pongo los valores en la consulta
$sql->bind_param("iiisssssss", $id_paciente, $id_medico, $id_cita, $fecha_consulta, $sintomas, $diagnostico, $tratamiento, $receta, $notas, $proxima_cita);

// aqui ejecuto la consulta y registro en bitacora
if ($sql->execute()) {
    $id_nuevo_expediente = $conexion->insert_id;
    
    // JR: registro en bitacora con datos completos
    registrar_bitacora(
        $_SESSION['id_usuario'],
        'Insertar',
        'Expedientes',
        'Creó expediente clínico #' . $id_nuevo_expediente . ' - Paciente ID: ' . $id_paciente . ' - Médico ID: ' . $id_medico,
        $id_nuevo_expediente,
        null,
        array(
            'IdPaciente' => $id_paciente,
            'IdMedico' => $id_medico,
            'IdCita' => $id_cita,
            'FechaConsulta' => $fecha_consulta,
            'Sintomas' => $sintomas,
            'Diagnostico' => $diagnostico,
            'Tratamiento' => $tratamiento,
            'RecetaMedica' => $receta,
            'NotasAdicionales' => $notas,
            'ProximaCita' => $proxima_cita
        )
    );
    
    header("Location: /practica-9/Expedientes.php?ok=1");
    $sql->close();
    $conexion->close();
    exit;
} else {
    header("Location: /practica-9/Expedientes.php?ok=0");
    $sql->close();
    $conexion->close();
    exit;
}
?>
