<?php
session_start();
require_once 'database.php';

class Auth {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // Register a new user
    public function register($name, $username, $userType, $password, $id) {
        // Check if the username is already registered
        $stmt = $this->db->prepare("SELECT userID FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->close();
            return "Username already exists.";
        }

        $stmt->close();

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert the user into the database
        $stmt = $this->db->prepare("INSERT INTO users (userID, name, username, userType, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $id, $name, $username, $userType, $hashedPassword);

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return "Error registering user: " . $this->db->error;
        }
    }

    // User login
    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT userID, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($userID, $hashedPassword);

        if ($stmt->fetch()) {
            if (password_verify($password, $hashedPassword)) {
                $_SESSION['user_id'] = $userID;
                $stmt->close();
                return true;
            } else {
                $stmt->close();
                return "Invalid password.";
            }
        } else {
            $stmt->close();
            return false;
        }
    }

    // Check if the user is logged in
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    // Logout the user
    public function logout() {
        session_start();
        session_unset();
        session_destroy();
    }
}
?>
