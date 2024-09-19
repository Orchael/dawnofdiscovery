<?php
class StarSystem {
    public static function getById($conn, $id) {
        $stmt = $conn->prepare("SELECT * FROM star_systems WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function getByName($conn, $name) {
        $stmt = $conn->prepare("SELECT * FROM star_systems WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function create($conn, $data) {
        $stmt = $conn->prepare("INSERT INTO star_systems (name, x_coord, y_coord, z_coord, economy_type, security_level) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siiiss", $data['name'], $data['x_coord'], $data['y_coord'], $data['z_coord'], $data['economy_type'], $data['security_level']);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public static function getAll($conn) {
        $result = $conn->query("SELECT * FROM star_systems");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}