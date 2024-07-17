<?php
require_once 'src/models/auth/register.php';
require_once 'src/helpers/login.php';

class RegisterService {
    private $model;
    private $loginHelper;

    public function __construct() {
        $this->model = new RegisterModel();
        $this->loginHelper = new LoginHelper();
    }

    public function register($name, $email, $phone, $password) {
        if ($this->model->getUser($email, $phone)) {
            return ['success' => false, 'error' => 'Email or phone number already registered'];
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $user = $this->model->createUser($name, $email, $phone, $hashedPassword);
        
        if ($user) {
            $token = $this->loginHelper->generateJWT($user);
            return ['success' => true, 'token' => $token];
        } else {
            return ['success' => false, 'error' => 'Registration failed'];
        }
    }
}
?>
