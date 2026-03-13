<?php
/**
 * TENSE QUEST - Guardar progreso
 * Endpoint para guardar el progreso del usuario (opcional - para uso futuro con BD)
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Content-Type');

// directorio para guardar progreso (alternativa simple a BD)
$progressDir = __DIR__ . '/../data/progress/';

// crear directorio si no existe
if (!file_exists($progressDir)) {
    mkdir($progressDir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // guardar progreso
    $input = json_decode(file_get_contents('php://input'), true);
    
    if ($input === null) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'json invalido'
        ]);
        exit;
    }
    
    // generar id unico si no existe
    $playerId = isset($input['playerId']) ? $input['playerId'] : uniqid('player_');
    
    $progressData = [
        'playerId' => $playerId,
        'totalXP' => isset($input['totalXP']) ? intval($input['totalXP']) : 0,
        'completedZones' => isset($input['completedZones']) ? $input['completedZones'] : [],
        'zoneStars' => isset($input['zoneStars']) ? $input['zoneStars'] : [],
        'lastUpdated' => date('Y-m-d H:i:s')
    ];
    
    // guardar en archivo
    $filename = $progressDir . $playerId . '.json';
    file_put_contents($filename, json_encode($progressData, JSON_PRETTY_PRINT));
    
    echo json_encode([
        'success' => true,
        'message' => 'progreso guardado',
        'playerId' => $playerId
    ]);
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // cargar progreso
    $playerId = isset($_GET['playerId']) ? $_GET['playerId'] : null;
    
    if ($playerId === null) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'playerId requerido'
        ]);
        exit;
    }
    
    $filename = $progressDir . $playerId . '.json';
    
    if (!file_exists($filename)) {
        echo json_encode([
            'success' => true,
            'found' => false,
            'message' => 'no se encontro progreso para este jugador'
        ]);
        exit;
    }
    
    $progressData = json_decode(file_get_contents($filename), true);
    
    echo json_encode([
        'success' => true,
        'found' => true,
        'progress' => $progressData
    ]);
    
} else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'metodo no permitido'
    ]);
}
