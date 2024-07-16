<?php
require_once 'src/services/loginModel.php';
require_once 'vendor/autoload.php';
require_once 'src/helpers/loadEnv.php';
loadEnv(__DIR__ . '/.env');

use Firebase\JWT\JWT;

class loginService {
    private $secretKey;
    

    public function login($username, $password) {
        $user = loginModel::getUserByUsername($username);

        if (!$user || !password_verify($password, $user['password'])) {
            return ['success' => false];
        }
    
        $token = generateJWT($user);
        return ['success' => true, 'token' => $token];
    }

    private function generateJWT($user) {
        $secretKey = getenv('JWT_SECRET');
        $payload = [
            "iss" => "admin",
            "aud" => "users",
            "iat" => time(),
            "exp" => time() + (60 * 60 * 24 * 30),
            "user_id" => $user["id"],
            "email" => $user["email"],
            "role" => $user["role"],
        ];

        return JWT::encode($payload, $secretKey, 'HS256');
    }
}