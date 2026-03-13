<?php
// este archivo validara si un medico esta disponible en cierta fecha y hora
header('Content-Type: application/json');

$conexion = new mysqli("localhost", "root", "edereder", "clinica_db");
if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}

// aqui obtengo los parametros
$id_medico = $_GET['id_medico'];
$fecha = $_GET['fecha']; // formato: YYYY-MM-DD
$hora = $_GET['hora']; // formato: HH:MM

// aqui obtengo el dia de la semana
$dias_semana = array('domingo', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado');
$dia_numero = date('w', strtotime($fecha));
$dia_nombre = $dias_semana[$dia_numero];

// aqui obtengo el horario del medico
$sql = $conexion->prepare("SELECT HorarioAtencion FROM Control_Medicos WHERE IdMedico = ?");
$sql->bind_param("i", $id_medico);
$sql->execute();
$resultado = $sql->get_result();

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    $horarios = json_decode($fila['HorarioAtencion'], true);
    
    // aqui verifico si trabaja ese dia
    if (isset($horarios[$dia_nombre]) && $horarios[$dia_nombre]['trabaja']) {
        $inicio = $horarios[$dia_nombre]['inicio'];
        $fin = $horarios[$dia_nombre]['fin'];
        
        // aqui verifico si la hora esta dentro del rango
        if ($hora >= $inicio && $hora <= $fin) {
            echo json_encode([
                'disponible' => true,
                'mensaje' => 'El médico está disponible',
                'horario' => $inicio . ' - ' . $fin
            ]);
        } else {
            echo json_encode([
                'disponible' => false,
                'mensaje' => 'Fuera del horario de atención',
                'horario' => $inicio . ' - ' . $fin
            ]);
        }
    } else {
        echo json_encode([
            'disponible' => false,
            'mensaje' => 'El médico no trabaja los ' . $dia_nombre
        ]);
    }
} else {
    echo json_encode(['error' => 'Médico no encontrado']);
}

$conexion->close();
?>
