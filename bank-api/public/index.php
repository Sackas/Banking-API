<?php

require_once __DIR__ . '/../src/Storage.php';
require_once __DIR__ . '/../src/Bank.php';

$bank = new Bank();

$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'] ?? '/';

header('Content-Type: application/json');

if ($method === 'POST' && $path === '/reset') {
    Storage::reset();
    echo "OK";
    return;
}

if ($method === 'GET' && $path === '/balance') {
    parse_str($_SERVER['QUERY_STRING'], $query);
    $accountId = $query['account_id'] ?? null;
    $balance = $bank->getBalance($accountId);

    if ($balance === null) {
        http_response_code(404);
        echo '0';
    } else {
        echo $balance;
    }
    return;
}

if ($method === 'POST' && $path === '/event') {
    $data = json_decode(file_get_contents('php://input'), true);
    $response = $bank->handleEvent($data);

    if ($response === null) {
        http_response_code(404);
        echo '0';
    } else {
        http_response_code(201);
        echo json_encode($response);
    }
    return;
}

http_response_code(404);
echo "Not Found";
