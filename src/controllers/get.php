<?php
require "connection.php";
require "src/services/service.php";
$requestUri = $_SERVER['REQUEST_URI'];
$requestUri = strtok($requestUri, '?');
parse_str($_SERVER['QUERY_STRING'], $queryParams);
$table_name = explode('/', $requestUri)[2];
$param = key($queryParams);
if (isset($param) && !empty($param)) {
    $value = $queryParams[$param];
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($param) && isset($value)) {
        if (isset($_GET['limit']) && !empty($_GET['limit']) && $_GET['limit'] > 0) {
            selectService::select($conn, $table_name, $param, $value, $_GET['limit']);
        } else {
            selectService::select($conn, $table_name, $param, $value);
        }
    } else {
        selectService::selectAll($conn, $table_name);
    }
} else {
    echo json_encode(["message" => "Error getting data"]);
}