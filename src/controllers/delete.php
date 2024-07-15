<?php
define('ROOT_PATH', dirname(__DIR__, 2)); // Adjust this if necessary

// Use the absolute path to require the connection.php file
require_once ROOT_PATH . "/connection.php";
require_once ROOT_PATH . "/src/services/service.php";
require_once ROOT_PATH . "/src/models/model.php"; 

$requestUri = $_SERVER['REQUEST_URI'];
$requestUri = strtok($requestUri, '?');
parse_str($_SERVER['QUERY_STRING'], $queryParams);
$table_name = explode('/', $requestUri)[2];

$param = key($queryParams);
if (isset($param) && !empty($param)) {
    $value = $queryParams[$param];
    selectService::delete($conn, $table_name, $param, $value);
} else {
    echo json_encode(["message" => "Invalid parameters for delete"]);
}

