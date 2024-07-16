<?php
require_once 'src/services/login.php';

function login() {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (!isset($data['email']) || !isset($data['password'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Email and password are required']);
        return;
    }

    $email = $data['email'];
    $password = $data['password'];

    $authService = new LoginService();
    $result = authService->login($email, $password);

    if ($result['success']) {
        http_response_code(200);
        echo json_encode(['token' => $result['token']]);
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid credentials']);
    }
}