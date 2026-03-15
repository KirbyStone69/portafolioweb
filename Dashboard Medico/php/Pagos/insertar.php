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
$id_cita = $_POST['id_cita'];
$id_paciente = $_POST['id_paciente'];
$monto = $_POST['monto'];
$metodo_pago = $_POST['metodo_pago'];
$referencia = $_POST['referencia'];
$estatus_pago = $_POST['estatus_pago'];
$id_usuario_recibe = $_SESSION['id_usuario']; // quien recibe el pago

// aqui obtengo los servicios (array de objetos JSON)
$servicios = isset($_POST['servicios']) ? json_decode($_POST['servicios'], true) : [];

// aqui decodifico el JSON de servicios
// (The previous line already decodes, this is for clarity if $_POST['servicios'] is always expected)
// $servicios = json_decode($_POST['servicios'], true); // This line is redundant if the above line is kept

// aqui valido que el monto coincida con la suma de servicios
$total_calculado = 0;
foreach ($servicios as $servicio) {
    $total_calculado += floatval($servicio['subtotal']);
}

if (abs($total_calculado - $monto) > 0.01) {
    header("Location: /Dashboard Medico/Pagos.php?ok=0&error=" . urlencode("El total no coincide con la suma de servicios"));
    exit;
}

// inicio transaccion para que todo se guarde o nada
$conexion->begin_transaction();

try {
    // aqui preparo la consulta para insertar el pago
    $sql = $conexion->prepare(
        "INSERT INTO Gestor_Pagos (IdCita, IdPaciente, Monto, MetodoPago, Referencia, EstatusPago, IdUsuarioRecibe) 
         VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    
    if (!$sql) {
        throw new Exception("Error en prepare pago: " . $conexion->error);
    }
    
    // aqui pongo los valores en la consulta
    $sql->bind_param("iidsssi", $id_cita, $id_paciente, $monto, $metodo_pago, $referencia, $estatus_pago, $id_usuario_recibe);
    
    // aqui ejecuto la consulta
    if (!$sql->execute()) {
        throw new Exception("Error al insertar pago: " . $sql->error);
    }
    
    // aqui obtengo el id del pago que acabo de insertar
    $id_pago = $conexion->insert_id;
    $sql->close();
    
    // aqui inserto cada servicio en Detalle_Pagos
    if (count($servicios) > 0) {
        $sql_detalle = $conexion->prepare(
            "INSERT INTO Detalle_Pagos (IdPago, IdTarifa, Cantidad, PrecioUnitario, Subtotal, Descripcion) 
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        
        if (!$sql_detalle) {
            throw new Exception("Error en prepare detalle: " . $conexion->error);
        }
        
        foreach ($servicios as $servicio) {
            $id_tarifa = $servicio['id_tarifa'];
            $cantidad = $servicio['cantidad'];
            $precio_unitario = $servicio['precio_unitario'];
            $subtotal = $servicio['subtotal'];
            $descripcion = $servicio['descripcion'];
            
            $sql_detalle->bind_param("iiidds", $id_pago, $id_tarifa, $cantidad, $precio_unitario, $subtotal, $descripcion);
            
            if (!$sql_detalle->execute()) {
                throw new Exception("Error al insertar detalle: " . $sql_detalle->error);
            }
        }
        
        $sql_detalle->close();
    }
    
    // si todo salio bien, confirmo la transaccion
    $conexion->commit();
    
    // JR: registro en bitacora con datos completos
    registrar_bitacora(
        $_SESSION['id_usuario'],
        'Insertar',
        'Pagos',
        'Registró pago #' . $id_pago . ' - Monto: $' . $monto . ' - Método: ' . $metodo_pago,
        $id_pago,
        null,
        array(
            'IdCita' => $id_cita,
            'IdPaciente' => $id_paciente,
            'Monto' => $monto,
            'MetodoPago' => $metodo_pago,
            'Referencia' => $referencia,
            'EstatusPago' => $estatus_pago,
            'IdUsuarioRecibe' => $id_usuario_recibe,
            'Servicios' => $servicios
        )
    );
    
    header("Location: /Dashboard Medico/Pagos.php?ok=1");
    $conexion->close();
    exit;
    
} catch (Exception $e) {
    // si algo fallo, deshago todo
    $conexion->rollback();
    error_log("Error en insertar pago: " . $e->getMessage());
    header("Location: /Dashboard Medico/Pagos.php?ok=0&error=" . urlencode($e->getMessage()));
    $conexion->close();
    exit;
}
?>
