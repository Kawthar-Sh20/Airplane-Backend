<?php
require "connection.php";
require "src/services/get.php";

$requestUri = $_SERVER['REQUEST_URI'];
$requestUri = strtok($requestUri, '?');
parse_str($_SERVER['QUERY_STRING'], $queryParams);
$table_name = explode('/', $requestUri)[2];

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

?>