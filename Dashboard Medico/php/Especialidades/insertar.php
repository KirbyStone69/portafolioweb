<?php 
// aqui inicio sesion y verifico que este logueado
session_start();
require_once '../login/verificar_sesion.php';
require_once '../login/registrar_bitacora.php';

$conexion = new mysqli("localhost", "root", "edereder", "clinica_db");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// aqui recibo los datos
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];

// aqui preparo la consulta
$sql = $conexion->prepare("INSERT INTO Especialidades (NombreEspecialidad, Descripcion) VALUES (?, ?)");

if (!$sql) {
    die("Error en prepare: " . $conexion->error);
}

$sql->bind_param("ss", $nombre, $descripcion);

// aqui ejecuto y registro en bitacora
if ($sql->execute()) {
    $id_nuevo = $conexion->insert_id;
    
    // JR: registro en bitacora con datos completos
    registrar_bitacora(
        $_SESSION['id_usuario'], 
        'Insertar', 
        'Especialidades', 
        'Insertó especialidad: ' . $nombre,
        $id_nuevo,
        null,
        array(
            'NombreEspecialidad' => $nombre,
            'Descripcion' => $descripcion
        )
    );
    
    header("Location: /Dashboard Medico/Especialidades.php?ok=1");
    $sql->close();
    $conexion->close();
    exit;
} else {
    header("Location: /Dashboard Medico/Especialidades.php?ok=0");
    $sql->close();
    $conexion->close();
    exit;
}
?>
