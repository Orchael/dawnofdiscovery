<?php
class User {
    private $conn;
    private $id;
    private $username;
    private $credits;

    public function __construct($conn, $id) {
        $this->conn = $conn;
        $this->id = $id;
        $this->loadData();
    }

    private function loadData() {
        $sql = "SELECT username, credits FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        
        $this->username = $data['username'];
        $this->credits = $data['credits'];
    }

    public function getCurrentShip() {
        $sql = "SELECT * FROM ships WHERE user_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getCredits() {
        return $this->credits;
    }

    public function adjustCredits($amount) {
        $this->credits += $amount;
        $sql = "UPDATE users SET credits = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $this->credits, $this->id);
        $stmt->execute();
    }

    public function getCargoQuantity($commodityId) {
        // Implement fetching cargo quantity
        // This is a placeholder implementation
        return rand(0, 100);
    }

    public function adjustCargo($commodityId, $quantity) {
        // Implement adjusting cargo
        // This is a placeholder implementation
        echo "Adjusted cargo: Commodity $commodityId by $quantity";
    }
}
