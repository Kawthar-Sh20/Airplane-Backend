<?php
header("Access-Control-Allow-Origin: *"); // Allow access from any origin
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Allow these request methods
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Allow these headers

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Handle preflight request
    http_response_code(200);
    exit;
}
echo("Router script reached.");

$apiBasePath = '/api/';

$allowedEndpoints = [
    'hotels',
    'taxis',
    'flights',
    'airports',
    'users',
    'hotel_bookings',
    'flight_bookings',
    'taxi_bookings'
];

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (strpos($requestUri, $apiBasePath) === 0) {
    $endpoint = substr($requestUri, strlen($apiBasePath));
    
    if (in_array($endpoint, $allowedEndpoints)) {
        if ($requestMethod === 'GET') {
            require __DIR__ . '/src/controllers/get.php';
        } elseif ($requestMethod === 'POST') {
            require __DIR__ . '/src/controllers/post.php';
        } elseif ($requestMethod === 'PUT') {
            require __DIR__ . '/src/controllers/put.php';
        } elseif ($requestMethod === 'DELETE') {
            require __DIR__ . '/src/controllers/delete.php';
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



