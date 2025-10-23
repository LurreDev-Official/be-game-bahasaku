<?php
class Player {
    private $conn;
    private $table_name = "players"; // Table name

    public $id;
    public $username;
    public $email;
    public $password;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create player
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    username = :username,
                    email = :email,
                    password = :password";

        $stmt = $this->conn->prepare($query);

        // Sanitize and hash password
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);

        // Bind values
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Read all players
    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read single player
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->username = $row['username'];
            $this->email = $row['email'];
            $this->created_at = $row['created_at'];
            return true;
        }
        return false;
    }

    // Update player
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET
                    username = :username,
                    email = :email
                WHERE
                    id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind values
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete player
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Check if username already exists
    public function usernameExists() {
        $query = "SELECT id FROM " . $this->table_name . " WHERE username = :username LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $this->username);

        // Execute query
        $stmt->execute();

        // Check if a record exists
        if ($stmt->rowCount() > 0) {
            return true; // Username exists
        }

        return false; // Username does not exist
    }

    // Get player details by username
    public function getPlayerByUsername() {
        $query = "SELECT id, username, email FROM " . $this->table_name . " WHERE username = :username LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $this->username);

        // Execute query
        $stmt->execute();

        // Fetch the player data
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return null; // No player found
    }
}
?>
