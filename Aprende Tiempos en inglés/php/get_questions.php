<?php
/**
 * TENSE QUEST - Obtener preguntas
 * Endpoint para cargar preguntas por tiempo verbal
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// parametros de consulta
$tense = isset($_GET['tense']) ? $_GET['tense'] : null;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 5;
$random = isset($_GET['random']) ? $_GET['random'] === 'true' : true;

// cargar archivo de preguntas
$questionsFile = __DIR__ . '/../data/questions.json';

if (!file_exists($questionsFile)) {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'error' => 'archivo de preguntas no encontrado'
    ]);
    exit;
}

$data = json_decode(file_get_contents($questionsFile), true);

if ($data === null) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'error al parsear archivo json'
    ]);
    exit;
}

// si se especifica un tiempo verbal
if ($tense !== null) {
    if (!isset($data['tenses'][$tense])) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'error' => 'tiempo verbal no encontrado: ' . $tense
        ]);
        exit;
    }
    
    $tenseData = $data['tenses'][$tense];
    $questions = $tenseData['questions'];
    
    // mezclar si es aleatorio
    if ($random) {
        shuffle($questions);
    }
    
    // limitar cantidad
    $questions = array_slice($questions, 0, $limit);
    
    echo json_encode([
        'success' => true,
        'tense' => [
            'key' => $tense,
            'name' => $tenseData['name'],
            'realm' => $tenseData['realm'],
            'formula' => $tenseData['formula'],
            'icon' => $tenseData['icon']
        ],
        'questions' => $questions,
        'total' => count($questions)
    ]);
} else {
    // devolver todos los tiempos verbales con info basica
    $tenses = [];
    foreach ($data['tenses'] as $key => $tense) {
        $tenses[$key] = [
            'name' => $tense['name'],
            'realm' => $tense['realm'],
            'formula' => $tense['formula'],
            'icon' => $tense['icon'],
            'questionCount' => count($tense['questions'])
        ];
    }
    
    echo json_encode([
        'success' => true,
        'tenses' => $tenses,
        'zones' => $data['zones']
    ]);
}
