<?php
// aqui inicio sesion y verifico que el usuario este logueado
session_start();
require_once '../login/verificar_sesion.php';
require_once '../login/registrar_bitacora.php';

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

// JR: obtengo los datos anteriores antes de actualizar
$datos_anteriores = obtener_datos_anteriores($conexion, 'Expediente_Clinico', 'IdExpediente', $id);

// aqui ejecuto la actualizacion
if ($sql->execute()) {
    // JR: registro en bitacora con datos completos
    registrar_bitacora(
        $_SESSION['id_usuario'],
        'Editar',
        'Expedientes',
        'Editó expediente clínico #' . $id . ' - Paciente ID: ' . $id_paciente,
        $id,
        $datos_anteriores,
        array(
            'IdPaciente' => $id_paciente,
            'IdMedico' => $id_medico,
            'FechaConsulta' => $fecha_consulta,
            'Sintomas' => $sintomas,
            'Diagnostico' => $diagnostico,
            'Tratamiento' => $tratamiento,
            'RecetaMedica' => $receta,
            'NotasAdicionales' => $notas,
            'ProximaCita' => $proxima_cita
        )
    );
    
    header("Location: /practica-9/Expedientes.php?ok=2");
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
