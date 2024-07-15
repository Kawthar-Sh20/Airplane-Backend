<?php
require '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare('select id,email,password,name
    from users 
    where email=?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $email, $hashed_password, $name);
    $stmt->fetch();
    $user_exists = $stmt->num_rows;

    if ($user_exists == 0) {
        $res['message'] = "user not found";
    } else {

        if (password_verify($password, $hashed_password)) {
            $res['status'] = 'authenticated';
            $res['id'] = $id;
            $res['name'] = $name;
            $res['email'] = $email;
        } else {
            $res['status'] = "wrong password";
        }
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
echo json_encode($res);
