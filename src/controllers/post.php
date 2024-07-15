<?php
define('ROOT_PATH', dirname(__DIR__, 2)); // Adjust this if necessary

// Use the absolute path to require the connection.php file
require_once ROOT_PATH . "/connection.php";
require_once ROOT_PATH . "/src/services/service.php";
require_once ROOT_PATH . "/src/models/model.php";

// Debugging connection success
echo "Router script reached. Connected successfully <br>";

// Extract request URI and segments
$requestUri = $_SERVER['REQUEST_URI'];
$requestUri = strtok($requestUri, '?');
$segments = explode('/', $requestUri);

// Debug information
echo "Request URI: " . $requestUri . "<br>";
echo "Segments: ";
print_r($segments);
echo "<br>";

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

// Debug information
echo "Table Name: " . $table_name . "<br>";
echo "Is Valid Table: " . (selectModel::isValidTable($table_name) ? "Yes" : "No") . "<br>";
echo "Param: " . $param . "<br>";
echo "Value: " . $value . "<br>";

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rawInputData = file_get_contents("php://input");
    $inputData = json_decode($rawInputData, true);

    // Debugging input data
    echo "Raw input data: " . $rawInputData . "<br>";
    echo "Decoded input data: ";
    print_r($inputData);
    echo "<br>";

    if (!empty($inputData) && is_array($inputData)) {
        // Handle insertion
        $sql = selectModel::insert($table_name, $inputData);
        echo "Generated SQL: " . $sql . "<br>";
        $stmt = $conn->prepare($sql);

        $types = str_repeat("s", count($inputData)); // Assuming all inputs are strings, adjust as necessary
        echo "Bind types: " . $types . "<br>";
        echo "Bind values: ";
        print_r(array_values($inputData));
        echo "<br>";

        $stmt->bind_param($types, ...array_values($inputData));

        if ($stmt->execute()) {
            echo json_encode(["message" => "Record inserted successfully"]);
        } else {
            echo json_encode(["message" => "Error inserting record: " . $stmt->error]);
        }
    } else {
        echo json_encode(["message" => "Invalid input data"]);
    }
}