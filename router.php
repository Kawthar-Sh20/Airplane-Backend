<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Handle preflight request
    http_response_code(200);
    exit;
}

$apiBasePath = '/api/';

$allowedEndpoints = [
    'auth/login',
    'auth/register',
    'users',
    'airports',
    'flights',
    'hotels',
    'taxis',
    'flight_bookings',
    'hotel_bookings',
    'taxi_bookings'
];

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (strpos($requestUri, $apiBasePath) === 0) {
    $endpoint = substr($requestUri, strlen($apiBasePath));
    
    if (in_array($endpoint, $allowedEndpoints)) {
        if ($requestUri === '/api/auth/login' && $requestMethod === 'POST') {
            require __DIR__ . '/src/controllers/login.php';
        } elseif ($requestUri === '/api/auth/register' && $requestMethod === 'POST') {
            require __DIR__ . '/src/controllers/register.php';
        } elseif ($requestMethod === 'GET') {
            require __DIR__ . '/src/controllers/get.php';
        } elseif ($requestMethod === 'POST') {
            require __DIR__ . '/src/controllers/post.php';
        } elseif ($requestMethod === 'PUT') {
            require __DIR__ . '/src/controllers/put.php';
        } elseif ($requestMethod === 'DELETE') {
            require __DIR__ . '/src/controllers/delete.php';
        } elseif($requestMethod === 'GET' && isset($_GET['openAI'])) {
            require __DIR__ . '/src/controllers/openAIGet.php';    
        }
        
        else {
            header("HTTP/1.0 405 Method Not Allowed");
            echo "405 Method Not Allowed";
        }
    } else {
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found";
    }
} else {
    header("HTTP/1.0 404 Not Found");
    echo "404 Not Found";
}