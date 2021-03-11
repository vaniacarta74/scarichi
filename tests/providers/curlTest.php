<?php

namespace vaniacarta74\Scarichi\tests\providers;

require __DIR__ . '/../../vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, PATCH, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $report = [
            'method' => 'GET',
            'params' => $_GET
        ];
        break;
    case 'POST':        
        if (isset($_GET['json']) && $_GET['json']) {
            $params = @file_get_contents('php://input');
            $arrPost = json_decode($params, true);
        } else {
            $arrPost = $_POST;
        }
        $report = [
            'method' => 'POST',
            'params' => $arrPost
        ];
        break;
    case 'PUT':
        $report = [
            'method' => 'PUT',
            'params' => $_GET
        ];
        break;
    case 'PATCH':
        $report = [
            'method' => 'PATCH',
            'params' => $_GET
        ];
        break;
    case 'DELETE':
        $report = [
            'method' => 'DELETE',
            'params' => $_GET
        ];
        break;
}
$response = [
    'ok' => true,
    'response' => $report
];    
http_response_code(200);
echo json_encode($response);
