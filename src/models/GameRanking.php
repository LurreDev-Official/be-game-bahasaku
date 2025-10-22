<?php
class GameRanking {
    private $conn;
    private $table_name = "game_rankings";

    public $id;
    public $player_id;
    public $score;
    public $level;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create ranking
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    player_id = :player_id,
                    score = :score,
                    level = :level";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->player_id = htmlspecialchars(strip_tags($this->player_id));
        $this->score = htmlspecialchars(strip_tags($this->score));
        $this->level = htmlspecialchars(strip_tags($this->level));

        // Bind values
        $stmt->bindParam(":player_id", $this->player_id);
        $stmt->bindParam(":score", $this->score);
        $stmt->bindParam(":level", $this->level);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Read all rankings
    public function read() {
        $query = "SELECT 
                    gr.id, gr.score, gr.level, gr.created_at,
                    p.username as player_name
                FROM
                    " . $this->table_name . " gr
                    LEFT JOIN players p ON gr.player_id = p.id
                ORDER BY
                    gr.score DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read single ranking
    public function readOne() {
        $query = "SELECT 
                    gr.id, gr.score, gr.level, gr.created_at,
                    p.username as player_name
                FROM
                    " . $this->table_name . " gr
                    LEFT JOIN players p ON gr.player_id = p.id
                WHERE
                    gr.id = ?
                LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->score = $row['score'];
            $this->level = $row['level'];
            $this->created_at = $row['created_at'];
            return true;
        }
        return false;
    }

    // Update ranking
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET
                    score = :score,
                    level = :level
                WHERE
                    id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->score = htmlspecialchars(strip_tags($this->score));
        $this->level = htmlspecialchars(strip_tags($this->level));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind values
        $stmt->bindParam(":score", $this->score);
        $stmt->bindParam(":level", $this->level);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete ranking
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
}