<?php

header('Content-Type: text/event-stream');
header('Connection: keep-alive');
header('Cache-Control: no-store');

header('Access-Control-Allow-Origin: *');

$messageCount = 0;
$infoCount = 0;
$errorCount = 0;

while (true) {
    // завешаем while, если клиент оборвал соединение
    if (connection_aborted()) {
        break;
    }

    // некая логика
    $id = $messageCount++;

    $eventType = null;
    $whom = null;
    $message = null;

    $rnd = random_int(1, 5);
    switch ($rnd) {
        case 1:
            $infoCount++;
            $eventType = 'info';
            $message = "Информационное сообщение $infoCount";
            break; 
        case 4:
        case 5:
            $errorCount++;
            $eventType = 'error';
            $message = "ОШИБКА $errorCount !!!";
            break;
        default:
            $eventType = 'message';
            $whom = $rnd === 2 ? 'Дима' : 'Саня';
            $message = $id;
            break;
    }

    $data = json_encode([
        'whom' => $whom,
        'message' => $message
    ], JSON_UNESCAPED_UNICODE);

    // данные для EvensSource
    echo "id: $id\n"; // id
    echo "event: $eventType\n"; // тип уведомления
    echo "data: $data\n"; // данные
    echo "\n";

    ob_flush();
    flush();

    // через $rnd секунд снова плюем в клиент
    sleep($rnd);
}
