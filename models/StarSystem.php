<?php
class StarSystem {
    private $conn;
    public $id;
    public $name;
    public $x;
    public $y;
    public $z;
    public $type;
    public $size;
    public $temperature;
    public $economyType;
    public $securityLevel;
    public $population;

    public function __construct($conn, $id) {
        $this->conn = $conn;
        $this->id = $id;
        $this->loadData();
    }

    private function loadData() {
        $sql = "SELECT * FROM star_systems WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function getInfo() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'economyType' => $this->economyType,
            'securityLevel' => $this->securityLevel
        ];
    }

    public function getDetailedInfo() {
        $info = $this->getInfo();
        $info['planets'] = $this->getPlanets();
        return $info;
    }

    public function getPlanets() {
        $sql = "SELECT * FROM planets WHERE system_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function distanceTo($otherSystem) {
        $dx = $this->x - $otherSystem->x;
        $dy = $this->y - $otherSystem->y;
        $dz = $this->z - $otherSystem->z;
        return sqrt($dx*$dx + $dy*$dy + $dz*$dz);
    }

    public function getCommodityPrice($commodityId) {
        // Implement commodity price calculation based on economy type, etc.
        // This is a placeholder implementation
        return rand(10, 1000);
    }

    public function getAllCommodityPrices() {
        // Implement fetching all commodity prices
        // This is a placeholder implementation
        return [
            ['id' => 1, 'name' => 'Food', 'price' => $this->getCommodityPrice(1)],
            ['id' => 2, 'name' => 'Minerals', 'price' => $this->getCommodityPrice(2)],
            ['id' => 3, 'name' => 'Technology', 'price' => $this->getCommodityPrice(3)]
        ];
    }
}
