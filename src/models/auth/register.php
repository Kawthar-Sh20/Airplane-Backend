<?php
require_once 'src/helpers/connection.php';

class RegisterModel {
    private $db;

    public function __construct() {
        $this->db = dbConnect();
    }

	public function getUser($email, $phone) {
        $stmt = $this->db->prepare("SELECT id_user, name, email, phone_number, password, role FROM users WHERE email = ? OR phone_number = ?");

        $stmt->bind_param("ss", $email, $phone);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        return $user;
    }

    public function createUser($name, $email, $phone, $password) {
        $role = 'user';

        $stmt = $this->db->prepare("INSERT INTO users (name, email, phone_number, password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $phone, $password, $role);

        if ($stmt->execute()) {
            $userId = $stmt->insert_id;
            $stmt->close();

            return [
                "id_user" => $userId,
                "name" => $name,
                "email" => $email,
                "role" => $role
            ];
        } else {
            $stmt->close();
            return false;
        }
    }

    public function __destruct() {
        $this->db->close();
    }
}
