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
$nombre = $_POST['nombre'];
$cedula = $_POST['cedula'];
$especialidad_id = $_POST['especialidad_id'];
$telefono = $_POST['telefono'];
$correo = $_POST['correo'];
$estatus = isset($_POST['estatus']) ? 1 : 0;

// aqui construyo el JSON de horarios
$horarios = array(
    'lunes' => array(
        'trabaja' => isset($_POST['lunes_trabaja']),
        'inicio' => isset($_POST['lunes_inicio']) ? $_POST['lunes_inicio'] : '',
        'fin' => isset($_POST['lunes_fin']) ? $_POST['lunes_fin'] : ''
    ),
    'martes' => array(
        'trabaja' => isset($_POST['martes_trabaja']),
        'inicio' => isset($_POST['martes_inicio']) ? $_POST['martes_inicio'] : '',
        'fin' => isset($_POST['martes_fin']) ? $_POST['martes_fin'] : ''
    ),
    'miercoles' => array(
        'trabaja' => isset($_POST['miercoles_trabaja']),
        'inicio' => isset($_POST['miercoles_inicio']) ? $_POST['miercoles_inicio'] : '',
        'fin' => isset($_POST['miercoles_fin']) ? $_POST['miercoles_fin'] : ''
    ),
    'jueves' => array(
        'trabaja' => isset($_POST['jueves_trabaja']),
        'inicio' => isset($_POST['jueves_inicio']) ? $_POST['jueves_inicio'] : '',
        'fin' => isset($_POST['jueves_fin']) ? $_POST['jueves_fin'] : ''
    ),
    'viernes' => array(
        'trabaja' => isset($_POST['viernes_trabaja']),
        'inicio' => isset($_POST['viernes_inicio']) ? $_POST['viernes_inicio'] : '',
        'fin' => isset($_POST['viernes_fin']) ? $_POST['viernes_fin'] : ''
    ),
    'sabado' => array(
        'trabaja' => isset($_POST['sabado_trabaja']),
        'inicio' => isset($_POST['sabado_inicio']) ? $_POST['sabado_inicio'] : '',
        'fin' => isset($_POST['sabado_fin']) ? $_POST['sabado_fin'] : ''
    ),
    'domingo' => array(
        'trabaja' => isset($_POST['domingo_trabaja']),
        'inicio' => isset($_POST['domingo_inicio']) ? $_POST['domingo_inicio'] : '',
        'fin' => isset($_POST['domingo_fin']) ? $_POST['domingo_fin'] : ''
    )
);

$horario_json = json_encode($horarios);

// aqui preparo la consulta para insertar
$sql = $conexion->prepare(
    "INSERT INTO Control_Medicos (NombreCompleto, CedulaProfesional, EspecialidadId, Telefono, CorreoElectronico, HorarioAtencion, Estatus) VALUES (?, ?, ?, ?, ?, ?, ?)"
);

if (!$sql) {
    die("Error en prepare: " . $conexion->error);
}

// aqui pongo los valores en la consulta
$sql->bind_param("ssisssi", $nombre, $cedula, $especialidad_id, $telefono, $correo, $horario_json, $estatus);

// aqui ejecuto la consulta y redirijo
if ($sql->execute()) {
    // registro en bitacora
    registrarBitacora($_SESSION['usuario_id'], 'Insertar medico', 'Medicos');
    
    header("Location: ../../Medicos.php?ok=1");
    $sql->close();
    $conexion->close();
    exit;
} else {
    header("Location: ../../Medicos.php?ok=0");
    $sql->close();
    $conexion->close();
    exit;
}
?>
