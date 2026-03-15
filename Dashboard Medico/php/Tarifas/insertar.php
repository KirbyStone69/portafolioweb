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

// aqui obtengo los datos que me enviaron del formulario
$descripcion = $_POST['descripcion'];
$costo = $_POST['costo'];
$especialidad_id = $_POST['especialidad_id'];
$estatus = isset($_POST['estatus']) ? 1 : 0;

// aqui preparo la consulta para insertar
$sql = $conexion->prepare(
    "INSERT INTO Gestor_Tarifas (DescripcionServicio, CostoBase, EspecialidadId, Estatus) VALUES (?, ?, ?, ?)"
);

if (!$sql) {
    die("Error en prepare: " . $conexion->error);
}

// aqui pongo los valores en la consulta (ndeaaaaaaaa)
$sql->bind_param("sdii", $descripcion, $costo, $especialidad_id, $estatus);

// aqui ejecuto la consulta y redirijo
if ($sql->execute()) {
    $id_nuevo = $conexion->insert_id;
    
    // JR: registro en bitacora con datos completos
    registrar_bitacora(
        $_SESSION['id_usuario'], 
        'Insertar', 
        'Tarifas', 
        'Insertó tarifa: ' . $descripcion . ' - Costo: $' . $costo,
        $id_nuevo,
        null,
        array(
            'DescripcionServicio' => $descripcion,
            'CostoBase' => $costo,
            'EspecialidadId' => $especialidad_id,
            'Estatus' => $estatus
        )
    );
    
    header("Location: /Dashboard Medico/Tarifas.php?ok=1");
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
