<?php
require_once 'src/helpers/loadEnv.php';
require_once 'assets/jwt/JWT.php';
loadEnv('.env');

use Firebase\JWT\JWT;

class LoginHelper {
	public function generateJWT($user) {
        $secretKey = getenv('JWT_SECRET');
        $payload = [
            "iss" => "admin",
            "aud" => "users",
            "iat" => time(),
            "exp" => time() + (60 * 60 * 24 * 30),
            "user_id" => $user["id_user"],
            "email" => $user["email"],
            "role" => $user["role"],
        ];

        return JWT::encode($payload, $secretKey, 'HS256');
    }
}