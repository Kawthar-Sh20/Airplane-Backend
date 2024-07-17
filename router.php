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
    'openai',
    'auth/login',
    'auth/register',
    'users',
    'airports',
    'flights',
    'hotels',
    'taxis',
    'flight_bookings',
    'hotel_bookings',
    'taxi_bookings',
    'openai'
    
];

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (strpos($requestUri, $apiBasePath) === 0) {
    $endpoint = substr($requestUri, strlen($apiBasePath));
    
    if (in_array($endpoint, $allowedEndpoints)) {
        if ($endpoint === 'openai' && $requestMethod === 'GET') {
            require __DIR__ . '/src/controllers/ai/openai.php';
        } elseif ($endpoint === 'auth/login' || $endpoint === 'auth/register') {
            if ($requestMethod === 'POST') {
                if ($endpoint === 'auth/login') {
                    require __DIR__ . '/src/controllers/auth/login.php';
                } else {
                    require __DIR__ . '/src/controllers/auth/register.php';
                }
            } else {
                header("HTTP/1.0 405 Method Not Allowed");
                echo "405 Method Not Allowed for authentication endpoints";
            }
        } else {
            if ($requestMethod === 'GET') {
                require __DIR__ . '/src/controllers/crud/get.php';
            } elseif ($requestMethod === 'POST') {
                require __DIR__ . '/src/controllers/crud/post.php';
            } elseif ($requestMethod === 'PUT') {
                require __DIR__ . '/src/controllers/crud/put.php';
            } elseif ($requestMethod === 'DELETE') {
                require __DIR__ . '/src/controllers/crud/delete.php';
            } else {
                header("HTTP/1.0 405 Method Not Allowed");
                echo "405 Method Not Allowed";
            }
        }
    } else {
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found";
    }
} else {
    header("HTTP/1.0 404 Not Found");
    echo "404 Not Found";
}