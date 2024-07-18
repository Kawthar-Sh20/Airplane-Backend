<?php
require_once 'src/helpers/loadEnv.php';
require_once 'assets/jwt/JWT.php';
require_once 'assets/jwt/Key.php';
loadEnv('.env');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function getDecodedToken($authHeader) {
    if (strpos($authHeader, 'Bearer ') !== 0) {
        return null;
    }

    $jwt = substr($authHeader, 7);
    $secretKey = getenv('JWT_SECRET');

    try {
        $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));
        return $decoded;
    } catch (Exception $e) {
        return null;
    }
}

function authorize($authHeader, $requiredRole) {
    $decoded = getDecodedToken($authHeader);
    if (!$decoded) {
        return false;
    }

    $userRole = $decoded->role ?? '';
    return $userRole === $requiredRole;
}

function isUserAuthorized($authHeader, $userId) {
    $decoded = getDecodedToken($authHeader);
    if (!$decoded) {
        return false;
    }

    return $decoded->user_id == $userId;
}

?>
