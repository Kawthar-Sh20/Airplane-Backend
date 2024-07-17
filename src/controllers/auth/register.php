<?php
require_once 'src/services/auth/register.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!isset($data['email']) || !isset($data['password']) || !isset($data['name']) || !isset($data['phone_number'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Name, email, password, and phone number are required']);
    return;
}

$name = filter_var($data['name'], FILTER_SANITIZE_STRING);
$email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
$phone = filter_var($data['phone_number'], FILTER_SANITIZE_STRING);
$password = $data['password'];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid email format']);
    return;
}

if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid phone number format']);
    return;
}

$authService = new RegisterService();
$result = $authService->register($name, $email, $phone, $password);

if ($result['success']) {
    http_response_code(201);
    echo json_encode(['token' => $result['token']]);
} else {
    http_response_code(400);
    echo json_encode(['error' => $result['error']]);
}
