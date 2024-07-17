<?php
require "../../connection.php";
echo "HELLO";
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    echo "Hii";
    $id_flight_booking = $_GET['id_flight_booking'];
    
    // Prepare statement with correct table names
    $stmt = $conn->prepare('SELECT c.name, f.id_flight_booking 
                            FROM flight_bookings f 
                            JOIN cities c ON f.arrival_city_id = c.id_city 
                            WHERE f.id_flight_booking = ?;');
    if ($stmt === false) {
        die('Prepare failed: ' . $conn->error);
    }
    $stmt->bind_param('i', $id_flight_booking);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $city = $result->fetch_assoc();
        echo json_encode(["city" => $city]);
    } else {
        echo json_encode(["message" => "No records were found"]);
    }
} else {
    echo json_encode(["message" => "Invalid request method"]);
}
?>
