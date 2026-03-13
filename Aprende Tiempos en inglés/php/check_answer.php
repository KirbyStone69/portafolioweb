<?php
/**
 * TENSE QUEST - Verificar respuesta
 * Endpoint para validar respuestas del usuario
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'metodo no permitido, use POST'
    ]);
    exit;
}

// obtener datos del body
$input = json_decode(file_get_contents('php://input'), true);

if ($input === null) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'json invalido'
    ]);
    exit;
}

// validar campos requeridos
$requiredFields = ['tense', 'questionIndex', 'selectedAnswer'];
foreach ($requiredFields as $field) {
    if (!isset($input[$field])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'campo requerido: ' . $field
        ]);
        exit;
    }
}

$tense = $input['tense'];
$questionIndex = intval($input['questionIndex']);
$selectedAnswer = intval($input['selectedAnswer']);

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

// verificar que existe el tiempo verbal
if (!isset($data['tenses'][$tense])) {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'error' => 'tiempo verbal no encontrado'
    ]);
    exit;
}

$questions = $data['tenses'][$tense]['questions'];

// verificar indice de pregunta
if ($questionIndex < 0 || $questionIndex >= count($questions)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'indice de pregunta invalido'
    ]);
    exit;
}

$question = $questions[$questionIndex];
$isCorrect = $selectedAnswer === $question['correct'];

echo json_encode([
    'success' => true,
    'isCorrect' => $isCorrect,
    'correctAnswer' => $question['correct'],
    'correctText' => $question['options'][$question['correct']],
    'explanation' => $question['explanation'],
    'xpEarned' => $isCorrect ? 10 : 0
]);
