<?php
require_once 'src/models/auth/login.php';
require_once 'src/helpers/login.php';

class LoginService {
    private $secretKey;
    private $model;
    private $loginHelper;

    public function __construct() {
        $this->model = new LoginModel();
        $this->loginHelper = new LoginHelper();
    }
    
    public function login($email, $password) {
        $user = $this->model->getUserByUsername($email);

        if (!$user || !password_verify($password, $user['password'])) {
            return ['success' => false];
        }
    
        $token = $this->loginHelper->generateJWT($user);
        return ['success' => true, 'token' => $token];
    }
}