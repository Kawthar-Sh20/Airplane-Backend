<?php

class loginModel {
    private $db;

    public function __construct() {
        $host = getenv('DB_HOST');
        $username = getenv('DB_USERNAME');
        $password = getenv('DB_PASSWORD');
        $database = getenv('DB_NAME');

        $conn = new mysqli($host, $username, $password, $database);

        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        } else {
            echo 'connected successfully <br>';
        }
    }

    public function getUserByUsername($username) {
        $stmt = $this->db->prepare("SELECT id, username, password_hash, email, role FROM users WHERE username = ?");
        
        // Bind the username parameter
        $stmt->bind_param("s", $username);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the user data
        $user = $result->fetch_assoc();

        // Close the statement
        $stmt->close();

        return $user;
    }

    public function __destruct() {
        $this->db->close();
    }
}
