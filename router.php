<?php
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
        } else {
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

?>