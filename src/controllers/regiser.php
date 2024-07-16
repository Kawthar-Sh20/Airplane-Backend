<?php

require '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {


    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $check_email = $conn->prepare('select id from users where username=? or email=?;');
    $check_email->bind_param('ss', $username, $email);
    $check_email->execute();
    $result = $check_email->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["error" => "Already exists"]);
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        $stmt = $conn->prepare('insert into users(name,username,email,password) values (?,?,?,?); ');
        $stmt->bind_param('ssss', $name, $username, $email, $hashed_password);
        $stmt->execute();
        $res['status'] = "success";
        $res['message'] = "inserted successfully";
        echo json_encode($res);
        //echo json_encode(["status"=>"success","message"=>"inserted successfully"]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
