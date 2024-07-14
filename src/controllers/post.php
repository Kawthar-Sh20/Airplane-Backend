<?php
/////////////////////////////////////////////////
define('ROOT_PATH', dirname(__DIR__, 2)); // Adjust this if necessary

// Use the absolute path to require the connection.php file
require_once ROOT_PATH . "/connection.php";
require_once ROOT_PATH . "/src/services/service.php";
require_once ROOT_PATH . "/src/models/model.php"; // make sure to require your model file
//////////////////////////////////////////////////////////////

$requestUri = $_SERVER['REQUEST_URI'];
$requestUri = strtok($requestUri, '?');
$segments = explode('/', $requestUri);


// Debug information
echo "Request URI: " . $requestUri . "<br>";
echo "Segments: ";
print_r($segments);
echo "<br>";

$table_name = isset($segments[2]) ? $segments[2] : null; // Extract table name from the URI

$param = key($_GET);
$value = $_GET[$param] ?? null;

// More debug information
echo "Table Name: " . $table_name . "<br>";
echo "Is Valid Table: " . (selectModel::isValidTable($table_name) ? "Yes" : "No") . "<br>";


// ////////////////////////////////////////////////////////////
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inputData = json_decode(file_get_contents("php://input"), true);

    if (selectModel::isValidTable($table_name)) {
        if (!empty($inputData) && is_array($inputData)) {
            // Handle insertion
            $sql = selectModel::insert($table_name, $inputData);
            $stmt = $conn->prepare($sql);
            
            $types = str_repeat("s", count($inputData)); // assuming all inputs are strings, adjust as necessary
            $stmt->bind_param($types, ...array_values($inputData));

            if ($stmt->execute()) {
                echo json_encode(["message" => "Record inserted successfully"]);
            } else {
                echo json_encode(["message" => "Error inserting record: " . $stmt->error]);
            }
        } else {
            // Handle selection
            if (isset($param) && isset($value)) {
                if (isset($_GET['limit']) && !empty($_GET['limit']) && $_GET['limit'] > 0) {
                    $stmt = $conn->prepare(selectModel::select($table_name, $param) . " LIMIT ?");
                    $stmt->bind_param("i", $_GET['limit']);
                } else {
                    $stmt = $conn->prepare(selectModel::select($table_name, $param));
                }
                $stmt->bind_param("s", $value);
                $stmt->execute();
                $result = $stmt->get_result();
                echo json_encode($result->fetch_all(MYSQLI_ASSOC));
            } else {
                $result = $conn->query(selectModel::selectAll($table_name));
                echo json_encode($result->fetch_all(MYSQLI_ASSOC));
            }
        }
    } else {
        echo json_encode(["message" => "Invalid table name"]);
    }
} else {
    echo json_encode(["message" => "Error processing request"]);
}
?>