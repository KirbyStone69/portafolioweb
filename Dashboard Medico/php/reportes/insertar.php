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

// aqui obtengo los datos del formulario
$tipo_reporte = $_POST['tipo_reporte'];
$id_medico = isset($_POST['id_medico']) && $_POST['id_medico'] !== '' ? $_POST['id_medico'] : NULL;
$id_paciente = isset($_POST['id_paciente']) && $_POST['id_paciente'] !== '' ? $_POST['id_paciente'] : NULL;
$descripcion = $_POST['descripcion'];
$generado_por = isset($_POST['generado_por']) ? $_POST['generado_por'] : 'Sistema';

// aqui genero la ruta del archivo (por ahora es placeholder)
$ruta_archivo = '/reportes/reporte_' . time() . '.pdf';

// aqui preparo la consulta para insertar
$sql = $conexion->prepare(
    "INSERT INTO Reportes (TipoReporte, IdPaciente, IdMedico, Descripcion, GeneradoPor, RutaArchivo) 
     VALUES (?, ?, ?, ?, ?, ?)"
);

if (!$sql) {
    die("Error en prepare: " . $conexion->error);
}

// aqui pongo los valores en la consulta
$sql->bind_param("siisss", $tipo_reporte, $id_paciente, $id_medico, $descripcion, $generado_por, $ruta_archivo);

// aqui ejecuto y redirijo
if ($sql->execute()) {
    // registro en bitacora
    registrarBitacora($_SESSION['usuario_id'], 'Generar reporte', 'Reportes');
    
    header("Location: ../../reportes.php?ok=1");
    $sql->close();
    $conexion->close();
    exit;
} else {
    header("Location: ../../reportes.php?ok=0");
    $sql->close();
    $conexion->close();
    exit;
}
?>
