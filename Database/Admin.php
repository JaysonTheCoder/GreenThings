<?php
require_once 'database.php';

class Admin {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function register($adminID, $name, $username, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $this->db->prepare("INSERT INTO admins (adminID, name, username, password, userType) VALUES (?, ?, ?, ?, 'admin')");
        $stmt->bind_param("ssss", $adminID, $name, $username, $hashedPassword);

        if ($stmt->execute()) {
            return "Registration successful.";
        } else {
            return "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT adminID, password FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
        
            if (password_verify($password, $row['password'])) {
                $_SESSION["initial_adminID"] = $row["adminID"];
                header("Location: /admin-dashboard");
                return true;
            } else {
                $_SESSION["Invalid-admin"];
                header("Location: /admin-login");
                return false;
            }
        } else {
            $_SESSION["Invalid-admin"];
            header("Location: /admin-login");
            return false;
        }

        $stmt->close();
    }

    // Create
    public function create($adminID, $name, $username, $userType, $password) {
        $stmt = $this->db->prepare("INSERT INTO admins (adminID, name, username, userType, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $adminID, $name, $username, $userType, $password);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Read
    public function read($id = null) {
        if ($id) {
            $stmt = $this->db->prepare("SELECT * FROM admins WHERE adminID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $result;
        } else {
            $result = $this->db->query("SELECT * FROM admins");
            return $result->fetch_all(MYSQLI_ASSOC);
        }
    }

    public function update($id, $data) {
        if (!is_array($data) || empty($data)) {
            throw new InvalidArgumentException("Data must be a non-empty associative array.");
        }

        $columns = array_keys($data);
        $placeholders = implode(" = ?, ", $columns) . " = ?";
        $sql = "UPDATE admins SET $placeholders WHERE adminID = ?";

        $stmt = $this->db->prepare($sql);

        $types = str_repeat("s", count($data)) . "i";
        $values = array_values($data);
        $values[] = $id;

        $stmt->bind_param($types, ...$values);

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM admins WHERE adminID = ?");
        $stmt->bind_param("i", $id); // bind the dynamic $id
        $result = $stmt->execute();
        return $result;
    }
    
}
?>
