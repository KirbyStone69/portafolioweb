<?php
// aqui inicio sesion y verifico que el usuario este logueado
session_start();
require_once '../login/verificar_sesion.php';
require_once '../login/registrar_bitacora.php';

// aqui me conecto a la base de datos
require_once __DIR__ . "/../mock_db.php";
$conexion = new MockMysqli();
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// aqui obtengo los datos que me enviaron del formulario
$id = $_POST['id'];
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

// aqui preparo la consulta para actualizar
$sql = $conexion->prepare(
    "UPDATE Control_Medicos SET NombreCompleto = ?, CedulaProfesional = ?, EspecialidadId = ?, Telefono = ?, CorreoElectronico = ?, HorarioAtencion = ?, Estatus = ? WHERE IdMedico = ?"
);

if (!$sql) {
    die("Error en prepare: " . $conexion->error);
}

// aqui pongo los valores en la consulta
$sql->bind_param("ssisssii", $nombre, $cedula, $especialidad_id, $telefono, $correo, $horario_json, $estatus, $id);

// JR: aqui obtengo los datos anteriores antes de actualizar
$datos_anteriores = obtener_datos_anteriores($conexion, 'Control_Medicos', 'IdMedico', $id);

// aqui ejecuto la consulta y redirijo
if ($sql->execute()) {
    // JR: registro en bitacora con datos completos
    registrar_bitacora(
        $_SESSION['id_usuario'], 
        'Editar', 
        'Medicos', 
        'Editó médico: ' . $nombre . ' (ID: ' . $id . ')',
        $id,
        $datos_anteriores,
        array(
            'NombreCompleto' => $nombre,
            'CedulaProfesional' => $cedula,
            'EspecialidadId' => $especialidad_id,
            'Telefono' => $telefono,
            'CorreoElectronico' => $correo,
            'Estatus' => $estatus
        )
    );
    
    header("Location: /Dashboard Medico/Medicos.php?ok=2");
    $sql->close();
    $conexion->close();
    exit;
} else {
    header("Location: /Dashboard Medico/Medicos.php?ok=0");
    $sql->close();
    $conexion->close();
    exit;
}
?>
