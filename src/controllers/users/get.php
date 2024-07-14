<?php

require "../../../connection.php";
require "../../services/service.php";
$requestUri = $_SERVER['REQUEST_URI'];
$requestUri = strtok($requestUri, '?');
parse_str($_SERVER['QUERY_STRING'], $queryParams);
$table_name = explode('/', $requestUri)[3];
$param = key($queryParams);
if (isset($param) && !empty($param)) {
    $value = $queryParams[$param];
}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($param) && isset($value)) {
        if (isset($_GET['total']) && !empty($_GET['total']) && $_GET['total'] > 0) {
            // selects all rows with param up to total
            selectService::selectQty($conn, $table_name, $param, $value, $_GET['total']);
        } else {
            // selects all rows with param
            selectService::selectParamAll($conn, $table_name, $param, $value);
        }
    } else {
        // selects all rows
        selectService::selectAll($conn, $table_name);
    }
} else {
    echo json_encode(["message" => "Error getting data"]);
}

// if ($_SERVER['REQUEST_METHOD'] == 'GET') {
// 	$id = $_GET['id'];

// 	$stmt = $conn->prepare("SELECT * FROM products where category_id = ?;");
// 	$stmt -> bind_param('', $id);
// 	$stmt->execute();
// 	$result = $stmt->get_result();
// 	if ($result->num_rows > 0) {
// 		$rows = [];
// 		while ($row = $result->fetch_assoc()) {
// 			$rows[] = $row;
// 		}
// 		echo json_encode(["products" => $rows]);
// 	} else {
// 		echo json_encode(["message" => "Products not found"]);
// 	}
// } else {
// 	echo json_encode(["message" => "Error reading products"]);
// }