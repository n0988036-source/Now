<?php
header('Content-Type: application/json');

// Проверяем, что запрос POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Метод не разрешён']);
    exit;
}

// Читаем JSON‑данные из запроса
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['ip'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Неверные данные']);
    exit;
}

// Форматируем запись для лога
$logEntry = sprintf(
    "[%s] IP: %s | User-Agent: %s\n",
    date('Y-m-d H:i:s'),
    $input['ip'],
    $input['userAgent'] ?? 'Не указан'
);

// Записываем в файл
file_put_contents('visits.log', $logEntry, FILE_APPEND | LOCK_EX);

// Отвечаем клиенту
echo json_encode(['success' => true, 'message' => 'IP записан в лог']);
?>

