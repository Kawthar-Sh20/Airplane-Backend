<?php
define('ROOT_PATH', dirname(__DIR__, 2)); // Adjust this if necessary

// Use the absolute path to require the connection.php file
require ROOT_PATH . "../../src/helpers/connection.php";
require_once ROOT_PATH . "../../src/models/crud/model.php";
$conn = dbConnect();
// Ensure the connection is established
if (!isset($conn) || !$conn) {
    die("Database connection not established");
}
// Extract request URI and segments
$requestUri = $_SERVER['REQUEST_URI'];
$requestUri = strtok($requestUri, '?');
$segments = explode('/', $requestUri);

// Extract table name from URI
$table_name = isset($segments[2]) ? $segments[2] : null;

// Validate the table name
if (!selectModel::isValidTable($table_name)) {
    echo json_encode(["message" => "Invalid table name"]);
    exit;
}

// Extract parameters from query string
$param = key($_GET);
$value = $_GET[$param] ?? null;

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rawInputData = file_get_contents("php://input");
    $inputData = json_decode($rawInputData, true);
    if (!empty($inputData) && is_array($inputData)) {
        // Handle insertion
        $sql = selectModel::insert($table_name, $inputData);
        $stmt = $conn->prepare($sql);

        $types = str_repeat("s", count($inputData)); // Assuming all inputs are strings, adjust as necessary
        $stmt->bind_param($types, ...array_values($inputData));

        if ($stmt->execute()) {
            echo json_encode(["message" => "Record inserted successfully"]);
        } else {
            echo json_encode(["message" => "Error inserting record: " . $stmt->error]);
        }
    } else {
        echo json_encode(["message" => "Invalid input data"]);
    }
} else {
    echo json_encode(["message" => "Method not allowed"]);
}
?>
