<?php
class ItemHandler {
    private $conn;

    public function __construct($host, $username, $password, $dbname) {
        $this->conn = new mysqli($host, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function create($table, $data) {
        $columns = implode(", ", array_keys($data));
        $values = "'" . implode("', '", array_values($data)) . "'";

        $sql = "INSERT INTO $table ($columns) VALUES ($values)";

        if ($this->conn->query($sql) === TRUE) {
            return $this->conn->insert_id; 
        } else {
            return false;
        }
    }


    public function readWithForeignKey($mainTable, $foreignTable, $foreignKey, $columns = "*", $conditions = "") {
        $sql = "SELECT $columns 
                FROM $mainTable 
                JOIN $foreignTable ON $mainTable.$foreignKey = $foreignTable.$foreignKey";
        if (!empty($conditions)) {
            $sql .= " $conditions";
        }
    
        $result = $this->conn->query($sql);
    
        if ($result && $result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return false;
        }
    }
    
    public function readWithThreeTables($mainTable, $table2, $table3, $foreignKey1, $foreignKey2, $columns = "*", $conditions = "") {
        $sql = "SELECT $columns 
                FROM $mainTable 
                JOIN $table2 ON $mainTable.$foreignKey1 = $table2.$foreignKey1 
                JOIN $table3 ON $table2.$foreignKey2 = $table3.$foreignKey2";
    
        if (!empty($conditions)) {
            $sql .= " $conditions";
        }
    
        $result = $this->conn->query($sql);
    
        if ($result && $result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return false;
        }
    }
    

    public function joinTables() {
        $query = "
            SELECT 
                *
                FROM approveditems
                JOIN items ON approveditems.itemID = items.itemID
                JOIN requests ON approveditems.requestID = requests.requestID
                JOIN users ON approveditems.userID = users.userID
            ";

        $result = $this->conn->query($query);

        if ($result === false) {
            return "Error: " . $this->conn->error;
        }

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }




    public function update($table, $data, $id) {
        $setPart = [];
        foreach ($data as $key => $value) {
            $setPart[] = "$key = '$value'";
        }
        $setClause = implode(", ", $setPart);
        $sql = "UPDATE $table SET $setClause WHERE itemID = $id";

        return $this->conn->query($sql);
    }
    public function readAllItems($table) {
        
        $sql = "SELECT * FROM $table";

        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function delete($table, $id) {
        $sql = "DELETE FROM $table WHERE itemID = $id";
        return $this->conn->query($sql);
    }

    public function close() {
        $this->conn->close();
    }
}


?>
