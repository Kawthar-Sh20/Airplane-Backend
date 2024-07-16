<?php 
require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $id = $_GET['id'];
    $stmt = $conn->prepare('SELECT c.name, f.id_flight_booking 
                            FROM flight_bookings f 
                            JOIN cities c 
                            ON f.arrival_city_id = c.id_city
                            WHERE f.id_flight_booking = ?;');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $city = $result->fetch_assoc();
        echo json_encode(["city" => $city]);
    } else {
        echo json_encode(["message" => "no records were found"]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_id = $_POST['user_id'];
    $chat_user_id = $_POST['chat_user_id'];
    $message = $_POST['message'];
    $is_bot = $_POST['is_bot'];
    $created_at = date('Y-m-d H:i:s'); 

    $stmt = $conn->prepare('INSERT INTO chats (user_id, chat_user_id, message, is_bot, created_at) 
                            VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param('iisii', $user_id, $chat_user_id, $message, $is_bot, $created_at);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Chat saved successfully"]);
    } else {
        echo json_encode(["error" => "Failed to save chat"]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
?>
