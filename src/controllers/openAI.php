<?php 
require "../../connection.php";
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $id=$_GET['id'];
    $stmt=$conn->prepare('SELECT c.name, f.id_flight_booking 
FROM flight_bookings f 
JOIN cities c 
ON f.arrival_city_id = c.id_city;  ');
    $stmt->bind_param('i',$id);
    $stmt->execute();
    $result=$stmt->get_result();
    if ($result->num_rows>0){
        $city=$result->fetch_assoc();
        echo json_encode(["city"=>$city]);
    } else {
        echo json_encode(["message"=>"no records were found"]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}