<?php
require_once 'database.php';

class User {
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
    public function read($id = null) {
        if ($id) {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE userID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $result;
        } else {
            $result = $this->db->query("SELECT * FROM users");
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

    public function deleteV2($id) {
        $this->db->begin_transaction();
    
        try {
            $stmt1 = $this->db->prepare("DELETE FROM approveditems WHERE requestID IN 
                (SELECT requestID FROM requests WHERE userID = ?) OR itemID IN 
                (SELECT itemID FROM items WHERE userID = ?)");
            $stmt1->bind_param("ii", $id, $id);
            $stmt1->execute();
            $stmt1->close();
    
            // Delete from requests table
            $stmt2 = $this->db->prepare("DELETE FROM requests WHERE userID = ? OR itemID IN 
                (SELECT itemID FROM items WHERE userID = ?)");
            $stmt2->bind_param("ii", $id, $id);
            $stmt2->execute();
            $stmt2->close();
    
            // Delete from items table
            $stmt3 = $this->db->prepare("DELETE FROM items WHERE userID = ?");
            $stmt3->bind_param("i", $id);
            $stmt3->execute();
            $stmt3->close();
    
            // Now delete from users table
            $stmt4 = $this->db->prepare("DELETE FROM users WHERE userID = ?");
            $stmt4->bind_param("i", $id);
            $result = $stmt4->execute();
            $stmt4->close();
    
            // Commit the transaction
            $this->db->commit();
    
            return $result;
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }
    
    
    
}
?>
