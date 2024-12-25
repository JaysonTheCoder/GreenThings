<?php
require_once 'database.php';

class Requests {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // Create
    public function create( $userID, $name, $username, $password, $userType = "user") {
        echo "type: ".$userType;
        $stmt = $this->db->prepare("INSERT INTO users (userID, name, username, password, userType) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $userID, $name, $username, $password, $userType);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Read
    public function read($userID = null) {
        if ($userID) {
            $stmt = $this->db->prepare("SELECT * FROM requests WHERE userID = ?");
            $stmt->bind_param("i", $userID);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $result;
        } else {
            $result = $this->db->query("SELECT * FROM requests");
            return $result->fetch_all(MYSQLI_ASSOC);
        }
    }


    // Update
    public function update($id, $data) {
        if (!is_array($data) || empty($data)) {
            throw new InvalidArgumentException("Data must be a non-empty associative array.");
        }

        $columns = array_keys($data);
        $placeholders = implode(" = ?, ", $columns) . " = ?";
        $sql = "UPDATE users SET $placeholders WHERE userID = ?";

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

        $stmt3 = $this->db->prepare("DELETE FROM items WHERE userID = ?");
        $stmt3->bind_param("i", $id);
        $stmt3->execute();
        $stmt3->close();
    
        // Now delete the user
        $stmt4 = $this->db->prepare("DELETE FROM users WHERE userID = ?");
        $stmt4->bind_param("i", $id);
        $result = $stmt4->execute();
        $stmt4->close();


    
        return $result;
    }

    
    
    
}
?>
