<?php
require_once 'src/services/auth/authorization.php';
require "src/helpers/connection.php";
require "src/services/crud/get.php";

$conn = dbConnect();
$requestUri = $_SERVER['REQUEST_URI'];
$requestUri = strtok($requestUri, '?');
parse_str($_SERVER['QUERY_STRING'], $queryParams);
$table_name = explode('/', $requestUri)[2];

$authHeader = $_SERVER['HTTP_AUTHORIZATION'];

$publicEndpoints = [
    'flights',
    'hotels',
    'taxis',
    'cities'
];

$userSpecificEndpoints = [
    'users',
    'flight_bookings',
    'hotel_bookings',
    'taxi_bookings'
];

$adminEndpoints = [
    'users',
    'airports'
];

$authorized = false;

if (in_array($table_name, $publicEndpoints)) {
    $authorized = true;
} elseif (authorize($authHeader, 'admin')) {
    $authorized = true;
} elseif (in_array($table_name, $userSpecificEndpoints)) {
    if (isset($queryParams['id']) && isUserAuthorized($authHeader, $queryParams['id'])) {
        $authorized = true;
    }
}

if ($authorized) {
    if (isset($_GET['limit']) && !empty($_GET['limit']) && $_GET['limit'] > 0) { 
        $limit = $_GET['limit'];
        unset($queryParams['limit']);
    }
    else
        $limit = null;

    $param = key($queryParams);
    if (isset($param) && !empty($param)) {
        $value = $queryParams[$param];
    }

    if (isset($param) && isset($value)) {
        selectService::select($conn, $table_name, $param, $value, $limit);
    } else {
        selectService::selectAll($conn, $table_name, $limit);
    }
} else {
    http_response_code(403);
    echo json_encode(["message" => "Forbidden"]);
}

?>