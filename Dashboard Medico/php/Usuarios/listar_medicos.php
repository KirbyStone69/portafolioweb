<?php
// esto le dice al navegador que voy a enviar json
header('Content-Type: application/json');

// aqui me conecto a la base de datos
$conexion = new mysqli("localhost", "root", "edereder", "clinica_db");
if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}

// aqui verifico si la tabla Control_Medicos existe
$tabla_existe = $conexion->query("SHOW TABLES LIKE 'Control_Medicos'");

if ($tabla_existe && $tabla_existe->num_rows > 0) {
    // aqui hago el select para traer los medicos solo si la tabla existe
    $sql = "SELECT IdMedico, NombreCompleto FROM Control_Medicos WHERE Estatus = 1 ORDER BY NombreCompleto ASC";
    $respuesta = $conexion->query($sql);
    
    if ($respuesta) {
        $array = [];
        // aqui meto cada fila en el array
        while ($linea = $respuesta->fetch_assoc()) {
            $array[] = $linea;
        }
        echo json_encode($array);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Error al consultar medicos']);
    }
} else {
    // si no existe la tabla, devuelvo array vacio
    echo json_encode([]);
}

$conexion->close();
