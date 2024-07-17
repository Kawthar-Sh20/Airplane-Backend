<?php 
require_once 'src/helpers/connection.php';


$conn = dbConnect();

 
require_once 'src/helpers/connection.php';

header('Content-Type: application/json'); // Set the content-type to JSON

$conn = dbConnect();

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['id_flight_booking'])) {
        $id_flight_booking = $_GET['id_flight_booking'];
        
        // Prepare statement with correct table names
        $stmt = $conn->prepare('SELECT name FROM cities WHERE id_city = (SELECT arrival_city_id FROM flight_bookings WHERE id_flight_booking = ?);');
        if ($stmt === false) {
            echo json_encode(['error' => 'Prepare failed: ' . $conn->error]);
            exit;
        }
        
        $stmt->bind_param('i', $id_flight_booking);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $city = $result->fetch_assoc();
            echo json_encode(["city" => $city]);
        } else {
            echo json_encode(["message" => "no records were found"]);
        }
    } else {
        echo json_encode(["message" => "Missing id_flight_booking parameter"]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_id = $_POST['user_id'];
    $chat_user_id = $_POST['chat_user_id'];
    $message = $_POST['message'];
    $is_bot = $_POST['is_bot'];
    $created_at = date('Y-m-d H:i:s'); 

    $stmt = $conn->prepare('INSERT INTO chats (user_id, chat_user_id, message, is_bot, created_at) 
                            VALUES (?, ?, ?, ?, ?)');
    if ($stmt === false) {
        echo json_encode(['error' => 'Prepare failed: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param('iisii', $user_id, $chat_user_id, $message, $is_bot, $created_at);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Chat saved successfully"]);
    } else {
        echo json_encode(["error" => "Failed to save chat"]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}

// if ($_SERVER['REQUEST_METHOD'] == "GET") {
//     if (isset($_GET['id_flight_booking'])) {
//         $id_flight_booking = $_GET['id_flight_booking'];
        
//         // Prepare statement with correct table names
//         $stmt = $conn->prepare('SELECT name FROM cities WHERE id_city = (SELECT arrival_city_id FROM flight_bookings WHERE id_flight_booking = ?);');
//         if ($stmt === false) {
//             die('Prepare failed: ' . $conn->error);
//         }
        
//         $stmt->bind_param('i', $id_flight_booking);
//         $stmt->execute();
//         $result = $stmt->get_result();
        
//         if ($result->num_rows > 0) {
//             $city = $result->fetch_assoc();
//             echo json_encode(["city" => $city]);
//         } else {
//             echo json_encode(["message" => "no records were found"]);
//         }
//     } else {
//         echo json_encode(["message" => "Missing id_flight_booking parameter"]);
//     }
// }elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
//     $user_id = $_POST['user_id'];
//     $chat_user_id = $_POST['chat_user_id'];
//     $message = $_POST['message'];
//     $is_bot = $_POST['is_bot'];
//     $created_at = date('Y-m-d H:i:s'); 

//     $stmt = $conn->prepare('INSERT INTO chats (user_id, chat_user_id, message, is_bot, created_at) 
//                             VALUES (?, ?, ?, ?, ?)');
//     $stmt->bind_param('iisii', $user_id, $chat_user_id, $message, $is_bot, $created_at);

//     if ($stmt->execute()) {
//         echo json_encode(["message" => "Chat saved successfully"]);
//     } else {
//         echo json_encode(["error" => "Failed to save chat"]);
//     }
// } else {
//     echo json_encode(["error" => "Wrong request method"]);
// }

