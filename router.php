<?php
// Debugging statements to check if script is being hit
error_log("Router script reached.");

// Define the base API path
$apiBasePath = '/api/';

// Define the allowed endpoints
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

// Debugging statements
error_log("Request Method: " . $requestMethod);
error_log("Request URI: " . $requestUri);

// Check if the request starts with the API base path
if (strpos($requestUri, $apiBasePath) === 0) {
    $endpoint = substr($requestUri, strlen($apiBasePath));
    
    if (in_array($endpoint, $allowedEndpoints)) {
        if ($requestMethod === 'GET') {
            error_log("GET route matched for endpoint: " . $endpoint);
            require __DIR__ . '/src/controllers/get.php';
        } elseif ($requestMethod === 'POST') {
            error_log("POST route matched for endpoint: " . $endpoint);
            require __DIR__ . '/src/controllers/post.php';
        } else {
            error_log("Method not allowed for endpoint: " . $endpoint);
            header("HTTP/1.0 405 Method Not Allowed");
            echo "405 Method Not Allowed";
        }
    } else {
        error_log("Invalid endpoint: " . $endpoint);
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found";
    }
} else {
    error_log("No API route matched.");
    header("HTTP/1.0 404 Not Found");
    echo "404 Not Found";
}

?>