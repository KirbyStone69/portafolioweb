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
$descripcion = $_POST['descripcion'];
$costo = $_POST['costo'];
$especialidad_id = $_POST['especialidad_id'];
$estatus = isset($_POST['estatus']) ? 1 : 0;

// aqui preparo la consulta para actualizar
$sql = $conexion->prepare(
    "UPDATE Gestor_Tarifas SET DescripcionServicio = ?, CostoBase = ?, EspecialidadId = ?, Estatus = ? WHERE IdTarifa = ?"
);

if (!$sql) {
    die("Error en prepare: " . $conexion->error);
}

// aqui pongo los valores en la consulta
$sql->bind_param("sdiii", $descripcion, $costo, $especialidad_id, $estatus, $id);

// JR: obtengo datos anteriores antes de actualizar
$datos_anteriores = obtener_datos_anteriores($conexion, 'Gestor_Tarifas', 'IdTarifa', $id);

// aqui ejecuto la consulta y redirijo
if ($sql->execute()) {
    // JR: registro en bitacora con datos completos
    registrar_bitacora(
        $_SESSION['id_usuario'],
        'Editar',
        'Tarifas',
        'Editó tarifa #' . $id . ': ' . $descripcion,
        $id,
        $datos_anteriores,
        array(
            'DescripcionServicio' => $descripcion,
            'CostoBase' => $costo,
            'EspecialidadId' => $especialidad_id,
            'Estatus' => $estatus
        )
    );
    
    header("Location: /Dashboard Medico/Tarifas.php?ok=2");
    $sql->close();
    $conexion->close();
    exit;
} else {
    header("Location: /Dashboard Medico/Tarifas.php?ok=0");
    $sql->close();
    $conexion->close();
    exit;
}
?>
